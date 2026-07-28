// LetsCode! Student Portal -- shared client-side behavior for
// settings tabs and the profile picture preview.

document.addEventListener('DOMContentLoaded', function () {

    // --- Settings tab switching ---
    const tabs = document.querySelectorAll('.settings-tab');
    const panels = document.querySelectorAll('.settings-panel');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const target = tab.getAttribute('data-tab');

            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            tab.classList.add('active');
            const panel = document.querySelector('.settings-panel[data-panel="' + target + '"]');
            if (panel) panel.classList.add('active');

            const url = new URL(window.location);
            url.searchParams.set('tab', target);
            window.history.replaceState({}, '', url);
        });
    });

    // --- Close modal when clicking outside the box ---
    document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });

    // --- Open switch-course modal automatically if URL ends in #switch-course ---
    if (window.location.hash === '#switch-course') {
        const modal = document.getElementById('switchCourseModal');
        if (modal) modal.classList.add('open');
    }
});

// --- Live preview for the profile picture upload ---
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const img = document.getElementById('avatarPreview');
        if (img) img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
