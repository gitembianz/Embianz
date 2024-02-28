<script>
 //script form modals
 window.addEventListener('show-delete-modal', event => {
  document.getElementById('confirmationmodal').style.display = 'flex';
 });
 window.addEventListener('show-delete-modal-multiple', event => {
  document.getElementById('confirmationmodalmultiple').style.display = 'flex';
 });
 window.addEventListener('show-link-modal-multiple', event => {
  document.getElementById('confirmationmodallinkmultiple').style.display = 'flex';
 });
 window.addEventListener('show-link-modal', event => {
  document.getElementById('confirmationmodallink').style.display = 'flex';
 });
 window.addEventListener('show-add-modal', event => {
  document.getElementById('addmodal').style.display = 'flex';
 });
 window.addEventListener('delete-media', event => {
  document.getElementById('confirmationmodalmedia').style.display = 'flex';
 });
 window.addEventListener('media', event => {
  document.getElementById('uploadmedia').style.display = 'flex';
 });
 window.addEventListener('delete-modal-category', event => {
  document.getElementById('confirmationmodalsingle').style.display = 'flex';
 });
 window.addEventListener('show-delete-spec', event => {
  document.getElementById('confirmationmodalspec').style.display = 'flex';
 });
 window.addEventListener('show-delete-modal-price', event => {
  document.getElementById('confirmationmodalprice').style.display = 'flex';
 });
 window.addEventListener('show-delete-item', event => {
  document.getElementById('confirmationmodalcart').style.display = 'flex';
 });
 window.addEventListener('show-deleterelatedprod-modal', event => {
  document.getElementById('confirmationmodalrelatedprod').style.display = 'flex';
 });
 window.addEventListener('show-deleterelatedprod-modal-multiple', event => {
  document.getElementById('confirmationmodalmultiplerelatedprod').style.display = 'flex';
 });
 window.addEventListener('show-delete-modal-script', event => {
  document.getElementById('confirmationmodalscript').style.display = 'flex';
 });
 window.addEventListener('show-delete-modal-multiple-script', event => {
  document.getElementById('confirmationmodalmultiplescript').style.display = 'flex';
 });

 // JavaScript code
 document.addEventListener('livewire:load', function() {
  Livewire.on('itemSaved', () => {
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
  });
 });

 // Save the state of .right and .sidebar after refresh
 document.addEventListener('DOMContentLoaded', () => {
  const rightPanel = document.querySelector('.right');
  const sidebar = document.querySelector('.sidebar');

  const rightPanelOpen = handleLocalStorage('rightPanelOpen');
  const sidebarOpen = handleLocalStorage('sidebarOpen');

  if (rightPanelOpen === 'true') {
   rightPanel.classList.add('open');
   document.querySelector('main').classList.add('mr');
  }

  if (sidebarOpen === 'true') {
   sidebar.classList.add('open');
   document.querySelector('main').classList.add('ml');
  }
 });
</script>
