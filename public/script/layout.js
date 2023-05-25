// Opening the Sidebar
const sidebar = document.querySelector('.sidebar');
const sidebarBtn = document.querySelector('.sidebar__btn');
const sidebarOpen = document.querySelector('.sidebar__open');
// Toggle function to open the Sidebar on Mobile
sidebarBtn.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    document.querySelector('.content').classList.toggle("overflow");
    document.querySelector('main').classList.toggle("stop-height");
    notify.classList.remove('open')
})
// Toggle function to open the Sidebar on Desktop
sidebarOpen.addEventListener('click', () => {
    sidebar.classList.toggle('open');
})


// Opening the Searchbar
const search = document.querySelector('.header__search');
const searchOpen = document.querySelector('#openSearch');
const searchClose = document.querySelector('#closeSearch');
const searchToggle = document.querySelector('#toggleSearch');
// function for open the Searchbar on mobile
searchOpen.addEventListener('click', () => {
    search.classList.add('active');
    notify.classList.remove('open');
    profile.classList.remove('active');
})
// function for open/close the Searchbar on Desktop version
searchToggle.addEventListener('click', () => {
    search.classList.toggle('active');
    notify.classList.remove('open');
    profile.classList.remove('active');
})
// function for close the Searchbar on mobile
searchClose.addEventListener('click', () => {
    search.classList.remove('active');
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
    search.classList.remove('active');
    notify.classList.remove('open')
})
// Function what is closing everethink of Profile
profileClose.addEventListener('click', () => {
    profile.classList.toggle('active');
    header.classList.remove('moved');
    search.classList.remove('active');
    notify.classList.remove('open')
})

const notify = document.querySelector('.header__notify');
const notifyBtn = document.querySelector('.header__notify-btn');

notifyBtn.addEventListener('click', () => {
    profile.classList.remove('active');
    header.classList.remove('moved');
    search.classList.remove('active')
    notify.classList.toggle('open')
})




///script for rightside
const right = document.querySelector('.right');
const calendarBtn = document.querySelector('.right__open');

calendarBtn.addEventListener('click', () => {
    right.classList.toggle('open');
})
