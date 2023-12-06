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
        2500
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
//<------------------------ Display Loading ---------------------------->
function displayLoading() {
  const loading = document.getElementById("loadingLogo");

  if (!loading) {
    return;
  } else {
    var currentDate = new Date();
    var currentDay = currentDate.toISOString().split("T")[0];
    if (document.cookie.indexOf("visited_loading_" + currentDay) === -1) {
      document.cookie =
        "visited_loading_" +
        currentDay +
        "=true; expires=" +
        new Date(
          currentDate.getFullYear(),
          currentDate.getMonth(),
          currentDate.getDate() + 1
        ).toUTCString() +
        "; path=/";

      loading.style.display = "flex";
    } else {
      loading.style.display = "none";
    }
  }
}
//<---------------------- End Display Loading -------------------------->
//<--------------------------------------------------------------------->
//<------------------------ Start Functions IOS ------------------------>
window.onload = displayLoading;
slider(".main-slider");
slider(".card-slider");
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
document.addEventListener("DOMContentLoaded", function () {
  slider(".main-slider");
  slider(".card-slider");
});
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
// function handleClick() {
//   setTimeout(() => {
//     slider(".main-slider");
//     slider(".card-slider");
//   }, 500);
// }
