<?php
$query = "SELECT * FROM company_profiles WHERE id = $company_id";
$result = mysqli_query($conn, $query);
$profile = mysqli_fetch_assoc($result);

$user_query = "SELECT email FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
?>

<div class="page-header">
    <h1><i class="fas fa-building"></i> Company Profile</h1>
</div>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
        Profile updated successfully!
    </div>
<?php endif; ?>

<div class="profile-container">
    <form method="POST" class="profile-form">
        <div class="form-section">
            <h2>Basic Information</h2>
            
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" id="company_name" name="company_name" 
                       value="<?php echo htmlspecialchars($profile['company_name']); ?>" 
                       class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                       class="form-control" disabled>
                <small class="form-text text-muted">Contact admin to update email address</small>
            </div>
            
            <div class="form-group">
                <label for="website">Company Website</label>
                <input type="url" id="website" name="website" 
                       value="<?php echo htmlspecialchars($profile['website']); ?>" 
                       class="form-control" placeholder="https://example.com">
            </div>
        </div>
        
        <div class="form-section">
            <h2>Company Description</h2>
            
            <div class="form-group">
                <label for="description">About Your Company</label>
                <textarea id="description" name="description" class="form-control" 
                          rows="6" placeholder="Tell potential candidates about your company..."
                          ><?php echo htmlspecialchars($profile['description']); ?></textarea>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" name="update_profile" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
}

.profile-form {
    background: var(--card-light);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s;
}

body.dark-mode .profile-form {
    background: var(--card-dark);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(132, 116, 203, 0.1);
}

body.dark-mode .form-section {
    border-bottom-color: rgba(132, 116, 203, 0.2);
}

.form-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.form-section h2 {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

body.dark-mode .form-section h2 {
    color: var(--text-dark);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

body.dark-mode .form-group label {
    color: var(--text-dark);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--input-bg);
    color: var(--text-primary);
    transition: border-color 0.2s ease, background-color 0.3s;
}

body.dark-mode .form-control {
    background: var(--card-dark);
    color: var(--text-dark);
    border-color: rgba(132, 116, 203, 0.3);
}

.form-control:focus {
    border-color: var(--primary-color);
    outline: none;
}

.form-control:disabled {
    background: var(--disabled-bg);
    cursor: not-allowed;
}

body.dark-mode .form-control:disabled {
    background: rgba(132, 116, 203, 0.1);
    color: rgba(255, 255, 255, 0.6);
}

.form-text {
    font-size: 0.875rem;
    margin-top: 0.25rem;
    color: var(--text-muted);
}

body.dark-mode .form-text {
    color: rgba(255, 255, 255, 0.6);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 2rem;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

body.dark-mode textarea.form-control {
    background: var(--card-dark);
    color: var(--text-dark);
    border-color: rgba(132, 116, 203, 0.3);
}
</style>