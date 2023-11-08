// header Fixed
function calculateBannerHeight() {
  var headerBanner = document.querySelector("#header-banner");
  var header = document.querySelector("header");
  var bannerHeight = headerBanner.clientHeight;

  if (window.scrollY > bannerHeight) {
    header.classList.add("fixed");
    header.style.top = "0";
  } else {
    header.classList.remove("fixed");
    header.style.top = bannerHeight + "px";
  }
}

// Calculează înălțimea banner-ului la începutul încărcării și la evenimentul resize
window.onload = function () {
  calculateBannerHeight();
};

window.addEventListener("resize", function () {
  calculateBannerHeight();
});

window.addEventListener("scroll", function () {
  calculateBannerHeight();
});

function setMarginTop() {
  const headerHeight = document.querySelector("header").offsetHeight;
  const mainElement = document.querySelector("main");
  mainElement.style.marginTop = `${headerHeight}px`;
}
window.addEventListener("resize", setMarginTop);
setMarginTop();

const homeprev = document.getElementById("home__prev");
if (homeprev) {
  homeprev.onclick = function () {
    let lists = document.querySelectorAll(".home__item");
    document.getElementById("home__slide").prepend(lists[lists.length - 1]);
  };
}

const homenext = document.getElementById("home__next");
if (homenext) {
  homenext.onclick = function () {
    let lists = document.querySelectorAll(".home__item");
    document.getElementById("home__slide").prepend(lists[lists.length - 1]);
  };
}

// ______________________________________________________________________
const wrapper = document.querySelector(".card-wrapper");
const carousel = document.querySelector(".card-carousel");
if (carousel) {
  const firstCardWidth = carousel.querySelector(".card").offsetWidth;
  const arrowBtns = document.querySelectorAll(".card-nav");
  const carouselChildrens = [...carousel.children];
  let isDragging = false,
    isAutoPlay = true,
    startX,
    startScrollLeft,
    timeoutId;

  // Get the number of cards that can fit in the carousel at once

  let cardPerView = Math.round(carousel.offsetWidth / firstCardWidth);

  // Insert copies of the last few cards to beginning of carousel for infinite scrolling
  carouselChildrens
    .slice(-cardPerView)
    .reverse()
    .forEach((card) => {
      carousel.insertAdjacentHTML("afterbegin", card.outerHTML);
    });

  // Insert copies of the first few cards to end of carousel for infinite scrolling
  carouselChildrens.slice(0, cardPerView).forEach((card) => {
    carousel.insertAdjacentHTML("beforeend", card.outerHTML);
  });

  // Scroll the carousel at appropriate postition to hide first few duplicate cards on Firefox
  carousel.classList.add("no-transition");
  carousel.scrollLeft = carousel.offsetWidth;
  carousel.classList.remove("no-transition");

  // Add event listeners for the arrow buttons to scroll the carousel left and right
  arrowBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      carousel.scrollLeft +=
        btn.id == "cardLeft" ? -firstCardWidth : firstCardWidth;
    });
  });

  const dragStart = (e) => {
    isDragging = true;
    carousel.classList.add("dragging");
    // Records the initial cursor and scroll position of the carousel
    startX = e.pageX;
    startScrollLeft = carousel.scrollLeft;
  };

  const dragging = (e) => {
    if (!isDragging) return; // if isDragging is false return from here
    // Updates the scroll position of the carousel based on the cursor movement
    carousel.scrollLeft = startScrollLeft - (e.pageX - startX);
  };

  const dragStop = () => {
    isDragging = false;
    carousel.classList.remove("dragging");
  };

  const infiniteScroll = () => {
    // If the carousel is at the beginning, scroll to the end
    if (carousel.scrollLeft === 0) {
      carousel.classList.add("no-transition");
      carousel.scrollLeft = carousel.scrollWidth - 2 * carousel.offsetWidth;
      carousel.classList.remove("no-transition");
    }
    // If the carousel is at the end, scroll to the beginning
    else if (
      Math.ceil(carousel.scrollLeft) ===
      carousel.scrollWidth - carousel.offsetWidth
    ) {
      carousel.classList.add("no-transition");
      carousel.scrollLeft = carousel.offsetWidth;
      carousel.classList.remove("no-transition");
    }

    // Clear existing timeout & start autoplay if mouse is not hovering over carousel
    clearTimeout(timeoutId);
    if (!wrapper.matches(":hover")) autoPlay();
  };

  const autoPlay = () => {
    if (window.innerWidth < 800 || !isAutoPlay) return; // Return if window is smaller than 800 or isAutoPlay is false
    // Autoplay the carousel after every 2500 ms
    timeoutId = setTimeout(() => (carousel.scrollLeft += firstCardWidth), 90000);
  };
  autoPlay();

  carousel.addEventListener("mousedown", dragStart);
  carousel.addEventListener("mousemove", dragging);
  document.addEventListener("mouseup", dragStop);
  carousel.addEventListener("scroll", infiniteScroll);
  wrapper.addEventListener("mouseenter", () => clearTimeout(timeoutId));
  wrapper.addEventListener("mouseleave", autoPlay);
}

// ______________________________________________________________________

const search = document.getElementById("search");
const searchOpen = document.getElementById("searchOpen");
const searchClose = document.getElementById("searchClose");

window.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    search.classList.remove("active");
  }
  if (event.key === "/") {
    search.classList.add("active");
  }
});

// Function on Header Items
const menuItems = document.querySelectorAll(".menu__item");

menuItems.forEach((item) => {
  item.addEventListener("click", function () {
    const nextElementSibling = item.nextElementSibling;
    const isActive = nextElementSibling.classList.contains("active");
    const isActiveBTN = item.classList.contains("active");

    menuItems.forEach((otherItem) => {
      otherItem.classList.remove("active");
    });

    // Add or remove "active" class based on current state
    if (!isActiveBTN) {
      item.classList.add("active");
    }

    // Remove "active" class from all items
    menuItems.forEach((otherItem) => {
      otherItem.nextElementSibling.classList.remove("active");
    });

    // Add or remove "active" class based on current state
    if (!isActive) {
      nextElementSibling.classList.add("active");
    }
  });

  window.addEventListener("resize", function () {
    if (window.innerWidth <= 768) {
      menuItems.forEach((item) => {
        item.classList.remove("active");
        item.nextElementSibling.classList.remove("active");
      });
    }
  });
});

const menu = document.querySelector("#menuOpen");
const cartBtn = document.querySelector(".cart");

function initializeMenu(open, close, menuId, content) {
  const menuOpen = document.getElementById(open);
  const menuClose = document.getElementById(close);
  const menu = document.getElementById(menuId);
  const menuList = document.getElementById(content);

  function openMenu() {
    menu.classList.add("active");
    menuList.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closeMenu() {
    menu.classList.remove("active");
    menuList.classList.remove("active");
    document.body.style.overflow = "unset";
  }

  function closeMenuOnOutsideClick(event) {
    if (event.target == menu) {
      closeMenu();
      document.body.style.overflow = "unset";
    }
  }

  menuOpen.addEventListener("click", openMenu);
  menuClose.addEventListener("click", closeMenu);
  window.addEventListener("click", closeMenuOnOutsideClick);
}

initializeMenu("menuOpen", "menuClose", "menu", "menuContent");
// initializeMenu('filterOpen', 'filterClose', 'filter', "filterContent");

function toggleDropdown(buttonId, dropdownId) {
  const button = document.querySelector(buttonId);
  const dropdown = document.querySelector(dropdownId);

  const toggleDropdown = () => dropdown.classList.toggle("show");

  button.addEventListener("click", toggleDropdown);

  document.addEventListener("click", (event) => {
    if (
      !event.target.matches(buttonId) &&
      dropdown.classList.contains("show")
    ) {
      toggleDropdown();
    }
  });
}

function dropdown(dropdown) {
  var buttons = document.querySelectorAll(dropdown);

  buttons.forEach(function (button) {
    button.addEventListener("click", function () {
      var dropdown = this.nextElementSibling;
      dropdown.classList.toggle("show");
    });
  });
}

// dropdown(".filter__dropdown--btn");
dropdown(".filter__sort--btn");

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
  showSlides((slideIndex = n));
}

function showSlides(n) {
  const slides = document.getElementsByClassName("slideshow--slides");
  const dotsContainer = document.getElementById("dots");
  const numSlides = slides.length;

  slideIndex = Math.max(1, Math.min(n, numSlides));

  Array.from(slides).forEach((slide, index) => {
    slide.style.display = index === slideIndex - 1 ? "block" : "none";
  });
  if (dotsContainer) {
    dotsContainer.innerHTML = "";
  }

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

document.body.addEventListener("click", function (event) {
  var cartList = document.getElementById("cartContent");
  var cartBtn = document.getElementById("cartBtn");

  var wishList = document.getElementById("heartContent");
  var wishBtn = document.getElementById("heartBtn");

  // Check if the clicked element is not inside the cart and the cart button
  if (!cartList.contains(event.target) && !cartBtn.contains(event.target)) {
    // If clicked outside, remove the 'show' class from cart__list
    cartList.classList.remove("show");
  }
  if (!wishList.contains(event.target) && !wishBtn.contains(event.target)) {
    // If clicked outside, remove the 'show' class from cart__list
    wishList.classList.remove("show");
  }
});

//order
// var accordions = document.getElementsByClassName('details__accordion');

// for (var i = 0; i < accordions.length; i++) {
//   var accordion = accordions[i];
//   var headers = accordion.getElementsByClassName('details__accordion-header');

//   for (var j = 0; j < headers.length; j++) {
//     var header = headers[j];
//     header.addEventListener('click', toggleAccordion);
//   }
// }

// function toggleAccordion() {
//   var content = this.nextElementSibling;
//   var accordionItem = this.parentNode;
//   var accordion = accordionItem.parentNode;

//   // Close all other accordion items
//   var items = accordion.getElementsByClassName('details__accordion--item');
//   for (var i = 0; i < items.length; i++) {
//     var item = items[i];
//     if (item !== accordionItem) {
//       var itemContent = item.querySelector('.details__accordion-wrap');
//       var itemHeader = item.querySelector('.details__accordion-header');
//       itemHeader.classList.remove('active');
//       itemContent.style.maxHeight = null;
//     }
//   }

//   this.classList.toggle('active');
//   content.style.maxHeight = content.style.maxHeight ? null : content.scrollHeight + 'px';
// }
function initializeSlider() {
  const sliderElement = document.querySelector(".slider");

  // Verificați dacă sliderElement există înainte de a inițializa sliderul
  if (sliderElement) {
    const slides = sliderElement.querySelectorAll(".slide");
    const prevButton = sliderElement.querySelector("#prev");
    const nextButton = sliderElement.querySelector("#next");
    const paginationContainer = sliderElement.querySelector(".slider__pagination");
    let currentSlideIndex = 0;
    let isDragging = false;
    let touchStartX = 0;
    let startX = 0;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.style.transform = `translateX(${100 * (i - index)}%)`;
      });
    }

    function createPaginationDots() {
      slides.forEach((_, i) => {
        const dot = document.createElement("span");
        dot.classList.add("pagination-dot");
        dot.addEventListener("click", () => {
          currentSlideIndex = i;
          showSlide(currentSlideIndex);
          updatePaginationDots();
        });
        paginationContainer.appendChild(dot);
      });
    }

    function updatePaginationDots() {
      const dots = paginationContainer.querySelectorAll(".pagination-dot");
      dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === currentSlideIndex);
      });
    }

    function goToPreviousSlide() {
      currentSlideIndex--;
      if (currentSlideIndex < 0) {
        currentSlideIndex = slides.length - 1;
      }
      showSlide(currentSlideIndex);
      updatePaginationDots();
    }

    function goToNextSlide() {
      currentSlideIndex++;
      if (currentSlideIndex >= slides.length) {
        currentSlideIndex = 0;
      }
      showSlide(currentSlideIndex);
      updatePaginationDots();
    }

    function goToSlide(index) {
      if (index < 0) {
        index = slides.length - 1;
      } else if (index >= slides.length) {
        index = 0;
      }
      currentSlideIndex = index;
      showSlide(currentSlideIndex);
      updatePaginationDots();
    }

    prevButton.addEventListener("click", () => goToSlide(currentSlideIndex - 1));
    nextButton.addEventListener("click", () => goToSlide(currentSlideIndex + 1));

    showSlide(currentSlideIndex);
    createPaginationDots();
    updatePaginationDots();

    sliderElement.addEventListener("touchstart", (e) => {
      touchStartX = e.touches[0].clientX;
      isDragging = true;
      e.preventDefault();
    }, { passive: false });

    sliderElement.addEventListener("touchmove", (e) => {
      if (!isDragging) return;

      const touchEndX = e.touches[0].clientX;
      const deltaX = touchStartX - touchEndX;

      if (deltaX > 50) {
        goToNextSlide();
        isDragging = false;
      } else if (deltaX < -50) {
        goToPreviousSlide();
        isDragging = false;
      }

      e.preventDefault();
    });

    sliderElement.addEventListener("touchend", () => {
      isDragging = false;
    });

    sliderElement.addEventListener("mousedown", (e) => {
      startX = e.clientX;
      isDragging = true;
      sliderElement.classList.add("grabbing");
    });

    sliderElement.addEventListener("mousemove", (e) => {
      if (!isDragging) return;

      const endX = e.clientX;
      const deltaX = startX - endX;

      if (deltaX > 50) {
        goToNextSlide();
        isDragging = false;
      } else if (deltaX < -50) {
        goToPreviousSlide();
        isDragging = false;
      }
    });

    sliderElement.addEventListener("mouseup", () => {
      isDragging = false;
      sliderElement.classList.remove("grabbing");
    });

    let autoplayInterval;

    function startAutoplay() {
      autoplayInterval = setInterval(() => {
        goToNextSlide();
      }, 5000);
    }

    startAutoplay();
  }
}

document.addEventListener("DOMContentLoaded", initializeSlider);