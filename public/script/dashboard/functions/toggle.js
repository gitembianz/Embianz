/// Button Toggle class
export function toggle(a, e) {
  a.addEventListener('click', function() {
    e.classList.toggle('active')
  })
}


/// Button Add 'special' class
export function addButton(a, e, f) {
  a.addEventListener('click', function() {
    e.classList.add(f)
  })
}


/// Button Remove 'special' class
export function removeButton(a, e, f) {
  a.addEventListener('click', function() {
    e.classList.remove(f)
  })
}
