<?php

$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_listings WHERE company_id = $company_id"))['count'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_applications ja JOIN job_listings jl ON ja.job_id = jl.id WHERE jl.company_id = $company_id"))['count'];
$pending_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_applications ja JOIN job_listings jl ON ja.job_id = jl.id WHERE jl.company_id = $company_id AND ja.status = 'pending'"))['count'];
$recent_jobs = mysqli_query($conn, "SELECT * FROM job_listings WHERE company_id = $company_id ORDER BY posted_at DESC LIMIT 5");
$recent_applications = mysqli_query($conn, "SELECT ja.*, jl.title, sp.full_name 
    FROM job_applications ja 
    JOIN job_listings jl ON ja.job_id = jl.id 
    JOIN student_profiles sp ON ja.student_id = sp.id 
    WHERE jl.company_id = $company_id 
    ORDER BY ja.application_date DESC 
    LIMIT 5");

$accepted_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_applications ja JOIN job_listings jl ON ja.job_id = jl.id WHERE jl.company_id = $company_id AND ja.status = 'accepted'"))['count'];
$rejected_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_applications ja JOIN job_listings jl ON ja.job_id = jl.id WHERE jl.company_id = $company_id AND ja.status = 'rejected'"))['count'];

$conversion_rate = $total_applications > 0 
    ? number_format(($accepted_applications / $total_applications) * 100, 2) 
    : '0.00';


$activity_analysis = [
    'new_jobs_last_month' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_listings WHERE company_id = $company_id AND posted_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"))['count'],
    'new_applications_last_month' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_applications ja JOIN job_listings jl ON ja.job_id = jl.id WHERE jl.company_id = $company_id AND ja.application_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"))['count']
];
?>
<style>
.insights-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.insight-card {
    background: var(--card-light);
    border-radius: 12px;
    padding: 1rem;
}

.insight-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.metric {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.metric-label {
    color: var(--text-secondary);
}

.metric-value {
    font-weight: bold;
}

.recent-activities {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.recent-jobs, .recent-applications {
    background: var(--card-light);
    border-radius: 12px;
    padding: 1rem;
}

.recent-jobs table, .recent-applications table {
    width: 100%;
    border-collapse: collapse;
}

.recent-jobs table th, .recent-applications table th,
.recent-jobs table td, .recent-applications table td {
    padding: 0.5rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

</style>
<h1>Dashboard Overview</h1>

<div class="analytics-grid">
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <h3>Total Jobs</h3>
            <p class="text-2xl font-bold"><?php echo $total_jobs; ?></p>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <h3>Total Applications</h3>
            <p class="text-2xl font-bold"><?php echo $total_applications; ?></p>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <h3>Pending Applications</h3>
            <p class="text-2xl font-bold"><?php echo $pending_applications; ?></p>
        </div>
    </div>

    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <h3>Accepted Applications</h3>
            <p class="text-2xl font-bold"><?php echo $accepted_applications; ?></p>
        </div>
    </div>
</div>

<div class="dashboard-insights">
    <div class="insights-grid">
        <div class="insight-card">
            <h3>Conversion Metrics</h3>
            <div class="insight-content">
                <div class="metric">
                    <span class="metric-label">Acceptance Rate</span>
                    <span class="metric-value"><?php echo $conversion_rate; ?>%</span>
                </div>
                <div class="metric">
                    <span class="metric-label">Rejected Applications</span>
                    <span class="metric-value"><?php echo $rejected_applications; ?></span>
                </div>
            </div>
        </div>

        <div class="insight-card">
            <h3>Recent Activity</h3>
            <div class="insight-content">
                <div class="metric">
                    <span class="metric-label">New Jobs (Last Month)</span>
                    <span class="metric-value"><?php echo $activity_analysis['new_jobs_last_month']; ?></span>
                </div>
                <div class="metric">
                    <span class="metric-label">New Applications (Last Month)</span>
                    <span class="metric-value"><?php echo $activity_analysis['new_applications_last_month']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="recent-activities">
        <div class="recent-jobs">
            <h3>Recent Job Listings</h3>
            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Posted At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($job = mysqli_fetch_assoc($recent_jobs)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($job['posted_at'])); ?></td>
                        <td><?php echo ucfirst($job['status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="recent-applications">
            <h3>Recent Applications</h3>
            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Applicant</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($application = mysqli_fetch_assoc($recent_applications)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['title']); ?></td>
                        <td><?php echo htmlspecialchars($application['full_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($application['application_date'])); ?></td>
                        <td><?php echo ucfirst($application['status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>