document.addEventListener('DOMContentLoaded', function () {
  var cookieConsentElement = document.getElementById('cookieConsent');
  var cookieConsentButton = document.getElementById('cookieConsentButton');

  // Check if the consent is already given
  if (!localStorage.getItem('cookieConsent')) {
    cookieConsentElement.style.display = 'flex';
  }

  // Function to hide the cookie consent message and store the consent in localStorage
  function hideCookieConsent() {
    localStorage.setItem('cookieConsent', 'true');
    cookieConsentElement.style.display = 'none';
  }

  // Add an event listener to the consent button
  cookieConsentButton.addEventListener('click', hideCookieConsent);
});

const searchBox = document.querySelector(".search-box");
const searchBtn = document.querySelector(".search");
const searchInput = document.querySelector("input");
const logo = document.querySelector(".logo");
const menu = document.querySelector("#menuOpen");
const cartBtn = document.querySelector(".cart");
const heart = document.querySelector(".heart");
const headerRight = document.querySelector(".header__right");

searchBtn.onclick = () => {
  searchBox.classList.toggle("active");
  searchBtn.classList.toggle("active");
  searchInput.classList.toggle("active");
  if (window.innerWidth < 1024) {
    logo.classList.toggle("none");
    menu.classList.toggle("none");
    cartBtn.classList.toggle("none");
    heart.classList.toggle("none");

    if (headerRight.style.width === "100%") {
      headerRight.style.width = "auto";
    } else {
      headerRight.style.width = "100%";
    }
  }
};

document.addEventListener("click", (event) => {
  if (
    !searchBox.contains(event.target) &&
    !searchBtn.contains(event.target) &&
    window.innerWidth < 1024
  ) {
    searchBox.classList.remove("active");
    searchBtn.classList.remove("active");
    searchInput.classList.remove("active");
    logo.classList.remove("none");
    menu.classList.remove("none");
    cartBtn.classList.remove("none");
    heart.classList.remove("none");
  }
});


function initializeMenu(open, close, menuId, content) {
  const menuOpen = document.getElementById(open);
  const menuClose = document.getElementById(close);
  const menu = document.getElementById(menuId);
  const menuList = document.getElementById(content);

  function openMenu() {
    menu.classList.add("active");
    menuList.classList.add("active");
  }

  function closeMenu() {
    menu.classList.remove("active");
    menuList.classList.remove("active");
  }

  function closeMenuOnOutsideClick(event) {
    if (event.target == menu) {
      closeMenu();
    }
  }

  menuOpen.addEventListener("click", openMenu);
  menuClose.addEventListener("click", closeMenu);
  window.addEventListener("click", closeMenuOnOutsideClick);
}

initializeMenu("menuOpen", "menuClose", "menu", "menuContent");
// initializeMenu('filterOpen', 'filterClose', 'filter', "filterContent");

function toggleDropdown(buttonId, dropdownId) {
  var button = document.querySelector(buttonId);
  var dropdown = document.querySelector(dropdownId);

  button.addEventListener("click", function () {
    dropdown.classList.toggle("show");
  });

  window.addEventListener("click", function (event) {
    if (!event.target.matches(buttonId)) {
      if (dropdown.classList.contains("show")) {
        dropdown.classList.remove("show");
      }
    }
  });
}

toggleDropdown(".cart__btn", ".cart__list");

const imgModal = document.getElementById("modal");
const btnModal = document.getElementById("openModal");
const prevModal = document.querySelector(".product__modal-prev");
const nextModal = document.querySelector(".product__modal-next");

if (btnModal) {
  btnModal.onclick = function () {
    imgModal.classList.toggle("active");
  };

  window.onclick = function (event) {
    if (event.target == imgModal) {
      imgModal.classList.toggle("active");
    }
  };

  const closeModal = document.getElementById("closeModal");
  if (closeModal) {
    closeModal.onclick = function () {
      imgModal.classList.toggle("active");
    };
  }
}


let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  const numSlides = document.getElementsByClassName("slideshow--slides").length;

  slideIndex += n;

  // Handle looping back to the first slide
  if (slideIndex > numSlides) {
    slideIndex = 1;
  } else if (slideIndex < 1) {
    slideIndex = numSlides;
  }

  showSlides(slideIndex);
}


function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  const slides = document.getElementsByClassName("slideshow--slides");
  const dotsContainer = document.getElementById("dots");
  const numSlides = slides.length;

  slideIndex = Math.max(1, Math.min(n, numSlides));

  Array.from(slides).forEach((slide, index) => {
    slide.style.display = index === slideIndex - 1 ? "block" : "none";
  });

  dotsContainer.innerHTML = "";

  for (let i = 0; i < numSlides; i++) {
    const dot = document.createElement("span");
    dot.className = "dot";
    dot.addEventListener("click", () => currentSlide(i + 1));
    dotsContainer.appendChild(dot);
  }

  const dots = document.getElementsByClassName("dot");
  Array.from(dots).forEach((dot, index) => {
    dot.classList.toggle("active", index === slideIndex - 1);
  });
}


//order
var accordions = document.getElementsByClassName('details__accordion');

for (var i = 0; i < accordions.length; i++) {
  var accordion = accordions[i];
  var headers = accordion.getElementsByClassName('details__accordion-header');

  for (var j = 0; j < headers.length; j++) {
    var header = headers[j];
    header.addEventListener('click', toggleAccordion);
  }
}

function toggleAccordion() {
  var content = this.nextElementSibling;
  var accordionItem = this.parentNode;
  var accordion = accordionItem.parentNode;

  // Close all other accordion items
  var items = accordion.getElementsByClassName('details__accordion--item');
  for (var i = 0; i < items.length; i++) {
    var item = items[i];
    if (item !== accordionItem) {
      var itemContent = item.querySelector('.details__accordion-wrap');
      var itemHeader = item.querySelector('.details__accordion-header');
      itemHeader.classList.remove('active');
      itemContent.style.maxHeight = null;
    }
  }

  this.classList.toggle('active');
  content.style.maxHeight = content.style.maxHeight ? null : content.scrollHeight + 'px';
}









