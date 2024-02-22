//<--------------------------------------------------------------------->
//<------------------------- Slider Product ---------------------------->
function sliderProduct(sliderId, modalId) {
  const slider = document.querySelector(sliderId);
  const wrapper = slider.querySelector(sliderId + "__wrapper");
  const slides = slider.querySelectorAll(sliderId + "__slide");
  const pagination = slider.querySelector(sliderId + "__pagination");
  const prevButton = slider.querySelector(sliderId + "__prev");
  const nextButton = slider.querySelector(sliderId + "__next");
  const modal = document.querySelector(modalId);
  const modalContent = modal.querySelector(modalId + "__content");
  const closeButton = modal.querySelector(modalId + "__close");
  const body = document.querySelector("body");

  let currentIndex = 0;
  let touchStartX = 0;
  let isDragging = false;
  let startX = 0;

  if (
    !slider ||
    !wrapper ||
    !slides ||
    !pagination ||
    !prevButton ||
    !nextButton ||
    !modal ||
    !modalContent ||
    !closeButton ||
    !body
  ) {
    console.log(
      "Elementele necesare pentru slider product sau modal nu au fost găsite.",
    );
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

    if (currentIndex === slides.length - 1) {
      nextButton.classList.add("disabled");
    } else {
      nextButton.classList.remove("disabled");
    }
    if (currentIndex === 0) {
      prevButton.classList.add("disabled");
    } else {
      prevButton.classList.remove("disabled");
    }

    updatePagination();
    updateTransform(wrapper);
  }

  closeButton.addEventListener("click", () => {
    modal.classList.remove("active");
    body.style.overflow = "auto"; // Activează scroll-ul paginii
  });

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
      if (index === currentIndex) {
        thumbnail.focus(); // Focalizăm punctul de paginare activ
        thumbnail.scrollIntoView({ behavior: "smooth", block: "nearest" });
      }
    });
    if (currentIndex === 0) {
      prevButton.classList.add("disabled");
    } else {
      prevButton.classList.remove("disabled");
    }

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
      `.thumbnail[data-index="${index}"]`,
    );

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
      thumbnail.setAttribute("data-index", index);

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

    // Adăugăm eveniment pentru trăgând și clic pe slide-uri
    slide.addEventListener("mousedown", (event) => {
      touchStartX = event.clientX;
      startX = event.clientX;
      isDragging = true;
      wrapper.style.cursor = "grab"; // Setează cursorul la "grab" la începutul trăgândului
    });

    slide.addEventListener("mousemove", (event) => {
      if (isDragging) {
        const swipeDistance = event.clientX - startX;

        if (Math.abs(swipeDistance) > 250 && !isScrolling()) {
          const indexChange = swipeDistance > 0 ? -1 : 1;
          const newIndex = currentIndex + indexChange;

          if (newIndex >= 0 && newIndex < slides.length) {
            currentIndex = newIndex;
            updatePagination();
            updateTransform(wrapper);
          }

          startX = event.clientX;
        }
      }
    });

    slide.addEventListener("mouseup", () => {
      isDragging = false;
      wrapper.style.cursor = "auto"; // Resetarea cursorului la cursorul implicit la sfârșitul trăgândului
    });

    slide.addEventListener("mouseleave", () => {
      if (isDragging) {
        isDragging = false;
        wrapper.style.cursor = "auto"; // Resetarea cursorului la cursorul implicit la părăsirea zonei slide-ului cu mouse-ul
      }
    });

    slide.addEventListener("click", () => {
      if (event.clientX === touchStartX) {
        // Execută doar dacă nu a fost un trăgând, ci un clic simplu
        const imgElement = slide.querySelector("img");
        if (imgElement) {
          const dataSrcValue = imgElement.getAttribute("data-img-src");
          const newImgElement = document.createElement("img");

          newImgElement.src = dataSrcValue;
          modalContent.innerHTML = ""; // Golește conținutul modalului înainte de a adăuga imaginea
          modalContent.appendChild(newImgElement); // Adaugă imaginea în conținutul modalului

          modal.classList.add("active"); // Deschide modalul
          body.style.overflow = "hidden"; // Blochează scroll-ul paginii
        } else {
          console.error(
            "Elementul <img> nu a fost găsit în cadrul slide-ului.",
          );
        }
      }
    });
  });

  function handleSlideSwipe(event, index) {
    const touchEndX = event.changedTouches[0].clientX;
    const swipeDistance = touchEndX - touchStartX;

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
    return false;
  }

  window.addEventListener("load", () => updatePagination());
}
//<----------------------- End Modal Product --------------------------->
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
//<-------------------------- Start Functions -------------------------->
sliderProduct(".product-slider", ".product-modal");
relatedSlider();
//<------------------------ End Start Functions ------------------------>
//<--------------------------------------------------------------------->
function flyToCart(button) {
  const shopping_cart = document.getElementById("basketOpen");
  const numberCart = shopping_cart.querySelector(".header__count");
  numberCart.style.scale = 1.5;

  setTimeout(() => {
    numberCart.style.scale = 1;
  }, 1500);
}
