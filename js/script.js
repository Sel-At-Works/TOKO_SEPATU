// toggle class active

const navbarNav = document.querySelector('.navbar-menu');

// ketika hamburger menu diklik
document.querySelector('#menu').onclick = () => {
    navbarNav.classList.toggle('active');
};

// klick di luar sidebar untuk menghilangkan sidebar
const menu = document.querySelector('#menu');

document.addEventListener('click', function (e) {
    if(!menu.contains(e.target) && !navbarNav.contains(e.target)) {
        navbarNav.classList.remove('active');
    }
});