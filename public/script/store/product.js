//<--------------------------------------------------------------------->
//<------------------------- Slider Product ---------------------------->
function sliderProduct(sliderId, modalId) {
  const slider = document.querySelector(sliderId);
  const wrapper = slider.querySelector(sliderId + "__wrapper");
  const slides = slider.querySelectorAll(sliderId + "__slide");
  const pagination = slider.querySelector(sliderId + "__pagination");
  const paginationLeft = slider.querySelector(sliderId + "__pagination--prev");
  const paginationRight = slider.querySelector(sliderId + "__pagination--next");
  const prevButton = slider.querySelector(sliderId + "__prev");
  const nextButton = slider.querySelector(sliderId + "__next");
  const modal = document.querySelector(modalId);
  const modalContent = modal.querySelector(modalId + "__content");
  const modalPrev = modal.querySelector(modalId + "__prev");
  const modalNext = modal.querySelector(modalId + "__next");
  const closeButton = modal.querySelector(modalId + "__close");
  const modalCount = modal.querySelector(modalId + "__count");
  const body = document.querySelector("body");

  let currentIndex = 0;
  let touchStartX = 0;
  let isDragging = false;
  let startX = 0;
  let mouseStartX = 0;

  if (
    !slider ||
    !wrapper ||
    !slides ||
    !pagination ||
    !paginationLeft ||
    !paginationRight ||
    !prevButton ||
    !nextButton ||
    !modal ||
    !modalContent ||
    !modalPrev ||
    !modalNext ||
    !modalCount ||
    !closeButton ||
    !body
  ) {
    console.log(
      "Elementele necesare pentru slider product sau modal nu au fost găsite."
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
        modalNext.classList.add("disabled");
      }
      prevButton.classList.remove("disabled");
      modalPrev.classList.remove("disabled");
    } else {
      newIndex = currentIndex - 1;
      if (newIndex < 0) {
        newIndex = 0;
        prevButton.classList.add("disabled");
        modalPrev.classList.add("disabled");
      }
      nextButton.classList.remove("disabled");
      modalNext.classList.remove("disabled");
    }
    currentIndex = newIndex;

    updatePagination();
    updateTransform(wrapper);
    modalCount.textContent = `${currentIndex + 1} / ${slides.length}`;
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
  modalPrev.classList.add("disabled");

  modalNext.addEventListener("click", () => {
    navigation("next");
    updateModalImage();
  });

  modalPrev.addEventListener("click", () => {
    navigation("prev");
    updateModalImage();
  });

  paginationLeft.addEventListener("click", () => {
    pagination.scrollTo({
      left: pagination.scrollLeft - 100,
      behavior: "smooth"
    });
    updatePaginationButtons();
  });

  paginationRight.addEventListener("click", () => {
    pagination.scrollTo({
      left: pagination.scrollLeft + 100,
      behavior: "smooth"
    });
    updatePaginationButtons();
  });

  pagination.addEventListener("scroll", () => {
    updatePaginationButtons();
  });

  function updatePaginationButtons() {
    if (pagination.scrollWidth > pagination.clientWidth) {
      if (pagination.scrollLeft === 0) {
        paginationLeft.classList.add("disabled");
        paginationRight.classList.remove("disabled");
      } else if (pagination.scrollLeft + pagination.clientWidth >= pagination.scrollWidth) {
        paginationLeft.classList.remove("disabled");
        paginationRight.classList.add("disabled");
      } else {
        paginationLeft.classList.remove("disabled");
        paginationRight.classList.remove("disabled");
      }
    } else {
      paginationLeft.classList.add("disabled");
      paginationRight.classList.add("disabled");
    }
  }


  setTimeout(() => {
    updatePaginationButtons();
  }, 500)

  function updatePagination() {
    // Actualizarea butoanelor pentru slider
    if (currentIndex === 0) {
      prevButton.classList.add("disabled");
      modalPrev.classList.add("disabled");
    } else {
      prevButton.classList.remove("disabled");
      modalPrev.classList.remove("disabled");
    }

    if (currentIndex === slides.length - 1) {
      nextButton.classList.add("disabled");
      modalNext.classList.add("disabled");
    } else {
      nextButton.classList.remove("disabled");
      modalNext.classList.remove("disabled");
    }

    // Actualizarea punctelor de paginare
    const thumbnails = Array.from(pagination.children);
    thumbnails.forEach((thumbnail, index) => {
      thumbnail.classList.toggle("active", index === currentIndex);
      if (index === currentIndex) {
        thumbnail.focus();
        pagination.scrollTo({
          left: thumbnail.offsetLeft - (pagination.offsetWidth - thumbnail.offsetWidth) / 2,
          behavior: "smooth"
        });
      }
    });
  }

  function updateTransform(element) {
    element.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function createThumb(slide, index) {
    const existingThumbnail = pagination.querySelector(
      `.thumbnail[data-index="${index}"]`
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
        modalCount.textContent = `${currentIndex + 1} / ${slides.length}`;
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

        if (Math.abs(swipeDistance) > 50 && !isScrolling()) {
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
          const dataAltValue = imgElement.getAttribute("data-name-alt");
          const newImgElement = document.createElement("img");

          newImgElement.src = dataSrcValue;
          newImgElement.alt = dataAltValue;
          modalContent.innerHTML = ""; // Golește conținutul modalului înainte de a adăuga imaginea
          modalContent.appendChild(newImgElement); // Adaugă imaginea în conținutul modalului

          modal.classList.add("active"); // Deschide modalul
          body.style.overflow = "hidden"; // Blochează scroll-ul paginii

          function handleKeyPress(event) {
            if (event.keyCode === 27) {
              modal.classList.remove("active"); // Deschide modalul
              body.style.overflow = "auto"; // Blochează scroll-ul paginii
            }
          }

          document.addEventListener("keydown", handleKeyPress);
        } else {
          console.error(
            "Elementul <img> nu a fost găsit în cadrul slide-ului."
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

        // Verifică dacă utilizatorul face un gest de zoom
        if (event.scale && event.scale !== 1) {
          return; // Ignoră acțiunea dacă utilizatorul face zoom
        }

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


  modalCount.textContent = `${currentIndex + 1} / ${slides.length}`;
  function updateModalImage() {
    // Actualizarea imaginii din modal
    const imgElement = slides[currentIndex].querySelector("img");
    if (imgElement) {
      const dataSrcValue = imgElement.getAttribute("data-img-src");
      const dataAltValue = imgElement.getAttribute("data-name-alte");
      const newImgElement = document.createElement("img");

      newImgElement.src = dataSrcValue;
      newImgElement.alt = dataAltValue;
      modalContent.innerHTML = "";
      modalContent.appendChild(newImgElement);

      modalCount.textContent = `${currentIndex + 1} / ${slides.length}`;

    } else {
      console.error(
        "Elementul <img> nu a fost găsit în cadrul slide-ului."
      );
    }
  }

  modalContent.addEventListener("mousedown", (event) => {
    mouseStartX = event.clientX;
  });

  modalContent.addEventListener("mousemove", (event) => {
    if (mouseStartX) {
      const dragDistance = event.clientX - mouseStartX;

      if (Math.abs(dragDistance) > 50 && !isScrolling()) {
        event.preventDefault();
        isDragging = true;
      }
    }
  });

  modalContent.addEventListener("mouseup", (event) => {
    if (isDragging) {
      const mouseEndX = event.clientX;
      const dragDistance = mouseEndX - mouseStartX;

      if (dragDistance > 50 && currentIndex > 0) {
        navigation("prev");
        updateModalImage();
      } else if (dragDistance < -50 && currentIndex < slides.length - 1) {
        navigation("next");
        updateModalImage();
      }

      mouseStartX = 0;
      isDragging = false;
    }
  });

  // Funcție pentru gesturi de touch pe modalContent
  modalContent.addEventListener("touchstart", (event) => {
    touchStartX = event.touches[0].clientX;
  });

  modalContent.addEventListener("touchmove", (event) => {
    if (touchStartX) {
      const swipeDistance = event.changedTouches[0].clientX - touchStartX;

      // Verifică dacă utilizatorul face un gest de zoom
      if (event.scale && event.scale !== 1) {
        return; // Ignoră acțiunea dacă utilizatorul face zoom
      }

      if (Math.abs(swipeDistance) > 50 && !isScrolling()) {
        event.preventDefault();
        isDragging = true;
      }
    }
  });

  modalContent.addEventListener("touchend", (event) => {
    if (isDragging) {
      const touchEndX = event.changedTouches[0].clientX;
      const swipeDistance = touchEndX - touchStartX;

      if (swipeDistance > 50 && currentIndex > 0) {
        navigation("prev");
        updateModalImage();
      } else if (swipeDistance < -50 && currentIndex < slides.length - 1) {
        navigation("next");
        updateModalImage();
      }

      touchStartX = 0;
      isDragging = false;
    }
  });

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
function flyToCart(button) {
  const shopping_cart = document.getElementById("basketOpen");
  const numberCart = shopping_cart.querySelector(".header__count");
  const target_parent = button.closest(".product"); // Obținem cel mai apropiat părinte cu clasa "product"

  if(!button.classList.contains('in')) {
      button.classList.add('in');
      setTimeout(() => button.classList.remove('in'), 1500);
  }

  if (!target_parent) {
    console.error("Nu s-a găsit părintele 'product'.");
    return;
  }

  const cardName = target_parent.querySelector(".product__title").innerText.trim(); // Obținem numele cardName
  const cardPrice = target_parent.querySelector(".product__price").innerText.trim(); // Obținem pretul cardPrice
  // console.log(cardName);
  // console.log(cardPrice);

  if (typeof dataLayer !== 'undefined') {
    dataLayer.push({
      'event': 'adaugareInCos',
      'cardName': cardName,
      'cardPrice': cardPrice
    });
  } else {
    // console.log('dataLayer is not defined');
  }


  // Creăm o imagine separată
  shopping_cart.classList.add("active");
  let img = target_parent.querySelector("img");
  let flying_img = img.cloneNode();
  flying_img.classList.add("flying-img-product");
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
    target_parent.removeChild(flying_img);
    shopping_cart.classList.remove("active");
  }, 1500);

  // Number Cart upscale
  if(!numberCart) {
    return;
  } else {
      numberCart.style.scale = 1.5;
      setTimeout(() => {
          numberCart.style.scale = 1;
      }, 1500);
  }
}

function addWishList(button) {
  const wish = document.getElementById("wishlistCount");

  const target_parent = button.closest(".product");
  const cardName = target_parent.querySelector(".product__title").innerText.trim(); // Obținem numele cardName
  const cardPrice = target_parent.querySelector(".product__price--title").innerText.trim(); // Obținem pretul cardPrice

  if (typeof dataLayer !== 'undefined') {
    dataLayer.push({
      'event': 'adaugareInFavorite',
      'cardName': cardName,
      'cardPrice': cardPrice
    });
  } else {
    // console.log('dataLayer is not defined');
  }

  if(!wish) {
    return;
  } else {
    wish.style.scale = 1.5;

    setTimeout(() => {
      wish.style.scale = 1;
    }, 1500);
  }
}
//<-------------------------- Start Functions -------------------------->
sliderProduct(".product-slider", ".product-modal");
relatedSlider();
//<------------------------ End Start Functions ------------------------>
//<--------------------------------------------------------------------->
// function flyToCart(button) {
//   const shopping_cart = document.getElementById("basketOpen");
//   const numberCart = shopping_cart.querySelector(".header__count");
//   numberCart.style.scale = 1.5;

//   setTimeout(() => {
//     numberCart.style.scale = 1;
//   }, 1500);
// }
