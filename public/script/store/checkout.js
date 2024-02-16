//<--------------------------------------------------------------------->
//<--------------------------- Sticky Element -------------------------->

function stickyElement(elementSelector) {
  let element = document.querySelector(elementSelector);

  if (!element) {
    return;
  }

  let isActive = false;
  let activationPosition = 10; // Poziția la care se activează funcționalitatea sticky
  // let elementPosition = element.offsetTop + element.offsetHeight + 20;
  let elementPosition = element.offsetTop + element.offsetHeight - 100;
  element.classList.add("sticky");

  function updateSticky() {
    let scrollPosition = window.scrollY;

    if (!isActive && scrollPosition > activationPosition) {
      isActive = true;
      element.classList.add("sticky");
    } else if (isActive && scrollPosition <= activationPosition) {
      isActive = false;
      element.classList.remove("sticky");
    }

    let position = window.scrollY + window.innerHeight;

    if (position > elementPosition) {
      element.classList.remove("sticky");
    } else {
      element.classList.add("sticky");
    }
  }

  window.addEventListener("scroll", updateSticky);
  window.addEventListener("resize", updateSticky);
}

stickyElement(".details");

//<------------------------- End Sticky Element ------------------------>
//<--------------------------------------------------------------------->
//<------------------------ Start Functions IOS ------------------------>
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
// document.addEventListener("DOMContentLoaded", function () {
//   stickyElement(".details");
// });
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
