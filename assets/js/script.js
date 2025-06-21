document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }, 4000); 
    });
});

function confirmAction(message = "Are you sure?") {
    return confirm(message);
}

