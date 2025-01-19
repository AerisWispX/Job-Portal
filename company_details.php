<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';

$company_id = $_GET['id'];

// Fetch company details
$company_query = "SELECT cp.*, u.email, u.username, u.created_at
                 FROM company_profiles cp
                 JOIN users u ON cp.user_id = u.id
                 WHERE cp.id = $company_id";
$company_result = mysqli_query($conn, $company_query);
$company = mysqli_fetch_assoc($company_result);

// Fetch company's job listings
$jobs_query = "SELECT * FROM job_listings WHERE company_id = $company_id ORDER BY posted_at DESC";
$jobs_result = mysqli_query($conn, $jobs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Details</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <main class="main-content" style="margin: 2rem auto; max-width: 1000px;">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2 style="margin: 0;">Company Details</h2>
                    <a href="admin_dashboard.php?page=companies" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Companies
                    </a>
                </div>

                <div class="company-details">
                    <div class="details-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <h3><?php echo $company['company_name'] ?: 'Company Name Not Set'; ?></h3>
                            <p><strong>Username:</strong> <?php echo $company['username']; ?></p>
                            <p><strong>Email:</strong> <?php echo $company['email']; ?></p>
                            <p><strong>Website:</strong> <?php echo $company['website'] ?: 'Not provided'; ?></p>
                        </div>
                        <div>
                            <p><strong>Joined Date:</strong> <?php echo date('M d, Y', strtotime($company['created_at'])); ?></p>
                            <p><strong>Total Jobs Posted:</strong> <?php echo mysqli_num_rows($jobs_result); ?></p>
                        </div>
                    </div>

                    <?php if($company['description']) { ?>
                        <div class="description-section" style="margin-bottom: 2rem;">
                            <h3>Company Description</h3>
                            <p><?php echo nl2br($company['description']); ?></p>
                        </div>
                    <?php } ?>

                    <h3>Job Listings</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Salary</th>
                                    <th>Posted Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($job = mysqli_fetch_assoc($jobs_result)) { ?>
                                    <tr>
                                        <td><?php echo $job['title']; ?></td>
                                        <td><?php echo $job['location']; ?></td>
                                        <td><?php echo $job['salary']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($job['posted_at'])); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $job['status']; ?>">
                                                <?php echo ucfirst($job['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
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