<script>
  //script for alerttable
window.addEventListener('show-delete-modal', event =>{
  document.getElementById('confirmationmodal').style.display = 'flex';


})
    // Script for all dropdown
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;
    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
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
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
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

    //Script form removing product from tables
    function removeProduct(prodid) {
        $.ajax({
            url: '/prodd/' + prodid,
            success: function(data) {
                location.reload();
                sessionStorage.setItem('message', 'Product deleted!');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    //Script for deleting products from checklist
    function deleteSelectedProducts() {
        const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
        const count = selectedProducts.length;
        const deleteButton = document.querySelector('#deletefromcheckbox');

        // Update the button text and visibility
        if (count > 0) {
            deleteButton.style.display = 'block';
            deleteButton.value = `Delete ${count} product${count > 1 ? 's' : ''}`;
        } else {
            deleteButton.style.display = 'none';
        }
        const productIds = Array.from(selectedProducts).map(checkbox => checkbox.value);

// Create a hidden input element
const hiddenInput = document.createElement('input');
hiddenInput.setAttribute('type', 'hidden');
hiddenInput.setAttribute('name', 'productIds');
hiddenInput.setAttribute('value', productIds);

// Append the hidden input to the desired location
const formElement = document.querySelector('#deleteProductsForm');
formElement.appendChild(hiddenInput);

    }
    // Add event listener to the checkboxes to trigger the update of the "Delete selected products" button
    const checkboxess = document.querySelectorAll('.product-checkbox');
    checkboxess.forEach(function(checkbox) {
        checkbox.addEventListener('change', deleteSelectedProducts);
    });

    // Add event listener to the delete button
    const deleteButton = document.querySelector('#deletefromcheckbox');
    if(deleteButton){
    deleteButton.addEventListener('click', deleteSelectedProducts);
    }
</script>

{{-- script for DataTables --}}
<script src="/script/cdn.js"></script>
    <script src="/script/datatable.js"></script>

