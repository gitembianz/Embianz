export function focusTo(targetId) {
  var targetElement = document.getElementById(targetId);
  if (targetElement) {
    targetElement.focus();
  }
}
