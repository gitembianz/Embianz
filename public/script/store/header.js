//<--------------------------------------------------------------------->
//<---------------------------- ScrollEvent ---------------------------->
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

  if(searchBtn){

  searchBtn.addEventListener("click", function () {
    new Promise((resolve) => {
      document.body.style.overflow = "hidden";
      resolve();
    }).then(() => {
      input.focus();
    });
  });
  }


  closeBtn.addEventListener("click", function () {
    document.body.style.overflow = "auto";
  });
  modalClose.addEventListener("click", function () {
    document.body.style.overflow = "auto";
  });
  function handleKeyPress(event) {
    if (event.keyCode === 27) {
      document.getElementById("searchList").classList.remove("active");
      document.body.style.overflow = "auto";
    }
  }

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
//<---------------------- End Double Tap Redirect ---------------------->
//<--------------------------------------------------------------------->
//<---------- GTM Select Item Tracking - ALL PAGES ---------->

function initSelectItemTracking() {
  // Selectează DOAR linkurile din interiorul cardurilor
  const productCards = document.querySelectorAll(".card");
  
  //console.log("Product cards found:", productCards.length);

  productCards.forEach((card) => {
    // Toate linkurile din card
    const allLinks = card.querySelectorAll("a[href]");

    allLinks.forEach((productLink) => {
      // Check daca deja are listener
      if (productLink.hasAttribute("data-gtm-select-item-tracked")) {
        return;
      }

      // Marchează ca tracked
      productLink.setAttribute("data-gtm-select-item-tracked", "true");

      // Extrage data
      let productData = null;
      let productName = "Unknown";
      let productPrice = "0";

      // Opțiunea 1: Din .json-ld-data (home, sliders)
      const jsonData = card.querySelector(".json-ld-data");
      if (jsonData) {
        try {
          const jsonAttr = jsonData.getAttribute("data-product-json");
          productData = JSON.parse(jsonAttr);
        } catch (e) {
          productData = null;
        }
      }

      // Opțiunea 2: Din DOM (catalog page)
      if (!productData) {
        const titleLink = card.querySelector(".card-title a, h2 a");
        const priceSpan = card.querySelector(".card-price span");
        const dlvName = card.querySelector(".dlv_name");
        const dlvPrice = card.querySelector(".dlv_price");

        if (titleLink) {
          productName = titleLink.textContent.trim() || "Unknown";
        }

        if (dlvPrice) {
          productPrice = dlvPrice.textContent.trim() || "0";
        } else if (priceSpan) {
          productPrice = priceSpan.textContent.trim().split(" ")[0] || "0";
        }

        productData = {
          name: productName,
          price: productPrice
        };
      }

      if (!productData || !productData.name) return;

      // Click handler
      productLink.addEventListener("click", function (e) {
        const name = productData.name || "Unknown";
        const price = productData.product_prices?.[0]?.value || productData.price || "0";
        const category = productData.short_description || "Uncategorized";
        const id = productData.id || productLink.href.split("/").pop() || "0";

       //console.log("✅ Select Item:", name, "Price:", price);

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
          event: "select_item",
          ecommerce: {
            items: [{
              item_id: String(id),
              item_name: name,
              item_category: category,
              price: parseFloat(price),
              currency: "RON"
            }]
          }
        });
      });
    });
  });
}

//<------- End GTM Select Item Tracking ------>




//<------- End GTM Select Item Tracking ------>
//<------- End GTM Select Item Tracking - Produs Link Click Handler ------>

//<------------------------ Start Functions IOS ------------------------>
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
initSelectItemTracking();

//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
