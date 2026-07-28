<?php
/**
 * Shared helper functions for every logged-in student portal page
 * (dashboard, lessons, settings). Requires db.php and courses_data.php
 * to already be included.
 */

/** Fetch the full student row fresh from the DB. */
function get_student(mysqli $conn, int $studentId): ?array
{
    $stmt = $conn->prepare("SELECT * FROM tb_registrations WHERE ID = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: null;
}

/** All course names a student is enrolled in (their original course + any added later). */
function get_student_course_list(mysqli $conn, int $studentId, string $primaryCourse): array
{
    $courses = [$primaryCourse];

    $stmt = $conn->prepare("SELECT course FROM student_courses WHERE student_id = ? ORDER BY enrolled_at ASC");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        if (!in_array($row['course'], $courses, true)) {
            $courses[] = $row['course'];
        }
    }

    return $courses;
}

/** Make sure the student's primary signup course is also mirrored into student_courses. */
function ensure_primary_course_enrolled(mysqli $conn, int $studentId, string $primaryCourse): void
{
    $stmt = $conn->prepare("INSERT IGNORE INTO student_courses (student_id, course) VALUES (?, ?)");
    $stmt->bind_param("is", $studentId, $primaryCourse);
    $stmt->execute();
}

/** Enroll a student in an additional course. Returns true if newly added. */
function enroll_student_in_course(mysqli $conn, int $studentId, string $course): bool
{
    if (!in_array($course, all_courses_list(), true)) {
        return false;
    }

    $stmt = $conn->prepare("INSERT IGNORE INTO student_courses (student_id, course) VALUES (?, ?)");
    $stmt->bind_param("is", $studentId, $course);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

/** Resolve which course should currently be "active" for lessons/progress. */
function get_active_course(mysqli $conn, array $student): string
{
    $enrolled = get_student_course_list($conn, (int)$student['ID'], $student['course']);

    if (!empty($student['active_course']) && in_array($student['active_course'], $enrolled, true)) {
        return $student['active_course'];
    }

    return $enrolled[0];
}

/** Set which course is currently active for a student. */
function set_active_course(mysqli $conn, int $studentId, string $course): void
{
    $stmt = $conn->prepare("UPDATE tb_registrations SET active_course = ? WHERE ID = ?");
    $stmt->bind_param("si", $course, $studentId);
    $stmt->execute();
}

/** Decode the completed_lessons JSON column into an array of lesson IDs. */
function get_completed_lesson_ids(?string $json): array
{
    if (!$json) {
        return [];
    }
    $decoded = json_decode($json, true);
    return is_array($decoded) ? $decoded : [];
}

/** Toggle a lesson's completed state and persist it. Returns the new completed list. */
function toggle_lesson_complete(mysqli $conn, int $studentId, string $currentJson, string $lessonId): array
{
    $completed = get_completed_lesson_ids($currentJson);

    if (in_array($lessonId, $completed, true)) {
        $completed = array_values(array_diff($completed, [$lessonId]));
    } else {
        $completed[] = $lessonId;
    }

    $json = json_encode($completed);
    $stmt = $conn->prepare("UPDATE tb_registrations SET completed_lessons = ? WHERE ID = ?");
    $stmt->bind_param("si", $json, $studentId);
    $stmt->execute();

    return $completed;
}

/** Progress percentage (0-100) for a course, given the completed lesson ids for the student. */
function calc_course_progress(string $course, array $completedIds): array
{
    $total = course_total_lessons($course);
    if ($total === 0) {
        return ['done' => 0, 'total' => 0, 'percent' => 0];
    }

    $done = 0;
    foreach (course_curriculum($course) as $module) {
        foreach ($module['lessons'] as $lesson) {
            if (in_array($lesson['id'], $completedIds, true)) {
                $done++;
            }
        }
    }

    return [
        'done'    => $done,
        'total'   => $total,
        'percent' => (int) round(($done / $total) * 100),
    ];
}

/**
 * Call once per successful login. Updates the day-streak counter:
 * same day -> unchanged, consecutive day -> +1, gap -> resets to 1.
 */
function bump_login_streak(mysqli $conn, array $student): int
{
    $today = new DateTime('today');
    $lastLogin = $student['last_login_date'] ? new DateTime($student['last_login_date']) : null;
    $streak = (int) ($student['login_streak'] ?? 0);

    if ($lastLogin === null) {
        $streak = 1;
    } else {
        $diff = (int) $today->diff($lastLogin)->format('%r%a');
        if ($diff === 0) {
            // already logged in today, streak unchanged
        } elseif ($diff === -1) {
            $streak += 1;
        } else {
            $streak = 1;
        }
    }

    $todayStr = $today->format('Y-m-d');
    $stmt = $conn->prepare("UPDATE tb_registrations SET login_streak = ?, last_login_date = ? WHERE ID = ?");
    $stmt->bind_param("isi", $streak, $todayStr, $student['ID']);
    $stmt->execute();

    return $streak;
}

/** Public URL for a student's avatar, falling back to a generated initials avatar. */
function avatar_url(array $student): string
{
    if (!empty($student['profile_picture']) && file_exists(__DIR__ . '/../' . $student['profile_picture'])) {
        return htmlspecialchars($student['profile_picture']);
    }

    $name = trim($student['full_name'] ?? 'Student');
    return 'https://ui-avatars.com/api/?name=' . urlencode($name) .
        '&background=7C3AED&color=fff&bold=true&size=128';
}

/** Random token generator for verification links. */
function portal_random_token(int $bytes = 32): string
{
    return bin2hex(random_bytes($bytes));
}
