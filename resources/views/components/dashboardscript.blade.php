<script>
  // Script for tabs working
  function opentab(evt, tabName) {
      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
      }
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
      }
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
  }
  let activeTab = document.getElementById("defaultOpen");
  if (activeTab) {
      activeTab.click();
  }

  //Script for alert desepear
  var element = document.getElementById('alertevent');
  if (element) {
      element.style.transition = 'opacity 0.5s ease';
      setTimeout(function() {
          element.style.opacity = '0';
          setTimeout(function() {
              element.remove();
          }, 500);
      }, 2000);
  }

  //script for active buttons in sidebar
  var sidebarItems = document.querySelectorAll(".sidebar__item");

  var activeIndex = localStorage.getItem("activeIndex");
  if (activeIndex !== null) {
      var activeItem = sidebarItems[activeIndex];
      activeItem.classList.add("active");
  }
  sidebarItems.forEach(function(item, index) {
      item.addEventListener("click", function() {
          var activeItem = document.querySelector(".sidebar__item.active");
          if (activeItem) {
              activeItem.classList.remove("active");
          }
          this.classList.add("active");
          localStorage.setItem("activeIndex", index.toString());
      });
  });
</script>
