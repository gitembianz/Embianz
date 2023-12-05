//<--------------------------------------------------------------------->
//<---------------------------- ScrollEvent ---------------------------->
function scrollEvent() {
  const header = document.querySelector("header");
  const banner = document.querySelector(".banner");
  const main = document.querySelector("main");

  const headerHeight = header.offsetHeight;
  const bannerHeight = banner.offsetHeight;

  // console.log("height of header: ", headerHeight);
  // console.log("height of banner: ", bannerHeight);

  main.style.paddingTop = `${headerHeight + bannerHeight}px`;
  header.style.top = `${bannerHeight}px`;

  if (window.pageYOffset > 0) {
    banner.style.top = `-${headerHeight + bannerHeight}px`;
    header.style.top = "0px";
  } else {
    banner.style.top = "0px";
    header.style.top = `${bannerHeight}px`;
  }
}

scrollEvent();
window.addEventListener("scroll", scrollEvent);
window.addEventListener("resize", scrollEvent);
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
//<------------------------ Start Functions IOS ------------------------>
// scrollEvent();
searchBar();
leftbar("basketOpen", "basketClose", "basketList", "basketContent");
leftbar("wishOpen", "wishClose", "wishList", "wishContent");
leftbar("menuOpen", "menuClose", "menuList", "menuContent");
dropmenus(".dropmenu", true);

//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
document.addEventListener("DOMContentLoaded", function () {
  searchBar();
  // scrollEvent();
  leftbar("basketOpen", "basketClose", "basketList", "basketContent");
  leftbar("wishOpen", "wishClose", "wishList", "wishContent");
  leftbar("menuOpen", "menuClose", "menuList", "menuContent");
  dropmenus(".dropmenu", true);
});
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
