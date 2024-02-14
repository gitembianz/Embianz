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
//<------------------------- Start Functions PC ------------------------>
window.addEventListener("scroll", hiddenOnScroll);
window.addEventListener("resize", hiddenOnScroll);
modal(".modal");

// document.addEventListener("DOMContentLoaded", function () {
// });
//<----------------------- End Start Functions PC ---------------------->
