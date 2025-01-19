<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Youth Bank</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .contact-hero {
            padding: 4rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, rgba(132, 116, 203, 0.1), rgba(237, 100, 166, 0.1));
            border-radius: 24px;
            margin: 2rem;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .contact-form {
            background-color: var(--card-light);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .contact-info {
            background-color: var(--card-light);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .contact-form,
        body.dark-mode .contact-info {
            background-color: var(--card-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .contact-method {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .contact-method i {
            font-size: 1.5rem;
            color: var(--primary-color);
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

    <div class="contact-hero">
        <h1 class="hero-title">Contact Us</h1>
        <p class="hero-subtitle">We're here to help. Reach out to us for any queries or support.</p>
    </div>

    <div class="contact-grid">
        <div class="contact-form">
            <h2 class="feature-title">Send us a message</h2>
            <form action="process_contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>

        <div class="contact-info">
            <h2 class="feature-title">Other ways to reach us</h2>
            <div class="contact-method">
                <i class="fas fa-envelope"></i>
                <div>
                    <h3>Email</h3>
                    <p>support@youthbank.com</p>
                </div>
            </div>
            <div class="contact-method">
                <i class="fas fa-phone"></i>
                <div>
                    <h3>Phone</h3>
                    <p>+1 (555) 123-4567</p>
                </div>
            </div>
            <div class="contact-method">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h3>Office</h3>
                    <p>123 Innovation Street<br>Tech City, TC 12345</p>
                </div>
            </div>
            <div class="contact-method">
                <i class="fas fa-clock"></i>
                <div>
                    <h3>Business Hours</h3>
                    <p>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 2PM</p>
                </div>
            </div>
        </div>
    </div>

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