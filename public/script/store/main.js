'use strict';function slider(b){const c=document.querySelector(b),a=document.querySelector(`${b}__wrapper`),d=document.querySelector(`${b}__button.prev`),f=document.querySelector(`${b}__button.next`);if(c&&a&&d&&f){let g=a.querySelector(`${b}__slide`).offsetWidth;const m=[...a.children];let h=!1,n,p,k,q=Math.round(a.offsetWidth/g);m.slice(-q).reverse().forEach(e=>{a.insertAdjacentHTML("afterbegin",e.outerHTML)});m.slice(0,q).forEach(e=>{a.insertAdjacentHTML("beforeend",e.outerHTML)});a.classList.add("no-transition");
a.scrollLeft=a.offsetWidth;a.classList.remove("no-transition");d.addEventListener("click",()=>{a.scrollLeft-=g});f.addEventListener("click",()=>{a.scrollLeft+=g});const l=()=>{800>window.innerWidth||(k=setTimeout(()=>a.scrollLeft+=g,25E3))};l();window.addEventListener("resize",()=>{g=a.querySelector(`${b}__slide`).offsetWidth});a.addEventListener("mousedown",e=>{h=!0;a.classList.add("dragging");n=e.pageX;p=a.scrollLeft});a.addEventListener("mousemove",e=>{h&&(a.scrollLeft=p-(e.pageX-n),a.style.cursor=
"grabbing")});document.addEventListener("mouseup",()=>{h=!1;a.classList.remove("dragging");a.style.cursor="initial"});a.addEventListener("scroll",()=>{0===a.scrollLeft?(a.classList.add("no-transition"),a.scrollLeft=a.scrollWidth-2*a.offsetWidth,a.classList.remove("no-transition")):Math.ceil(a.scrollLeft)===a.scrollWidth-a.offsetWidth&&(a.classList.add("no-transition"),a.scrollLeft=a.offsetWidth,a.classList.remove("no-transition"));clearTimeout(k);c.matches(":hover")||l()});c.addEventListener("mouseenter",
()=>clearTimeout(k));c.addEventListener("mouseleave",l)}}
function flyToCart(b){const c=document.getElementById("basketOpen").querySelector(".header__count");var a=b.closest(".card").querySelector(".dlv");const d=a.querySelector(".dlv_name").innerText.trim(),f=parseFloat(a.querySelector(".dlv_price").innerText.trim().replace(",","."));a=a.querySelector(".dlv_currency").innerText.trim();window.dataLayer=window.dataLayer||[];window.dataLayer.push({ecommerce:null});window.dataLayer.push({event:"add_to_cart",ecommerce:{currency:a,value:1*f,items:[{item_name:d,
price:f,quantity:1}]}});b.classList.contains("in")||(b.classList.add("in"),setTimeout(()=>b.classList.remove("in"),1500));c&&(c.style.scale=1.5,setTimeout(()=>{c.style.scale=1},1E3))}
function addWishList(b){const c=document.getElementById("wishlistCount");var a=b.closest(".card").querySelector(".dlv");b=a.querySelector(".dlv_name").innerText.trim();const d=parseFloat(a.querySelector(".dlv_price").innerText.trim().replace(",","."));a=a.querySelector(".dlv_currency").innerText.trim();window.dataLayer=window.dataLayer||[];window.dataLayer.push({ecommerce:null});window.dataLayer.push({event:"add_to_wishlist",ecommerce:{currency:a,value:1*d,items:[{item_name:b,price:d,quantity:1}]}});
c&&(c.style.scale=1.5,setTimeout(()=>{c.style.scale=1},1500))}slider(".main-slider");slider(".new-slider");slider(".popular-slider");
function lastseenSlider() {
  const slider = document.getElementById("lastseenSlider");

  if (!slider) {
    return;
  } else {
    const wrapper = slider.querySelector(".related__wrapperlast");
    const left = slider.querySelector(".related__btnlast.prev");
    const right = slider.querySelector(".related__btnlast.next");

    function updateCardWidth() {
      const cards = wrapper.querySelectorAll(".card");
      const cardWidth = cards[0].offsetWidth + 16; // adăugăm 16px pentru gap-ul dintre card-uri
      return cardWidth;
    }

    function scrollSlider(distance) {
      wrapper.scrollBy({
        left: distance,
        behavior: "smooth",
      });
    }

    function toggleButtonsVisibility(entries) {
      const hasVerticalScrollbar = wrapper.scrollHeight > wrapper.clientHeight;
      const hasHorizontalScrollbar = wrapper.scrollWidth > wrapper.clientWidth;

      // Ascundem sau afișăm butoanele în funcție de existența scrollbar-ului
      if (hasVerticalScrollbar || hasHorizontalScrollbar) {
        left.style.display = "flex";
        right.style.display = "flex";
      } else {
        left.style.display = "none";
        right.style.display = "none";
      }
    }

    function updateButtonStates() {
      const scrollLeft = wrapper.scrollLeft;
      const maxScrollLeft = wrapper.scrollWidth - wrapper.clientWidth;

      if (scrollLeft <= 0) {
        left.classList.add("disabled");
      } else {
        left.classList.remove("disabled");
      }

      if (scrollLeft >= maxScrollLeft) {
        right.classList.add("disabled");
      } else {
        right.classList.remove("disabled");
      }
    }

    // Creăm un nou ResizeObserver
    const resizeObserver = new ResizeObserver(toggleButtonsVisibility);

    // Observăm schimbările în dimensiunile wrapper-ului
    resizeObserver.observe(wrapper);

    left.addEventListener("click", () => {
      if (!left.classList.contains("disabled")) {
        scrollSlider(-updateCardWidth());
      }
    });

    right.addEventListener("click", () => {
      if (!right.classList.contains("disabled")) {
        scrollSlider(updateCardWidth());
      }
    });

    wrapper.addEventListener("scroll", updateButtonStates);

    window.addEventListener("resize", () => {
      const cardWidth = updateCardWidth();
      window.cardWidth = cardWidth;
    });

    window.cardWidth = updateCardWidth();

    // Actualizăm starea butoanelor la încărcarea paginii
    updateButtonStates();
  }
}
lastseenSlider();
