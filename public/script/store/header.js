//<--------------------------------------------------------------------->
//<---------------------------- ScrollEvent ---------------------------->
function startHeaderScripts() {
    searchBar();
    leftbar(
        "basketOpen",
        "basketClose",
        "basketList",
        "basketContent",
        "basketHidden",
    );
    leftbar("wishOpen", "wishClose", "wishList", "wishContent", "wishHidden");
    leftbar("menuOpen", "menuClose", "menuList", "menuContent", "menuHidden");
    dropmenus(".dropmenu", false);
    dropmenus(".submenu", false);
    scrollEvent();
    window.addEventListener("scroll", scrollEvent);
    window.addEventListener("resize", scrollEvent);
}

// expose globally
window.initHeaderJs = function () {
    startHeaderScripts();
};

function scrollEvent() {
  const header = document.querySelector("header");
  const banner = document.querySelector(".banner");
  const main = document.querySelector("main");
  const body = document.body;

  if (!banner) {
    const headerHeight = header.offsetHeight;
    // const bannerHeight = banner.offsetHeight;

    if (window.pageYOffset > 200) {
      // banner.style.transform = `translate3d(0px, -${bannerHeight}px, 0px)`;
      main.style.paddingTop = `${headerHeight}px`;
      header.style.paddingTop = 0;
    } else {
      // banner.style.transform = `translate3d(0px, 0px, 0px)`;
      main.style.paddingTop = `${headerHeight}px`;
      // header.style.paddingTop = `${headerHeight}px`;
    }
  } else {
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

        if (menu.classList.contains("active")) {
          menu.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });
    } else {
      return;
    }
  });
}
//<---------------------- End DropMenu on leftbar ---------------------->
//<--------------------------------------------------------------------->
//<------------------------------ LeftBar ------------------------------>
function leftbar(idOpen, idClose, idList, idContent, hiddenId) {
  const buttonOpen = document.getElementById(idOpen);
  const buttonClose = document.getElementById(idClose);
  const list = document.getElementById(idList);
  const content = document.getElementById(idContent);
  const contentModal = document.getElementById(hiddenId);
  const body = document.querySelector("body");

  if (!buttonOpen || !buttonClose || !list || !content) {
    // console.log("leftbar error");
    return;
  } else {

    buttonOpen.addEventListener("click", () => {
      list.classList.add("active");
      // gtm view_cart event

      dataLayer.push({
        event: "view_minicart",
        ecommerce: {
        }
       });

      body.style.overflow = "hidden";
      scrollEvent();
    });
    buttonClose.addEventListener("click", () => {
      list.classList.remove("active");
      body.style.overflow = "auto";
    });
    contentModal.addEventListener("click", (event) => {
      list.classList.remove("active");
      body.style.overflow = "auto";
    });

    function handleKeyPress(event) {
      if (event.keyCode === 27) {
        list.classList.remove("active");
        body.style.overflow = "auto";
      }
    }

    document.addEventListener("keydown", handleKeyPress);
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
    const searching = document.getElementById("searching");
    const searchList = document.getElementById("searchList");

    if (!searchBtn || !closeBtn || !input || !modalClose || !searching || !searchList) {
        return;
    }

    searchBtn.addEventListener("click", function () {
        searchList.classList.add("active");
        document.body.style.overflow = "hidden";
        setTimeout(() => input.focus(), 50);
    });

    closeBtn.addEventListener("click", function () {
        searchList.classList.remove("active");
        document.body.style.overflow = "auto";
    });

    modalClose.addEventListener("click", function () {
        searchList.classList.remove("active");
        document.body.style.overflow = "auto";
    });

    function handleKeyPress(event) {
        if (event.keyCode === 27) {
            searchList.classList.remove("active");
            document.body.style.overflow = "auto";
        }
    }

    document.addEventListener("keydown", handleKeyPress);

    input.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            window.location.href = "/search/" + encodeURIComponent(input.value);
        }
    });

    searching.addEventListener("click", function () {
        window.location.href = "/search/" + encodeURIComponent(input.value);
    });
}


//<--------------------------- End SearchBar --------------------------->
//<--------------------------------------------------------------------->
//<------------------------ Double Tap Redirect ------------------------>
let lastTap = 0;

function DoubleTapRedirect(link) {
  const currentTime = new Date().getTime();
  const tapLength = currentTime - lastTap;
  lastTap = currentTime;

  if (tapLength < 500 && tapLength > 0) {
    window.location.href = link;
  }
}
