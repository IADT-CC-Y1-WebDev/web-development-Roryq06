const toggleBtn = document.getElementById('themeToggle');

function updateButton() {
    if (!toggleBtn) return; // ✅ prevent crash

    if (document.body.classList.contains('dark-mode')) {
        toggleBtn.textContent = '☀️ Light Mode';
    } else {
        toggleBtn.textContent = '🌙 Dark Mode';
    }
}

// Only add event if button exists
if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');

        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }

        updateButton();
    });
}

// Always apply saved theme
document.addEventListener("DOMContentLoaded", function () {
    const theme = localStorage.getItem('theme');

    if (theme === 'dark') {
        document.body.classList.add('dark-mode');
    }

    updateButton();
});