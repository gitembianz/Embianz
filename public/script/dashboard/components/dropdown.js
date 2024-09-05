export function dropdown(){
  const dropdowns = document.querySelectorAll(".dropdown");

  dropdowns.forEach((dropdown) => {
    const button = dropdown.querySelector("button");

    if (!button) {
      console.error("Butonul din dropdown nu este definit...");
      return;
    }

    button.addEventListener("click", () => {
      dropdown.classList.toggle("active");
    });

    document.addEventListener('click', (event) => {
      if (!dropdown.contains(event.target)) {
        dropdown.classList.remove('active');
      }
    });
  });
}
