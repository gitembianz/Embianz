//<--------------------------------------------------------------------->
//<------------------------- Slider Product ---------------------------->
function sliderProduct(sliderId) {
  const slider = document.querySelector(sliderId);
  const wrapper = slider.querySelector(sliderId + "__wrapper");
  const slides = slider.querySelectorAll(sliderId + "__slide");
  const pagination = slider.querySelector(sliderId + "__pagination");
  const prevButton = slider.querySelector(sliderId + "__prev");
  const nextButton = slider.querySelector(sliderId + "__next");
  let currentIndex = 0;
  let touchStartX = 0;

  if (
    !slider ||
    !wrapper ||
    !slides ||
    !pagination ||
    !prevButton ||
    !nextButton
  ) {
    console.log("Elementele necesare pentru slider product nu au fost găsite.");
    return;
  }

  function navigation(direction) {
    let newIndex;
    if (direction === "next") {
      newIndex = currentIndex + 1;
      if (newIndex >= slides.length) {
        newIndex = slides.length - 1;
        nextButton.classList.add("disabled");
      }
      prevButton.classList.remove("disabled");
    } else {
      newIndex = currentIndex - 1;
      if (newIndex < 0) {
        newIndex = 0;
        prevButton.classList.add("disabled");
      }
      nextButton.classList.remove("disabled");
    }
    currentIndex = newIndex;

    // Dezactivați butonul din dreapta când ajungeți la ultimul slide
    if (currentIndex === slides.length - 1) {
      nextButton.classList.add("disabled");
    } else {
      nextButton.classList.remove("disabled");
    }
    // Dezactivați butonul din stânga când ajungeți la primul slide
    if (currentIndex === 0) {
      prevButton.classList.add("disabled");
    } else {
      prevButton.classList.remove("disabled");
    }

    updatePagination();
    updateTransform(wrapper);
  }

  nextButton.addEventListener("click", () => {
    navigation("next");
  });

  prevButton.addEventListener("click", () => {
    navigation("prev");
  });
  prevButton.classList.add("disabled");

  function updatePagination() {
    const thumbnails = Array.from(pagination.children);
    thumbnails.forEach((thumbnail, index) => {
      thumbnail.classList.toggle("active", index === currentIndex);
    });
    // Dezactivați butonul din stânga când ajungeți la primul slide
    if (currentIndex === 0) {
      prevButton.classList.add("disabled");
    } else {
      prevButton.classList.remove("disabled");
    }

    // Dezactivați butonul din dreapta când ajungeți la ultimul slide
    if (currentIndex === slides.length - 1) {
      nextButton.classList.add("disabled");
    } else {
      nextButton.classList.remove("disabled");
    }
  }

  function updateTransform(element) {
    element.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function createThumb(slide, index) {
    const existingThumbnail = pagination.querySelector(
      `.thumbnail[data-index="${index}"]`
    );

    // Verificăm dacă thumbnail-ul există deja
    if (existingThumbnail) {
      return;
    }

    const thumbnail = document.createElement("img");
    const mediaElement =
      slide.querySelector("img") || slide.querySelector("video");

    if (mediaElement) {
      thumbnail.src = mediaElement.src;
      thumbnail.alt = `Thumbnail ${index + 1}`;
      thumbnail.classList.add("thumbnail");
      thumbnail.setAttribute("data-index", index); // Adăugăm un atribut pentru a identifica slide-ul asociat

      thumbnail.addEventListener("click", () => {
        currentIndex = index;
        updateTransform(wrapper);
        updatePagination();
      });

      pagination.appendChild(thumbnail);
    } else {
      console.error(`Elementul media lipsește în slide-ul cu indexul ${index}`);
    }
  }

  slides.forEach((slide, index) => {
    const videoElement = slide.querySelector("video");
    if (videoElement) {
      videoElement.controls = false;
    }
    createThumb(slide, index);
  });

  function handleSlideSwipe(event, index) {
    const touchEndX = event.changedTouches[0].clientX;
    const swipeDistance = touchEndX - touchStartX;

    // Eliminarea verificării pentru modal
    if (swipeDistance > 50 && index > 0) {
      currentIndex = index - 1;
    } else if (swipeDistance < -50 && index < slides.length - 1) {
      currentIndex = index + 1;
    }

    updatePagination();
    updateTransform(wrapper);

    touchStartX = 0;
  }

  slides.forEach((slide, index) => {
    let isSwiping = false;

    slide.addEventListener("touchstart", (event) => {
      touchStartX = event.touches[0].clientX;
    });

    slide.addEventListener("touchmove", (event) => {
      if (touchStartX) {
        const swipeDistance = event.changedTouches[0].clientX - touchStartX;

        // Dacă se realizează un swipe în orizontală și nu se derulează, blocăm derularea implicită
        if (Math.abs(swipeDistance) > 10 && !isScrolling()) {
          isSwiping = true;
          event.preventDefault();
        }
      }
    });

    slide.addEventListener("touchend", (event) => {
      if (isSwiping) {
        handleSlideSwipe(event, index);
        isSwiping = false;
      }
    });
  });

  function isScrolling() {
    return false; // Adăugați aici logica pentru a verifica dacă derularea este în curs de desfășurare
  }

  window.addEventListener("load", () => updatePagination());
}
//<----------------------- End Slider Product -------------------------->
//<--------------------------------------------------------------------->
//<------------------------- Modal Product ----------------------------->
function modalProduct(modalId, sliderId) {
  const modal = document.querySelector(modalId);
  const modalContent = modal.querySelector(modalId + "__content");
  const closeButton = modal.querySelector(modalId + "__close");

  const slider = document.querySelector(sliderId);
  const slides = slider.querySelectorAll(sliderId + "__slide");

  const body = document.querySelector("body");

  if (!modal || !modalContent || !closeButton) {
    console.log("Componentele Modalului nu au fost găsite.");
    return;
  } else {
    closeButton.addEventListener("click", () => {
      modal.classList.remove("active");
      body.style.overflow = "auto";
    });
    slides.forEach((slide, index) => {
      slide.addEventListener("click", () => {
        // Găsește elementul <img> în cadrul fiecărui slide
        var imgElement = slide.querySelector("img");

        // Verifică dacă elementul <img> există
        if (imgElement) {
          // Accesează atributul data-img-src
          var dataSrcValue = imgElement.getAttribute("data-img-src");

          // Creează un nou element <img>
          var newImgElement = document.createElement("img");

          // Setează atributul src al noului element <img> la valoarea din data-img-src
          newImgElement.src = dataSrcValue;

          // Adaugă noul element <img> în conținutul modalului
          modalContent.innerHTML = "";
          modalContent.appendChild(newImgElement);

          // Adaugă clasa "active" la modal
          modal.classList.add("active");

          // Blochează scroll-ul paginii
          body.style.overflow = "hidden";
        } else {
          console.error(
            "Elementul <img> nu a fost găsit în cadrul slide-ului."
          );
        }
      });
    });

    window.addEventListener("click", (event) => {
      if (event.target === modal) {
        modal.classList.remove("active");
        body.style.overflow = "auto";
      }
    });
  }
}
//<----------------------- End Modal Product --------------------------->
//<--------------------------------------------------------------------->
//<--------------------------- Slider-Images --------------------------->
function slider(sliderID) {
  const slider = document.querySelector(sliderID);
  const wrapper = document.querySelector(`${sliderID}__wrapper`);
  const sliderControl = document.querySelectorAll(`${sliderID}__button`);

  if (!slider || !wrapper || !sliderControl.length) {
    return;
  } else {
    const firstCardWidth = wrapper.querySelector(
      `${sliderID}__slide`
    ).offsetWidth;
    const wrapperChildrens = [...wrapper.children];

    let isDragging = false,
      isAutoPlay = true,
      startX,
      startScrollLeft,
      timeoutId;

    // Get the number of cards that can fit in the wrapper at once
    let cardPerView = Math.round(wrapper.offsetWidth / firstCardWidth);

    // Insert copies of the last few cards to beginning of wrapper for infinite scrolling
    wrapperChildrens
      .slice(-cardPerView)
      .reverse()
      .forEach((card) => {
        wrapper.insertAdjacentHTML("afterbegin", card.outerHTML);
      });

    // Insert copies of the first few cards to end of wrapper for infinite scrolling
    wrapperChildrens.slice(0, cardPerView).forEach((card) => {
      wrapper.insertAdjacentHTML("beforeend", card.outerHTML);
    });

    // Scroll the wrapper at appropriate postition to hide first few duplicate cards on Firefox
    wrapper.classList.add("no-transition");
    wrapper.scrollLeft = wrapper.offsetWidth;
    wrapper.classList.remove("no-transition");

    // Add event listeners for the arrow buttons to scroll the wrapper left and right
    sliderControl.forEach((btn) => {
      btn.addEventListener("click", () => {
        wrapper.scrollLeft += btn.classList.contains("prev")
          ? -firstCardWidth
          : firstCardWidth;
      });
    });

    const dragStart = (e) => {
      isDragging = true;
      wrapper.classList.add("dragging");
      // Records the initial cursor and scroll position of the wrapper
      startX = e.pageX;
      startScrollLeft = wrapper.scrollLeft;
    };

    const dragging = (e) => {
      if (!isDragging) return; // if isDragging is false return from here
      // Updates the scroll position of the wrapper based on the cursor movement
      wrapper.scrollLeft = startScrollLeft - (e.pageX - startX);
    };

    const dragStop = () => {
      isDragging = false;
      wrapper.classList.remove("dragging");
    };

    const infiniteScroll = () => {
      // If the wrapper is at the beginning, scroll to the end
      if (wrapper.scrollLeft === 0) {
        wrapper.classList.add("no-transition");
        wrapper.scrollLeft = wrapper.scrollWidth - 2 * wrapper.offsetWidth;
        wrapper.classList.remove("no-transition");
      }
      // If the wrapper is at the end, scroll to the beginning
      else if (
        Math.ceil(wrapper.scrollLeft) ===
        wrapper.scrollWidth - wrapper.offsetWidth
      ) {
        wrapper.classList.add("no-transition");
        wrapper.scrollLeft = wrapper.offsetWidth;
        wrapper.classList.remove("no-transition");
      }

      // Clear existing timeout & start autoplay if mouse is not hovering over wrapper
      clearTimeout(timeoutId);
      if (!slider.matches(":hover")) autoPlay();
    };

    const autoPlay = () => {
      if (window.innerWidth < 800 || !isAutoPlay) return; // Return if window is smaller than 800 or isAutoPlay is false
      // Autoplay the wrapper after every 2500 ms
      timeoutId = setTimeout(
        () => (wrapper.scrollLeft += firstCardWidth),
        9999999
      );
    };
    autoPlay();

    wrapper.addEventListener("mousedown", dragStart);
    wrapper.addEventListener("mousemove", dragging);
    document.addEventListener("mouseup", dragStop);
    wrapper.addEventListener("scroll", infiniteScroll);
    slider.addEventListener("mouseenter", () => clearTimeout(timeoutId));
    slider.addEventListener("mouseleave", autoPlay);
  }
}
//<------------------------- End Slider-Images ------------------------->
//<--------------------------------------------------------------------->
//<--------------------------- Related-Slider -------------------------->
function relatedSlider() {
  const slider = document.getElementById("relatedSlider");

  if (!slider) {
    return;
  } else {
    const wrapper = slider.querySelector(".related__wrapper");
    const left = slider.querySelector(".related__btn.prev");
    const right = slider.querySelector(".related__btn.next");

    function updateCardWidth() {
      const cards = wrapper.querySelectorAll(".card");
      const cardWidth = cards[0].offsetWidth + 20; // adăugăm 20px pentru gap-ul dintre card-uri
      return cardWidth;
    }

    function scrollSlider(distance) {
      wrapper.scrollBy({
        left: distance,
        behavior: "smooth",
      });
    }

    function toggleButtonsVisibility(entries) {
      const hasVerticalScrollbar = wrapper.scrollHeight > wrapper.clientHeight;
      const hasHorizontalScrollbar = wrapper.scrollWidth > wrapper.clientWidth;

      // Ascundem sau afișăm butoanele în funcție de existența scrollbar-ului
      if (hasVerticalScrollbar || hasHorizontalScrollbar) {
        left.style.display = "flex";
        right.style.display = "flex";
      } else {
        left.style.display = "none";
        right.style.display = "none";
      }
    }

    function updateButtonStates() {
      const scrollLeft = wrapper.scrollLeft;
      const maxScrollLeft = wrapper.scrollWidth - wrapper.clientWidth;

      if (scrollLeft <= 0) {
        left.classList.add("disabled");
      } else {
        left.classList.remove("disabled");
      }

      if (scrollLeft >= maxScrollLeft) {
        right.classList.add("disabled");
      } else {
        right.classList.remove("disabled");
      }
    }

    // Creăm un nou ResizeObserver
    const resizeObserver = new ResizeObserver(toggleButtonsVisibility);

    // Observăm schimbările în dimensiunile wrapper-ului
    resizeObserver.observe(wrapper);

    left.addEventListener("click", () => {
      if (!left.classList.contains("disabled")) {
        scrollSlider(-updateCardWidth());
      }
    });

    right.addEventListener("click", () => {
      if (!right.classList.contains("disabled")) {
        scrollSlider(updateCardWidth());
      }
    });

    wrapper.addEventListener("scroll", updateButtonStates);

    window.addEventListener("resize", () => {
      const cardWidth = updateCardWidth();
      window.cardWidth = cardWidth;
    });

    window.cardWidth = updateCardWidth();

    // Actualizăm starea butoanelor la încărcarea paginii
    updateButtonStates();
  }
}

//<------------------------- End Related-Slider ------------------------>
//<--------------------------------------------------------------------->
//<---------------------------- Add to Cart ---------------------------->
function flyToCart(button) {
  const shopping_cart = document.getElementById("basketOpen");
  const numberCart = shopping_cart.querySelector(".header__count");
  const target_parent = button.closest(".product"); // Obținem cel mai apropiat părinte cu clasa "product"

  numberCart.style.scale = 1.4;

  if (!target_parent) {
    console.error("Nu s-a găsit părintele 'product'.");
    return;
  }

  shopping_cart.classList.add("active");

  // Creăm o imagine separată
  let img = target_parent.querySelector("img");
  let flying_img = img.cloneNode();
  flying_img.classList.add("flying-img");
  target_parent.appendChild(flying_img);

  // Obținem poziția imaginii care va zbura
  const flying_img_pos = flying_img.getBoundingClientRect();
  const shopping_cart_pos = shopping_cart.getBoundingClientRect();

  let data = {
    left:
      shopping_cart_pos.left -
      (shopping_cart_pos.width / 2 +
        flying_img_pos.left +
        flying_img_pos.width / 2),
    top: shopping_cart_pos.bottom - flying_img_pos.bottom + 30,
  };

  flying_img.style.cssText = `
      --left : ${data.left.toFixed(2)}px;
      --top : ${data.top.toFixed(2)}px;
      z-index: 400;
  `;

  setTimeout(() => {
    target_parent.style.zIndex = "";
    target_parent.removeChild(flying_img);
    shopping_cart.classList.remove("active");
    numberCart.style.scale = 1;
  }, 1500);
}
//<-------------------------- End Add to Cart -------------------------->
//<--------------------------------------------------------------------->
//<------------------------- Add On WishList --------------------------->
function addWishList(button) {
  const wish = document.getElementById("wishlistCount");
  wish.style.scale = 1.4;

  setTimeout(() => {
    wish.style.scale = 1;
  }, 1500);
}
//<----------------------- End Add On WishList ------------------------->
//<--------------------------------------------------------------------->
//<-------------------------- Start Functions -------------------------->
sliderProduct(".product-slider");
modalProduct(".product-modal", ".product-slider");
slider(".card-slider");
relatedSlider();
//<------------------------ End Start Functions ------------------------>
//<--------------------------------------------------------------------->
