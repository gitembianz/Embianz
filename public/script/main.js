function setBodyOverflow(value) {
  document.querySelector("body").style.overflow = value;
}

document.body.addEventListener("click", function (event) {
  var cartList = document.getElementById("cartContent");
  var cartContent = document.querySelector(".cart__list");
  var cartBtn = document.getElementById("cartBtn");

  var wishList = document.getElementById("heartContent");
  var wishContent = document.querySelector(".heart__list");
  var wishBtn = document.getElementById("heartBtn");

  if (!cartList.contains(event.target) && !cartBtn.contains(event.target)) {
    cartContent.classList.remove("show");
    setBodyOverflow("unset");
  } else {
    setBodyOverflow("hidden");
  }

  if (!wishList.contains(event.target) && !wishBtn.contains(event.target)) {
    wishContent.classList.remove("show");
    setBodyOverflow("unset");
  } else {
    setBodyOverflow("hidden");
  }
});

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

// const homeprev = document.getElementById("home__prev");
// if (homeprev) {
//   homeprev.onclick = function () {
//     let lists = document.querySelectorAll(".home__item");
//     document.getElementById("home__slide").prepend(lists[lists.length - 1]);
//   };
// }

// const homenext = document.getElementById("home__next");
// if (homenext) {
//   homenext.onclick = function () {
//     let lists = document.querySelectorAll(".home__item");
//     document.getElementById("home__slide").prepend(lists[lists.length - 1]);
//   };
// }

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
    timeoutId = setTimeout(
      () => (carousel.scrollLeft += firstCardWidth),
      90000
    );
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
const searchInput = document.getElementById("searchInput");

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape" && !searchInput.matches(":focus")) {
    search.classList.remove("active");
    document.querySelector("body").style.overflow = "unset";
  }
  if (event.key === "/") {
    search.classList.add("active");
    setTimeout(() => {
      searchInput.focus();
    }, 100);
    document.querySelector("body").style.overflow = "hidden";
  }
});

searchOpen.addEventListener("click", () => {
  search.classList.add("active");
  setTimeout(() => {
    searchInput.focus();
  }, 100);
  document.querySelector("body").style.overflow = "hidden";
});

searchClose.addEventListener("click", () => {
  search.classList.remove("active");
  document.querySelector("body").style.overflow = "unset";
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

// const imgModal = document.getElementById("modal");
// const btnModal = document.getElementById("openModal");
// const prevModal = document.querySelector(".product__modal-prev");
// const nextModal = document.querySelector(".product__modal-next");

// if (btnModal) {
//   btnModal.onclick = function () {
//     imgModal.classList.toggle("active");
//   };

//   window.onclick = function (event) {
//     if (event.target == imgModal) {
//       imgModal.classList.toggle("active");
//     }
//   };

//   const closeModal = document.getElementById("closeModal");
//   if (closeModal) {
//     closeModal.onclick = function () {
//       imgModal.classList.toggle("active");
//     };
//   }
// }

// let slideIndex = 1;
// showSlides(slideIndex);

// function plusSlides(n) {
//   const numSlides = document.getElementsByClassName("slideshow--slides").length;

//   slideIndex += n;

//   // Handle looping back to the first slide
//   if (slideIndex > numSlides) {
//     slideIndex = 1;
//   } else if (slideIndex < 1) {
//     slideIndex = numSlides;
//   }

//   showSlides(slideIndex);
// }

// function currentSlide(n) {
//   showSlides((slideIndex = n));
// }

// function showSlides(n) {
//   const slides = document.getElementsByClassName("slideshow--slides");
//   const dotsContainer = document.getElementById("dots");
//   const numSlides = slides.length;

//   slideIndex = Math.max(1, Math.min(n, numSlides));

//   Array.from(slides).forEach((slide, index) => {
//     slide.style.display = index === slideIndex - 1 ? "block" : "none";
//   });
//   if (dotsContainer) {
//     dotsContainer.innerHTML = "";
//   }

//   for (let i = 0; i < numSlides; i++) {
//     const dot = document.createElement("span");
//     dot.className = "dot";
//     dot.addEventListener("click", () => currentSlide(i + 1));
//     dotsContainer.appendChild(dot);
//   }

//   const dots = document.getElementsByClassName("dot");
//   Array.from(dots).forEach((dot, index) => {
//     dot.classList.toggle("active", index === slideIndex - 1);
//   });
// }

function initializeSlider() {
  const sliderElement = document.querySelector(".slider");

  // Verificați dacă sliderElement există înainte de a inițializa sliderul
  if (sliderElement) {
    const slides = sliderElement.querySelectorAll(".slide");
    const prevButton = sliderElement.querySelector("#prev");
    const nextButton = sliderElement.querySelector("#next");
    const paginationContainer = sliderElement.querySelector(
      ".slider__pagination"
    );
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

    prevButton.addEventListener("click", () =>
      goToSlide(currentSlideIndex - 1)
    );
    nextButton.addEventListener("click", () =>
      goToSlide(currentSlideIndex + 1)
    );

    showSlide(currentSlideIndex);
    createPaginationDots();
    updatePaginationDots();

    sliderElement.addEventListener(
      "touchstart",
      (e) => {
        touchStartX = e.touches[0].clientX;
        isDragging = true;
        e.preventDefault();
      },
      { passive: false }
    );

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

// document.addEventListener("DOMContentLoaded", initializeSlider);
function initializeSlider2() {
  const slider = document.querySelector('.slider2');
  const wrapper = slider.querySelector('.slider-wrapper2');
  const slides = slider.querySelectorAll('.slide2');
  const pagination = slider.querySelector('.pagination2');

  // Verify the existence of required variables
  if (!slider || !wrapper || !slides || !pagination) {
    console.error('One or more required elements are missing.');
    return;
  }

  // Variables
  let slideIndex = 0;
  let startX = null;
  let endX = null;

  // Function to update pagination
  function updatePagination() {
    pagination.innerHTML = '';
    slides.forEach((_, index) => {
      const button = document.createElement('div');
      button.classList.add('pagination-button');
      if (index === slideIndex) {
        button.classList.add('active');
      }
      button.addEventListener('click', () => {
        goToSlide(index);
      });
      pagination.appendChild(button);
    });
  }

  // Function to navigate to a specific slide
  function goToSlide(index) {
    slideIndex = index;
    const translateX = -index * 100;
    wrapper.style.transform = `translateX(${translateX}%)`;
    updatePagination();
  }

  // Add touch event listeners to each slide
  slides.forEach((slide, index) => {
    slide.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
    });

    slide.addEventListener('touchmove', (e) => {
      endX = e.touches[0].clientX;
    });

    slide.addEventListener('touchend', () => {
      if (startX !== null && endX !== null) {
        const deltaX = endX - startX;
        const threshold = 50; // You can adjust this threshold as needed
        if (deltaX > threshold && index > 0) {
          goToSlide(index - 1); // Swipe right
        } else if (deltaX < -threshold && index < slides.length - 1) {
          goToSlide(index + 1); // Swipe left
        } else {
          goToSlide(index); // Return to the current slide
        }
      }
      startX = null;
      endX = null;
    });
  });

  // Initial setup
  updatePagination();
}

// Call the function when the DOM is ready
document.addEventListener('DOMContentLoaded', initializeSlider2);
