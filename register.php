<?php
require_once 'db_connect.php';

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];


    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if(mysqli_num_rows($result) > 0) {
        $error = "Email address already exists! Please use a different email.";
    } else {

        $query = "INSERT INTO users (username, email, password, user_type) 
                  VALUES ('$username', '$email', '$password', '$user_type')";
        
        if(mysqli_query($conn, $query)) {
            $user_id = mysqli_insert_id($conn);
            if($user_type === 'company') {
                $company_name = $_POST['company_name'];
                $query = "INSERT INTO company_profiles (user_id, company_name) 
                         VALUES ($user_id, '$company_name')";
                mysqli_query($conn, $query);
            } 
            elseif($user_type === 'student') {
                $full_name = $_POST['full_name'];
                $query = "INSERT INTO student_profiles (user_id, full_name) 
                         VALUES ($user_id, '$full_name')";
                mysqli_query($conn, $query);
            }
            header('Location: login.php');
            exit();
        } else {
            $error = "Registration failed! Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Portal</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(132, 116, 203, 0.1), rgba(237, 100, 166, 0.1));
        }

        .register-card {
            background-color: var(--card-light);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-title {
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

        .input-group input,
        .input-group select {
            padding-left: 3rem;
            width: 100%;
            box-sizing: border-box;
        }

        .register-footer {
            text-align: center;
            margin-top: 2rem;
        }

        .register-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .register-footer a:hover {
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

        /* Dark mode adjustments */
        body.dark-mode .register-card {
            background-color: var(--card-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode .register-title,
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
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1 class="register-title">Create Account</h1>
                <p style="color: var(--secondary-color);">Join our community and start your journey</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="register-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="user_type">I am a</label>
                    <div class="input-group">
                        <i class="fas fa-users"></i>
                        <select id="user_type" name="user_type" required>
                            <option value="">Select account type</option>
                            <option value="company">Company / Employer</option>
                            <option value="student">Student / Job Seeker</option>
                        </select>
                    </div>
                </div>
                
                <div id="company_fields" class="form-group" style="display: none;">
                    <label for="company_name">Company Name</label>
                    <div class="input-group">
                        <i class="fas fa-building"></i>
                        <input type="text" id="company_name" name="company_name">
                    </div>
                </div>
                
                <div id="student_fields" class="form-group" style="display: none;">
                    <label for="full_name">Full Name</label>
                    <div class="input-group">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="full_name" name="full_name">
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="register-footer">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </div>

    <button id="darkModeToggle" class="btn btn-secondary" style="position: fixed; bottom: 2rem; right: 2rem;">
        <i class="fas fa-moon"></i>
    </button>

    <script>
        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
        });

        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark-mode');
        }

        // Form field toggle based on user type
        document.getElementById('user_type').addEventListener('change', function() {
            var companyFields = document.getElementById('company_fields');
            var studentFields = document.getElementById('student_fields');
            
            if (this.value === 'company') {
                companyFields.style.display = 'block';
                studentFields.style.display = 'none';
                document.getElementById('company_name').required = true;
                document.getElementById('full_name').required = false;
            } else if (this.value === 'student') {
                companyFields.style.display = 'none';
                studentFields.style.display = 'block';
                document.getElementById('company_name').required = false;
                document.getElementById('full_name').required = true;
            } else {
                companyFields.style.display = 'none';
                studentFields.style.display = 'none';
                document.getElementById('company_name').required = false;
                document.getElementById('full_name').required = false;
            }
        });
    </script>
</body>
</html>