document.addEventListener("DOMContentLoaded", function () {
    // Toggle collapse functionality
    document.querySelectorAll('.toggle-collapse').forEach(function (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const targetSelector = toggleBtn.getAttribute('data-target');
            const targetElement = document.querySelector(targetSelector);
            if (targetElement) {
                targetElement.classList.toggle('show');
            }

            const arrowIcon = toggleBtn.querySelector('.arrow-icon');
            if (arrowIcon) {
                arrowIcon.classList.toggle('rotate-up');
            }
        });
    });
});
