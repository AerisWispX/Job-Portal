<?php
session_start();
require_once 'utils.php';

if (is_logged_in()) {
    $user_type = get_user_type();
    switch ($user_type) {
        case 'admin':
            header('Location: admin_dashboard.php');
            break;
        case 'company':
            header('Location: company_dashboard.php');
            break;
        case 'student':
            header('Location: student_dashboard.php');
            break;
        default:

            session_destroy();
            header('Location: login.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Find Your Dream Job</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .hero-section {
            padding: 4rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, rgba(132, 116, 203, 0.1), rgba(237, 100, 166, 0.1));
            border-radius: 24px;
            margin: 2rem;
        }

        .hero-title {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--secondary-color);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            background-color: var(--card-light);
            padding: 1.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 1rem;
        }

        .features-section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background-color: var(--card-light);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .feature-description {
            color: var(--secondary-color);
            line-height: 1.6;
        }
        body.dark-mode .hero-title {
            color: var(--text-dark);
        }

        body.dark-mode .hero-subtitle,
        body.dark-mode .stat-label,
        body.dark-mode .feature-description {
            color: var(--text-dark);
        }

        body.dark-mode .stat-card,
        body.dark-mode .feature-card {
            background-color: var(--card-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-buttons {
                flex-direction: column;
                padding: 0 2rem;
            }

            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .stars-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 0;
}

body.dark-mode .stars-container {
    opacity: 1;
}

.star {
    position: absolute;
    background: white;
    border-radius: 50%;
    animation: twinkle var(--twinkle-duration) infinite ease-in-out,
               move var(--move-duration) infinite linear;
    opacity: var(--star-opacity);
}

@keyframes twinkle {
    0%, 100% { opacity: var(--star-opacity); }
    50% { opacity: 0.2; }
}

@keyframes move {
    from {
        transform: translateZ(0) translateY(0);
    }
    to {
        transform: translateZ(100px) translateY(100vh);
    }
}
.hero-section, .stats-section, .features-section {
    position: relative;
    z-index: 1;
}
.header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: var(--card-light);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        body.dark-mode .header {
            background-color: var(--card-dark);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        body.dark-mode .nav-menu a {
            color: var(--text-dark);
        }

        .nav-menu a:hover {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .nav-menu {
                gap: 1rem;
            }
        }
    </style>
    <header class="header">
        <a href="index.php" class="logo">
            <i class="fas fa-briefcase"></i>
            Youth Bank
        </a>
        <nav class="nav-menu">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact Us</a>
            <a href="login.php" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </a>
        </nav>
    </header>

<script>
function createStars() {
    const starsContainer = document.createElement('div');
    starsContainer.className = 'stars-container';
    document.body.prepend(starsContainer);

    const numberOfStars = 150;

    for (let i = 0; i < numberOfStars; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        const size = Math.random() * 2 + 1;
        star.style.width = `${size}px`;
        star.style.height = `${size}px`;
        star.style.left = `${Math.random() * 100}%`;
        star.style.top = `${Math.random() * 100}%`;
        star.style.setProperty('--twinkle-duration', `${Math.random() * 3 + 2}s`);
        star.style.setProperty('--move-duration', `${Math.random() * 100 + 50}s`);
        star.style.setProperty('--star-opacity', Math.random() * 0.5 + 0.5);

        starsContainer.appendChild(star);
    }
}
document.addEventListener('DOMContentLoaded', createStars);
const originalDarkModeCode = darkModeToggle.onclick;
darkModeToggle.onclick = function() {
    body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
    if (body.classList.contains('dark-mode')) {
        body.style.background = 'linear-gradient(to bottom, #1a1c25, #2d3748)';
    } else {
        body.style.background = '';
    }
};
if (localStorage.getItem('darkMode') === 'true') {
    body.classList.add('dark-mode');
    body.style.background = 'linear-gradient(to bottom, #1a1c25, #2d3748)';
}
</script>
</head>
<body>
    <div class="hero-section">
        <h1 class="hero-title">Find Your Dream Job Today</h1>
        <p class="hero-subtitle">Connect with top companies and opportunities that match your skills and aspirations. Start your journey to success with us.</p>
        <div class="hero-buttons">
            <a href="login.php" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </a>
            <a href="register.php" class="btn btn-secondary">
                <i class="fas fa-user-plus"></i> Create Account
            </a>
        </div>
    </div>

    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-number">5000+</div>
            <div class="stat-label">Active Jobs</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">2000+</div>
            <div class="stat-label">Companies</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">10000+</div>
            <div class="stat-label">Job Seekers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">95%</div>
            <div class="stat-label">Success Rate</div>
        </div>
    </div>

    <div class="features-section">
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-search feature-icon"></i>
                <h3 class="feature-title">Smart Job Matching</h3>
                <p class="feature-description">Our AI-powered system matches you with jobs that perfectly align with your skills and experience.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-building feature-icon"></i>
                <h3 class="feature-title">Top Companies</h3>
                <p class="feature-description">Connect with leading companies across industries looking for talented professionals like you.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-clock feature-icon"></i>
                <h3 class="feature-title">Real-time Updates</h3>
                <p class="feature-description">Get instant notifications about new job postings and application status updates.</p>
            </div>
        </div>
    </div>

    <button id="darkModeToggle" class="btn btn-secondary" style="position: fixed; bottom: 2rem; right: 2rem;">
        <i class="fas fa-moon"></i>
    </button>

    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
        });

        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark-mode');
        }
    </script>
</body>
</html>
