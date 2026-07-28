<?php
/**
 * Static curriculum data for every course offered. Each course has a list of
 * modules, and each module has a list of lessons. Lesson IDs must be unique
 * *within* a course (they're combined with the course name when tracking
 * completion), and are never reused/renumbered once published.
 */

function all_courses_list(): array
{
    return [
        'Web Development',
        'Mobile App Development',
        'UI / UX Design',
        'Cyber Security',
        'Data Analytics',
        'Artificial Intelligence',
    ];
}

function course_icon(string $course): string
{
    $icons = [
        'Web Development'          => 'bi-code-slash',
        'Mobile App Development'   => 'bi-phone',
        'UI / UX Design'           => 'bi-palette-fill',
        'Cyber Security'           => 'bi-shield-lock-fill',
        'Data Analytics'           => 'bi-bar-chart-fill',
        'Artificial Intelligence'  => 'bi-cpu-fill',
    ];

    return $icons[$course] ?? 'bi-mortarboard-fill';
}

function course_curriculum(string $course): array
{
    $catalog = [

        'Web Development' => [
            [
                'title' => 'Getting Started',
                'lessons' => [
                    ['id' => 'wd-01', 'title' => 'Orientation & Dev Environment Setup', 'duration' => '15 min'],
                    ['id' => 'wd-02', 'title' => 'How the Web Works', 'duration' => '20 min'],
                    ['id' => 'wd-03', 'title' => 'HTML Fundamentals', 'duration' => '35 min'],
                    ['id' => 'wd-04', 'title' => 'CSS Basics & the Box Model', 'duration' => '30 min'],
                ],
            ],
            [
                'title' => 'Core Fundamentals',
                'lessons' => [
                    ['id' => 'wd-05', 'title' => 'Responsive Layout with Flexbox & Grid', 'duration' => '40 min'],
                    ['id' => 'wd-06', 'title' => 'JavaScript Essentials', 'duration' => '45 min'],
                    ['id' => 'wd-07', 'title' => 'DOM Manipulation & Events', 'duration' => '35 min'],
                    ['id' => 'wd-08', 'title' => 'Working with APIs & Fetch', 'duration' => '30 min'],
                ],
            ],
            [
                'title' => 'Hands-On Projects',
                'lessons' => [
                    ['id' => 'wd-09', 'title' => 'Building a Component Library', 'duration' => '50 min'],
                    ['id' => 'wd-10', 'title' => 'Intro to React', 'duration' => '55 min'],
                    ['id' => 'wd-11', 'title' => 'Connecting a Backend', 'duration' => '45 min'],
                    ['id' => 'wd-12', 'title' => 'Portfolio Capstone Project', 'duration' => '90 min'],
                ],
            ],
            [
                'title' => 'Final Certification',
                'lessons' => [
                    ['id' => 'wd-13', 'title' => 'Code Review & Best Practices', 'duration' => '25 min'],
                    ['id' => 'wd-14', 'title' => 'Deployment & Hosting', 'duration' => '30 min'],
                    ['id' => 'wd-15', 'title' => 'Final Assessment', 'duration' => '60 min'],
                ],
            ],
        ],

        'Mobile App Development' => [
            [
                'title' => 'Getting Started',
                'lessons' => [
                    ['id' => 'mad-01', 'title' => 'Orientation & Toolchain Setup', 'duration' => '15 min'],
                    ['id' => 'mad-02', 'title' => 'Mobile UI Principles', 'duration' => '20 min'],
                    ['id' => 'mad-03', 'title' => 'Intro to React Native', 'duration' => '35 min'],
                ],
            ],
            [
                'title' => 'Core Fundamentals',
                'lessons' => [
                    ['id' => 'mad-04', 'title' => 'Navigation & Screens', 'duration' => '30 min'],
                    ['id' => 'mad-05', 'title' => 'State Management', 'duration' => '40 min'],
                    ['id' => 'mad-06', 'title' => 'Device APIs (Camera, Location)', 'duration' => '35 min'],
                ],
            ],
            [
                'title' => 'Hands-On Projects',
                'lessons' => [
                    ['id' => 'mad-07', 'title' => 'Building a To-Do App', 'duration' => '45 min'],
                    ['id' => 'mad-08', 'title' => 'Connecting to a REST API', 'duration' => '40 min'],
                    ['id' => 'mad-09', 'title' => 'Capstone: Full Mobile App', 'duration' => '90 min'],
                ],
            ],
            [
                'title' => 'Final Certification',
                'lessons' => [
                    ['id' => 'mad-10', 'title' => 'App Store Submission Basics', 'duration' => '25 min'],
                    ['id' => 'mad-11', 'title' => 'Final Assessment', 'duration' => '60 min'],
                ],
            ],
        ],

        'UI / UX Design' => [
            [
                'title' => 'Getting Started',
                'lessons' => [
                    ['id' => 'uiux-01', 'title' => 'Orientation & Design Tools Setup', 'duration' => '15 min'],
                    ['id' => 'uiux-02', 'title' => 'Design Thinking Fundamentals', 'duration' => '25 min'],
                    ['id' => 'uiux-03', 'title' => 'User Research Basics', 'duration' => '30 min'],
                ],
            ],
            [
                'title' => 'Core Fundamentals',
                'lessons' => [
                    ['id' => 'uiux-04', 'title' => 'Wireframing', 'duration' => '30 min'],
                    ['id' => 'uiux-05', 'title' => 'Visual Design & Typography', 'duration' => '35 min'],
                    ['id' => 'uiux-06', 'title' => 'Prototyping in Figma', 'duration' => '40 min'],
                ],
            ],
            [
                'title' => 'Hands-On Projects',
                'lessons' => [
                    ['id' => 'uiux-07', 'title' => 'Usability Testing', 'duration' => '35 min'],
                    ['id' => 'uiux-08', 'title' => 'Design Systems', 'duration' => '40 min'],
                    ['id' => 'uiux-09', 'title' => 'Capstone: End-to-End App Design', 'duration' => '90 min'],
                ],
            ],
            [
                'title' => 'Final Certification',
                'lessons' => [
                    ['id' => 'uiux-10', 'title' => 'Portfolio Presentation', 'duration' => '25 min'],
                    ['id' => 'uiux-11', 'title' => 'Final Assessment', 'duration' => '60 min'],
                ],
            ],
        ],

        'Cyber Security' => [
            [
                'title' => 'Getting Started',
                'lessons' => [
                    ['id' => 'cyb-01', 'title' => 'Orientation & Lab Environment Setup', 'duration' => '15 min'],
                    ['id' => 'cyb-02', 'title' => 'Security Fundamentals & CIA Triad', 'duration' => '25 min'],
                    ['id' => 'cyb-03', 'title' => 'Networking Basics for Security', 'duration' => '35 min'],
                ],
            ],
            [
                'title' => 'Core Fundamentals',
                'lessons' => [
                    ['id' => 'cyb-04', 'title' => 'Common Vulnerabilities & Attacks', 'duration' => '40 min'],
                    ['id' => 'cyb-05', 'title' => 'Cryptography Essentials', 'duration' => '35 min'],
                    ['id' => 'cyb-06', 'title' => 'Threat Modeling', 'duration' => '30 min'],
                ],
            ],
            [
                'title' => 'Hands-On Projects',
                'lessons' => [
                    ['id' => 'cyb-07', 'title' => 'Penetration Testing Basics', 'duration' => '50 min'],
                    ['id' => 'cyb-08', 'title' => 'Incident Response Simulation', 'duration' => '45 min'],
                    ['id' => 'cyb-09', 'title' => 'Capstone: Security Audit', 'duration' => '90 min'],
                ],
            ],
            [
                'title' => 'Final Certification',
                'lessons' => [
                    ['id' => 'cyb-10', 'title' => 'Compliance & Best Practices', 'duration' => '25 min'],
                    ['id' => 'cyb-11', 'title' => 'Final Assessment', 'duration' => '60 min'],
                ],
            ],
        ],

        'Data Analytics' => [
            [
                'title' => 'Getting Started',
                'lessons' => [
                    ['id' => 'da-01', 'title' => 'Orientation & Tooling Setup', 'duration' => '15 min'],
                    ['id' => 'da-02', 'title' => 'Data Analysis Mindset', 'duration' => '20 min'],
                    ['id' => 'da-03', 'title' => 'Excel & Spreadsheets for Analysis', 'duration' => '30 min'],
                ],
            ],
            [
                'title' => 'Core Fundamentals',
                'lessons' => [
                    ['id' => 'da-04', 'title' => 'SQL Fundamentals', 'duration' => '40 min'],
                    ['id' => 'da-05', 'title' => 'Python for Data Analysis', 'duration' => '45 min'],
                    ['id' => 'da-06', 'title' => 'Data Cleaning & Wrangling', 'duration' => '35 min'],
                ],
            ],
            [
                'title' => 'Hands-On Projects',
                'lessons' => [
                    ['id' => 'da-07', 'title' => 'Data Visualization', 'duration' => '40 min'],
                    ['id' => 'da-08', 'title' => 'Building Dashboards', 'duration' => '45 min'],
                    ['id' => 'da-09', 'title' => 'Capstone: Business Insights Report', 'duration' => '90 min'],
                ],
            ],
            [
                'title' => 'Final Certification',
                'lessons' => [
                    ['id' => 'da-10', 'title' => 'Presenting Data to Stakeholders', 'duration' => '25 min'],
                    ['id' => 'da-11', 'title' => 'Final Assessment', 'duration' => '60 min'],
                ],
            ],
        ],

        'Artificial Intelligence' => [
            [
                'title' => 'Getting Started',
                'lessons' => [
                    ['id' => 'ai-01', 'title' => 'Orientation & Python Refresher', 'duration' => '15 min'],
                    ['id' => 'ai-02', 'title' => 'Math Foundations for AI', 'duration' => '30 min'],
                    ['id' => 'ai-03', 'title' => 'Intro to Machine Learning', 'duration' => '35 min'],
                ],
            ],
            [
                'title' => 'Core Fundamentals',
                'lessons' => [
                    ['id' => 'ai-04', 'title' => 'Supervised & Unsupervised Learning', 'duration' => '40 min'],
                    ['id' => 'ai-05', 'title' => 'Neural Networks with TensorFlow', 'duration' => '50 min'],
                    ['id' => 'ai-06', 'title' => 'Working with Large Language Models', 'duration' => '40 min'],
                ],
            ],
            [
                'title' => 'Hands-On Projects',
                'lessons' => [
                    ['id' => 'ai-07', 'title' => 'Training an Image Classifier', 'duration' => '50 min'],
                    ['id' => 'ai-08', 'title' => 'Building a Chatbot', 'duration' => '45 min'],
                    ['id' => 'ai-09', 'title' => 'Capstone: End-to-End ML Project', 'duration' => '90 min'],
                ],
            ],
            [
                'title' => 'Final Certification',
                'lessons' => [
                    ['id' => 'ai-10', 'title' => 'Responsible & Ethical AI', 'duration' => '25 min'],
                    ['id' => 'ai-11', 'title' => 'Final Assessment', 'duration' => '60 min'],
                ],
            ],
        ],

    ];

    return $catalog[$course] ?? [];
}

function course_total_lessons(string $course): int
{
    $count = 0;
    foreach (course_curriculum($course) as $module) {
        $count += count($module['lessons']);
    }
    return $count;
}
