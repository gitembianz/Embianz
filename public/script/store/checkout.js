//<--------------------------------------------------------------------->
//<--------------------------- Sticky Element -------------------------->
function stickyElement(elementSelector) {
  let element = document.querySelector(elementSelector);

  if (!element) {
    return;
  } else {
    let isActive = false;
    let activationPosition = 10; // Poziția la care se activează funcționalitatea sticky

    window.addEventListener("scroll", function () {
      let scrollPosition = window.scrollY;

      if (!isActive && scrollPosition > activationPosition) {
        isActive = true;
        let elementPosition = element.offsetTop + element.offsetHeight + 20;

        window.addEventListener("scroll", function () {
          let position = window.scrollY + window.innerHeight;

          if (position > elementPosition) {
            element.classList.remove("sticky");
          } else {
            element.classList.add("sticky");
          }
        });
      } else if (isActive && scrollPosition == activationPosition) {
        isActive = false;
        element.classList.add("sticky"); // Adaugăm "sticky" înapoi când se revine la partea de sus
      }
    });
  }
}
//<------------------------- End Sticky Element ------------------------>
//<--------------------------------------------------------------------->
//<------------------------ Start Functions IOS ------------------------>
stickyElement(".details");
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
//<------------------------- Start Functions PC ------------------------>
document.addEventListener("DOMContentLoaded", function () {
  stickyElement(".details");
});
//<----------------------- End Start Functions PC ---------------------->
//<--------------------------------------------------------------------->
