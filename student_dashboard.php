<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';

$user_id = (int)$_SESSION['user_id'];
$query = "SELECT * FROM student_profiles WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

function calculateProfileCompletion($student) {
    $total_fields = 5; 
    $filled_fields = 0;
    
    if (!empty($student['full_name'])) $filled_fields++;
    if (!empty($student['date_of_birth'])) $filled_fields++;
    if (!empty($student['education'])) $filled_fields++;
    if (!empty($student['skills'])) $filled_fields++;
    if (!empty($student['resume_path'])) $filled_fields++;
    
    return ($filled_fields / $total_fields) * 100;
}

$completion_percentage = calculateProfileCompletion($student);
$is_profile_complete = $completion_percentage === 100;

if (isset($_POST['job_id'])) {
    $job_id = (int)$_POST['job_id'];
    $student_id = (int)$student['id'];
    
    $query = "INSERT INTO job_applications (job_id, student_id, status) VALUES ($job_id, $student_id, 'pending')";
    mysqli_query($conn, $query);
    
    header('Location: ?page=dashboard');
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'profile', 'jobs', 'applications', 'resume'];
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - <?php echo ucfirst($page); ?></title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .profile-complete-message {
        background-color: rgba(22, 163, 74, 0.1);
        color: #16a34a;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }

    body.dark-mode .profile-complete-message {
        background-color: rgba(22, 163, 74, 0.2);
    }

    .profile-complete-message i {
        font-size: 1.25rem;
    }
        .tab-content {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .tab-content[style*="display: block"] {
            opacity: 1;
            transform: translateY(0);
        }

        .job-card {
            transition: transform 0.3s ease;
        }

       
        .profile-progress {
            background-color: var(--card-light);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        body.dark-mode .profile-progress {
            background-color: var(--card-dark);
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .progress-title {
            font-size: 1rem;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .progress-percentage {
            font-weight: bold;
            color: var(--primary-color);
        }

        .progress-bar-container {
            width: 100%;
            height: 8px;
            background-color: rgba(132, 116, 203, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .progress-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .progress-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .progress-item i {
            color: var(--primary-color);
        }

        .progress-item.completed {
            color: var(--primary-color);
        }

        .progress-item.pending {
            color: var(--secondary-color);
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                <div class="profile-icon"><?php echo htmlspecialchars(substr($student['full_name'], 0, 1)); ?></div>
                <h2><?php echo htmlspecialchars($student['full_name']); ?></h2>
            </div>

       
            <?php if ($is_profile_complete): ?>
    <div class="profile-complete-message">
        <i class="fas fa-check-circle"></i>
        Profile Completed
    </div>
<?php else: ?>
    <div class="profile-progress">
        <div class="progress-header">
            <span class="progress-title">
                <i class="fas fa-user-circle"></i>
                Profile Completion
            </span>
            <span class="progress-percentage"><?php echo round($completion_percentage); ?>%</span>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar" style="width: <?php echo $completion_percentage; ?>%"></div>
        </div>
        <div class="progress-items">
            <div class="progress-item <?php echo !empty($student['full_name']) ? 'completed' : 'pending'; ?>">
                <i class="fas <?php echo !empty($student['full_name']) ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                Basic Info
            </div>
            <div class="progress-item <?php echo !empty($student['education']) ? 'completed' : 'pending'; ?>">
                <i class="fas <?php echo !empty($student['education']) ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                Education
            </div>
            <div class="progress-item <?php echo !empty($student['skills']) ? 'completed' : 'pending'; ?>">
                <i class="fas <?php echo !empty($student['skills']) ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                Skills
            </div>
            <div class="progress-item <?php echo !empty($student['resume_path']) ? 'completed' : 'pending'; ?>">
                <i class="fas <?php echo !empty($student['resume_path']) ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                Resume
            </div>
        </div>
    </div>
<?php endif; ?>

            <nav>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="?page=dashboard" class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=profile" class="nav-link <?php echo $page === 'profile' ? 'active' : ''; ?>">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=jobs" class="nav-link <?php echo $page === 'jobs' ? 'active' : ''; ?>">
                            <i class="fas fa-briefcase"></i> Jobs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=applications" class="nav-link <?php echo $page === 'applications' ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i> Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=resume" class="nav-link <?php echo $page === 'resume' ? 'active' : ''; ?>">
                            <i class="fas fa-file-pdf"></i> Resume
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
            $page_path = "pages/student/{$page}.php";
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