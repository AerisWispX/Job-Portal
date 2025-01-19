<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Youth Bank</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .about-hero {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(132, 116, 203, 0.1), rgba(237, 100, 166, 0.1));
            padding: 4rem 2rem;
            margin-bottom: 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            text-align: center;
            max-width: 800px;
            z-index: 1;
        }

        .hero-stats {
            display: flex;
            gap: 3rem;
            justify-content: center;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .mission-section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .mission-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .mission-content {
            padding: 2rem;
        }

        .mission-image {
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            height: 400px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }

        .team-section {
            background-color: rgba(132, 116, 203, 0.05);
            padding: 6rem 2rem;
            margin-top: 4rem;
        }

        .team-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .team-card {
            background-color: var(--card-light);
            border-radius: 24px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .team-image {
            height: 250px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }

        .team-info {
            padding: 2rem;
            text-align: center;
        }

        .team-role {
            color: var(--primary-color);
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .social-links a {
            color: var(--secondary-color);
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: var(--primary-color);
        }

        body.dark-mode .team-card {
            background-color: var(--card-dark);
        }

        @media (max-width: 768px) {
            .mission-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 2rem;
            }

            .mission-image {
                height: 300px;
            }
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
</head>
<body>
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

    <section class="about-hero">
        <div class="hero-content">
            <h1 class="hero-title">Empowering Youth Through Opportunities</h1>
            <p class="hero-subtitle">Building bridges between talent and opportunity since 2020</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Job Seekers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2K+</div>
                    <div class="stat-label">Companies</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <section class="mission-section">
        <div class="mission-grid">
            <div class="mission-content">
                <h2 class="hero-title">Our Mission</h2>
                <p class="feature-description">Youth Bank is dedicated to revolutionizing how young talent connects with meaningful career opportunities. We believe in creating a future where every ambitious individual has access to the resources and opportunities they need to thrive.</p>
                <div style="margin-top: 2rem;">
                    <a href="#" class="btn btn-primary">Learn More</a>
                </div>
            </div>
            <div class="mission-image">
                <i class="fas fa-rocket"></i>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="team-container">
            <h2 class="hero-title" style="text-align: center;">Meet Our Leadership</h2>
            <p class="hero-subtitle" style="text-align: center;">The passionate minds behind Youth Bank</p>
            
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="team-info">
                        <h3 class="feature-title">DEVIS PAUL</h3>
                        <div class="team-role">Chief Executive Officer</div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-card">
                    <div class="team-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="team-info">
                        <h3 class="feature-title">AMAL KURIAN TOMY</h3>
                        <div class="team-role">Chief Technology Officer</div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>

                <div class="team-card">
                    <div class="team-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="team-info">
                        <h3 class="feature-title">SEENA S NAIR</h3>
                        <div class="team-role">Head of Operations</div>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <button id="darkModeToggle" class="btn btn-secondary" style="position: fixed; bottom: 2rem; right: 2rem;">
        <i class="fas fa-moon"></i>
    </button>

    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

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
</body>
</html>