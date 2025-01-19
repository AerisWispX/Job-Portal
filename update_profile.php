<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';
$user_id = (int)$_SESSION['user_id'];
$query = "SELECT * FROM student_profiles WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $date_of_birth = mysqli_real_escape_string($conn, trim($_POST['date_of_birth']));
    $education = mysqli_real_escape_string($conn, trim($_POST['education']));
    $skills = mysqli_real_escape_string($conn, trim($_POST['skills']));


    $errors = [];
    if (empty($full_name)) {
        $errors[] = 'Full name is required.';
    }
    if (empty($date_of_birth)) {
        $errors[] = 'Date of birth is required.';
    }
    if (empty($education)) {
        $errors[] = 'Education details are required.';
    }
    if (empty($skills)) {
        $errors[] = 'Skills are required.';
    }

    $resume_path = $student['resume_path'];
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowed_types = ['application/pdf'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['resume']['tmp_name']);

        if (!in_array($mime_type, $allowed_types)) {
            $errors[] = 'Invalid file type. Only PDF files are allowed.';
        } else {
            $upload_dir = 'uploads/resumes/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = uniqid('resume_') . '.pdf';
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['resume']['tmp_name'], $file_path)) {
    
                if ($resume_path && file_exists($resume_path)) {
                    unlink($resume_path);
                }
                $resume_path = $file_path;
            } else {
                $errors[] = 'Failed to upload resume.';
            }
        }
    }

    if (empty($errors)) {
  
        $update_query = "UPDATE student_profiles SET 
            full_name = '$full_name', 
            date_of_birth = '$date_of_birth', 
            education = '$education', 
            skills = '$skills'";
   
        if ($resume_path) {
            $update_query .= ", resume_path = '$resume_path'";
        }
        
        $update_query .= " WHERE user_id = $user_id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['message'] = 'Profile updated successfully!';
            header('Location: student_dashboard.php?page=profile');
            exit();
        } else {
            $errors[] = 'Database update failed: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <main class="main-content">
            <?php

            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
                echo '</div>';
            }
            ?>

            <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="update-profile-form">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" 
                        value="<?php echo htmlspecialchars($student['full_name']); ?>" 
                        required maxlength="100">
                </div>
                
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" 
                        value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" 
                        required>
                </div>
                
                <div class="form-group">
                    <label for="education">Education:</label>
                    <textarea id="education" name="education" 
                        required maxlength="500"><?php echo htmlspecialchars($student['education']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="skills">Skills:</label>
                    <textarea id="skills" name="skills" 
                        required maxlength="500"><?php echo htmlspecialchars($student['skills']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="resume">Resume (PDF only):</label>
                    <input type="file" id="resume" name="resume" accept=".pdf">
                    <?php if ($student['resume_path']): ?>
                        <p class="small-text">Current resume: <?php echo basename(htmlspecialchars($student['resume_path'])); ?></p>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-primary btn-full">Update Profile</button>
                <a href="student_dashboard.php?page=profile" class="btn btn-secondary btn-full mt-2">Cancel</a>
            </form>
        </main>
    </div>

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

 
        document.querySelector('form').addEventListener('submit', function(event) {
            const fullName = document.getElementById('full_name');
            const dateOfBirth = document.getElementById('date_of_birth');
            const education = document.getElementById('education');
            const skills = document.getElementById('skills');


            if (fullName.value.trim() === '') {
                event.preventDefault();
                alert('Please enter your full name.');
                fullName.focus();
                return false;
            }

            if (dateOfBirth.value === '') {
                event.preventDefault();
                alert('Please select your date of birth.');
                dateOfBirth.focus();
                return false;
            }

            if (education.value.trim() === '') {
                event.preventDefault();
                alert('Please provide your education details.');
                education.focus();
                return false;
            }

            if (skills.value.trim() === '') {
                event.preventDefault();
                alert('Please list your skills.');
                skills.focus();
                return false;
            }

            return true;
        });
    </script>
</body>
</html>