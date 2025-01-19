<?php

if(isset($_POST['update_status'])) {
    $job_id = $_POST['job_id'];
    $status = $_POST['status'];
    
    $update_query = "UPDATE job_listings SET status = '$status' WHERE id = $job_id";
    mysqli_query($conn, $update_query);
    
    echo "<div class='alert alert-success'>Job status updated successfully!</div>";
}

if(isset($_POST['delete_job'])) {
    $job_id = $_POST['job_id'];
    
    $delete_query = "DELETE FROM job_listings WHERE id = $job_id";
    mysqli_query($conn, $delete_query);
    
    echo "<div class='alert alert-success'>Job deleted successfully!</div>";
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$jobs_query = "SELECT j.*, c.company_name 
               FROM job_listings j 
               LEFT JOIN company_profiles c ON j.company_id = c.id 
               WHERE 1=1";

if($status_filter) {
    $jobs_query .= " AND j.status = '$status_filter'";
}

if($search) {
    $jobs_query .= " AND (j.title LIKE '%$search%' OR j.description LIKE '%$search%' OR c.company_name LIKE '%$search%')";
}

$jobs_query .= " ORDER BY j.posted_at DESC";
$jobs_result = mysqli_query($conn, $jobs_query);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0;">Job Listings Management</h2>
        

        <form method="GET" class="search-form" style="display: flex; gap: 1rem;">
            <input type="hidden" name="page" value="jobs">
            <input type="text" name="search" placeholder="Search jobs..." value="<?php echo $search; ?>">
            <select name="status">
                <option value="">All Status</option>
                <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="approved" <?php echo $status_filter == 'approved' ? 'selected' : ''; ?>>Approved</option>
                <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Company</th>
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
                        <td><?php echo $job['id']; ?></td>
                        <td><?php echo $job['title']; ?></td>
                        <td><?php echo $job['company_name']; ?></td>
                        <td><?php echo $job['location']; ?></td>
                        <td><?php echo $job['salary']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($job['posted_at'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $job['status']; ?>">
                                <?php echo ucfirst($job['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <?php if($job['status'] == 'pending'): ?>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" name="update_status" class="btn btn-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" name="update_status" class="btn btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <button class="btn btn-secondary" onclick="viewJobDetails(<?php echo $job['id']; ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                    <button type="submit" name="delete_job" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function viewJobDetails(jobId) {
    window.location.href = 'job_details.php?id=' + jobId;
}
</script>
