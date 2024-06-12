export function focusTo(targetId) {
  var targetElement = document.querySelector(targetId);
  if (targetElement) {
    targetElement.focus();
  }
}
