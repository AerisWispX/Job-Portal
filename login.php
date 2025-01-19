<?php
session_start();
require_once 'db_connect.php';

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];
            if($user['user_type'] == 'admin') {
                header('Location: admin_dashboard.php');
            }
            else if($user['user_type'] == 'company') {
                header('Location: company_dashboard.php');
            }
            else if($user['user_type'] == 'student') {
                header('Location: student_dashboard.php');
            }
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Job Portal</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(132, 116, 203, 0.1), rgba(237, 100, 166, 0.1));
        }

        .login-card {
            background-color: var(--card-light);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-title {
            font-size: 2rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        .input-group input {
            padding-left: 3rem;
            width: 100%;
            box-sizing: border-box;
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            background-color: rgba(245, 101, 101, 0.1);
            color: var(--danger-color);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        body.dark-mode .login-card {
            background-color: var(--card-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .login-title,
        body.dark-mode .form-group label {
            color: var(--text-dark);
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
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Welcome Back</h1>
                <p style="color: var(--secondary-color);">Sign in to continue to your account</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="register.php">Create one</a></p>
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

        if(localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark-mode');
        }
    </script>
</body>
</html>