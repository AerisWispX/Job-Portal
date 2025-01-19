<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';
$user_id = (int)$_SESSION['user_id'];

$query = "SELECT * FROM company_profiles WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$company = mysqli_fetch_assoc($result);
$company_id = (int)$company['id'];

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$allowed_pages = ['dashboard', 'jobs', 'applications', 'profile'];
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

if ($page === 'jobs' && isset($_POST['post_job'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $expiration_date = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    $query = "INSERT INTO job_listings 
              (company_id, title, job_type, description, requirements, salary, location, status, expiration_date) 
              VALUES 
              ($company_id, '$title', '$job_type', '$description', '$requirements', '$salary', '$location', 'pending', '$expiration_date')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: company_dashboard.php?page=jobs&success=1');
        exit();
    }
}
if ($page === 'profile' && isset($_POST['update_profile'])) {
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    
    $update_query = "UPDATE company_profiles 
                    SET company_name = '$company_name',
                        description = '$description',
                        website = '$website'
                    WHERE id = $company_id";
        
    if (mysqli_query($conn, $update_query)) {
        header('Location: company_dashboard.php?page=profile&updated=1');
        exit();
    }
}
if ($page === 'jobs' && isset($_POST['delete_job'])) {
    $job_id = (int)$_POST['job_id'];
    $delete_query = "DELETE FROM job_listings WHERE id = $job_id AND company_id = $company_id";
    
    if (mysqli_query($conn, $delete_query)) {
        header('Location: company_dashboard.php?page=jobs&deleted=1');
        exit();
    }
}
if ($page === 'applications' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $application_id = (int)$_POST['application_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE job_applications 
                    SET status = '$new_status' 
                    WHERE id = $application_id 
                    AND job_id IN (SELECT id FROM job_listings WHERE company_id = $company_id)";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: company_dashboard.php?page=applications&status_updated=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Portal - <?php echo ucfirst($page); ?></title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body.dark-mode .insights-grid .insight-card,
body.dark-mode .recent-activities .recent-jobs,
body.dark-mode .recent-activities .recent-applications {
    background: var(--card-dark);
}

body.dark-mode .metric-label {
    color: rgba(226, 232, 240, 0.7);
}

body.dark-mode .recent-jobs table th, 
body.dark-mode .recent-applications table th,
body.dark-mode .recent-jobs table td, 
body.dark-mode .recent-applications table td {
    border-bottom: 1px solid rgba(132, 116, 203, 0.2);
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

        .job-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .image-upload-form {
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(132, 116, 203, 0.1);
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                <div class="profile-icon"><?php echo substr($company['company_name'], 0, 1); ?></div>
                <h2><?php echo $company['company_name']; ?></h2>
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
                        <a href="?page=applications" class="nav-link <?php echo $page === 'applications' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i> Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=profile" class="nav-link <?php echo $page === 'profile' ? 'active' : ''; ?>">
                            <i class="fas fa-user"></i> Company Profile
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
    
            $page_path = "pages/company/{$page}.php";
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