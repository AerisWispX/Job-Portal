<?php

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_companies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type = 'company'"))['count'];
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type = 'student'"))['count'];
$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_listings"))['count'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_applications"))['count'];
$pending_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM job_listings WHERE status = 'pending'"))['count'];
?>



<h2>Dashboard Overview</h2>
<div class="analytics-grid">
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-building"></i>
        </div>
        <div>
            <h3>Companies</h3>
            <p><?php echo $total_companies; ?></p>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div>
            <h3>Students</h3>
            <p><?php echo $total_students; ?></p>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-icon">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <h3>Active Jobs</h3>
            <p><?php echo $total_jobs; ?></p>
        </div>
    </div>
</div>
<div class="card">
    <h3>Recent Job Listings</h3>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Company</th>
                <th>Location</th>
                <th>Status</th>
                <th>Posted Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent_jobs_query = "SELECT j.*, c.company_name 
                                FROM job_listings j 
                                LEFT JOIN company_profiles c ON j.company_id = c.id 
                                ORDER BY j.posted_at DESC LIMIT 5";
            $recent_jobs = mysqli_query($conn, $recent_jobs_query);
            while($job = mysqli_fetch_assoc($recent_jobs)) {
            ?>
            <tr>
                <td><?php echo $job['title']; ?></td>
                <td><?php echo $job['company_name']; ?></td>
                <td><?php echo $job['location']; ?></td>
                <td><span class="status-badge status-<?php echo $job['status']; ?>"><?php echo ucfirst($job['status']); ?></span></td>
                <td><?php echo date('M d, Y', strtotime($job['posted_at'])); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Recent Applications</h3>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Job Title</th>
                <th>Status</th>
                <th>Applied Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent_applications_query = "SELECT a.*, j.title, s.full_name 
                                        FROM job_applications a 
                                        LEFT JOIN job_listings j ON a.job_id = j.id 
                                        LEFT JOIN student_profiles s ON a.student_id = s.id 
                                        ORDER BY a.application_date DESC LIMIT 5";
            $recent_applications = mysqli_query($conn, $recent_applications_query);
            while($application = mysqli_fetch_assoc($recent_applications)) {
            ?>
            <tr>
                <td><?php echo $application['full_name']; ?></td>
                <td><?php echo $application['title']; ?></td>
                <td><span class="status-badge status-<?php echo $application['status']; ?>"><?php echo ucfirst($application['status']); ?></span></td>
                <td><?php echo date('M d, Y', strtotime($application['application_date'])); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>