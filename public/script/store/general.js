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
//<--------------- Hidden On Scroll(breadcrumb & Control) -------------->
let lastScrollTop = 0;

function hiddenOnScroll() {
  const breadcrumbs = document.querySelector(".breadcrumbs");
  const control = document.querySelector(".controls");
  const currentScrollTop =
    window.pageYOffset || document.documentElement.scrollTop;

  if (breadcrumbs) {
    if (currentScrollTop > lastScrollTop) {
      // Scrolling down
      breadcrumbs.style.top = "-60px";
    } else {
      // Scrolling up
      breadcrumbs.style.top = "59px";
    }
  }

  if (control) {
    if (currentScrollTop > lastScrollTop) {
      // Scrolling down
      control.style.top = "-90px";
    } else {
      // Scrolling up
      control.style.top = "89px";
    }
  }

  lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop;
}
//<------------- End Hidden On Scroll(breadcrumb & Control) ------------>
//<--------------------------------------------------------------------->
//<------------------------- Add On WishList --------------------------->
function addWishList(button) {
  const wish = document.getElementById("wishlistCount");
  wish.style.scale = 1.5;

  setTimeout(() => {
    wish.style.scale = 1;
  }, 1500);
}

//<----------------------- End Add On WishList ------------------------->
//<--------------------------------------------------------------------->
function flyToCart(button) {
  const shopping_cart = document.getElementById("basketOpen");
  const numberCart = shopping_cart.querySelector(".header__count");
  const target_parent = button.closest(".product"); // Obținem cel mai apropiat părinte cu clasa "product"

  setTimeout(() => {

    button.classList.remove('out')
    button.classList.add('in')

    setTimeout(() => {
      button.classList.add('out')
    }, 650)

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
  }, 400)
}

//<------------------------- Start Functions PC ------------------------>
window.addEventListener("scroll", hiddenOnScroll);
window.addEventListener("resize", hiddenOnScroll);
modal(".modal");

// document.addEventListener("DOMContentLoaded", function () {
// });
//<----------------------- End Start Functions PC ---------------------->
