<?php
$query = "SELECT 
    ja.id as application_id,
    ja.status as application_status,
    ja.application_date,
    jl.title as job_title,
    sp.full_name as student_name,
    sp.resume_path,
    sp.education,
    sp.skills
    FROM job_applications ja
    JOIN job_listings jl ON ja.job_id = jl.id
    JOIN student_profiles sp ON ja.student_id = sp.id
    WHERE jl.company_id = $company_id
    ORDER BY ja.application_date DESC";

$result = mysqli_query($conn, $query);
?>

<div class="page-header">
    <h1><i class="fas fa-users"></i> Job Applications</h1>
</div>

<?php if (isset($_GET['status_updated'])): ?>
    <div class="alert alert-success">
        Application status has been updated successfully!
    </div>
<?php endif; ?>

<div class="applications-grid">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($application = mysqli_fetch_assoc($result)): ?>
            <div class="application-card">
                <div class="application-header">
                    <h3><?php echo htmlspecialchars($application['job_title']); ?></h3>
                    <span class="status-badge <?php echo $application['application_status']; ?>">
                        <?php echo ucfirst($application['application_status']); ?>
                    </span>
                </div>
                
                <div class="application-body">
                    <div class="applicant-info">
                        <p><strong>Applicant:</strong> <?php echo htmlspecialchars($application['student_name']); ?></p>
                        <p><strong>Applied:</strong> <?php echo date('F j, Y', strtotime($application['application_date'])); ?></p>
                        <p><strong>Education:</strong> <?php echo htmlspecialchars($application['education']); ?></p>
                        <p><strong>Skills:</strong> <?php echo htmlspecialchars($application['skills']); ?></p>
                    </div>
                    
                    <?php if ($application['resume_path']): ?>
                        <div class="resume-section">
                            <a href="<?php echo htmlspecialchars($application['resume_path']); ?>" 
                               class="btn btn-secondary" target="_blank">
                                <i class="fas fa-file-pdf"></i> View Resume
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="status-update-form">
                        <input type="hidden" name="application_id" 
                               value="<?php echo $application['application_id']; ?>">
                        <div class="form-group">
                            <label for="status">Update Status:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending" <?php echo $application['application_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="reviewed" <?php echo $application['application_status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                <option value="accepted" <?php echo $application['application_status'] === 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                                <option value="rejected" <?php echo $application['application_status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                        <button type="submit" name="update_status" class="btn btn-primary">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-applications">
            <i class="fas fa-inbox fa-3x"></i>
            <p>No applications received yet.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.applications-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.application-card {
    background: var(--card-light);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s;
}

body.dark-mode .application-card {
    background: var(--card-dark);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.application-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(132, 116, 203, 0.1);
}

body.dark-mode .application-header {
    border-bottom-color: rgba(132, 116, 203, 0.2);
}

.application-header h3 {
    margin: 0;
    font-size: 1.2rem;
    color: var(--text-light);
}

body.dark-mode .application-header h3 {
    color: var(--text-dark);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Status badges with dark mode support */
.status-badge.pending { 
    background: rgba(236, 201, 75, 0.2); 
    color: #b7791f; 
}

.status-badge.reviewed { 
    background: rgba(66, 153, 225, 0.2); 
    color: #2b6cb0; 
}

.status-badge.accepted { 
    background: rgba(72, 187, 120, 0.2); 
    color: #2f855a; 
}

.status-badge.rejected { 
    background: rgba(245, 101, 101, 0.2); 
    color: #c53030; 
}

body.dark-mode .status-badge.pending { 
    background: rgba(236, 201, 75, 0.15); 
    color: #fbd38d; 
}

body.dark-mode .status-badge.reviewed { 
    background: rgba(66, 153, 225, 0.15); 
    color: #90cdf4; 
}

body.dark-mode .status-badge.accepted { 
    background: rgba(72, 187, 120, 0.15); 
    color: #9ae6b4; 
}

body.dark-mode .status-badge.rejected { 
    background: rgba(245, 101, 101, 0.15); 
    color: #feb2b2; 
}

.application-body {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.applicant-info p {
    margin: 0.5rem 0;
    color: var(--text-light);
}

body.dark-mode .applicant-info p {
    color: var(--text-dark);
}

.resume-section {
    display: flex;
    justify-content: center;
    margin: 1rem 0;
}

.status-update-form {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(132, 116, 203, 0.1);
}

body.dark-mode .status-update-form {
    border-top-color: rgba(132, 116, 203, 0.2);
}

.no-applications {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    background: var(--card-light);
    border-radius: 12px;
    color: var(--text-muted);
}

body.dark-mode .no-applications {
    background: var(--card-dark);
    color: var(--text-dark);
}

body.dark-mode .no-applications i {
    color: var(--text-dark);
}

/* Form elements dark mode support */
body.dark-mode select,
body.dark-mode input {
    background-color: var(--card-dark);
    color: var(--text-dark);
    border-color: rgba(132, 116, 203, 0.3);
}

body.dark-mode label {
    color: var(--text-dark);
}
</style>