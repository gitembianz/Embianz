//<--------------------------------------------------------------------->

// const { forEach } = require("lodash");

//<--------------------------- Slider-Images --------------------------->
function slider(sliderID) {
  const slider = document.querySelector(sliderID);
  const wrapper = document.querySelector(`${sliderID}__wrapper`);
  // const sliderControl = document.querySelectorAll(`${sliderID}__button`);

  const buttonPrev = document.querySelector(`${sliderID}__button.prev`);
  const buttonNext = document.querySelector(`${sliderID}__button.next`);

  if (!slider || !wrapper || !buttonPrev || !buttonNext) {
    return;
  } else {
    let firstCardWidth = wrapper.querySelector(
      `${sliderID}__slide`,
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
    buttonPrev.addEventListener("click", () => {
      // Derulează sliderul spre stânga cu o distanță egală cu lățimea primei cărți
      wrapper.scrollLeft -= firstCardWidth;
    });

    buttonNext.addEventListener("click", () => {
      // Derulează sliderul spre dreapta cu o distanță egală cu lățimea primei cărți
      wrapper.scrollLeft += firstCardWidth;
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
      // wrapper.scrollIntoView({ behavior: "smooth", block: "nearest" });
      wrapper.style.cursor = "grabbing";
      wrapperChildrens.forEach((card) => {
        card.style.pointerEvents = "none";
      });
    };

    const dragStop = () => {
      isDragging = false;
      wrapper.classList.remove("dragging");
      wrapper.style.cursor = "initial";
      wrapperChildrens.forEach((card) => {
        card.style.pointerEvents = "auto";
      });
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
        25000,
      );
    };
    autoPlay();

    // const adjustInitialScrollPosition = () => {
    //   wrapper.scrollLeft = wrapper.offsetWidth;
    // };

    const resizeHandler = () => {
      // Recalculează lățimea primei cărți în funcție de dimensiunea actualizată a ferestrei de browser
      firstCardWidth = wrapper.querySelector(`${sliderID}__slide`).offsetWidth;
      // Reajustăm poziția de scroll initială pentru a menține consistența în cazul redimensionărilor
      // adjustInitialScrollPosition();
    };

    // Adaugă evenimentul de redimensionare la fereastra browser-ului
    window.addEventListener("resize", resizeHandler);

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
//<---------------------------- Add to Cart ---------------------------->
function flyToCart(button) {
  const shopping_cart = document.getElementById("basketOpen");
  const numberCart = shopping_cart.querySelector(".header__count");
  const target_parent = button.closest(".product"); // Obținem cel mai apropiat părinte cu clasa "product"
  const sliderWrappers = document.querySelectorAll(".card-slider__wrapper");

  setTimeout(() => {
    button.classList.remove('out')
    button.classList.add('in')

    sliderWrappers.forEach(wrapper => {
      wrapper.style.overflowX = 'clip';
      setTimeout(() => {
        wrapper.style.overflowX = 'auto';
      }, 2000);
    });

    setTimeout(() => {
      button.classList.add('out')
    }, 650)

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
      target_parent.removeChild(flying_img);
      shopping_cart.classList.remove("active");
      if (numberCart) {
        numberCart.style.scale = 1;
      }
    }, 1500);

    if (numberCart) {
      numberCart.style.scale = 1.5;
    }
  }, 400)
}
//<-------------------------- End Add to Cart -------------------------->
//<--------------------------------------------------------------------->
//<------------------------ Start Functions IOS ------------------------>
slider(".main-slider");
slider(".new-slider");
slider(".popular-slider");

//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
