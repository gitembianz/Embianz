//<--------------------------------------------------------------------->
//<---------------------------- ScrollEvent ---------------------------->
function scrollEvent() {
  const header = document.querySelector("header");
  const banner = document.querySelector(".banner");
  const main = document.querySelector("main");
  const body = document.body;

  const headerHeight = header.offsetHeight;
  const bannerHeight = banner.offsetHeight;

  if (window.pageYOffset > 200) {
    banner.style.transform = `translate3d(0px, -${bannerHeight}px, 0px)`;
    main.style.paddingTop = `${headerHeight}px`;
    header.style.paddingTop = 0;
  } else {
    banner.style.transform = `translate3d(0px, 0px, 0px)`;
    main.style.paddingTop = `${60 + bannerHeight}px`;
    header.style.paddingTop = `${bannerHeight}px`;
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
      scrollEvent();
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
//<----------------------------- SearchBar ----------------------------->
function searchBar() {
  const searchBtn = document.getElementById("searchOpen");
  const closeBtn = document.getElementById("searchClose");
  const input = document.getElementById("searchInput");
  const modalClose = document.getElementById("modalClose");

  searchBtn.addEventListener("click", function () {
    document.body.style.overflow = "hidden";
    setTimeout(() => {
      input.focus();
    }, 400);
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
//<------------------------ Start Functions IOS ------------------------>
searchBar();
leftbar("basketOpen", "basketClose", "basketList", "basketContent");
leftbar("wishOpen", "wishClose", "wishList", "wishContent");
leftbar("menuOpen", "menuClose", "menuList", "menuContent");
dropmenus(".dropmenu", false);
dropmenus(".submenu", false);

//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
// document.addEventListener("DOMContentLoaded", function () {
// searchBar();
// leftbar("basketOpen", "basketClose", "basketList", "basketContent");
// leftbar("wishOpen", "wishClose", "wishList", "wishContent");
// leftbar("menuOpen", "menuClose", "menuList", "menuContent");
// dropmenus(".dropmenu", false);
// dropmenus(".submenu", false);
// });
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
scrollEvent();
window.addEventListener("scroll", scrollEvent);
window.addEventListener("resize", scrollEvent);
