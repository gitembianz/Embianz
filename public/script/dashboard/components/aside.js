// Import functions
import { removeOnKey } from "../functions/removeOnKey.js";
import { toggle } from "../functions/toggle.js";

// Function
export function aside(elementId, openBtnId, closeBtnId, backdropId) {
  const element = document.getElementById(elementId);
  const backdrop = document.getElementById(backdropId);
  const openBtn = document.getElementById(openBtnId);
  const closeBtn = document.getElementById(closeBtnId);

  if (!element || !openBtn || !closeBtn || !backdrop) {
    // console.error('Un element din aside nu a fost găsit.');
    return;
  }
  toggle(openBtn, element);
  toggle(closeBtn, element);
  toggle(backdrop, element);
  toggle(openBtn, backdrop);
  toggle(closeBtn, backdrop);
  toggle(backdrop, backdrop);

  removeOnKey(27, element);
  removeOnKey(27, backdrop);
}
