
// Form validation and dynamic behaviors
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Date range validation for leave requests
    const dateDebut = document.querySelector('input[name="dateDebut"]');
    const dateFin = document.querySelector('input[name="dateFin"]');
    
    if (dateDebut && dateFin) {
        dateDebut.addEventListener('change', function() {
            dateFin.min = this.value;
        });
        dateFin.addEventListener('change', function() {
            dateDebut.max = this.value;
        });
    }

    // Alert auto-dismiss
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    });

    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                e.preventDefault();
            }
        });
    });

    // Dynamic form fields for overtime hours
    const addHoursButton = document.querySelector('.add-hours');
    if (addHoursButton) {
        addHoursButton.addEventListener('click', function() {
            const container = document.querySelector('.hours-container');
            const newRow = document.createElement('div');
            newRow.className = 'row mb-3';
            newRow.innerHTML = `
                <div class="col-md-5">
                    <input type="date" name="dates[]" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <input type="number" name="hours[]" class="form-control" step="0.5" min="0" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-hours">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        });
    }

    // Remove hours row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-hours')) {
            e.target.closest('.row').remove();
        }
    });

    // Search functionality
    const searchInput = document.querySelector('#search');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

// AJAX form submissions
function submitForm(formId, url) {
    const form = document.getElementById(formId);
    const formData = new FormData(form);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Une erreur est survenue');
        console.error('Error:', error);
    });
}

// Alert helper function
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
}