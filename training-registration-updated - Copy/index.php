<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LetsCode! | Learn Digital Skills</title>

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

            <a class="navbar-brand logo" href="#">

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
                        <a class="nav-link active" href="#">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#courses" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Courses <i class="bi bi-chevron-down dropdown-caret"></i></a>
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
                        <a class="nav-link" href="#about">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Reviews</a>
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
    <!-- Hero -->
    <!-- ========================= -->

    <section class="hero">

        <div class="container">

            <div class="row align-items-center">

                <!-- Left -->

                <div class="col-lg-6 hero-text">

                    <span class="badge-purple">

                        Learn Future Skills

                    </span>

                    <h1>

                        Become a Professional

                        <span>Developer</span>

                        With LetsCode!

                    </h1>

                    <p>

                        Learn directly from industry mentors, build real-world
                        projects, and prepare yourself for internships and
                        software engineering careers.

                    </p>

                    <div class="hero-buttons">

                        <a href="#courses"
                            class="btn btn-purple btn-lg">

                            Explore Courses

                        </a>

                        <a href="#about"
                            class="btn btn-outline-light btn-lg">

                            Learn More

                        </a>

                    </div>

                </div>

                <!-- Right -->

                <div class="col-lg-6 text-center">

                    <div class="hero-image">

                        <img src="assets/images/hero.jpg"
                            class="img-fluid">

                        <div class="floating-card">

                            <i class="bi bi-award-fill"></i>

                            Industry Certified

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ========================= -->
    <!-- Trusted Companies -->
    <!-- ========================= -->

    <section class="trusted">

        <div class="container text-center">

            <p>

                Trusted by students preparing for internships

            </p>

            <div class="company-logos">

                <!-- Replace these with SVG logos later if desired -->
                <div class="company">
                    <img src="assets/logos/google.svg" alt="Google">
                </div>

                <div class="company">
                    <img src="assets/logos/microsoft.svg" alt="Microsoft">
                </div>

                <div class="company">
                    <img src="assets/logos/adobe.svg" alt="Adobe">
                </div>

                <div class="company">
                    <img src="assets/logos/figma.svg" alt="Figma">
                </div>

                <div class="company">
                    <img src="assets/logos/github.svg" alt="GitHub">
                </div>

            </div>

        </div>

    </section>

    <!-- ====================================== -->
<!-- ABOUT -->
<!-- ====================================== -->

<section class="about py-5" id="about">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <div class="about-image">

                    <img src="assets/images/about.png"
                        class="img-fluid rounded-5">

                    <div class="experience-card">

                        <h2>10+</h2>

                        <p>Years Teaching Tech</p>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <span class="section-tag">

                    ABOUT LETSCODE

                </span>

                <h2 class="section-title">

                    Learn Coding Through

                    <span>Real Projects</span>

                </h2>

                <p class="section-text">

                    LetsCode! is a modern learning platform designed to prepare
                    students for internships and careers in technology.

                    Our courses combine hands-on projects, industry mentors,
                    and practical experience to help students build
                    portfolio-worthy applications.

                </p>

                <div class="row g-4 mt-4">

                    <div class="col-md-6">

                        <div class="mini-card">

                            <i class="bi bi-laptop"></i>

                            <div>

                                <h5>

                                    Practical Learning

                                </h5>

                                <p>

                                    Build real-world projects.

                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mini-card">

                            <i class="bi bi-person-workspace"></i>

                            <div>

                                <h5>

                                    Industry Mentors

                                </h5>

                                <p>

                                    Learn directly from professionals.

                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mini-card">

                            <i class="bi bi-award-fill"></i>

                            <div>

                                <h5>

                                    Certificate

                                </h5>

                                <p>

                                    Earn recognized certificates.

                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mini-card">

                            <i class="bi bi-briefcase-fill"></i>

                            <div>

                                <h5>

                                    Internship Ready

                                </h5>

                                <p>

                                    Prepare for your future career.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ====================================== -->
<!-- STATISTICS -->
<!-- ====================================== -->

<section class="stats py-4">

    <div class="container">

        <div class="row text-center g-4">

            <div class="col-md-3">

                <div class="stat-card">

                    <h2 class="counter" data-target="10000" data-suffix="+">

                        0

                    </h2>

                    <p>Students</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <h2 class="counter" data-target="50" data-suffix="+">

                        0

                    </h2>

                    <p>Courses</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <h2 class="counter" data-target="35" data-suffix="+">

                        0

                    </h2>

                    <p>Mentors</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <h2 class="counter" data-target="95" data-suffix="%">

                        0

                    </h2>

                    <p>Student Satisfaction</p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ====================================== -->
<!-- COURSES -->
<!-- ====================================== -->

<section class="courses py-5" id="courses">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-tag">

                OUR COURSES

            </span>

            <h2 class="section-title">

                Build Skills

                <span>That Companies Need</span>

            </h2>

        </div>

        <div class="carousel-wrapper courses-carousel">
            <button class="carousel-btn prev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
            <div class="carousel-track row g-4">

            <!-- Card -->

            <div class="col-lg-4 col-md-6">

                <div class="course-card">

                    <div class="course-icon">

                        <i class="bi bi-code-slash"></i>

                    </div>

                    <h4>

                        Web Development

                    </h4>

                    <p>

                        Learn HTML, CSS, JavaScript,
                        Bootstrap and modern frontend
                        development.

                    </p>

                    <ul>

                        <li>HTML5</li>
                        <li>CSS3</li>
                        <li>Bootstrap</li>
                        <li>JavaScript</li>

                    </ul>

                    <a href="course.php?slug=web-development"
                        class="learn-btn">

                        Learn More

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            <!-- Card -->

            <div class="col-lg-4 col-md-6">

                <div class="course-card">

                    <div class="course-icon">

                        <i class="bi bi-phone"></i>

                    </div>

                    <h4>

                        Mobile App Development

                    </h4>

                    <p>

                        Create Android applications using
                        Flutter and Firebase.

                    </p>

                    <ul>

                        <li>Flutter</li>
                        <li>Dart</li>
                        <li>Firebase</li>
                        <li>REST API</li>

                    </ul>

                    <a href="course.php?slug=mobile-app-development"
                        class="learn-btn">

                        Learn More

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            <!-- Card -->

            <div class="col-lg-4 col-md-6">

                <div class="course-card">

                    <div class="course-icon">

                        <i class="bi bi-palette-fill"></i>

                    </div>

                    <h4>

                        UI / UX Design

                    </h4>

                    <p>

                        Design beautiful interfaces with
                        Figma while learning UX principles.

                    </p>

                    <ul>

                        <li>Figma</li>
                        <li>Prototype</li>
                        <li>Wireframe</li>
                        <li>Design System</li>

                    </ul>

                    <a href="course.php?slug=ui-ux-design"
                        class="learn-btn">

                        Learn More

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            <!-- Card -->

            <div class="col-lg-4 col-md-6">

                <div class="course-card">

                    <div class="course-icon">

                        <i class="bi bi-shield-lock-fill"></i>

                    </div>

                    <h4>

                        Cyber Security

                    </h4>

                    <p>

                        Learn ethical hacking,
                        networking,
                        Linux and penetration testing.

                    </p>

                    <ul>

                        <li>Linux</li>
                        <li>Networking</li>
                        <li>Kali Linux</li>
                        <li>Security</li>

                    </ul>

                    <a href="course.php?slug=cyber-security"
                        class="learn-btn">

                        Learn More

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            <!-- Card -->

            <div class="col-lg-4 col-md-6">

                <div class="course-card">

                    <div class="course-icon">

                        <i class="bi bi-bar-chart-fill"></i>

                    </div>

                    <h4>

                        Data Analytics

                    </h4>

                    <p>

                        Analyze and visualize
                        business data using
                        Excel, SQL and Power BI.

                    </p>

                    <ul>

                        <li>Excel</li>
                        <li>SQL</li>
                        <li>Power BI</li>
                        <li>Dashboard</li>

                    </ul>

                    <a href="course.php?slug=data-analytics"
                        class="learn-btn">

                        Learn More

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            <!-- Card -->

            <div class="col-lg-4 col-md-6">

                <div class="course-card">

                    <div class="course-icon">

                        <i class="bi bi-cpu-fill"></i>

                    </div>

                    <h4>

                        Artificial Intelligence

                    </h4>

                    <p>

                        Explore machine learning,
                        Python and generative AI.

                    </p>

                    <ul>

                        <li>Python</li>
                        <li>TensorFlow</li>
                        <li>Machine Learning</li>
                        <li>LLMs</li>

                    </ul>

                    <a href="course.php?slug=artificial-intelligence"
                        class="learn-btn">

                        Learn More

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            </div>
            <button class="carousel-btn next" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
        </div>

    </div>

</section>

<!-- ====================================== -->
<!-- WHY CHOOSE US -->
<!-- ====================================== -->

<section class="why-us py-5">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-tag">
                WHY LETSCODE
            </span>

            <h2 class="section-title">
                Why Students Choose
                <span>LetsCode!</span>
            </h2>

            <p class="section-text">
                Learn with confidence through project-based learning,
                experienced mentors, and career-focused training.
            </p>
        </div>
        
        <div class="carousel-wrapper why-carousel">
            <button class="carousel-btn prev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
            <div class="carousel-track row g-4">

            <div class="col-lg-4">

                <div class="feature-card">

                    <i class="bi bi-laptop feature-icon"></i>

                    <h4>Real Projects</h4>

                    <p>
                        Every course includes practical projects that become
                        part of your portfolio.
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature-card">

                    <i class="bi bi-person-video3 feature-icon"></i>

                    <h4>Expert Mentors</h4>

                    <p>
                        Learn directly from professionals with real
                        industry experience.
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature-card">

                    <i class="bi bi-briefcase-fill feature-icon"></i>

                    <h4>Internship Ready</h4>

                    <p>
                        Gain the skills employers actually look for.
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature-card">

                    <i class="bi bi-award-fill feature-icon"></i>

                    <h4>Certification</h4>

                    <p>
                        Receive certificates after completing each course.
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature-card">

                    <i class="bi bi-clock-fill feature-icon"></i>

                    <h4>Flexible Schedule</h4>

                    <p>
                        Learn anywhere and anytime at your own pace.
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature-card">

                    <i class="bi bi-stars feature-icon"></i>

                    <h4>Friendly Community</h4>

                    <p>
                        Join thousands of learners and collaborate together.
                    </p>

                </div>

            </div>

            </div>
            <button class="carousel-btn next" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
        </div>

    </div>

</section>

<!-- ====================================== -->
<!-- LEARNING PROCESS -->
<!-- ====================================== -->

<section class="process py-5">

<div class="container">

<div class="text-center mb-5">

<span class="section-tag">

HOW IT WORKS

</span>

<h2 class="section-title">

Your Learning Journey

</h2>

</div>

<div class="row g-4">

<div class="col-md-3">

<div class="process-card">

<div class="process-number">

01

</div>

<i class="bi bi-person-plus-fill"></i>

<h4>Register</h4>

<p>Create your account.</p>

</div>

</div>

<div class="col-md-3">

<div class="process-card">

<div class="process-number">

02

</div>

<i class="bi bi-book-half"></i>

<h4>Learn</h4>

<p>Study with expert mentors.</p>

</div>

</div>

<div class="col-md-3">

<div class="process-card">

<div class="process-number">

03

</div>

<i class="bi bi-code-slash"></i>

<h4>Build</h4>

<p>Create portfolio projects.</p>

</div>

</div>

<div class="col-md-3">

<div class="process-card">

<div class="process-number">

04

</div>

<i class="bi bi-trophy-fill"></i>

<h4>Graduate</h4>

<p>Receive your certificate.</p>

</div>

</div>

</div>

</div>

</section>

<!-- ====================================== -->
<!-- TESTIMONIALS -->
<!-- ====================================== -->

<section class="testimonials py-5" id="testimonials">

<div class="container">

<div class="text-center mb-5">

<span class="section-tag">

REVIEWS

</span>

<h2 class="section-title">

What Our Students Say

</h2>

</div>

<div class="row g-4">

<div class="col-lg-4">

<div class="testimonial-card">

<img src="assets/images/student1.jpg"
class="testimonial-img">

<h5>

Sarah Johnson

</h5>

<span>

Frontend Developer

</span>

<div class="stars">

★★★★★

</div>

<p>

LetsCode helped me build my portfolio and land my internship.

</p>

</div>

</div>

<div class="col-lg-4">

<div class="testimonial-card">

<img src="assets/images/student2.jpg"
class="testimonial-img">

<h5>

Michael Tan

</h5>

<span>

UI Designer

</span>

<div class="stars">

★★★★★

</div>

<p>

The mentors are amazing and every project feels like real work.

</p>

</div>

</div>

<div class="col-lg-4">

<div class="testimonial-card">

<img src="assets/images/student3.jpg"
class="testimonial-img">

<h5>

Amanda Lee

</h5>

<span>

Software Engineer

</span>

<div class="stars">

★★★★★

</div>

<p>

The internship preparation sessions really boosted my confidence.

</p>

</div>

</div>

</div>

</div>

</section>

<!-- ====================================== -->
<!-- FAQ -->
<!-- ====================================== -->

<section class="faq py-5">

<div class="container">

<div class="text-center mb-5">

<span class="section-tag">

FAQ

</span>

<h2 class="section-title">

Frequently Asked Questions

</h2>

</div>

<div class="accordion accordion-flush"
id="faqAccordion">

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#faq1">

How long does each course last?

</button>

</h2>

<div id="faq1"
class="accordion-collapse collapse"
data-bs-parent="#faqAccordion">

<div class="accordion-body">

Most courses last between 8 and 12 weeks depending on the program.

</div>

</div>

</div>

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#faq2">

Do I receive a certificate?

</button>

</h2>

<div id="faq2"
class="accordion-collapse collapse"
data-bs-parent="#faqAccordion">

<div class="accordion-body">

Yes! Every completed course includes an official certificate.

</div>

</div>

</div>

<div class="accordion-item">

<h2 class="accordion-header">

<button class="accordion-button collapsed"
data-bs-toggle="collapse"
data-bs-target="#faq3">

Can beginners join?

</button>

</h2>

<div id="faq3"
class="accordion-collapse collapse"
data-bs-parent="#faqAccordion">

<div class="accordion-body">

Absolutely! We provide beginner-friendly learning paths.

</div>

</div>

</div>

</div>

</div>

</section>

<!-- ====================================== -->
<!-- CTA -->
<!-- ====================================== -->

<section class="cta py-5">

<div class="container">

<div class="cta-box">

<h2>

Ready To Start Your Tech Journey?

</h2>

<p>

Join thousands of students learning modern technology today.

</p>

<a href="../signup_page/signup.php"
class="btn btn-purple btn-lg">

Enroll Now

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

<h5>

Company

</h5>

<ul>

<li><a href="#">About</a></li>

<li><a href="#">Courses</a></li>

</ul>

</div>

<div class="col-lg-3">

<h5>

Contact

</h5>

<p>

Email: hello@letscode.id

</p>

<p>

Phone: +62 812 3456 7890

</p>

</div>

<div class="col-lg-3">

<h5>

Follow Us

</h5>

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

    <!-- JS -->
    <script src="assets/js/script.js"></script>

</body>
</html>