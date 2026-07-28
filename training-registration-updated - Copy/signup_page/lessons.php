<?php
session_start();
include "db.php";
include "includes/courses_data.php";
include "includes/portal_functions.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: portal_login.php");
    exit();
}

$student = get_student($conn, (int) $_SESSION["student_id"]);
if (!$student) {
    header("Location: portal_logout.php");
    exit();
}

ensure_primary_course_enrolled($conn, (int) $student['ID'], $student['course']);
$enrolledCourses = get_student_course_list($conn, (int) $student['ID'], $student['course']);

// Handle "switch course" (only meaningful if enrolled in more than one).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['switch_course'])) {
    $chosen = $_POST['switch_course'];
    if (in_array($chosen, $enrolledCourses, true)) {
        set_active_course($conn, (int) $student['ID'], $chosen);
    }
    header("Location: lessons.php");
    exit();
}

// Handle "add another course" enrollment.
$enrollMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $newCourse = $_POST['add_course'];
    if (enroll_student_in_course($conn, (int) $student['ID'], $newCourse)) {
        set_active_course($conn, (int) $student['ID'], $newCourse);
        header("Location: lessons.php");
        exit();
    }
    $enrollMessage = "Couldn't add that course. Please try again.";
    $enrolledCourses = get_student_course_list($conn, (int) $student['ID'], $student['course']);
}

// Handle mark lesson complete/incomplete (AJAX-friendly, but works with a normal form post too).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_lesson'])) {
    $lessonId = $_POST['toggle_lesson'];
    $completed = toggle_lesson_complete($conn, (int) $student['ID'], $student['completed_lessons'] ?? '', $lessonId);

    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['completed' => $completed]);
        exit();
    }

    header("Location: lessons.php#lesson-" . urlencode($lessonId));
    exit();
}

$activeCourse = get_active_course($conn, $student);
$completedIds = get_completed_lesson_ids($student['completed_lessons']);
$progress = calc_course_progress($activeCourse, $completedIds);
$curriculum = course_curriculum($activeCourse);
$availableToAdd = array_values(array_diff(all_courses_list(), $enrolledCourses));

$activePage = 'lessons';
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Lessons | LetsCode!</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/portal.css">

</head>
<body>

    <div class="bg-blur blur1"></div>
    <div class="bg-blur blur2"></div>
    <div class="bg-blur blur3"></div>

    <?php include "includes/portal_nav.php"; ?>

    <div class="portal-shell wide">
        <div class="dash-wrap">

            <div class="page-heading">
                <div>
                    <span class="badge-purple">
                        <i class="bi <?= course_icon($activeCourse) ?>"></i>
                        <?= htmlspecialchars($activeCourse) ?>
                    </span>
                    <h1>Your Lessons</h1>
                    <p>Work through each module in order, check off lessons as you complete them.</p>
                </div>

                <?php if (count($enrolledCourses) > 1): ?>
                    <button type="button" class="btn btn-outline btn-sm-icon" onclick="document.getElementById('switchCourseModal').classList.add('open')">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Switch course</span>
                    </button>
                <?php endif; ?>
            </div>

            <div class="progress-block card-style" style="margin-bottom:26px;">
                <div class="progress-block-labels">
                    <span>Overall progress</span>
                    <span><?= $progress['done'] ?> / <?= $progress['total'] ?> lessons</span>
                </div>
                <div class="progress-bar-track">
                    <div class="progress-bar-fill" style="width: <?= $progress['percent'] ?>%;"></div>
                </div>
                <div class="progress-block-percent"><?= $progress['percent'] ?>% complete</div>
            </div>

            <?php if (!empty($availableToAdd)): ?>
                <div class="add-course-card">
                    <div>
                        <i class="bi bi-plus-circle-fill"></i>
                        <div>
                            <h4>Want to learn something new?</h4>
                            <span>Add another course to your account without losing your current progress.</span>
                        </div>
                    </div>
                    <form method="POST" class="add-course-form">
                        <select name="add_course" class="form-control" required>
                            <option value="" disabled selected>Choose a course</option>
                            <?php foreach ($availableToAdd as $c): ?>
                                <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-purple btn-sm-icon">
                            <i class="bi bi-plus-lg"></i> Add course
                        </button>
                    </form>
                </div>
                <?php if ($enrollMessage): ?>
                    <div class="alert-box alert-error"><?= htmlspecialchars($enrollMessage) ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="lesson-modules">
                <?php foreach ($curriculum as $mi => $module):
                    $moduleTotal = count($module['lessons']);
                    $moduleDone = 0;
                    foreach ($module['lessons'] as $l) {
                        if (in_array($l['id'], $completedIds, true)) $moduleDone++;
                    }
                ?>
                <div class="lesson-module" id="module-<?= $mi ?>">
                    <div class="lesson-module-header">
                        <h3><span class="module-index"><?= $mi + 1 ?></span> <?= htmlspecialchars($module['title']) ?></h3>
                        <span class="module-progress-tag"><?= $moduleDone ?> / <?= $moduleTotal ?> done</span>
                    </div>

                    <div class="lesson-list">
                        <?php foreach ($module['lessons'] as $lesson):
                            $isDone = in_array($lesson['id'], $completedIds, true);
                        ?>
                            <div class="lesson-row <?= $isDone ? 'done' : '' ?>" id="lesson-<?= htmlspecialchars($lesson['id']) ?>">
                                <form method="POST" class="lesson-check-form">
                                    <input type="hidden" name="toggle_lesson" value="<?= htmlspecialchars($lesson['id']) ?>">
                                    <button type="submit" class="lesson-check" aria-label="Mark lesson complete">
                                        <i class="bi <?= $isDone ? 'bi-check-circle-fill' : 'bi-circle' ?>"></i>
                                    </button>
                                </form>
                                <div class="lesson-row-body">
                                    <h5><?= htmlspecialchars($lesson['title']) ?></h5>
                                    <span><i class="bi bi-clock"></i> <?= htmlspecialchars($lesson['duration']) ?></span>
                                </div>
                                <i class="bi bi-play-circle lesson-play-icon"></i>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <?php if (count($enrolledCourses) > 1): ?>
    <div class="modal-overlay" id="switchCourseModal">
        <div class="modal-box">
            <button type="button" class="modal-close" onclick="document.getElementById('switchCourseModal').classList.remove('open')">
                <i class="bi bi-x-lg"></i>
            </button>
            <h3><i class="bi bi-arrow-left-right"></i> Switch Course</h3>
            <p class="portal-subtitle">Pick which enrolled course you want to view lessons for.</p>

            <form method="POST" class="switch-course-list">
                <?php foreach ($enrolledCourses as $c): ?>
                    <button type="submit" name="switch_course" value="<?= htmlspecialchars($c) ?>"
                        class="switch-course-option <?= $c === $activeCourse ? 'selected' : '' ?>">
                        <i class="bi <?= course_icon($c) ?>"></i>
                        <span><?= htmlspecialchars($c) ?></span>
                        <?php if ($c === $activeCourse): ?><i class="bi bi-check-lg check"></i><?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src="assets/js/portal.js"></script>

</body>
</html>
