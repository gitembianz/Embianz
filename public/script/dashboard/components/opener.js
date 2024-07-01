// Import functions
import { removeOnKey } from "../functions/removeOnKey.js";
import { toggle } from "../functions/toggle.js";

// Function
export function opener(dropdownID, toggleID, clickOutside) {
  const element = document.getElementById(dropdownID);
  const button = document.getElementById(toggleID);

  if (!element || !button) {
    // console.error('Un element din Opener nu a fost găsit.');
    return;
  }

  if (clickOutside) {
    document.addEventListener('click', (event) => {
      if (!element.contains(event.target)) {
        element.classList.remove('active');
      }
    });
  }

  toggle(button, element);
  removeOnKey(27, element);
}
