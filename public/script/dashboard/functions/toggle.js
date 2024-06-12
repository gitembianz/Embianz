export	function toggle(a, e) {
  a.addEventListener('click', function() {
    e.classList.toggle('active')
  })
}
