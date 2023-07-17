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


const search = document.getElementById("search");
const searchOpen = document.getElementById("searchOpen");
const searchClose = document.getElementById("searchClose");

searchOpen.addEventListener("click", function(){
  search.classList.add("active");
})
searchClose.addEventListener("click", function(){
  search.classList.remove("active");
})
window.onclick = function(event) {
  if (event.target == search) {
    search.classList.remove("active");
  }
}
window.addEventListener("keydown", function(event) {
  if (event.keyCode === 27) {
    search.classList.remove("active");
  }
  if (event.keyCode === 191) {
    search.classList.add("active");
  }
});



const logo = document.querySelector(".logo");
const menu = document.querySelector("#menuOpen");
const cartBtn = document.querySelector(".cart");
const heart = document.querySelector(".heart");
const headerRight = document.querySelector(".header__right");






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


// Get references to DOM elements
const mainImage = document.querySelector(".thumbnail-active");
const thumbnails = document.querySelectorAll(".thumbnail");
const prevButton = document.querySelector(".product__image-prev");
const nextButton = document.querySelector(".product__image-next");

// Attach click event listener to each thumbnail
thumbnails.forEach((thumbnail, index) => {
  thumbnail.addEventListener("click", function () {
    // Update main image source with clicked thumbnail's source
    const thumbnailSrc = this.getAttribute("src");
    mainImage.setAttribute("src", thumbnailSrc);

    // Remove 'thumbnail-active' class from all thumbnails
    thumbnails.forEach((t) => t.classList.remove("thumbnail-active"));

    // Add 'thumbnail-active' class to the clicked thumbnail
    this.classList.add("thumbnail-active");
  });
});
if (prevButton) {
  prevButton.addEventListener("click", function () {
    const activeIndex = Array.from(thumbnails).findIndex((t) =>
      t.classList.contains("thumbnail-active")
    );
    const previousIndex =
      activeIndex === 0 ? thumbnails.length - 1 : activeIndex - 1;
    thumbnails[previousIndex].click();
  });
}
if (nextButton) {
  nextButton.addEventListener("click", function () {
    const activeIndex = Array.from(thumbnails).findIndex((t) =>
      t.classList.contains("thumbnail-active")
    );
    const nextIndex = activeIndex === thumbnails.length - 1 ? 0 : activeIndex + 1;
    thumbnails[nextIndex].click();
  });
}


// Counter
if (count, countIncrease, countDecrease) {

  let counter = 1;
  const counterInput = document.getElementById("count");
  const incrementBtn = document.getElementById("countIncrease");
  const decrementBtn = document.getElementById("countDecrease");

  function updateCounterValue() {
    counterInput.value = counter;
  }

  function incrementCounter() {
    counter++;
    updateCounterValue();
  }

  function decrementCounter() {
    if (counter > 1) {
      counter--;
      updateCounterValue();
    }
  }

  function validateAndSetCounterValue() {
    const inputValue = parseInt(counterInput.value);
    if (!isNaN(inputValue)) {
      counter = Math.max(inputValue, 1);
    } else {
      counter = 0;
    }
    updateCounterValue();
  }

  incrementBtn.addEventListener("click", incrementCounter);
  decrementBtn.addEventListener("click", decrementCounter);
  counterInput.addEventListener("input", validateAndSetCounterValue);
}

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









