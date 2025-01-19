<?php

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';
$user_id = (int)$_SESSION['user_id'];
$query = "SELECT * FROM student_profiles WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
if (isset($_POST['full_name'])) {
    $full_name = $_POST['full_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $education = $_POST['education'];
    $skills = $_POST['skills'];

    $query = "UPDATE student_profiles SET full_name = '$full_name', date_of_birth = '$date_of_birth', 
              education = '$education', skills = '$skills' WHERE user_id = $user_id";
    mysqli_query($conn, $query);
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowed_types = ['application/pdf'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['resume']['tmp_name']);

        if (!in_array($mime_type, $allowed_types)) {
            $_SESSION['error'] = 'Invalid file type. Only PDF files are allowed.';
            header('Location: update_profile.php');
            exit();
        }

        $upload_dir = 'uploads/resumes/';
        $file_name = uniqid('resume_') . '.pdf';
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['resume']['tmp_name'], $file_path)) {
            $query = "UPDATE student_profiles SET resume_path = '$file_path' WHERE user_id = $user_id";
            mysqli_query($conn, $query);
        } else {
            $_SESSION['error'] = 'Failed to upload file.';
            header('Location: update_profile.php');
            exit();
        }
    }

    $_SESSION['message'] = 'Profile updated successfully!';
    header('Location: student_dashboard.php');
    exit();
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
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="update-profile-form">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="education">Education:</label>
                    <textarea id="education" name="education" required><?php echo htmlspecialchars($student['education']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="skills">Skills:</label>
                    <textarea id="skills" name="skills" required><?php echo htmlspecialchars($student['skills']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="resume">Resume (PDF only):</label>
                    <input type="file" id="resume" name="resume" accept=".pdf">
                </div>
                <button type="submit" class="btn btn-primary btn-full">Update Profile</button>
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
    </script>
</body>
</html>
