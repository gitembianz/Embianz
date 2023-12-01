// //<--------------------------------------------------------------------->
// //<---------------------------- ScrollEvent ---------------------------->
// function scrollEvent() {
//   let header = document.querySelector("header");
//   let banner = document.querySelector(".banner");
//   let main = document.querySelector("main");
//   let body = document.body;

//   function updateStyles() {
//     main.style.paddingTop = header.clientHeight + banner.clientHeight + "px";
//     header.style.top = banner.clientHeight + "px";
//   }

//   function handleOverflowChange() {
//     if (body.style.overflow === "hidden") {
//       main.style.paddingTop = header.clientHeight + "px";
//     }
//   }

//   if (header && banner && main) {
//     updateStyles();

//     document.addEventListener("scroll", function () {
//       if (window.scrollY > banner.clientHeight) {
//         header.style.top = "0";
//         banner.style.visibility = "hidden";
//       } else {
//         header.style.top = banner.clientHeight + "px";
//         banner.style.visibility = "visible";
//       }
//     });

//     window.addEventListener("resize", function () {
//       // Actualizează stilurile atunci când se schimbă dimensiunea ecranului
//       updateStyles();
//     });

//     // Monitorizează schimbările la proprietatea overflow
//     const observer = new MutationObserver(handleOverflowChange);
//     observer.observe(body, { attributes: true, attributeFilter: ["style"] });
//   } else {
//     return;
//   }
// }
// //<-------------------------- End ScrollEvent -------------------------->
// //<--------------------------------------------------------------------->
// //<------------------------ DropMenu on leftbar ------------------------>
// function dropmenus(menuID, setActive = false) {
//   let dropmenus = document.querySelectorAll(menuID);

//   dropmenus.forEach(function (menu) {
//     let button = menu.querySelector(`.${menu.className}__open`);
//     let list = menu.querySelector(`.${menu.className}__list`);

//     if (button && list) {
//       if (setActive) {
//         menu.classList.add("active");
//         list.classList.add("active");
//       }

//       button.addEventListener("click", function () {
//         menu.classList.toggle("active");
//         list.classList.toggle("active");
//       });
//     } else {
//       return;
//     }
//   });
// }
// //<---------------------- End DropMenu on leftbar ---------------------->
// //<--------------------------------------------------------------------->
// //<------------------------------ LeftBar ------------------------------>
// function leftbar(idOpen, idClose, idList, idContent) {
//   const buttonOpen = document.getElementById(idOpen);
//   const buttonClose = document.getElementById(idClose);
//   const list = document.getElementById(idList);
//   const content = document.getElementById(idContent);
//   const body = document.querySelector("body");

//   if (!buttonOpen || !buttonClose || !list || !content) {
//     // console.log("leftbar error");
//     return;
//   } else {
//     buttonOpen.addEventListener("click", () => {
//       list.classList.add("active");
//       body.style.overflow = "hidden";
//     });
//     buttonClose.addEventListener("click", () => {
//       list.classList.remove("active");
//       body.style.overflow = "auto";
//     });
//     list.addEventListener("click", (event) => {
//       if (
//         !content.contains(event.target) &&
//         !buttonOpen.contains(event.target)
//       ) {
//         list.classList.remove("active");
//         body.style.overflow = "auto";
//       }
//     });
//   }
// }
// //<---------------------------- End LeftBar ---------------------------->
// //<--------------------------------------------------------------------->
// //<---------------------------- Apply Filter --------------------------->
// function applyFilter(Close, Reset) {
//   const buttonClose = document.getElementById(Close);
//   const buttonReset = document.getElementById(Reset);
//   const body = document.querySelector("body");

//   if (!buttonClose || !buttonReset) {
//     // console.log("leftbar error");
//     return;
//   } else {
//     buttonClose.addEventListener("click", () => {
//       body.style.overflow = "auto";
//     });
//     buttonReset.addEventListener("click", () => {
//       body.style.overflow = "auto";
//     });
//   }
// }
// //<------------------------- End Apply Filter -------------------------->
// //<--------------------------------------------------------------------->
// //<-------------------------- Apply Asortiment ------------------------->
// function applySort(selector) {
//   const items = document.querySelectorAll(selector);
//   const body = document.querySelector("body");

//   if (!items) {
//     // console.log("leftbar error");
//     return;
//   } else {
//     items.forEach(function (item) {
//       item.addEventListener("click", () => {
//         body.style.overflow = "auto";
//       });
//     });
//   }
// }
// //<------------------------ End Apply Asortiment ----------------------->
// //<--------------------------------------------------------------------->
// //<----------------------------- SearchBar ----------------------------->
// function searchBar() {
//   const searchBtn = document.getElementById("searchOpen");
//   const closeBtn = document.getElementById("searchClose");
//   const input = document.getElementById("searchInput");
//   const modalClose = document.getElementById("modalClose");
//   searchBtn.addEventListener("click", function () {
//     document.body.style.overflow = "hidden";
//     input.focus();
//   });

//   closeBtn.addEventListener("click", function () {
//     document.body.style.overflow = "auto";
//   });
//   modalClose.addEventListener("click", function () {
//     document.body.style.overflow = "auto";
//   });
// }
// //<--------------------------- End SearchBar --------------------------->
// //<--------------------------------------------------------------------->
// //<--------------------------- Slider-Images --------------------------->
// function slider(sliderID) {
//   const slider = document.querySelector(sliderID);
//   const wrapper = document.querySelector(`${sliderID}__wrapper`);
//   const sliderControl = document.querySelectorAll(`${sliderID}__button`);

//   if (!slider || !wrapper || !sliderControl.length) {
//     return;
//   } else {
//     const firstCardWidth = wrapper.querySelector(
//       `${sliderID}__slide`
//     ).offsetWidth;
//     const wrapperChildrens = [...wrapper.children];

//     let isDragging = false,
//       isAutoPlay = true,
//       startX,
//       startScrollLeft,
//       timeoutId;

//     // Get the number of cards that can fit in the wrapper at once
//     let cardPerView = Math.round(wrapper.offsetWidth / firstCardWidth);

//     // Insert copies of the last few cards to beginning of wrapper for infinite scrolling
//     wrapperChildrens
//       .slice(-cardPerView)
//       .reverse()
//       .forEach((card) => {
//         wrapper.insertAdjacentHTML("afterbegin", card.outerHTML);
//       });

//     // Insert copies of the first few cards to end of wrapper for infinite scrolling
//     wrapperChildrens.slice(0, cardPerView).forEach((card) => {
//       wrapper.insertAdjacentHTML("beforeend", card.outerHTML);
//     });

//     // Scroll the wrapper at appropriate postition to hide first few duplicate cards on Firefox
//     wrapper.classList.add("no-transition");
//     wrapper.scrollLeft = wrapper.offsetWidth;
//     wrapper.classList.remove("no-transition");

//     // Add event listeners for the arrow buttons to scroll the wrapper left and right
//     sliderControl.forEach((btn) => {
//       btn.addEventListener("click", () => {
//         wrapper.scrollLeft += btn.classList.contains("prev")
//           ? -firstCardWidth
//           : firstCardWidth;
//       });
//     });

//     const dragStart = (e) => {
//       isDragging = true;
//       wrapper.classList.add("dragging");
//       // Records the initial cursor and scroll position of the wrapper
//       startX = e.pageX;
//       startScrollLeft = wrapper.scrollLeft;
//     };

//     const dragging = (e) => {
//       if (!isDragging) return; // if isDragging is false return from here
//       // Updates the scroll position of the wrapper based on the cursor movement
//       wrapper.scrollLeft = startScrollLeft - (e.pageX - startX);
//     };

//     const dragStop = () => {
//       isDragging = false;
//       wrapper.classList.remove("dragging");
//     };

//     const infiniteScroll = () => {
//       // If the wrapper is at the beginning, scroll to the end
//       if (wrapper.scrollLeft === 0) {
//         wrapper.classList.add("no-transition");
//         wrapper.scrollLeft = wrapper.scrollWidth - 2 * wrapper.offsetWidth;
//         wrapper.classList.remove("no-transition");
//       }
//       // If the wrapper is at the end, scroll to the beginning
//       else if (
//         Math.ceil(wrapper.scrollLeft) ===
//         wrapper.scrollWidth - wrapper.offsetWidth
//       ) {
//         wrapper.classList.add("no-transition");
//         wrapper.scrollLeft = wrapper.offsetWidth;
//         wrapper.classList.remove("no-transition");
//       }

//       // Clear existing timeout & start autoplay if mouse is not hovering over wrapper
//       clearTimeout(timeoutId);
//       if (!slider.matches(":hover")) autoPlay();
//     };

//     const autoPlay = () => {
//       if (window.innerWidth < 800 || !isAutoPlay) return; // Return if window is smaller than 800 or isAutoPlay is false
//       // Autoplay the wrapper after every 2500 ms
//       timeoutId = setTimeout(
//         () => (wrapper.scrollLeft += firstCardWidth),
//         2500
//       );
//     };
//     autoPlay();

//     wrapper.addEventListener("mousedown", dragStart);
//     wrapper.addEventListener("mousemove", dragging);
//     document.addEventListener("mouseup", dragStop);
//     wrapper.addEventListener("scroll", infiniteScroll);
//     slider.addEventListener("mouseenter", () => clearTimeout(timeoutId));
//     slider.addEventListener("mouseleave", autoPlay);
//   }
// }
// //<------------------------- End Slider-Images ------------------------->
// //<--------------------------------------------------------------------->
// //<------------------------------- Modal ------------------------------->
// function modal(modalID) {
//   const modal = document.querySelector(modalID);
//   const body = document.querySelector("body");

//   if (!modal) {
//     // console.warn("Nu exista nici un modal pe aceasta pagina");
//     return;
//   } else {
//     const content = modal.querySelector(modalID + "__content");
//     const close = modal.querySelector(modalID + "__close");

//     close.addEventListener("click", () => {
//       modal.classList.remove("active");
//       body.style.overflow = "auto";
//     });

//     window.addEventListener("alert__modal", (event) => {
//       modal.classList.add("active");
//       body.style.overflow = "hidden";
//     });

//     window.addEventListener("click", (event) => {
//       if (event.target === modal) {
//         modal.classList.remove("active");
//         body.style.overflow = "auto";
//       }
//     });
//   }
// }
// //<----------------------------- End Modal ----------------------------->
// //<--------------------------------------------------------------------->
// //<--------------------------- Sticky Element -------------------------->
// function stickyElement(elementSelector) {
//   let element = document.querySelector(elementSelector);

//   if (!element) {
//     return;
//   } else {
//     let isActive = false;
//     let activationPosition = 10; // Poziția la care se activează funcționalitatea sticky

//     window.addEventListener("scroll", function () {
//       let scrollPosition = window.scrollY;

//       if (!isActive && scrollPosition > activationPosition) {
//         isActive = true;
//         let elementPosition = element.offsetTop + element.offsetHeight + 20;

//         window.addEventListener("scroll", function () {
//           let position = window.scrollY + window.innerHeight;

//           if (position > elementPosition) {
//             element.classList.remove("sticky");
//           } else {
//             element.classList.add("sticky");
//           }
//         });
//       } else if (isActive && scrollPosition == activationPosition) {
//         isActive = false;
//         element.classList.add("sticky"); // Adaugăm "sticky" înapoi când se revine la partea de sus
//       }
//     });
//   }
// }
// //<------------------------- End Sticky Element ------------------------>
// //<--------------------------------------------------------------------->
// //<------------------------- Slider Product ---------------------------->
// function sliderProduct(sliderId) {
//   const slider = document.querySelector(sliderId);
//   const wrapper = slider.querySelector(sliderId + "__wrapper");
//   const slides = slider.querySelectorAll(sliderId + "__slide");
//   const pagination = slider.querySelector(sliderId + "__pagination");
//   const prevButton = slider.querySelector(sliderId + "__prev");
//   const nextButton = slider.querySelector(sliderId + "__next");
//   let currentIndex = 0;
//   let touchStartX = 0;

//   if (
//     !slider ||
//     !wrapper ||
//     !slides ||
//     !pagination ||
//     !prevButton ||
//     !nextButton
//   ) {
//     console.log("Elementele necesare pentru slider product nu au fost găsite.");
//     return;
//   }

//   function navigation(direction) {
//     currentIndex =
//       (currentIndex + (direction === "next" ? 1 : slides.length - 1)) %
//       slides.length;

//     updatePagination();
//     updateTransform(wrapper);
//   }

//   nextButton.addEventListener("click", () => {
//     navigation("next");
//   });

//   prevButton.addEventListener("click", () => {
//     navigation("prev");
//   });

//   function updatePagination() {
//     const thumbnails = Array.from(pagination.children);
//     thumbnails.forEach((thumbnail, index) => {
//       thumbnail.classList.toggle("active", index === currentIndex);
//     });
//   }

//   function updateTransform(element) {
//     element.style.transform = `translateX(-${currentIndex * 100}%)`;
//   }

//   function createThumb(slide, index) {
//     const thumbnail = document.createElement("img");
//     const mediaElement =
//       slide.querySelector("img") || slide.querySelector("video");

//     if (mediaElement) {
//       thumbnail.src = mediaElement.src;
//       thumbnail.alt = `Thumbnail ${index + 1}`;
//       thumbnail.classList.add("thumbnail");

//       thumbnail.addEventListener("click", () => {
//         currentIndex = index;
//         updateTransform(wrapper);
//         updatePagination();
//       });

//       pagination.appendChild(thumbnail);
//     }
//   }

//   slides.forEach((slide, index) => {
//     const videoElement = slide.querySelector("video");
//     if (videoElement) {
//       videoElement.controls = false;
//     }
//     createThumb(slide, index);
//   });

//   function handleSlideSwipe(event, index) {
//     const touchEndX = event.changedTouches[0].clientX;
//     const swipeDistance = touchEndX - touchStartX;

//     const isInsideModal = event.target.closest(".modal") !== null;

//     if (!isInsideModal) {
//       if (swipeDistance > 50 && index > 0) {
//         currentIndex = index - 1;
//       } else if (swipeDistance < -50 && index < slides.length - 1) {
//         currentIndex = index + 1;
//       }

//       updatePagination();
//       updateTransform(wrapper);
//     }

//     touchStartX = 0;
//   }

//   slides.forEach((slide, index) => {
//     slide.addEventListener("touchstart", (event) => {
//       touchStartX = event.touches[0].clientX;
//     });

//     slide.addEventListener("touchmove", (event) => {
//       if (touchStartX) {
//         event.preventDefault(); // Evită derularea implicită a paginii pe swipe
//       }
//     });

//     slide.addEventListener("touchend", (event) => {
//       handleSlideSwipe(event, index);
//     });
//   });

//   window.addEventListener("load", () => updatePagination());
// }
// //<----------------------- End Slider Product -------------------------->
// //<--------------------------------------------------------------------->
// //<------------------------- Modal Product ----------------------------->
// function modalProduct(modalId, sliderId) {
//   const modal = document.querySelector(modalId);
//   const modalContent = modal.querySelector(modalId + "__content");
//   const closeButton = modal.querySelector(modalId + "__close");

//   const slider = document.querySelector(sliderId);
//   const slides = slider.querySelectorAll(sliderId + "__slide");

//   const body = document.querySelector("body");

//   if (!modal || !modalContent || !closeButton) {
//     console.log("Componentele Modalului nu au fost găsite.");
//     return;
//   } else {
//     closeButton.addEventListener("click", () => {
//       modal.classList.remove("active");
//       body.style.overflow = "auto";
//     });

//     slides.forEach((slide, index) => {
//       slide.addEventListener("click", () => {
//         modalContent.innerHTML = slide.innerHTML;
//         modal.classList.add("active");
//         body.style.overflow = "hidden";
//       });
//     });

//     window.addEventListener("click", (event) => {
//       if (event.target === modal) {
//         modal.classList.remove("active");
//         body.style.overflow = "auto";
//       }
//     });
//   }
// }
// //<----------------------- End Modal Product --------------------------->
// //<--------------------------------------------------------------------->
// //<------------------------ Display Loading ---------------------------->
// function displayLoading() {
//   const loading = document.getElementById("loadingLogo");

//   if (!loading) {
//     return;
//   } else {
//     var currentDate = new Date();
//     var currentDay = currentDate.toISOString().split("T")[0];
//     if (document.cookie.indexOf("visited_loading_" + currentDay) === -1) {
//       document.cookie =
//         "visited_loading_" +
//         currentDay +
//         "=true; expires=" +
//         new Date(
//           currentDate.getFullYear(),
//           currentDate.getMonth(),
//           currentDate.getDate() + 1
//         ).toUTCString() +
//         "; path=/";

//       loading.style.display = "flex";
//     } else {
//       loading.style.display = "none";
//     }
//   }
// }
// //<---------------------- End Display Loading -------------------------->
// //<--------------------------------------------------------------------->
// //<------------------------ Start Functions IOS ------------------------>
// // loading logo
// window.onload = displayLoading;
// // -----------------------------------
// scrollEvent();
// searchBar();
// // leftbar functions for basket, wish, menu, filter and sort
// leftbar("basketOpen", "basketClose", "basketList", "basketContent");
// leftbar("wishOpen", "wishClose", "wishList", "wishContent");
// leftbar("menuOpen", "menuClose", "menuList", "menuContent");
// leftbar("filterOpen", "filterClose", "filterList", "filterContent");
// leftbar("sortOpen", "sortClose", "sortList", "sortContent");
// // dropdown functions for menu and filter
// dropmenus(".dropfilter", true);
// dropmenus(".dropmenu", true);
// // filter functions for closing and resetting
// applyFilter("closeFilter", "resetFilter");
// applySort(".sort__item");
// // Sliders
// slider(".main-slider");
// slider(".card-slider");
// sliderProduct(".product-slider");
// modalProduct(".product-modal", ".product-slider");
// // Modal
// modal(".modal");
// // sticky element
// stickyElement(".details");

// //<---------------------- End Start Functions IOS ---------------------->
// //<--------------------------------------------------------------------->
// //<------------------------- Start Functions PC ------------------------>
// document.addEventListener("DOMContentLoaded", function () {
//   searchBar();
//   scrollEvent();
//   // leftbar functions for basket, wish, menu, filter and sort
//   leftbar("basketOpen", "basketClose", "basketList", "basketContent");
//   leftbar("wishOpen", "wishClose", "wishList", "wishContent");
//   leftbar("menuOpen", "menuClose", "menuList", "menuContent");
//   leftbar("filterOpen", "filterClose", "filterList", "filterContent");
//   leftbar("sortOpen", "sortClose", "sortList", "sortContent");
//   // dropdown functions for menu and filter
//   dropmenus(".dropfilter", true);
//   dropmenus(".dropmenu", true);
//   // filter functions for closing and resetting
//   applyFilter("closeFilter", "resetFilter");
//   applySort(".sort__item");
//   // Sliders
//   slider(".main-slider");
//   slider(".card-slider");

//   // Modal
//   modal(".modal");
//   // sticky element
//   stickyElement(".details");
// });
// //<----------------------- End Start Functions PC ---------------------->
// //<--------------------------------------------------------------------->
