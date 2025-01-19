<?php
$student_id = (int)$student['id'];
$query = "SELECT ja.*, jl.title AS job_title, jl.description, jl.requirements, 
          jl.salary, jl.location, cp.company_name, ja.application_date
          FROM job_applications ja
          JOIN job_listings jl ON ja.job_id = jl.id
          JOIN company_profiles cp ON jl.company_id = cp.id
          WHERE ja.student_id = $student_id
          ORDER BY ja.application_date DESC";
$result = mysqli_query($conn, $query);
$applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<style>
.applications-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.application-card {
    background-color: var(--card-light);
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}

.application-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.application-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid rgba(132, 116, 203, 0.1);
    padding-bottom: 1rem;
}

.job-title {
    margin: 0 0 0.5rem 0;
    color: var(--primary-color);
    font-size: 1.2rem;
}

.company-details {
    color: var(--secondary-color);
    font-size: 0.9rem;
}

.application-status {
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.application-status.pending {
    background-color: rgba(236, 201, 75, 0.2);
    color: #b7791f;
}

.application-status.approved {
    background-color: rgba(72, 187, 120, 0.2);
    color: #2f855a;
}

.application-status.rejected {
    background-color: rgba(245, 101, 101, 0.2);
    color: #c53030;
}

.job-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    background-color: rgba(132, 116, 203, 0.05);
    padding: 1rem;
    border-radius: 12px;
}

.meta-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.meta-item i {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
}

.meta-label {
    color: var(--secondary-color);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.meta-value {
    font-weight: 500;
    color: var(--text-light);
}

.job-description {
    margin-bottom: 1rem;
}

.job-description h4 {
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.application-actions {
    margin-top: auto;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: var(--card-light);
    margin: 10% auto;
    border-radius: 16px;
    width: 80%;
    max-width: 900px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.2);
    position: relative;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(132, 116, 203, 0.1);
    position: relative;
}

.modal-header h2 {
    margin: 0;
    color: var(--primary-color);
}

.close-modal {
    position: absolute;
    right: 1.5rem;
    top: 1rem;
    font-size: 1.5rem;
    color: var(--secondary-color);
    cursor: pointer;
    transition: color 0.3s ease;
}

.close-modal:hover {
    color: var(--primary-color);
}

.modal-body {
    padding: 1.5rem;
}

.modal-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.modal-meta-item {
    background-color: rgba(132, 116, 203, 0.05);
    padding: 1rem;
    border-radius: 12px;
}

.modal-section {
    margin-bottom: 2rem;
}

.modal-section h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid rgba(132, 116, 203, 0.1);
    display: flex;
    justify-content: flex-end;
}

body.dark-mode .modal-content {
    background-color: var(--card-dark);
}

body.dark-mode .application-card {
    background-color: var(--card-dark);
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 5% auto;
    }
    
    .modal-meta {
        grid-template-columns: 1fr;
    }
}

body.dark-mode .job-title {
    color: var(--text-dark);
}

body.dark-mode .company-details {
    color: #a0aec0;  
}

body.dark-mode .meta-label {
    color: #a0aec0;
}

body.dark-mode .meta-value {
    color: var(--text-dark);
}

body.dark-mode .modal-content {
    background-color: var(--card-dark);
}

body.dark-mode .modal-header h2 {
    color: var(--text-dark);
}

body.dark-mode .modal-meta-item {
    background-color: rgba(132, 116, 203, 0.1);
}

body.dark-mode .modal-meta-item h4 {
    color: var(--text-dark);
}

body.dark-mode .modal-meta-item p {
    color: #a0aec0;
}

body.dark-mode .modal-section h3 {
    color: var(--text-dark);
}

body.dark-mode .modal-section p {
    color: #a0aec0;
}


body.dark-mode .application-status.pending {
    background-color: rgba(236, 201, 75, 0.15);
    color: #fbd38d;
}

body.dark-mode .application-status.approved {
    background-color: rgba(72, 187, 120, 0.15);
    color: #9ae6b4;
}

body.dark-mode .application-status.rejected {
    background-color: rgba(245, 101, 101, 0.15);
    color: #feb2b2;
}


body.dark-mode .meta-item i {
    color: var(--primary-color);
    opacity: 0.9;
}

body.dark-mode .job-meta {
    background-color: rgba(132, 116, 203, 0.08);
}
</style>

<div class="dashboard-header">
    <h1>My Applications</h1>
</div>

<div class="applications-grid">
    <?php foreach ($applications as $application): ?>
    <div class="application-card" data-application='<?php echo json_encode($application); ?>'>
        <div class="application-header">
            <div class="job-info">
                <h3 class="job-title"><?php echo htmlspecialchars($application['job_title']); ?></h3>
                <div class="company-details">
                    <span class="company-name">
                        <i class="fas fa-building"></i>
                        <?php echo htmlspecialchars($application['company_name']); ?>
                    </span>
                </div>
            </div>
            <span class="application-status <?php echo strtolower($application['status']); ?>">
                <?php echo ucfirst($application['status']); ?>
            </span>
        </div>

        <div class="job-meta">
            <div class="meta-item">
                <i class="fas fa-money-bill-wave"></i>
                <span class="meta-label">Salary</span>
                <span class="meta-value"><?php echo htmlspecialchars($application['salary']); ?></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-map-marker-alt"></i>
                <span class="meta-label">Location</span>
                <span class="meta-value"><?php echo htmlspecialchars($application['location']); ?></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span class="meta-label">Applied</span>
                <span class="meta-value"><?php echo date('M d, Y', strtotime($application['application_date'])); ?></span>
            </div>
        </div>

        <div class="application-actions">
            <button class="btn btn-secondary view-details">
                <i class="fas fa-eye"></i> View Details
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div id="applicationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalJobTitle"></h2>
            <div id="modalCompanyName" class="company-details"></div>
            <span class="close-modal">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="modal-meta">
                <div class="modal-meta-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <h4>Salary</h4>
                    <p id="modalSalary"></p>
                </div>
                <div class="modal-meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Location</h4>
                    <p id="modalLocation"></p>
                </div>
                <div class="modal-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <h4>Applied On</h4>
                    <p id="modalApplicationDate"></p>
                </div>
                <div class="modal-meta-item">
                    <i class="fas fa-check-circle"></i>
                    <h4>Status</h4>
                    <p id="modalStatus"></p>
                </div>
            </div>
            
            <div class="modal-section">
                <h3>Job Description</h3>
                <p id="modalDescription"></p>
            </div>
            
            <div class="modal-section">
                <h3>Requirements</h3>
                <p id="modalRequirements"></p>
            </div>
        </div>
        
        <div class="modal-footer">
            <button class="btn btn-secondary close-modal-btn">Close</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('applicationModal');
    const viewButtons = document.querySelectorAll('.view-details');
    const closeButtons = document.querySelectorAll('.close-modal, .close-modal-btn');

    viewButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const card = e.target.closest('.application-card');
            const data = JSON.parse(card.dataset.application);
            
            document.getElementById('modalJobTitle').textContent = data.job_title;
            document.getElementById('modalCompanyName').textContent = data.company_name;
            document.getElementById('modalSalary').textContent = data.salary;
            document.getElementById('modalLocation').textContent = data.location;
            document.getElementById('modalApplicationDate').textContent = new Date(data.application_date).toLocaleDateString();
            document.getElementById('modalStatus').textContent = data.status;
            document.getElementById('modalDescription').textContent = data.description;
            document.getElementById('modalRequirements').textContent = data.requirements || 'Contact company for detailed requirements';
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});
</script>