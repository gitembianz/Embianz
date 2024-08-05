export function addOnKey(keyCode, element) {
  document.addEventListener('keydown', function(event) {
    if (event.keyCode === keyCode) {
      element.classList.remove('active');
    }
  });
}
