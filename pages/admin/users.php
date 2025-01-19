<?php

if(isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $delete_query);
    
    echo "<div class='alert alert-success'>User deleted successfully!</div>";
}

$users_query = "SELECT 
    u.*,
    COALESCE(sp.full_name, cp.company_name, '') as display_name
    FROM users u 
    LEFT JOIN student_profiles sp ON u.id = sp.user_id
    LEFT JOIN company_profiles cp ON u.id = cp.user_id
    ORDER BY u.created_at DESC";

$users_result = mysqli_query($conn, $users_query);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0;">User Management</h2>
        <button class="btn btn-primary" onclick="showAddUserModal()">
            <i class="fas fa-plus"></i> Add New User
        </button>
    </div>


    <div class="analytics-grid">
        <?php
        $total_query = "SELECT COUNT(*) as total FROM users";
        $total_result = mysqli_query($conn, $total_query);
        $total_row = mysqli_fetch_assoc($total_result);
        
        $types_query = "SELECT user_type, COUNT(*) as count FROM users GROUP BY user_type";
        $types_result = mysqli_query($conn, $types_query);
        $user_counts = [];
        while($type_row = mysqli_fetch_assoc($types_result)) {
            $user_counts[$type_row['user_type']] = $type_row['count'];
        }
        ?>
        
        <div class="analytics-card">
            <div class="analytics-icon">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3>Total Users</h3>
                <p><?php echo $total_row['total']; ?></p>
            </div>
        </div>
        
        <div class="analytics-card">
            <div class="analytics-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <h3>Students</h3>
                <p><?php echo isset($user_counts['student']) ? $user_counts['student'] : 0; ?></p>
            </div>
        </div>
        
        <div class="analytics-card">
            <div class="analytics-icon">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <h3>Companies</h3>
                <p><?php echo isset($user_counts['company']) ? $user_counts['company'] : 0; ?></p>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Name/Company</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($users_result)) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($user['user_type']); ?>">
                                <?php echo ucfirst($user['user_type']); ?>
                            </span>
                        </td>
                        <td><?php echo $user['display_name']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-secondary" onclick="editUser(<?php echo $user['id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger">
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

<div id="addUserModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Add New User</h2>
        <form method="POST" action="process_add_user.php">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>User Type:</label>
                <select name="user_type" required>
                    <option value="student">Student</option>
                    <option value="company">Company</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add User</button>
                <button type="button" class="btn btn-secondary" onclick="hideAddUserModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddUserModal() {
    document.getElementById('addUserModal').style.display = 'block';
}

function hideAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
}

function editUser(userId) {
    window.location.href = 'edit_user.php?id=' + userId;
}
</script>