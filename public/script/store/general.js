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
//<------------------------- header Animation -------------------------->
function headerAnimation(targetElement) {
  const cart = document.getElementById(targetElement);
  // console.log("clicked");
  cart.classList.add("active");
  setTimeout(function () {
    cart.classList.remove("active");
  }, 600);
}
//<----------------------- End header Animation ------------------------>
//<--------------------------------------------------------------------->
//<---------------------------- Fly to Cart ---------------------------->
function startShoppingAnimation(button) {
  // const cart_btns = document.querySelectorAll(".add-to-cart");
  const shopping_cart = document.getElementById("basketOpen");

  // for (cart_btn of cart_btns) {

  button.onclick = (e) => {
    shopping_cart.classList.add("active");

    // finding first grandparent of target button
    let target_parent = e.target.parentNode.parentNode.parentNode;
    target_parent.style.zIndex = "200";
    // Creating separate Image
    let img = target_parent.querySelector("img");
    let flying_img = img.cloneNode();
    flying_img.classList.add("flying-img");

    target_parent.appendChild(flying_img);

    // Finding position of flying image
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
    }, 1500);
  };
  // }
}
//<-------------------------- End Fly to Cart -------------------------->
//<--------------------------------------------------------------------->
//<------------------ Onclick function (Cart & wish) ------------------->
function addToCartClick(button) {
  if (button.classList.contains("add-to-cart")) {
    headerAnimation("cartCount");
    startShoppingAnimation(button);
  }
}
//<--------------------------------------------------------------------->
function addToWishClick(button) {
  if (button.classList.contains("card-favorites")) {
    headerAnimation("wishlistCount");
  }
}
//<---------------- End Onclick function (Cart & wish) ----------------->
//<--------------------------------------------------------------------->
