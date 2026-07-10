import './bootstrap';
import 'bootstrap';

const sidebar = document.getElementById('adminSidebar');
const body = document.body;

const openSidebar = () => body.classList.add('admin-sidebar-open');
const closeSidebar = () => body.classList.remove('admin-sidebar-open');

document.querySelectorAll('[data-admin-sidebar-open]').forEach((trigger) => {
    trigger.addEventListener('click', openSidebar);
});

document.querySelectorAll('[data-admin-sidebar-close]').forEach((trigger) => {
    trigger.addEventListener('click', closeSidebar);
});

if (sidebar) {
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });
}
