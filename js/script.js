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
}); // <-- tambahkan kurung tutup di sini

// Tangkap elemen
const produkCards = document.querySelectorAll('.produk-card');
const popupOverlay = document.getElementById('popupOverlay');
const popupImg = document.getElementById('popupImg');
const popupTitle = document.getElementById('popupTitle');
const popupPrice = document.getElementById('popupPrice');
const closePopup = document.getElementById('closePopup');

// Tambahkan event ke setiap produk
produkCards.forEach(card => {
  card.addEventListener('click', () => {
    const imgSrc = card.querySelector('img').src;
    const title = card.querySelector('.produk-card-title').textContent;
    const price = card.querySelector('.produk-card-price').textContent;

    popupImg.src = imgSrc;
    popupTitle.textContent = title;
    popupPrice.textContent = price;

    popupOverlay.style.display = 'flex';
  });
});

// Tutup popup saat klik tombol close
closePopup.addEventListener('click', () => {
  popupOverlay.style.display = 'none';
});

// Tutup popup saat klik di luar box
popupOverlay.addEventListener('click', (e) => {
  if (e.target === popupOverlay) {
    popupOverlay.style.display = 'none';
  }
});
