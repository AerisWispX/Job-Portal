<?php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';

$success_message = '';
$error_message = '';


if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user_query = "SELECT 
        u.*,
        COALESCE(sp.full_name, cp.company_name, '') as display_name,
        sp.date_of_birth,
        sp.education,
        sp.skills,
        cp.description as company_description,
        cp.website
    FROM users u 
    LEFT JOIN student_profiles sp ON u.id = sp.user_id
    LEFT JOIN company_profiles cp ON u.id = cp.user_id
    WHERE u.id = $user_id";
    
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    
    if (!$user) {
        header('Location: admin_dashboard.php?page=users');
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    
 
    $update_user = "UPDATE users SET 
        username = '$username',
        email = '$email',
        user_type = '$user_type'
        WHERE id = $user_id";
    
    if (mysqli_query($conn, $update_user)) {

        if ($user_type == 'student') {
            $full_name = $_POST['full_name'];
            $date_of_birth = $_POST['date_of_birth'];
            $education = $_POST['education'];
            $skills = $_POST['skills'];
            
           
            $check_profile = mysqli_query($conn, "SELECT id FROM student_profiles WHERE user_id = $user_id");
            if (mysqli_num_rows($check_profile) > 0) {
                $update_profile = "UPDATE student_profiles SET 
                    full_name = '$full_name',
                    date_of_birth = '$date_of_birth',
                    education = '$education',
                    skills = '$skills'
                    WHERE user_id = $user_id";
            } else {
                $update_profile = "INSERT INTO student_profiles 
                    (user_id, full_name, date_of_birth, education, skills) 
                    VALUES ($user_id, '$full_name', '$date_of_birth', '$education', '$skills')";
            }
            mysqli_query($conn, $update_profile);
        } 
        else if ($user_type == 'company') {
            $company_name = $_POST['company_name'];
            $description = $_POST['description'];
            $website = $_POST['website'];
            
            
            $check_profile = mysqli_query($conn, "SELECT id FROM company_profiles WHERE user_id = $user_id");
            if (mysqli_num_rows($check_profile) > 0) {
                $update_profile = "UPDATE company_profiles SET 
                    company_name = '$company_name',
                    description = '$description',
                    website = '$website'
                    WHERE user_id = $user_id";
            } else {
                $update_profile = "INSERT INTO company_profiles 
                    (user_id, company_name, description, website) 
                    VALUES ($user_id, '$company_name', '$description', '$website')";
            }
            mysqli_query($conn, $update_profile);
        }
        
        $success_message = "User updated successfully!";
        
      
        $user_result = mysqli_query($conn, $user_query);
        $user = mysqli_fetch_assoc($user_result);
    } else {
        $error_message = "Error updating user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Portal</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="container">

        
        <main class="main-content">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2 style="margin: 0;">Edit User</h2>
                    <a href="admin_dashboard.php?page=users" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form method="POST" class="form-grid">
          
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>User Type:</label>
                            <select name="user_type" id="user_type" required>
                                <option value="student" <?php echo $user['user_type'] == 'student' ? 'selected' : ''; ?>>Student</option>
                                <option value="company" <?php echo $user['user_type'] == 'company' ? 'selected' : ''; ?>>Company</option>
                                <option value="admin" <?php echo $user['user_type'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                    </div>

         
                    <div id="student_fields" class="form-section" style="display: <?php echo $user['user_type'] == 'student' ? 'block' : 'none'; ?>">
                        <h3>Student Profile</h3>
                        
                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" name="full_name" value="<?php echo $user['full_name'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Date of Birth:</label>
                            <input type="date" name="date_of_birth" value="<?php echo $user['date_of_birth'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Education:</label>
                            <textarea name="education"><?php echo $user['education'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Skills:</label>
                            <textarea name="skills"><?php echo $user['skills'] ?? ''; ?></textarea>
                        </div>
                    </div>

                
                    <div id="company_fields" class="form-section" style="display: <?php echo $user['user_type'] == 'company' ? 'block' : 'none'; ?>">
                        <h3>Company Profile</h3>
                        
                        <div class="form-group">
                            <label>Company Name:</label>
                            <input type="text" name="company_name" value="<?php echo $user['company_name'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Description:</label>
                            <textarea name="description"><?php echo $user['company_description'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Website:</label>
                            <input type="url" name="website" value="<?php echo $user['website'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
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
    document.getElementById('user_type').addEventListener('change', function() {
        const studentFields = document.getElementById('student_fields');
        const companyFields = document.getElementById('company_fields');
        
        if (this.value === 'student') {
            studentFields.style.display = 'block';
            companyFields.style.display = 'none';
        } else if (this.value === 'company') {
            studentFields.style.display = 'none';
            companyFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
            companyFields.style.display = 'none';
        }
    });
    </script>


</body>
</html>