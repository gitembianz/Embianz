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
    currentIndex =
      (currentIndex + (direction === "next" ? 1 : slides.length - 1)) %
      slides.length;

    updatePagination();
    updateTransform(wrapper);
  }

  nextButton.addEventListener("click", () => {
    navigation("next");
  });

  prevButton.addEventListener("click", () => {
    navigation("prev");
  });

  function updatePagination() {
    const thumbnails = Array.from(pagination.children);
    thumbnails.forEach((thumbnail, index) => {
      thumbnail.classList.toggle("active", index === currentIndex);
    });
  }

  function updateTransform(element) {
    element.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function createThumb(slide, index) {
    const thumbnail = document.createElement("img");
    const mediaElement =
      slide.querySelector("img") || slide.querySelector("video");

    if (mediaElement) {
      thumbnail.src = mediaElement.src;
      thumbnail.alt = `Thumbnail ${index + 1}`;
      thumbnail.classList.add("thumbnail");

      thumbnail.addEventListener("click", () => {
        currentIndex = index;
        updateTransform(wrapper);
        updatePagination();
      });

      pagination.appendChild(thumbnail);
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

    const isInsideModal = event.target.closest(".modal") !== null;

    if (!isInsideModal) {
      if (swipeDistance > 50 && index > 0) {
        currentIndex = index - 1;
      } else if (swipeDistance < -50 && index < slides.length - 1) {
        currentIndex = index + 1;
      }

      updatePagination();
      updateTransform(wrapper);
    }

    touchStartX = 0;
  }

  slides.forEach((slide, index) => {
    slide.addEventListener("touchstart", (event) => {
      touchStartX = event.touches[0].clientX;
    });

    slide.addEventListener("touchmove", (event) => {
      if (touchStartX) {
        event.preventDefault(); // Evită derularea implicită a paginii pe swipe
      }
    });

    slide.addEventListener("touchend", (event) => {
      handleSlideSwipe(event, index);
    });
  });

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
        modalContent.innerHTML = slide.innerHTML;
        modal.classList.add("active");
        body.style.overflow = "hidden";
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
//<------------------------ Start Functions IOS ------------------------>
sliderProduct(".product-slider");
modalProduct(".product-modal", ".product-slider");
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
