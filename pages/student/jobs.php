<?php
// Get all approved job listings with company information
$query = "SELECT jl.*, cp.company_name 
          FROM job_listings jl 
          JOIN company_profiles cp ON jl.company_id = cp.id 
          WHERE jl.status = 'approved'
          ORDER BY jl.posted_at DESC";
$result = mysqli_query($conn, $query);
$job_listings = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get student's applied jobs to disable already applied buttons
$student_id = (int)$student['id'];
$query = "SELECT job_id FROM job_applications WHERE student_id = $student_id";
$result = mysqli_query($conn, $query);
$applied_jobs = array_column(mysqli_fetch_all($result, MYSQLI_ASSOC), 'job_id');

// Prepare filter options
$locations = array_unique(array_column($job_listings, 'location'));
$job_types = array_unique(array_column($job_listings, 'job_type'));
$salary_ranges = [
    ['min' => 0, 'max' => 25000, 'label' => 'Entry Level (0-25K)'],
    ['min' => 25000, 'max' => 50000, 'label' => 'Mid Level (25K-50K)'],
    ['min' => 50000, 'max' => 75000, 'label' => 'Senior Level (50K-75K)'],
    ['min' => 75000, 'max' => PHP_INT_MAX, 'label' => 'Executive Level (75K+)']
];

$full_job_details = [];
foreach ($job_listings as $job) {
    $full_job_details[$job['id']] = $job;
}
?>

<div class="dashboard-header">
    <h1>Job Opportunities</h1>
    <div class="job-search-container">
        <input type="text" id="jobSearch" placeholder="Search jobs by title, company, or location" class="search-input">
        <button class="btn btn-secondary" id="filterToggleBtn">
            <i class="fas fa-filter"></i> Filters
        </button>
    </div>
</div>

<div class="job-filters" id="jobFilters">
    <div class="filter-grid">
        <div class="filter-group">
            <label>Location</label>
            <select id="locationFilter" multiple class="multiselect">
                <?php foreach($locations as $location): ?>
                    <option value="<?php echo htmlspecialchars(strtolower($location)); ?>">
                        <?php echo htmlspecialchars($location); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Job Type</label>
            <select id="jobTypeFilter" multiple class="multiselect">
                <?php foreach($job_types as $type): ?>
                    <option value="<?php echo htmlspecialchars(strtolower($type)); ?>">
                        <?php echo htmlspecialchars($type); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Salary Range</label>
            <select id="salaryRangeFilter" multiple class="multiselect">
                <?php foreach($salary_ranges as $range): ?>
                    <option value="<?php echo htmlspecialchars("{$range['min']}:{$range['max']}"); ?>">
                        <?php echo htmlspecialchars($range['label']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-actions">
            <button id="applyFilters" class="btn btn-primary">Apply Filters</button>
            <button id="clearFilters" class="btn btn-secondary">Clear</button>
        </div>
    </div>
</div>

<div class="job-listings" id="jobListings">
    <?php foreach ($job_listings as $job): ?>
        <div class="card job-card" 
             data-title="<?php echo htmlspecialchars(strtolower($job['title'])); ?>"
             data-company="<?php echo htmlspecialchars(strtolower($job['company_name'])); ?>"
             data-location="<?php echo htmlspecialchars(strtolower($job['location'])); ?>"
             data-job-type="<?php echo htmlspecialchars(strtolower($job['job_type'] ?? 'not specified')); ?>"
             data-salary="<?php echo (int)preg_replace('/[^0-9]/', '', $job['salary']); ?>">
            <div class="job-header">
                <div class="job-title-section">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <span class="company-name">
                        <i class="fas fa-building"></i>
                        <?php echo htmlspecialchars($job['company_name']); ?>
                    </span>
                </div>
                <div class="job-meta">
                    <span class="job-type badge">
                        <i class="fas fa-briefcase"></i> 
                        <?php echo htmlspecialchars($job['job_type'] ?? 'Not Specified'); ?>
                    </span>
                    <span class="salary"><i class="fas fa-money-bill-wave"></i> <?php echo htmlspecialchars($job['salary']); ?></span>
                    <span class="location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                </div>
            </div>
            <div class="job-description">
                <h4>Description</h4>
                <p><?php echo nl2br(htmlspecialchars(substr($job['description'], 0, 200) . '...')); ?></p>
            </div>
            
            <div class="job-actions">
                <form method="POST" action="student_dashboard.php">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <button type="submit" class="btn btn-primary" <?php echo in_array($job['id'], $applied_jobs) ? 'disabled' : ''; ?>>
                        <?php echo in_array($job['id'], $applied_jobs) ? 'Already Applied' : 'Apply Now'; ?>
                    </button>
                </form>
                <a href="#" class="btn btn-secondary view-details" data-job-id="<?php echo $job['id']; ?>">
                    View Details
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div id="jobDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="modalJobContent"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const jobFiltersContainer = document.getElementById('jobFilters');
    const filterToggleBtn = document.getElementById('filterToggleBtn');
    const locationFilter = document.getElementById('locationFilter');
    const jobTypeFilter = document.getElementById('jobTypeFilter');
    const salaryRangeFilter = document.getElementById('salaryRangeFilter');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const jobSearch = document.getElementById('jobSearch');
    const jobListings = document.getElementById('jobListings');
    const jobCards = document.querySelectorAll('.job-card');
    const jobDetailsModal = document.getElementById('jobDetailsModal');
    const modalJobContent = document.getElementById('modalJobContent');
    const closeModalBtn = document.querySelector('.close-modal');
    const viewDetailsButtons = document.querySelectorAll('.view-details');

    // Initialize MultiSelect for filters
    function initMultiSelect() {
        const multiSelects = document.querySelectorAll('.multiselect');
        multiSelects.forEach(select => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('multiselect-wrapper');
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);
            select.multiple = true;
        });
    }
    initMultiSelect();

    // Initially hide filters
    jobFiltersContainer.style.display = 'none';

    // Toggle Filters
    filterToggleBtn.addEventListener('click', () => {
        jobFiltersContainer.style.display = 
            jobFiltersContainer.style.display === 'none' ? 'block' : 'none';
    });

    // Filter and Search Function
    function filterJobs() {
        const searchTerm = jobSearch.value.toLowerCase();
        const selectedLocations = Array.from(locationFilter.selectedOptions).map(opt => opt.value);
        const selectedJobTypes = Array.from(jobTypeFilter.selectedOptions).map(opt => opt.value);
        const selectedSalaryRanges = Array.from(salaryRangeFilter.selectedOptions)
            .map(opt => opt.value.split(':').map(Number));

        jobCards.forEach(card => {
            const title = card.dataset.title;
            const company = card.dataset.company;
            const location = card.dataset.location;
            const jobType = card.dataset.jobType;
            const salary = parseInt(card.dataset.salary);

            const matchesSearch = title.includes(searchTerm) || 
                                  company.includes(searchTerm) || 
                                  location.includes(searchTerm);
            const matchesLocation = selectedLocations.length === 0 || 
                selectedLocations.includes(location);
            const matchesJobType = selectedJobTypes.length === 0 || 
                selectedJobTypes.includes(jobType);
            const matchesSalary = selectedSalaryRanges.length === 0 || 
                selectedSalaryRanges.some(([min, max]) => salary >= min && salary <= max);

            card.style.display = (matchesSearch && matchesLocation && 
                matchesJobType && matchesSalary) ? 'block' : 'none';
        });
    }


    jobSearch.addEventListener('input', filterJobs);
    applyFiltersBtn.addEventListener('click', filterJobs);

    clearFiltersBtn.addEventListener('click', () => {
        locationFilter.selectedIndex = -1;
        jobTypeFilter.selectedIndex = -1;
        salaryRangeFilter.selectedIndex = -1;
        jobSearch.value = '';
        
        jobCards.forEach(card => {
            card.style.display = 'block';
        });
    });


    const jobDetails = <?php echo json_encode($full_job_details); ?>;


    viewDetailsButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const jobId = button.dataset.jobId;
            const job = jobDetails[jobId];
            
            if (job) {
                modalJobContent.innerHTML = `
                    <div class="job-details-header">
                        <div class="job-details-title">
                            <h2>${escapeHtml(job.title)}</h2>
                            <span class="company-name">
                                <i class="fas fa-building"></i> ${escapeHtml(job.company_name)}
                            </span>
                        </div>
                        <div class="job-details-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${escapeHtml(job.location)}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-briefcase"></i>
                                <span>${escapeHtml(job.job_type || 'Not Specified')}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>${escapeHtml(job.salary)}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>Posted: ${formatDate(job.posted_at)}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="job-details-section">
                        <h3>Job Description</h3>
                        <p>${escapeHtml(job.description)}</p>
                    </div>
                    
                    ${job.requirements ? `
                    <div class="job-details-section">
                        <h3>Requirements</h3>
                        <p>${escapeHtml(job.requirements)}</p>
                    </div>
                    ` : ''}
                    
                    <div class="job-details-actions">
                        <form method="POST" action="student_dashboard.php">
                            <input type="hidden" name="job_id" value="${job.id}">
                            <button type="submit" class="btn btn-primary" 
                                    ${<?php echo json_encode($applied_jobs); ?>.includes(job.id) ? 'disabled' : ''}>
                                ${<?php echo json_encode($applied_jobs); ?>.includes(job.id) ? 'Already Applied' : 'Apply Now'}
                            </button>
                        </form>
                    </div>
                `;

                jobDetailsModal.classList.add('show');
            }
        });
    });

 
    function closeModal() {
        jobDetailsModal.classList.remove('show');
    }

    closeModalBtn.addEventListener('click', closeModal);


    jobDetailsModal.addEventListener('click', (e) => {
        if (e.target === jobDetailsModal) {
            closeModal();
        }
    });


    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }
});
</script>

<style>

.multiselect-wrapper {
    position: relative;
    width: 100%;
}

.multiselect-wrapper select {
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    padding-right: 30px;
}

.multiselect-wrapper::after {
    content: '▼';
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--secondary-color);
}

.multiselect-wrapper select[multiple] {
    height: auto;
    max-height: 200px;
    overflow-y: auto;
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
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
}

.modal-content {
    background-color: var(--card-light);
    border-radius: 16px;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 2rem;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    animation: slideUp 0.3s ease;
}

body.dark-mode .modal-content {
    background-color: var(--card-dark);
    color: var(--text-dark);
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.close-modal {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 2rem;
    cursor: pointer;
    color: var(--secondary-color);
    transition: color 0.3s ease;
}

.close-modal:hover {
    color: var(--primary-color);
}

.job-details-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(132, 116, 203, 0.1);
}

.job-details-title h2 {
    margin-bottom: 0.5rem;
}

.job-details-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: var(--secondary-color);
}

.meta-item i {
    color: var(--primary-color);
}

.job-details-section {
    margin-bottom: 1.5rem;
}

.job-details-section h3 {
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.job-details-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        padding: 1rem;
        margin: 0 auto;
    }

    .job-details-meta {
        flex-direction: column;
        align-items: flex-start;
    }
}
.job-filters {
    background-color: rgba(132, 116, 203, 0.05);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.job-search-container {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-input {
    flex-grow: 1;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.filter-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .job-search-container {
        flex-direction: column;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }
}
.job-card {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.job-title-section {
    flex-grow: 1;
    margin-right: 1rem;
}

.job-title-section h3 {
    margin-bottom: 0.5rem;
    line-height: 1.3;
    color: var(--primary-color);
    font-size: 1.2rem;
}

.job-title-section .company-name {
    display: block;
    color: var(--secondary-color);
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
}

.job-meta .badge,
.job-meta .salary,
.job-meta .location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-color);
}

.job-meta i {
    color: var(--primary-color);
    margin-right: 0.25rem;
}

.job-description {
    flex-grow: 1;
}

.job-description h4 {
    margin-bottom: 0.5rem;
    color: var(--primary-color);
    font-size: 1rem;
}

.job-description p {
    color: var(--text-color);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.job-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
    gap: 1rem;
}

.job-actions .btn {
    flex-grow: 1;
    text-align: center;
}


@media (max-width: 768px) {
    .job-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .job-meta {
        margin-top: 0.5rem;
    }
}


body.dark-mode .job-card {
    background-color: var(--card-dark);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

body.dark-mode .job-title-section h3 {
    color: var(--primary-color-light);
}

body.dark-mode .job-title-section .company-name {
    color: var(--secondary-color-light);
}

body.dark-mode .job-description p {
    color: var(--text-dark);
}
</style>