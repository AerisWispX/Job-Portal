<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'jobs', 'companies', 'users'];
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - <?php echo ucfirst($page); ?></title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .tab-content {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .tab-content[style*="display: block"] {
            opacity: 1;
            transform: translateY(0);
        }
        
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            background: var(--card-light);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .analytics-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(132, 116, 203, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                <div class="profile-icon">A</div>
                <h2>Admin Portal</h2>
            </div>
            <nav>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="?page=dashboard" class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=jobs" class="nav-link <?php echo $page === 'jobs' ? 'active' : ''; ?>">
                            <i class="fas fa-briefcase"></i> Job Listings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=companies" class="nav-link <?php echo $page === 'companies' ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i> Companies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=users" class="nav-link <?php echo $page === 'users' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                </ul>
            </nav>
            <div style="margin-top: auto;">
              
                <a href="logout.php" class="btn btn-primary btn-full">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>
        <button id="darkModeToggle" class="btn btn-secondary" style="position: fixed; bottom: 2rem; right: 2rem;">
        <i class="fas fa-moon"></i>
    </button>
        <main class="main-content">
            <?php
    
            $page_path = "pages/admin/{$page}.php";
            if (file_exists($page_path)) {
                include $page_path;
            } else {
                echo '<div class="alert alert-danger">Page not found</div>';
            }
            ?>
        </main>
    </div>

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