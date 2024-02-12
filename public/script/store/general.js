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
//<------------------------ Start Functions IOS ------------------------>
modal(".modal");
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
document.addEventListener("DOMContentLoaded", function () {
  modal(".modal");
});
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
//<---------------------------- Add to Cart ---------------------------->
function flyToCart(button) {
  const shopping_cart = document.getElementById("basketOpen");
  const numberCart = shopping_cart.querySelector(".header__count");
  const target_parent = button.closest(".product"); // Obținem cel mai apropiat părinte cu clasa "product"

  numberCart.style.scale = 1.4;

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
  `;

  setTimeout(() => {
    target_parent.style.zIndex = "";
    target_parent.removeChild(flying_img);
    shopping_cart.classList.remove("active");
    numberCart.style.scale = 1;
  }, 1500);
}

//<-------------------------- End Add to Cart -------------------------->
//<--------------------------------------------------------------------->
//<------------------------- Add On WishList --------------------------->
function addWishList(button) {
  const wish = document.getElementById("wishlistCount");
  wish.style.scale = 1.4;

  setTimeout(() => {
    wish.style.scale = 1;
  }, 1500);
}
//<----------------------- End Add On WishList ------------------------->
//<--------------------------------------------------------------------->
