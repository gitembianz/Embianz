// Opening the Sidebar
const sidebar = document.querySelector('.sidebar');
const sidebarBtn = document.querySelector('.sidebar__btn');
const sidebarOpen = document.querySelector('.sidebar__open');
// Toggle function to open the Sidebar on Mobile
sidebarBtn.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  search.classList.remove('active');
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
  profile.classList.remove('active');
  header.classList.remove('moved');
})



const header = document.querySelector('header');
const profile = document.querySelector('.profile');
const profileOpen = document.querySelector('#openProfile');
const profileClose = document.querySelector('#closeProfile');

// Function for to open profile section
profileOpen.addEventListener('click', () => {
    profile.classList.add('active');
    header.classList.add('moved');
    search.classList.remove('active');
    notify.classList.remove('open');
})
// Function what is closing everethink of Profile
profileClose.addEventListener('click', () => {
  header.classList.remove('moved');
  profile.classList.toggle('active');
  search.classList.remove('active');
  notify.classList.remove('open');
})

const search = document.querySelector('.search');
const searchOpen = document.querySelector('#openSearch');
const searchClose = document.querySelector('#closeSearch');

searchOpen.addEventListener('click', () => {
  search.classList.add('active');
  profile.classList.remove('active');
  header.classList.remove('moved');
  notify.classList.remove('open');
})
// Function what is closing everethink of Profile
searchClose.addEventListener('click', () => {
  search.classList.toggle('active');
  header.classList.remove('moved');
  profile.classList.remove('active');
  notify.classList.remove('open');
})


const notify = document.querySelector('.notify');
const notifyBtn = document.querySelector('.notify__btn');

notifyBtn.addEventListener('click', () => {
  document.querySelector('body').classList.toggle("overflow")
  notify.classList.toggle('open');
    profile.classList.remove('active');
    header.classList.remove('moved');
    search.classList.remove('active');
})




// /script for rightside
const right = document.querySelector('.right');
const calendarBtn = document.querySelector('.right__open');

calendarBtn.addEventListener('click', () => {
    right.classList.toggle('open');
    profile.classList.remove('active');
    header.classList.remove('moved');
    search.classList.remove('active');
    notify.classList.remove('open');
});



// Select all buttons with the class "dropdown-button"

document.addEventListener('DOMContentLoaded', function() {
  // Select all buttons with the class "dropdown-button"
  const dropdownButtons = document.querySelectorAll('.dropdown-button');

  dropdownButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      const dropdown = button.closest('.dropdown');
      dropdown.classList.toggle('open');

      // Remove the class "open" from other buttons
      dropdownButtons.forEach(function(otherButton) {
        if (otherButton !== button) {
          const otherDropdown = otherButton.closest('.dropdown');
          otherDropdown.classList.remove('open');
        }
      });
    });
  });
});



// The Top Up Button
const topUpBtn = document.getElementById('topUp');
window.addEventListener('scroll', () => {
  if(window.pageYOffset > 100){
    topUpBtn.classList.add("actived");
  } else {
    topUpBtn.classList.remove("actived");
  }
})

// Tabs
let tabs = document.querySelectorAll(".tabs__page");
let tabContents = document.querySelectorAll(".tabs__content");
tabs.forEach((tab, index) => {
  tab.addEventListener("click", () => {
    tabContents.forEach((content) => {
      content.classList.remove("active");
    });
    tabs.forEach((tab) => {
      tab.classList.remove("active");
    });
    tabContents[index].classList.add("active");
    tabs[index].classList.add("active");
  });
});


// Get all accordion buttons
const accordionButtons = document.querySelectorAll('.accordion__btn');

// Loop through each button and attach a click event listener
accordionButtons.forEach(button => {
  button.addEventListener('click', function() {
    const accordionContent = this.nextElementSibling;

    // Check if the accordion content is currently active
    const isActive = accordionContent.classList.contains('active');

    // Close all accordions
    closeAllAccordions();

    // Toggle the visibility of the content
    if (!isActive) {
      accordionContent.style.display = 'block';
      accordionContent.classList.add('active');
    }
  });
});

// Function to close all accordions
function closeAllAccordions() {
  const activeAccordions = document.querySelectorAll('.accordion__content.active');

  activeAccordions.forEach(accordion => {
    accordion.style.display = 'none';
    accordion.classList.remove('active');
  });
}

// Close all accordions by default on page load
closeAllAccordions();
