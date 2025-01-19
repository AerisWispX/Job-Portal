<?php
$current_date = date('Y-m-d H:i:s');

$query = "SELECT 
            jl.*, 
            COUNT(ja.id) as total_applications,
            CASE 
                WHEN jl.expiration_date < '$current_date' THEN 'expired'
                ELSE jl.status 
            END as current_status
          FROM job_listings jl
          LEFT JOIN job_applications ja ON jl.id = ja.job_id
          WHERE jl.company_id = $company_id 
          GROUP BY jl.id
          ORDER BY jl.posted_at DESC";
$result = mysqli_query($conn, $query);
$job_listings = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h1>Job Listings</h1>

<button class="btn btn-primary" onclick="document.getElementById('postJobForm').style.display='block'">
    <i class="fas fa-plus"></i> Post New Job
</button>

<div id="postJobForm" style="display: none;" class="card">
    <h2>Post a New Job</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Job Title" required>
        <select name="job_type" required>
            <option value="full-time">Full Time</option>
            <option value="part-time">Part Time</option>
            <option value="contract">Contract</option>
            <option value="internship">Internship</option>
        </select>
        <textarea name="description" placeholder="Job Description" required></textarea>
        <textarea name="requirements" placeholder="Job Requirements"></textarea>
        <input type="text" name="salary" placeholder="Salary">
        <input type="text" name="location" placeholder="Location">
        <div class="form-group">
            <label>Job will automatically expire in 30 days</label>
        </div>
        <button type="submit" name="post_job" class="btn btn-primary">Post Job</button>
    </form>
</div>


<div class="job-listings">
    <?php foreach ($job_listings as $job): 

        $status_class = 'status-' . strtolower($job['current_status']);
        $is_expired = $job['current_status'] === 'expired';
    ?>
    <div class="card <?php echo $is_expired ? 'job-expired' : ''; ?>">
    <div class="job-header">
    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
    <span class="job-type-badge"><?php echo ucfirst($job['job_type']); ?></span>
    <?php if ($is_expired): ?>
        <span class="badge badge-danger">Expired</span>
    <?php endif; ?>
</div>
        
        <p><?php echo htmlspecialchars($job['description']); ?></p>
        
        <div class="job-details">
            <span><i class="fas fa-money-bill-wave"></i> <?php echo htmlspecialchars($job['salary']); ?></span>
            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></span>
            <span class="status-badge <?php echo $status_class; ?>">
                <?php echo ucfirst($job['current_status']); ?>
            </span>
        </div>
        
        <div class="job-meta">
            <div class="applications-count">
                <i class="fas fa-users"></i> 
                <?php echo $job['total_applications']; ?> Applications
            </div>
            
            <div class="job-actions">
                <?php if (!$is_expired): ?>
                    <a href="company_dashboard.php?page=applications&job_id=<?php echo $job['id']; ?>" 
                       class="btn btn-sm btn-secondary">
                        View Applications
                    </a>
                <?php endif; ?>
                
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this job listing?');">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <button type="submit" name="delete_job" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        
        <?php if (!$is_expired): ?>
            <div class="job-expiration">
                <small>
                    <i class="fas fa-clock"></i> 
                    Expires on: <?php echo date('M d, Y', strtotime($job['expiration_date'])); ?>
                </small>
            </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>


<style>
    .job-type-badge {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
}
    .job-expired {
        opacity: 0.6;
        background-color: #f8f9fa;
    }
    
    .job-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .job-details, .job-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
    }
    
    .job-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .job-expiration {
        margin-top: 1rem;
        text-align: right;
        color: #6c757d;
    }
    
    .applications-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const postJobForm = document.getElementById('postJobForm');
        postJobForm.addEventListener('submit', function(e) {
            const title = this.querySelector('input[name="title"]');
            const description = this.querySelector('textarea[name="description"]');
            
            if (title.value.trim() === '') {
                alert('Job Title cannot be empty');
                e.preventDefault();
                return false;
            }
            
            if (description.value.trim() === '') {
                alert('Job Description cannot be empty');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    });
</script>