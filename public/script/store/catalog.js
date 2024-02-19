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
//<---------------------------- FilterLive ----------------------------->
//Filtrele se deschid cu Livewire function, acesta este pentru active
function filterLive(idOpen, idClose, idList, idContent) {
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
      body.style.overflow = "hidden";
    });
    buttonClose.addEventListener("click", () => {
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

//<-------------------------- End FilterLive --------------------------->
//<--------------------------------------------------------------------->
//<---------------------------- Apply Filter --------------------------->
function applyFilter(Close, Reset) {
  const buttonClose = document.getElementById(Close);
  const buttonReset = document.getElementById(Reset);
  const body = document.querySelector("body");

  if (!buttonClose || !buttonReset) {
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
//<---------------------------- Add to Cart ---------------------------->
function flyToCart(button) {
  const shopping_cart = document.getElementById("basketOpen");
  const numberCart = shopping_cart.querySelector(".header__count");
  const target_parent = button.closest(".product"); // Obținem cel mai apropiat părinte cu clasa "product"

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
}

//<-------------------------- End Add to Cart -------------------------->
//<--------------------------------------------------------------------->
//<------------------------ Start Functions IOS ------------------------>
leftbar("sortOpen", "sortClose", "sortList", "sortContent");
dropmenus(".dropfilter", false);
applyFilter("closeFilter", "resetFilter");
applySort(".sort__item");
filterLive("filterList", "filterContent");
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
// document.addEventListener("DOMContentLoaded", function () {
//   leftbar("sortOpen", "sortClose", "sortList", "sortContent");
//   dropmenus(".dropfilter", true);
//   applyFilter("closeFilter", "resetFilter");
//   applySort(".sort__item");
//   filterLive("filterOpen", ".filterClose", ".filterList", "filterContent");
// });
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
