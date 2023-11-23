//<--------------------------------------------------------------------->
//<---------------------------- ScrollEvent ---------------------------->
function scrollEvent() {
  var header = document.querySelector("header");

  if (header) {
    document.addEventListener("scroll", function () {
      if (typeof header !== "undefined" && header !== null) {
        if (window.scrollY > header.clientHeight) {
          header.classList.add("fixed");
        } else {
          header.classList.remove("fixed");
        }
      }
    });
  } else {
    console.error("Variabila 'header' nu există.");
  }
}
//<-------------------------- End ScrollEvent -------------------------->
//<--------------------------------------------------------------------->
//<------------------------ DropMenu on leftbar ------------------------>
function dropmenus(iddropmenus) {
  var dropmenus = document.querySelectorAll(iddropmenus);

  // Verifică dacă există cel puțin un element .dropmenu
  if (dropmenus.length === 0) {
    console.warn("Nu există elemente .dropmenu.");
    return;
  }

  dropmenus.forEach(function (dropmenu) {
    // Găsește elementele relevante în cadrul fiecărui dropmenu
    var button = dropmenu.querySelector(`.${dropmenu.className}__open`);
    var list = dropmenu.querySelector(`.${dropmenu.className}__list`);

    // Adaugă evenimentul de click la buton
    button.addEventListener("click", function () {
      list.classList.toggle("active");
      dropmenu.classList.toggle("active");
    });
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
    console.log("leftbar error");
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
    console.log("leftbar error");
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
    console.log("leftbar error");
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
  const search = document.getElementById("searchList");
  const input = document.getElementById("searchInput");
  const content = document.getElementById("searchContent");

  searchBtn.addEventListener("click", function () {
    document.body.style.overflow = "hidden";
    setTimeout(() => {
      input.focus();
    }, 200);
  });

  closeBtn.addEventListener("click", function () {
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
  const content = modal.querySelector(modalID + "__content");
  const close = modal.querySelector(modalID + "__close");

  if (!modal && !content && !close) {
    console.warn("Nu exista nici un modal pe aceasta pagina");
    return;
  } else {
    window.addEventListener("alert__modal", (event) => {
      modal.classList.add("active");
    });

    close.addEventListener("click", () => {
      modal.classList.remove = "active";
    });
    window.addEventListener("click", (event) => {
      if (event.target === modal) {
        modal.classList.remove("active");
      }
    });
  }
}
//<----------------------------- End Modal ----------------------------->
//<--------------------------------------------------------------------->
//<-------------------------- Start Functions -------------------------->
document.addEventListener("DOMContentLoaded", function () {
  scrollEvent();
  searchBar();
  // leftbar functions for basket, wish, menu, filter and sort
  leftbar("basketOpen", "basketClose", "basketList", "basketContent");
  leftbar("wishOpen", "wishClose", "wishList", "wishContent");
  leftbar("menuOpen", "menuClose", "menuList", "menuContent");
  leftbar("filterOpen", "filterClose", "filterList", "filterContent");
  leftbar("sortOpen", "sortClose", "sortList", "sortContent");
  // dropdown functions for menu and filter
  dropmenus(".dropmenu");
  dropmenus(".dropfilter");
  // filter functions for closing and resetting
  applyFilter("closeFilter", "resetFilter");
  applySort(".sort__item");
  // Sliders
  slider(".main-slider");
  slider(".card-slider");
  slider(".product-slider");
  // Modal
  modal(".modal");
});
//<------------------------ End Start Functions ------------------------>
//<--------------------------------------------------------------------->

