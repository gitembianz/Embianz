//<--------------------------------------------------------------------->
//<------------------------------Accordions------------------------------------>
function initializeAccordions(closeOthers) {
  const accordions = document.querySelectorAll(".accordion");

  if (accordions.length === 0) {
    return;
  }

  accordions.forEach((accordion) => {
    const button = accordion.querySelector(".accordion-button");
    const content = accordion.querySelector(".accordion-wrapper");

    button.addEventListener("click", () => {
      if (closeOthers) {
        accordions.forEach((otherAccordion) => {
          if (otherAccordion !== accordion) {
            otherAccordion.classList.remove("active");
            otherAccordion
              .querySelector(".accordion-wrapper")
              .classList.remove("active");
          }
        });
      }

      accordion.classList.toggle("active");
      content.classList.toggle("active");
    });
  });
}
//<----------------------------End-Accordions---------------------------------->
//<--------------------------------------------------------------------->
//<------------------------ Start Functions IOS ------------------------>
initializeAccordions(false);
//<---------------------- End Start Functions IOS ---------------------->
//<--------------------------------------------------------------------->
