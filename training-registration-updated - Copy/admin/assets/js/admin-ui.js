// Some browsers restore the previous scroll position on reload/back-forward
// navigation, which made the toolbar buttons look "cut off" at the top of
// the page. Force every admin page to always start scrolled to the top.
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
window.scrollTo(0, 0);

// ---------- Toast notifications ----------
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const icons = { success: '✅', error: '⚠️', info: 'ℹ️' };

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span>${icons[type] || 'ℹ️'}</span><span>${message}</span>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('toast-out');
        setTimeout(() => toast.remove(), 250);
    }, 3500);
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.__pendingToast) {
        showToast(window.__pendingToast.message, window.__pendingToast.type);
    }

    // ---------- Confirmation modal (used for delete links) ----------
    const modal = document.getElementById('confirmModal');
    const modalTitle = document.getElementById('confirmModalTitle');
    const modalMessage = document.getElementById('confirmModalMessage');
    const modalCancel = document.getElementById('confirmModalCancel');
    const modalConfirm = document.getElementById('confirmModalConfirm');

    let pendingHref = null;

    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            pendingHref = el.getAttribute('href');
            modalTitle.textContent = el.getAttribute('data-confirm-title') || 'Are you sure?';
            modalMessage.textContent = el.getAttribute('data-confirm') || 'This action cannot be undone.';
            modal.classList.add('open');
        });
    });

    if (modalCancel) {
        modalCancel.addEventListener('click', () => {
            modal.classList.remove('open');
            pendingHref = null;
        });
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('open');
                pendingHref = null;
            }
        });
    }

    if (modalConfirm) {
        modalConfirm.addEventListener('click', () => {
            if (pendingHref) {
                showLoading();
                window.location.href = pendingHref;
            }
        });
    }

    // ---------- Loading spinner on navigation ----------
    document.querySelectorAll('a[href]:not([href^="#"]):not([data-confirm]):not([target="_blank"])').forEach(a => {
        a.addEventListener('click', () => showLoading());
    });

    document.querySelectorAll('form').forEach(f => {
        f.addEventListener('submit', () => showLoading());
    });

    window.addEventListener('pageshow', (e) => {
        // Hide the spinner if the page was restored from bfcache.
        if (e.persisted) {
            hideLoading();
            window.scrollTo(0, 0);
        }
    });

    // ---------- Mobile sidebar toggle ----------
    const toggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (toggle && sidebar && overlay) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });

        sidebar.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            });
        });
    }
});

function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.classList.add('open');
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.classList.remove('open');
}
