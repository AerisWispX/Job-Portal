
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';

$job_id = $_GET['id'];

$job_query = "SELECT j.*, c.company_name, c.website, u.email as company_email
              FROM job_listings j 
              LEFT JOIN company_profiles c ON j.company_id = c.id
              LEFT JOIN users u ON c.user_id = u.id
              WHERE j.id = $job_id";
$job_result = mysqli_query($conn, $job_query);
$job = mysqli_fetch_assoc($job_result);
$applications_query = "SELECT ja.*, sp.full_name, u.email
                      FROM job_applications ja
                      JOIN student_profiles sp ON ja.student_id = sp.id
                      JOIN users u ON sp.user_id = u.id
                      WHERE ja.job_id = $job_id";
$applications_result = mysqli_query($conn, $applications_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <main class="main-content" style="margin: 2rem auto; max-width: 1000px;">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2 style="margin: 0;">Job Details</h2>
                    <a href="admin_dashboard.php?page=jobs" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Jobs
                    </a>
                </div>

                <div class="job-details">
                    <div class="details-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <h3><?php echo $job['title']; ?></h3>
                            <p><strong>Company:</strong> <?php echo $job['company_name']; ?></p>
                            <p><strong>Location:</strong> <?php echo $job['location']; ?></p>
                            <p><strong>Salary:</strong> <?php echo $job['salary']; ?></p>
                            <p><strong>Status:</strong> 
                                <span class="status-badge status-<?php echo $job['status']; ?>">
                                    <?php echo ucfirst($job['status']); ?>
                                </span>
                            </p>
                        </div>
                        <div>
                            <p><strong>Company Email:</strong> <?php echo $job['company_email']; ?></p>
                            <p><strong>Website:</strong> <?php echo $job['website'] ?: 'Not provided'; ?></p>
                            <p><strong>Posted Date:</strong> <?php echo date('M d, Y', strtotime($job['posted_at'])); ?></p>
                        </div>
                    </div>

                    <div class="description-section" style="margin-bottom: 2rem;">
                        <h3>Job Description</h3>
                        <p><?php echo nl2br($job['description']); ?></p>
                    </div>

                    <div class="requirements-section" style="margin-bottom: 2rem;">
                        <h3>Requirements</h3>
                        <p><?php echo nl2br($job['requirements']); ?></p>
                    </div>

                    <h3>Applications (<?php echo mysqli_num_rows($applications_result); ?>)</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Application Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($application = mysqli_fetch_assoc($applications_result)) { ?>
                                    <tr>
                                        <td><?php echo $application['full_name']; ?></td>
                                        <td><?php echo $application['email']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($application['application_date'])); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $application['status']; ?>">
                                                <?php echo ucfirst($application['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary" onclick="viewApplication(<?php echo $application['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
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
    <script>
        function viewApplication(applicationId) {
            alert('View application details for ID: ' + applicationId);
        }
    </script>
</body>
</html>