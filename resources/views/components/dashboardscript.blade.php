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

    // Get all the elements with class "sidebar__item"
    var sidebarItems = document.querySelectorAll(".sidebar__item");

    // Retrieve the index of the previously active item from local storage
    var activeIndex = localStorage.getItem("activeIndex");
    if (activeIndex !== null) {
        // Remove the "active" class from any previously clicked items
        var activeItem = sidebarItems[activeIndex];
        activeItem.classList.add("active");
    }

    // Loop through each sidebar item and add a click event listener
    sidebarItems.forEach(function(item, index) {
        item.addEventListener("click", function() {
            // Remove the "active" class from any previously clicked items
            var activeItem = document.querySelector(".sidebar__item.active");
            if (activeItem) {
                activeItem.classList.remove("active");
            }
            // Add the "active" class to the clicked item
            this.classList.add("active");

            // Store the index of the active item in local storage
            localStorage.setItem("activeIndex", index.toString());
        });
    });

</script>


