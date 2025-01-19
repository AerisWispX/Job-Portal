<?php
if(isset($_POST['delete_company'])) {
    $company_id = $_POST['company_id'];
    $delete_query = "DELETE FROM company_profiles WHERE id = $company_id";
    mysqli_query($conn, $delete_query);
    
    echo "<div class='alert alert-success'>Company deleted successfully!</div>";
}
$companies_query = "SELECT 
    cp.*,
    u.email,
    u.username,
    COUNT(jl.id) as total_jobs
    FROM company_profiles cp
    JOIN users u ON cp.user_id = u.id
    LEFT JOIN job_listings jl ON cp.id = jl.company_id
    GROUP BY cp.id
    ORDER BY cp.id DESC";

$companies_result = mysqli_query($conn, $companies_query);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0;">Company Management</h2>
    </div>
    <div class="analytics-grid">
        <?php
        $total_query = "SELECT COUNT(*) as total FROM company_profiles";
        $total_result = mysqli_query($conn, $total_query);
        $total_row = mysqli_fetch_assoc($total_result);
        $jobs_query = "SELECT COUNT(*) as total FROM job_listings";
        $jobs_result = mysqli_query($conn, $jobs_query);
        $jobs_row = mysqli_fetch_assoc($jobs_result);
        $active_jobs_query = "SELECT COUNT(*) as total FROM job_listings WHERE status = 'approved'";
        $active_jobs_result = mysqli_query($conn, $active_jobs_query);
        $active_jobs_row = mysqli_fetch_assoc($active_jobs_result);
        ?>
        
        <div class="analytics-card">
    <div class="analytics-icon">
        <i class="fas fa-building"></i>
    </div>
    <div class="analytics-content">
        <h3>Total Companies</h3>
        <p class="analytics-number"><?php echo $total_row['total']; ?></p>
    </div>
</div>

<div class="analytics-card">
    <div class="analytics-icon">
        <i class="fas fa-briefcase"></i>
    </div>
    <div class="analytics-content">
        <h3>Total Jobs</h3>
        <p class="analytics-number"><?php echo $jobs_row['total']; ?></p>
    </div>
</div>

<div class="analytics-card">
    <div class="analytics-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="analytics-content">
        <h3>Active Jobs</h3>
        <p class="analytics-number"><?php echo $active_jobs_row['total']; ?></p>
    </div>
</div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Total Jobs</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($company = mysqli_fetch_assoc($companies_result)) { ?>
                    <tr>
                        <td><?php echo $company['id']; ?></td>
                        <td><?php echo $company['company_name'] ?: 'Not Set'; ?></td>
                        <td><?php echo $company['email']; ?></td>
                        <td><?php echo $company['website'] ?: 'Not Set'; ?></td>
                        <td><?php echo $company['total_jobs']; ?></td>
                        <td>
                            <button class="btn btn-secondary" onclick="viewCompanyDetails(<?php echo $company['id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <form method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this company?');">
                                <input type="hidden" name="company_id" value="<?php echo $company['id']; ?>">
                                <button type="submit" name="delete_company" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function viewCompanyDetails(companyId) {
    window.location.href = 'company_details.php?id=' + companyId;
}
</script>