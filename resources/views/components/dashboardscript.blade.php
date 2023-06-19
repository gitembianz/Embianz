<script>
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
