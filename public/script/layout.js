// Opening the Sidebar
const sidebar = document.querySelector('.sidebar');
const sidebarBtn = document.querySelector('.sidebar__btn');
const sidebarOpen = document.querySelector('.sidebar__open');
// Toggle function to open the Sidebar on Mobile
sidebarBtn.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  search.classList.remove('active');
  right.classList.remove('open');
  profile.classList.remove('active');
  header.classList.remove('moved');
  notify.classList.remove('open');
  document.querySelector('.content').classList.toggle("overflow");
  document.querySelector('main').classList.toggle("stop-height");
})
// Toggle function to open the Sidebar on Desktop
sidebarOpen.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  search.classList.remove('active');
  // right.classList.remove('open');
  profile.classList.remove('active');
  header.classList.remove('moved');
  notify.classList.remove('open');
})


// Opening the Searchbar
const search = document.querySelector('.header__search');
const searchOpen = document.querySelector('#openSearch');
const searchClose = document.querySelector('#closeSearch');
const searchToggle = document.querySelector('#toggleSearch');
// function for open the Searchbar on mobile
searchOpen.addEventListener('click', () => {
    search.classList.add('active');
    right.classList.remove('open');
    profile.classList.remove('active');
    header.classList.remove('moved');
    notify.classList.remove('open');
    sidebar.classList.remove('open');
})
// function for open/close the Searchbar on Desktop version
searchToggle.addEventListener('click', () => {
  search.classList.toggle('active');
  right.classList.remove('open');
  profile.classList.remove('active');
  header.classList.remove('moved');
  notify.classList.remove('open');
  sidebar.classList.remove('open');
})
// function for close the Searchbar on mobile
searchClose.addEventListener('click', () => {
    right.classList.remove('open');
    profile.classList.remove('active');
    header.classList.remove('moved');
    search.classList.remove('active');
    notify.classList.remove('open');
    sidebar.classList.remove('open');
})


// Header for increase Profile height
const header = document.querySelector('header');
// Opening the Profile button
const profile = document.querySelector('.header__profile');
const profileOpen = document.querySelector('#openProfile');
const profileClose = document.querySelector('#closeProfile');
// Opening the Profile bottom part
const profileTop = document.querySelector('.header__profile-top');
const profileBottom = document.querySelector('.header__profile-bottom');
// Function for to open profile section
profileOpen.addEventListener('click', () => {
    profile.classList.add('active');
    header.classList.add('moved');
    right.classList.remove('open');
    search.classList.remove('active');
    notify.classList.remove('open');
    sidebar.classList.remove('open');
})
// Function what is closing everethink of Profile
profileClose.addEventListener('click', () => {
  profile.classList.toggle('active');
  right.classList.remove('open');
  header.classList.remove('moved');
  search.classList.remove('active');
  notify.classList.remove('open');
  sidebar.classList.remove('open');
})

const notify = document.querySelector('.header__notify');
const notifyBtn = document.querySelector('.header__notify-btn');

notifyBtn.addEventListener('click', () => {
  notify.classList.toggle('open');
    right.classList.remove('open');
    profile.classList.remove('active');
    header.classList.remove('moved');
    search.classList.remove('active');
    sidebar.classList.remove('open');
})




///script for rightside
const right = document.querySelector('.right');
const calendarBtn = document.querySelector('.right__open');

calendarBtn.addEventListener('click', () => {
    right.classList.toggle('open');
    profile.classList.remove('active');
    header.classList.remove('moved');
    search.classList.remove('active');
    notify.classList.remove('open');
    sidebar.classList.remove('open');
});

//  myfunc = function dropdown(){
//   const dropdownButtons = document.querySelectorAll(".dropdown-name");

// dropdownButtons.forEach(function(button) {
//   button.addEventListener("click", function() {
//     const dropdownContent = this.nextElementSibling;

//     dropdownContent.classList.toggle("show");

//     const otherDropdowns = document.querySelectorAll(".dropdown-content");

//     otherDropdowns.forEach(function(content) {
//       if (content !== dropdownContent) {
//         content.classList.remove("show");
//       }
//     });
//   });
// });
// }

function OpenDropdown(){

  const dropdownButtons = document.querySelectorAll(".dropdown-name");

  dropdownButtons.forEach(function(button) {
  button.addEventListener("click", function() {
    const dropdownContent = this.nextElementSibling;

    dropdownContent.classList.toggle("show");

    const otherDropdowns = document.querySelectorAll(".dropdown-content");

    otherDropdowns.forEach(function(content) {
      if (content !== dropdownContent) {
        content.classList.remove("show");
      }
    });
  });
  });
}









