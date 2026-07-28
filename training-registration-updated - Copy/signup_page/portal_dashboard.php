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
$activeCourse = get_active_course($conn, $student);

$completedIds = get_completed_lesson_ids($student['completed_lessons']);
$progress = calc_course_progress($activeCourse, $completedIds);
$curriculum = course_curriculum($activeCourse);

// Find the next lesson that isn't completed yet, to power the "Continue" CTA.
$nextLesson = null;
foreach ($curriculum as $module) {
    foreach ($module['lessons'] as $lesson) {
        if (!in_array($lesson['id'], $completedIds, true)) {
            $nextLesson = $lesson;
            break 2;
        }
    }
}

$firstName = trim(explode(' ', $student["full_name"])[0]);
$activePage = 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Dashboard | LetsCode!</title>

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

            <div class="welcome-card">
                <span class="badge-purple">
                    <i class="bi bi-mortarboard-fill"></i>
                    Student Dashboard
                </span>
                <h1>Welcome back, <?= htmlspecialchars($firstName) ?>!</h1>
                <p>You're all set up and ready to start learning.</p>

                <div class="welcome-card-footer">
                    <div class="course-badge">
                        <i class="bi <?= course_icon($activeCourse) ?>"></i>
                        <?= htmlspecialchars($activeCourse) ?>
                    </div>

                    <?php if (count($enrolledCourses) > 1): ?>
                        <a href="lessons.php#switch-course" class="btn btn-outline btn-sm-icon">
                            <i class="bi bi-arrow-left-right"></i>
                            <span>Switch course</span>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="progress-block">
                    <div class="progress-block-labels">
                        <span>Course progress</span>
                        <span><?= $progress['done'] ?> / <?= $progress['total'] ?> lessons</span>
                    </div>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" style="width: <?= $progress['percent'] ?>%;"></div>
                    </div>
                    <div class="progress-block-percent"><?= $progress['percent'] ?>% complete</div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="bi bi-collection-play-fill"></i>
                    <div class="stat-value"><?= $progress['done'] ?> / <?= $progress['total'] ?></div>
                    <div class="stat-label">Lessons Completed</div>
                </div>
                <div class="stat-card">
                    <i class="bi bi-fire"></i>
                    <div class="stat-value"><?= (int) $student['login_streak'] ?> day<?= (int) $student['login_streak'] === 1 ? '' : 's' ?></div>
                    <div class="stat-label">Learning Streak</div>
                </div>
                <div class="stat-card">
                    <i class="bi bi-patch-check-fill"></i>
                    <div class="stat-value">
                        <?= $progress['percent'] === 100 ? 'Earned' : ($progress['percent'] > 0 ? 'In progress' : 'Not started') ?>
                    </div>
                    <div class="stat-label">Certificate Status</div>
                </div>
            </div>

            <div class="section-title">
                <i class="bi bi-grid-fill"></i>
                Course Modules
            </div>

            <div class="module-grid" style="margin-bottom:22px;">

                <?php foreach ($curriculum as $mi => $module):
                    $moduleTotal = count($module['lessons']);
                    $moduleDone = 0;
                    foreach ($module['lessons'] as $l) {
                        if (in_array($l['id'], $completedIds, true)) $moduleDone++;
                    }
                    $prevModuleDone = true;
                    if ($mi > 0) {
                        foreach ($curriculum[$mi - 1]['lessons'] as $l) {
                            if (!in_array($l['id'], $completedIds, true)) { $prevModuleDone = false; break; }
                        }
                    }
                    $isUnlocked = ($mi === 0) || $prevModuleDone || $moduleDone > 0;
                ?>
                    <a href="lessons.php#module-<?= $mi ?>" class="module-card <?= $isUnlocked ? 'active' : '' ?>">
                        <i class="bi <?= $isUnlocked ? 'bi-play-circle-fill' : 'bi-lock-fill' ?>"></i>
                        <h4><?= htmlspecialchars($module['title']) ?></h4>
                        <span><?= $moduleDone ?> / <?= $moduleTotal ?> lessons</span>
                        <div class="lock-tag">
                            <?php if ($moduleDone === $moduleTotal): ?>
                                <i class="bi bi-patch-check-fill"></i> Completed
                            <?php elseif ($isUnlocked): ?>
                                <i class="bi bi-unlock-fill"></i> Available
                            <?php else: ?>
                                <i class="bi bi-lock-fill"></i> Locked
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>

            </div>

            <?php if ($nextLesson): ?>
                <div class="continue-card">
                    <div>
                        <div class="continue-label"><i class="bi bi-play-fill"></i> Continue learning</div>
                        <h4><?= htmlspecialchars($nextLesson['title']) ?></h4>
                        <span><?= htmlspecialchars($nextLesson['duration']) ?></span>
                    </div>
                    <a href="lessons.php#lesson-<?= htmlspecialchars($nextLesson['id']) ?>" class="btn btn-purple">
                        Resume <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="placeholder-card">
                    <p>You've completed every lesson in <?= htmlspecialchars($activeCourse) ?>! Your certificate is ready to be issued -- we'll be in touch by email.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>
