<?php
if (isset($_POST['job_id'])) {
    $job_id = (int)$_POST['job_id'];
    $student_id = (int)$student['id'];
    
    $query = "INSERT INTO job_applications (job_id, student_id, status) VALUES ($job_id, $student_id, 'pending')";
    mysqli_query($conn, $query);
    
    header('Location: ?page=dashboard');
    exit();
}

// Get job listings
$query = "SELECT jl.*, cp.company_name FROM job_listings jl 
          JOIN company_profiles cp ON jl.company_id = cp.id 
          WHERE jl.status = 'approved'";
$result = mysqli_query($conn, $query);
$job_listings = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get student's job applications with additional analytics
$student_id = (int)$student['id'];
$query = "SELECT ja.*, jl.title AS job_title, cp.company_name, jl.salary, jl.location
          FROM job_applications ja
          JOIN job_listings jl ON ja.job_id = jl.id
          JOIN company_profiles cp ON jl.company_id = cp.id
          WHERE ja.student_id = $student_id
          ORDER BY ja.application_date DESC";
$result = mysqli_query($conn, $query);
$job_applications = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Calculate analytics
$total_applications = count($job_applications);
$pending_applications = 0;
$accepted_applications = 0;
$rejected_applications = 0;

foreach ($job_applications as $application) {
    switch ($application['status']) {
        case 'pending':
            $pending_applications++;
            break;
        case 'accepted':
            $accepted_applications++;
            break;
        case 'rejected':
            $rejected_applications++;
            break;
    }
}


$success_rate = $total_applications > 0 ? 
    round(($accepted_applications / $total_applications) * 100, 1) : 0;
$query = "SELECT DATE_FORMAT(application_date, '%Y-%m') as month, 
          COUNT(*) as application_count
          FROM job_applications 
          WHERE student_id = $student_id 
          AND application_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
          GROUP BY DATE_FORMAT(application_date, '%Y-%m')
          ORDER BY month DESC";
$result = mysqli_query($conn, $query);
$monthly_stats = mysqli_fetch_all($result, MYSQLI_ASSOC);
$profile_completion = [
    'basic_info' => !empty($student['full_name']) && !empty($student['date_of_birth']),
    'education' => !empty($student['education']),
    'skills' => !empty($student['skills']),
    'resume' => !empty($student['resume_path'])
];
$total_applications = count($job_applications);
$has_applied = $total_applications > 0;
$is_new_user = !$has_applied && $completion_percentage < 100;
?>

<div class="dashboard-header">
    <h1>Welcome, <?php echo htmlspecialchars($student['full_name']); ?>!</h1>
    <p class="text-secondary">Here's your job search overview</p>
</div>
<?php if ($is_new_user): ?>
<div class="get-started-section">
    <div class="get-started-header">
        <h2><i class="fas fa-flag"></i> Get Started with Your Job Search</h2>
        <p>Complete these steps to maximize your chances of finding the perfect job.</p>
    </div>
    
    <div class="steps-grid">
        <!-- Step 1: Complete Profile -->
        <div class="step-card <?php echo $completion_percentage === 100 ? 'completed' : 'active'; ?>">
            <div class="step-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="step-content">
                <h3>Complete Your Profile</h3>
                <p>Add your information to help employers know you better.</p>
                <div class="step-progress">
                    <div class="progress-bar">
                        <div class="progress" style="width: <?php echo $completion_percentage; ?>%"></div>
                    </div>
                    <span><?php echo round($completion_percentage); ?>% Complete</span>
                </div>
                <ul class="step-checklist">
                    <li class="<?php echo $profile_completion['basic_info'] ? 'completed' : ''; ?>">
                        <i class="fas <?php echo $profile_completion['basic_info'] ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                        Basic Information
                    </li>
                    <li class="<?php echo $profile_completion['education'] ? 'completed' : ''; ?>">
                        <i class="fas <?php echo $profile_completion['education'] ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                        Education Details
                    </li>
                    <li class="<?php echo $profile_completion['skills'] ? 'completed' : ''; ?>">
                        <i class="fas <?php echo $profile_completion['skills'] ? 'fa-check-circle' : 'fa-circle'; ?>"></i>
                        Skills & Expertise
                    </li>
                </ul>
                <a href="?page=profile" class="btn btn-primary">
                    <?php echo $completion_percentage === 100 ? 'View Profile' : 'Complete Profile'; ?>
                </a>
            </div>
        </div>

        <!-- Step 2: Upload Resume -->
        <div class="step-card <?php echo $profile_completion['resume'] ? 'completed' : ($completion_percentage === 100 ? 'active' : ''); ?>">
            <div class="step-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="step-content">
                <h3>Upload Your Resume</h3>
                <p>Add your resume to apply for jobs quickly and easily.</p>
                <?php if ($profile_completion['resume']): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        Resume Uploaded Successfully
                    </div>
                <?php endif; ?>
                <a href="?page=resume" class="btn btn-primary">
                    <?php echo $profile_completion['resume'] ? 'Update Resume' : 'Upload Resume'; ?>
                </a>
            </div>
        </div>

        <!-- Step 3: Browse Jobs -->
        <div class="step-card <?php echo $has_applied ? 'completed' : ($profile_completion['resume'] ? 'active' : ''); ?>">
            <div class="step-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="step-content">
                <h3>Browse Available Jobs</h3>
                <p>Explore and apply to jobs that match your skills and interests.</p>
                <?php if ($has_applied): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        You've submitted <?php echo $total_applications; ?> application(s)
                    </div>
                <?php endif; ?>
                <a href="?page=jobs" class="btn btn-primary">Browse Jobs</a>
            </div>
        </div>
    </div>
</div>
<style>
.get-started-section {
    background: var(--card-light);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

body.dark-mode .get-started-section {
    background: var(--card-dark);
}

.get-started-header {
    margin-bottom: 1.5rem;
}

.get-started-header h2 {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-color);
    margin: 0 0 0.5rem 0;
}

.get-started-header p {
    color: var(--text-light);
    margin: 0;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.step-card {
    background: var(--background-light);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(132, 116, 203, 0.1);
    transition: all 0.3s ease;
}

body.dark-mode .step-card {
    background: var(--background-dark);
}

.step-card.active {
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(132, 116, 203, 0.1);
}

.step-card.completed {
    border-color: #48bb78;
}

.step-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(132, 116, 203, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.step-icon i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.step-card.completed .step-icon {
    background: rgba(72, 187, 120, 0.1);
}

.step-card.completed .step-icon i {
    color: #48bb78;
}

.step-content h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-color);
}

.step-content p {
    color: var(--text-light);
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
}

.step-progress {
    margin-bottom: 1rem;
}

.progress-bar {
    height: 6px;
    background: rgba(132, 116, 203, 0.1);
    border-radius: 3px;
    margin-bottom: 0.5rem;
}

.progress {
    height: 100%;
    background: var(--primary-color);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.step-checklist {
    list-style: none;
    padding: 0;
    margin: 0 0 1rem 0;
}

.step-checklist li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.step-checklist li.completed {
    color: #48bb78;
}

.success-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #48bb78;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.success-message i {
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .get-started-section {
        padding: 1.5rem;
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?php endif; ?>
<div class="analytics-grid">
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="analytics-content">
            <h3>Total Applications</h3>
            <p class="analytics-number"><?php echo $total_applications; ?></p>
        </div>
    </div>

    <div class="analytics-card">
        <div class="analytics-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="analytics-content">
            <h3>Success Rate</h3>
            <p class="analytics-number"><?php echo $success_rate; ?>%</p>
        </div>
    </div>

    <div class="analytics-card">
        <div class="analytics-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="analytics-content">
            <h3>Pending</h3>
            <p class="analytics-number"><?php echo $pending_applications; ?></p>
        </div>
    </div>

    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="analytics-content">
            <h3>Available Jobs</h3>
            <p class="analytics-number"><?php echo count($job_listings); ?></p>
        </div>
    </div>
</div>
<div class="charts-grid">
    <div class="chart-card">
        <h3>Application Timeline</h3>
        <canvas id="applicationTimeline"></canvas>
    </div>
    <div class="chart-card">
        <h3>Application Status</h3>
        <canvas id="statusDistribution"></canvas>
    </div>
</div>
<div class="recent-applications">
    <h3>Recent Applications</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Applied Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($job_applications, 0, 5) as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($application['company_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($application['application_date'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($application['status']); ?>">
                                <?php echo ucfirst($application['status']); ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-secondary btn-sm" 
                                    onclick="viewApplication(<?php echo $application['id']; ?>)">
                                View Details
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

const timelineCtx = document.getElementById('applicationTimeline').getContext('2d');
const monthlyData = <?php echo json_encode($monthly_stats); ?>;

new Chart(timelineCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [{
            label: 'Applications',
            data: monthlyData.map(item => item.application_count),
            borderColor: '#8474cb',
            tension: 0.4,
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
const statusCtx = document.getElementById('statusDistribution').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Accepted', 'Rejected'],
        datasets: [{
            data: [<?php echo "$pending_applications, $accepted_applications, $rejected_applications"; ?>],
            backgroundColor: ['#ecc94b', '#48bb78', '#f56565'],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function viewApplication(id) {
    window.location.href = `?page=applications&id=${id}`;
}
</script>

<style>
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

body.dark-mode .analytics-card {
    background: var(--card-dark);
}

.analytics-card:hover {
    transform: translateY(-4px);
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

.analytics-icon.success {
    background: rgba(72, 187, 120, 0.1);
    color: #48bb78;
}

.analytics-icon.pending {
    background: rgba(236, 201, 75, 0.1);
    color: #ecc94b;
}

.analytics-icon i {
    font-size: 1.5rem;
}

.analytics-content h3 {
    font-size: 0.875rem;
    color: var(--text-light);
    margin: 0;
}

body.dark-mode .analytics-content h3 {
    color: var(--text-dark);
}

.analytics-number {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: var(--primary-color);
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: var(--card-light);
    border-radius: 16px;
    padding: 1.5rem;
    height: 300px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

body.dark-mode .chart-card {
    background: var(--card-dark);
}

.chart-card h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: var(--text-light);
}

body.dark-mode .chart-card h3 {
    color: var(--text-dark);
}

.recent-applications {
    background: var(--card-light);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

body.dark-mode .recent-applications {
    background: var(--card-dark);
}

.table-responsive {
    overflow-x: auto;
}

@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-card {
        min-height: 300px;
    }
}
</style>