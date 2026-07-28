<?php
/**
 * Single course detail page, driven by a ?slug= query param.
 * Example: course.php?slug=web-development
 */

$courses = [

    'web-development' => [
        'title'    => 'Web Development',
        'icon'     => 'bi-code-slash',
        'tagline'  => 'Build fast, modern websites and web apps from scratch.',
        'intro'    => "You'll go from writing your first line of HTML to building fully responsive, interactive websites. This course focuses on the exact stack companies use to hire junior frontend developers, so everything you build doubles as portfolio material.",
        'tags'     => ['HTML5', 'CSS3', 'Bootstrap', 'JavaScript'],
        'benefits' => [
            ['icon' => 'bi-layout-text-window-reverse', 'title' => 'Ship real layouts', 'desc' => 'Structure and style pages with HTML5 and CSS3, from simple landing pages to full multi-section sites.'],
            ['icon' => 'bi-phone', 'title' => 'Responsive by default', 'desc' => 'Use Bootstrap to build layouts that work smoothly on mobile, tablet, and desktop without extra effort.'],
            ['icon' => 'bi-lightning-charge-fill', 'title' => 'Add real interactivity', 'desc' => 'Learn JavaScript fundamentals -- DOM manipulation, events, and form handling -- to make pages actually respond to users.'],
            ['icon' => 'bi-kanban', 'title' => 'Portfolio-ready projects', 'desc' => 'Finish with 3+ deployed projects you can link on your resume or show in interviews.'],
        ],
    ],

    'mobile-app-development' => [
        'title'    => 'Mobile App Development',
        'icon'     => 'bi-phone',
        'tagline'  => 'Design and build real Android apps with Flutter.',
        'intro'    => "You'll learn to build cross-platform mobile apps using Flutter and Dart, then connect them to a real backend with Firebase. By the end, you'll understand the full loop of building, testing, and shipping a mobile app not just the UI.",
        'tags'     => ['Flutter', 'Dart', 'Firebase', 'REST API'],
        'benefits' => [
            ['icon' => 'bi-grid-1x2-fill', 'title' => 'One codebase, real apps', 'desc' => 'Use Flutter and Dart to build apps that look and feel native, without writing separate code for every platform.'],
            ['icon' => 'bi-cloud-fill', 'title' => 'Connect to a real backend', 'desc' => 'Store data, handle authentication, and sync in real time using Firebase.'],
            ['icon' => 'bi-plug-fill', 'title' => 'Work with live data', 'desc' => 'Learn to call REST APIs so your apps can pull in real, external data sources.'],
            ['icon' => 'bi-rocket-takeoff-fill', 'title' => 'Launch-ready skills', 'desc' => 'Understand the steps to package and prepare an app for release, not just prototype it.'],
        ],
    ],

    'ui-ux-design' => [
        'title'    => 'UI / UX Design',
        'icon'     => 'bi-palette-fill',
        'tagline'  => 'Design interfaces people actually enjoy using.',
        'intro'    => "This course covers both sides of product design: the visual craft of UI and the research/thinking behind UX. You'll learn to go from a blank canvas to a polished, testable prototype in Figma, backed by real design principles.",
        'tags'     => ['Figma', 'Prototype', 'Wireframe', 'Design System'],
        'benefits' => [
            ['icon' => 'bi-vector-pen', 'title' => 'Design in Figma', 'desc' => 'Learn the industry-standard design tool used by product teams everywhere, from basics to advanced components.'],
            ['icon' => 'bi-diagram-3-fill', 'title' => 'Think in wireframes first', 'desc' => 'Practice sketching structure and flow before visuals, the way real design teams work.'],
            ['icon' => 'bi-play-btn-fill', 'title' => 'Build clickable prototypes', 'desc' => 'Turn static screens into interactive prototypes you can test with real users.'],
            ['icon' => 'bi-grid-3x3-gap-fill', 'title' => 'Design systems that scale', 'desc' => 'Learn to build reusable components and consistent design systems, not one-off screens.'],
        ],
    ],

    'cyber-security' => [
        'title'    => 'Cyber Security',
        'icon'     => 'bi-shield-lock-fill',
        'tagline'  => 'Learn how attackers think, and how to stop them.',
        'intro'    => "You'll build a solid foundation in networking and Linux, then move into ethical hacking and penetration testing using industry-standard tools. Everything is taught in safe, legal lab environments so you can practice real techniques responsibly.",
        'tags'     => ['Linux', 'Networking', 'Kali Linux', 'Security'],
        'benefits' => [
            ['icon' => 'bi-terminal-fill', 'title' => 'Get fluent in Linux', 'desc' => 'Master the command line environment that powers most security tooling and servers.'],
            ['icon' => 'bi-diagram-2-fill', 'title' => 'Understand networks deeply', 'desc' => 'Learn how data actually moves so you can spot where and how systems get exploited.'],
            ['icon' => 'bi-bug-fill', 'title' => 'Practice ethical hacking', 'desc' => 'Use Kali Linux and real penetration testing tools in safe, legal lab environments.'],
            ['icon' => 'bi-shield-check', 'title' => 'Think like a defender too', 'desc' => "Learn not just how to break in, but how to secure systems and respond to incidents."],
        ],
    ],

    'data-analytics' => [
        'title'    => 'Data Analytics',
        'icon'     => 'bi-bar-chart-fill',
        'tagline'  => 'Turn raw data into decisions businesses can act on.',
        'intro'    => "You'll learn the full analytics workflow: cleaning and organizing data in Excel, querying it with SQL, and turning it into dashboards stakeholders can actually understand using Power BI. This is the exact skillset companies look for in junior analysts.",
        'tags'     => ['Excel', 'SQL', 'Power BI', 'Dashboard'],
        'benefits' => [
            ['icon' => 'bi-file-earmark-spreadsheet-fill', 'title' => 'Master spreadsheet analysis', 'desc' => 'Go beyond basic Excel -- pivot tables, formulas, and data cleaning that real analysts use daily.'],
            ['icon' => 'bi-database-fill', 'title' => 'Query real databases', 'desc' => 'Learn SQL to pull exactly the data you need from large, real-world datasets.'],
            ['icon' => 'bi-graph-up-arrow', 'title' => 'Build dashboards that matter', 'desc' => 'Use Power BI to turn numbers into visuals that non-technical stakeholders can understand instantly.'],
            ['icon' => 'bi-lightbulb-fill', 'title' => 'Tell a story with data', 'desc' => "Learn to go from raw numbers to a clear recommendation, which is the skill that actually gets analysts hired."],
        ],
    ],

    'artificial-intelligence' => [
        'title'    => 'Artificial Intelligence',
        'icon'     => 'bi-cpu-fill',
        'tagline'  => 'Learn to build and understand modern AI systems.',
        'intro'    => "Starting from Python fundamentals, you'll work your way up to training machine learning models with TensorFlow and understanding how today's large language models actually work. This course balances theory with hands-on model building.",
        'tags'     => ['Python', 'TensorFlow', 'Machine Learning', 'LLMs'],
        'benefits' => [
            ['icon' => 'bi-code-square', 'title' => 'Python for AI', 'desc' => 'Build a strong Python foundation focused specifically on data and machine learning workflows.'],
            ['icon' => 'bi-diagram-3', 'title' => 'Train real models', 'desc' => 'Use TensorFlow to build, train, and evaluate machine learning models on real datasets.'],
            ['icon' => 'bi-graph-up', 'title' => 'Understand ML concepts', 'desc' => 'Learn the core ideas behind classification, regression, and neural networks -- not just the code.'],
            ['icon' => 'bi-stars', 'title' => 'Explore modern LLMs', 'desc' => 'Get a practical introduction to how large language models work and how to build with them.'],
        ],
    ],

];

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (!isset($courses[$slug])) {
    header("Location: index.php#courses");
    exit();
}

$course = $courses[$slug];
$signupUrl = "../signup_page/signup.php?course=" . urlencode($course['title']);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($course['title']) ?> | LetsCode!</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=7">
    <link rel="stylesheet" href="assets/css/responsive.css?v=7">
    <link rel="stylesheet" href="assets/css/course.css?v=7">

</head>

<body>

    <!-- ========================= -->
    <!-- Background Glow -->
    <!-- ========================= -->

    <div class="bg-blur blur1"></div>
    <div class="bg-blur blur2"></div>
    <div class="bg-blur blur3"></div>

    <!-- ========================= -->
    <!-- Navbar -->
    <!-- ========================= -->

    <nav class="navbar navbar-expand-lg fixed-top glass-nav">

        <div class="container">

            <a class="navbar-brand logo" href="index.php">

                <i class="bi bi-code-slash"></i>

                LetsCode!

            </a>

            <button class="navbar-toggler border-0 shadow-none"
                data-bs-toggle="collapse"
                data-bs-target="#menu">

                <i class="bi bi-list text-white fs-2"></i>

            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="index.php#courses" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Courses <i class="bi bi-chevron-down dropdown-caret"></i></a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="course.php?slug=web-development"><i class="bi bi-code-slash"></i> Web Development</a></li>
                            <li><a class="dropdown-item" href="course.php?slug=mobile-app-development"><i class="bi bi-phone"></i> Mobile App Development</a></li>
                            <li><a class="dropdown-item" href="course.php?slug=ui-ux-design"><i class="bi bi-palette-fill"></i> UI / UX Design</a></li>
                            <li><a class="dropdown-item" href="course.php?slug=cyber-security"><i class="bi bi-shield-lock-fill"></i> Cyber Security</a></li>
                            <li><a class="dropdown-item" href="course.php?slug=data-analytics"><i class="bi bi-bar-chart-fill"></i> Data Analytics</a></li>
                            <li><a class="dropdown-item" href="course.php?slug=artificial-intelligence"><i class="bi bi-cpu-fill"></i> Artificial Intelligence</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about">About</a>
                    </li>

                    <li class="nav-item ms-lg-3">
                        <a class="nav-profile-btn" href="../signup_page/portal_login.php" title="Student Login" aria-label="Student Login">
                            <i class="bi bi-person-fill"></i>
                        </a>
                    </li>

                    <li class="nav-item ms-lg-4">

                        <a class="btn btn-purple px-4" href="../signup_page/signup.php">

                            Join Now

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <!-- ========================= -->
    <!-- Course Hero -->
    <!-- ========================= -->

    <section class="course-hero">

        <div class="container">

            <a href="index.php#courses" class="course-back-link">
                <i class="bi bi-arrow-left"></i>
                Back to all courses
            </a>

            <div class="row align-items-center g-4">

                <div class="col-lg-7">

                    <span class="badge-purple">
                        <i class="bi <?= htmlspecialchars($course['icon']) ?>"></i>
                        <?= htmlspecialchars($course['title']) ?>
                    </span>

                    <h1 class="course-hero-title">
                        <?= htmlspecialchars($course['tagline']) ?>
                    </h1>

                    <p class="course-hero-desc">
                        <?= htmlspecialchars($course['intro']) ?>
                    </p>

                    <div class="course-tag-list">
                        <?php foreach ($course['tags'] as $tag): ?>
                            <span class="course-tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>

                    <a href="<?= htmlspecialchars($signupUrl) ?>" class="btn btn-purple btn-lg mt-4">
                        Join Us
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

                <div class="col-lg-5 text-center">

                    <div class="course-hero-icon">
                        <i class="bi <?= htmlspecialchars($course['icon']) ?>"></i>
                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- What You'll Learn -->
    <!-- ========================= -->

    <section class="course-benefits py-5">

        <div class="container">

            <div class="text-center mb-5">

                <span class="section-tag">WHAT YOU'LL GAIN</span>

                <h2 class="section-title">
                    Benefits Of This
                    <span>Course</span>
                </h2>

            </div>

            <div class="row g-4">

                <?php foreach ($course['benefits'] as $benefit): ?>
                <div class="col-lg-3 col-md-6">

                    <div class="feature-card">

                        <i class="bi <?= htmlspecialchars($benefit['icon']) ?> feature-icon"></i>

                        <h4><?= htmlspecialchars($benefit['title']) ?></h4>

                        <p><?= htmlspecialchars($benefit['desc']) ?></p>

                    </div>

                </div>
                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- CTA -->
    <!-- ========================= -->

    <section class="course-cta">

        <div class="container">

            <div class="course-cta-box">

                <h2>Ready to start <?= htmlspecialchars($course['title']) ?>?</h2>

                <p>it only takes a minute to sign up.</p>

                <a href="<?= htmlspecialchars($signupUrl) ?>" class="btn btn-purple btn-lg">
                    Join Us
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>

    </section>

    <!-- ====================================== -->
    <!-- FOOTER -->
    <!-- ====================================== -->

    <footer class="footer">

        <div class="container">

            <div class="row">

                <div class="col-lg-4">

                    <h3>
                        <i class="bi bi-code-slash"></i>
                        LetsCode!
                    </h3>

                    <p>
                        Empowering future developers through practical technology education.
                    </p>

                </div>

                <div class="col-lg-2">

                    <h5>Company</h5>

                    <ul>
                        <li><a href="index.php#about">About</a></li>
                        <li><a href="index.php#courses">Courses</a></li>
                    </ul>

                </div>

                <div class="col-lg-3">

                    <h5>Contact</h5>

                    <p>Email: hello@letscode.id</p>
                    <p>Phone: +62 812 3456 7890</p>

                </div>

                <div class="col-lg-3">

                    <h5>Follow Us</h5>

                    <div class="socials">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                        <a href="#"><i class="bi bi-github"></i></a>
                    </div>

                </div>

            </div>

            <hr>

            <div class="text-center">
                © 2026 LetsCode! All Rights Reserved.
            </div>

        </div>

    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
