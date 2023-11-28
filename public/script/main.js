//<--------------------------------------------------------------------->
//<---------------------------- ScrollEvent ---------------------------->
function scrollEvent() {
  let header = document.querySelector("header");
  let banner = document.querySelector(".banner");
  let main = document.querySelector("main");
  let body = document.body;

  function updateStyles() {
    main.style.paddingTop = header.clientHeight + banner.clientHeight + "px";
    header.style.top = banner.clientHeight + "px";
  }

  function handleOverflowChange() {
    if (body.style.overflow === "hidden") {
      main.style.paddingTop = header.clientHeight + "px";
    }
  }

  if (header && banner && main) {
    updateStyles();

    document.addEventListener("scroll", function () {
      if (window.scrollY > banner.clientHeight) {
        header.style.top = "0";
        banner.style.visibility = "hidden";
      } else {
        header.style.top = banner.clientHeight + "px";
        banner.style.visibility = "visible";
      }
    });

    window.addEventListener("resize", function () {
      // Actualizează stilurile atunci când se schimbă dimensiunea ecranului
      updateStyles();
    });

    // Monitorizează schimbările la proprietatea overflow
    const observer = new MutationObserver(handleOverflowChange);
    observer.observe(body, { attributes: true, attributeFilter: ["style"] });
  } else {
    return;
  }
}
//<-------------------------- End ScrollEvent -------------------------->
//<--------------------------------------------------------------------->
//<------------------------ DropMenu on leftbar ------------------------>
function dropmenus(menuID, setActive = false) {
  let dropmenus = document.querySelectorAll(menuID);

  dropmenus.forEach(function (menu) {
    let button = menu.querySelector(`.${menu.className}__open`);
    let list = menu.querySelector(`.${menu.className}__list`);

    if (button && list) {
      if (setActive) {
        menu.classList.add("active");
        list.classList.add("active");
      }

      button.addEventListener("click", function () {
        menu.classList.toggle("active");
        list.classList.toggle("active");
      });
    } else {
      return;
    }
  });
}
//<---------------------- End DropMenu on leftbar ---------------------->
//<--------------------------------------------------------------------->
//<------------------------------ LeftBar ------------------------------>
function leftbar(idOpen, idClose, idList, idContent) {
  const buttonOpen = document.getElementById(idOpen);
  const buttonClose = document.getElementById(idClose);
  const list = document.getElementById(idList);
  const content = document.getElementById(idContent);
  const body = document.querySelector("body");

  if (!buttonOpen || !buttonClose || !list || !content) {
    // console.log("leftbar error");
    return;
  } else {
    buttonOpen.addEventListener("click", () => {
      list.classList.add("active");
      body.style.overflow = "hidden";
    });
    buttonClose.addEventListener("click", () => {
      list.classList.remove("active");
      body.style.overflow = "auto";
    });
    list.addEventListener("click", (event) => {
      if (
        !content.contains(event.target) &&
        !buttonOpen.contains(event.target)
      ) {
        list.classList.remove("active");
        body.style.overflow = "auto";
      }
    });
  }
}
//<---------------------------- End LeftBar ---------------------------->
//<--------------------------------------------------------------------->
//<---------------------------- Apply Filter --------------------------->
function applyFilter(Close, Reset) {
  const buttonClose = document.getElementById(Close);
  const buttonReset = document.getElementById(Reset);
  const body = document.querySelector("body");

  if (!buttonClose || !buttonReset) {
    // console.log("leftbar error");
    return;
  } else {
    buttonClose.addEventListener("click", () => {
      body.style.overflow = "auto";
    });
    buttonReset.addEventListener("click", () => {
      body.style.overflow = "auto";
    });
  }
}
//<------------------------- End Apply Filter -------------------------->
//<--------------------------------------------------------------------->
//<-------------------------- Apply Asortiment ------------------------->
function applySort(selector) {
  const items = document.querySelectorAll(selector);
  const body = document.querySelector("body");

  if (!items) {
    // console.log("leftbar error");
    return;
  } else {
    items.forEach(function (item) {
      item.addEventListener("click", () => {
        body.style.overflow = "auto";
      });
    });
  }
}
//<------------------------ End Apply Asortiment ----------------------->
//<--------------------------------------------------------------------->
//<----------------------------- SearchBar ----------------------------->
function searchBar() {
  const searchBtn = document.getElementById("searchOpen");
  const closeBtn = document.getElementById("searchClose");
  const input = document.getElementById("searchInput");
  const modalClose = document.getElementById("modalClose");
  searchBtn.addEventListener("click", function () {
    document.body.style.overflow = "hidden";
    input.focus();
  });

  closeBtn.addEventListener("click", function () {
    document.body.style.overflow = "auto";
  });
  modalClose.addEventListener("click", function () {
    document.body.style.overflow = "auto";
  });
}
//<--------------------------- End SearchBar --------------------------->
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
//<------------------------------- Modal ------------------------------->
function modal(modalID) {
  const modal = document.querySelector(modalID);
  const body = document.querySelector("body");

  if (!modal) {
    // console.warn("Nu exista nici un modal pe aceasta pagina");
    return;
  } else {
    const content = modal.querySelector(modalID + "__content");
    const close = modal.querySelector(modalID + "__close");

    close.addEventListener("click", () => {
      modal.classList.remove("active");
      body.style.overflow = "auto";
    });

    window.addEventListener("alert__modal", (event) => {
      modal.classList.add("active");
      body.style.overflow = "hidden";
    });

    window.addEventListener("click", (event) => {
      if (event.target === modal) {
        modal.classList.remove("active");
        body.style.overflow = "auto";
      }
    });
  }
}
//<----------------------------- End Modal ----------------------------->
//<--------------------------------------------------------------------->
//<--------------------------- Sticky Element -------------------------->
function stickyElement(elementSelector) {
  let element = document.querySelector(elementSelector);

  if (!element) {
    return;
  } else {
    let isActive = false;
    let activationPosition = 10; // Poziția la care se activează funcționalitatea sticky

    window.addEventListener("scroll", function () {
      let scrollPosition = window.scrollY;

      if (!isActive && scrollPosition > activationPosition) {
        isActive = true;
        let elementPosition = element.offsetTop + element.offsetHeight + 20;

        window.addEventListener("scroll", function () {
          let position = window.scrollY + window.innerHeight;

          if (position > elementPosition) {
            element.classList.remove("sticky");
          } else {
            element.classList.add("sticky");
          }
        });
      } else if (isActive && scrollPosition == activationPosition) {
        isActive = false;
        element.classList.add("sticky"); // Adaugăm "sticky" înapoi când se revine la partea de sus
      }
    });
  }
}
//<------------------------- End Sticky Element ------------------------>
//<--------------------------------------------------------------------->
//<------------------------ Start Functions IOS ------------------------>
scrollEvent();
searchBar();
// leftbar functions for basket, wish, menu, filter and sort
leftbar("basketOpen", "basketClose", "basketList", "basketContent");
leftbar("wishOpen", "wishClose", "wishList", "wishContent");
leftbar("menuOpen", "menuClose", "menuList", "menuContent");
leftbar("filterOpen", "filterClose", "filterList", "filterContent");
leftbar("sortOpen", "sortClose", "sortList", "sortContent");
// dropdown functions for menu and filter
dropmenus(".dropfilter", true);
dropmenus(".dropmenu");
// filter functions for closing and resetting
applyFilter("closeFilter", "resetFilter");
applySort(".sort__item");
// Sliders
slider(".main-slider");
slider(".card-slider");
slider(".product-slider");
// Modal
modal(".modal");
// sticky element
stickyElement(".details");
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
document.addEventListener("DOMContentLoaded", function () {
  searchBar();
  scrollEvent();
  // leftbar functions for basket, wish, menu, filter and sort
  leftbar("basketOpen", "basketClose", "basketList", "basketContent");
  leftbar("wishOpen", "wishClose", "wishList", "wishContent");
  leftbar("menuOpen", "menuClose", "menuList", "menuContent");
  leftbar("filterOpen", "filterClose", "filterList", "filterContent");
  leftbar("sortOpen", "sortClose", "sortList", "sortContent");
  // dropdown functions for menu and filter
  dropmenus(".dropfilter", true);
  dropmenus(".dropmenu");
  // filter functions for closing and resetting
  applyFilter("closeFilter", "resetFilter");
  applySort(".sort__item");
  // Sliders
  slider(".main-slider");
  slider(".card-slider");
  slider(".product-slider");
  // Modal
  modal(".modal");
  // sticky element
  stickyElement(".details");
});
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
