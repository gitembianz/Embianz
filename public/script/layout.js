// This function handles storing and retrieving data from the local storage
const handleLocalStorage = (name, value) => {
  if (value) {
    // If a value is provided, store it in the local storage
    localStorage.setItem(name, value);
  } else {
    // If no value is provided, retrieve the value from the local storage
    return localStorage.getItem(name);
  }
};

// This function toggles the specified class on the given element and main element, and updates the value in local storage
const togglePanel = (element, className, mainClass, localStorageName) => {
  // Toggle the specified class on the element
  element.classList.toggle(className);
  // Toggle the specified class on the main element
  document.querySelector('main').classList.toggle(mainClass);
  // Update the value in local storage based on whether the class is present or not
  handleLocalStorage(localStorageName, element.classList.contains(className).toString());
};

// This function initializes the panel by adding event listeners and handling resize events
const initializePanel = (panelElement, className, mainClass, localStorageName, buttonSelector) => {
  // Get the panel button element
  const panelButton = document.querySelector(buttonSelector);
  // Add a click event listener to the panel button
  panelButton.addEventListener('click', () => togglePanel(panelElement, className, mainClass, localStorageName));

  // Define a resize event handler
  const handleResize = () => {
    // Check if the window width is greater than or equal to 1024 and the local storage value is true
    if (window.innerWidth >= 1024 && handleLocalStorage(localStorageName) === 'true') {
      // Add the specified class to the panel element
      panelElement.classList.add(className);
      // Add the specified class to the main element
      document.querySelector('main').classList.add(mainClass);
    } else {
      // Remove the specified class from the panel element
      panelElement.classList.remove(className);
      // Remove the specified class from the main element
      document.querySelector('main').classList.remove(mainClass);
    }
  };

  // Add a resize event listener to the window
  window.addEventListener('resize', handleResize);
  // Call the handleResize function to initialize the panel based on the initial window size
  handleResize();
};

// Wait for the DOM content to be loaded
document.addEventListener('DOMContentLoaded', () => {
  // Initialize the (right,sidebar panel, sidebar button on mobile) with the specified parameters
  initializePanel(document.querySelector('.right'), 'open', 'mr', 'rightPanelOpen', '.right__open');
  initializePanel(document.querySelector('.sidebar'), 'open', 'ml', 'sidebarOpen', '.sidebar__btn');
  initializePanel(document.querySelector('.sidebar'), 'open', 'ml', 'sidebarOpen', '.sidebar__open');
});
//==========================================================================================================================




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


let prevScrollPos = window.pageYOffset;
// const header = document.querySelector('header');

if (header) {
  window.addEventListener('scroll', () => {
    const currentScrollPos = window.pageYOffset;

    if (currentScrollPos >= 50) {
      if (prevScrollPos > currentScrollPos) {
        // Scrolling up
        header.style.top = '0';
      } else {
        // Scrolling down
        header.style.top = `-${header.offsetHeight}px`;
      }
    }

    prevScrollPos = currentScrollPos;
  });
}
