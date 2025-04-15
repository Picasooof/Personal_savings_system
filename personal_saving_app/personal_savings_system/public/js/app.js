// Add any necessary JavaScript functionality here
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add any form submission handling here
        });
    });

    // Handle error messages
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(message => {
        if (message.textContent.trim() !== '') {
            message.style.display = 'block';
        }
    });

    // Handle success messages
    const successMessages = document.querySelectorAll('.success-message');
    successMessages.forEach(message => {
        if (message.textContent.trim() !== '') {
            message.style.display = 'block';
        }
    });
}); 