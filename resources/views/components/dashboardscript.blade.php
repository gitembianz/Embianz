<script>
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

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



    const buttonn = document.getElementById("browse_saerch");
    buttonn.addEventListener("click", function() {
        fetch('/get-images')
            .then(response => response.json())
            .then(data => {
                document.getElementById('imageModal1').style.display = 'block';
                const imagesContainer = document.getElementById("image-container1");

                data.forEach(image => {
                    const img = document.createElement("img");
                    img.src = '/categories/' + image.filename;
                    img.alt = image.filename;
                    img.classList.add("grid-item");
                    img.addEventListener("click", function() {
                        const selected = document.querySelector(".selected");
                        if (selected) {
                            selected.classList.remove("selected");
                        }
                        this.classList.add("selected");
                        document.getElementById("selected-image1").value = image.filename;
                    });
                    imagesContainer.appendChild(img);
                });
            });
    });

    //remove browse value for main image
    document.getElementById("category_image").addEventListener("change", function() {
        const categoryImage = document.getElementById("category_image").value;
        document.getElementById('bloc_image_main').classList.add("display-n");
        document.getElementById('browse_main').value = 'or Browse';
        if (categoryImage) {
            const imageSpan = document.getElementById("select_image_main");
            imageSpan.innerText = "";
            document.getElementById('select_image_main_hidden').value = "";
        }
    });

    //remove browse value for search image
    document.getElementById("category_image_search").addEventListener("change", function() {
        const categoryImage = document.getElementById("category_image_search").value;
        document.getElementById('bloc_image_search').classList.add("display-n");
        document.getElementById('browse_saerch').value = 'or Browse';
        if (categoryImage) {
            const imageSpan = document.getElementById("select_image_search");
            imageSpan.innerText = "";
            document.getElementById('select_image_search_hidden').value = "";
        }
    });




    //script for apear search input
const searchIcon = document.getElementById("search-icon");
const inputSearch = document.getElementById("input_search");

searchIcon.addEventListener("click", function() {
  inputSearch.style.display = "block";
});


    //script on press confirm image search
    document.getElementById("confirmimageselection1").addEventListener("click", function() {
        const selectedImage = document.getElementById("selected-image1").value;
        const fileInput = document.getElementById("category_image_search");
        fileInput.value = "";
        if (!selectedImage) {
            // Show an error message
            alert("Please select an image");
            return;
        }

        const imageSpan = document.getElementById("select_image_search");
        imageSpan.innerText = selectedImage;

        const imagesContainer = document.getElementById("image-container1");
        while (imagesContainer.firstChild) {
            imagesContainer.removeChild(imagesContainer.firstChild);
        }
        document.getElementById('bloc_image_search').classList.remove("display-n");
        document.getElementById("img_select_image_search").src = "/categories/" + selectedImage;
        document.getElementById('imageModal1').style.display = 'none';
        document.getElementById('browse_saerch').value = 'Browse again';
        document.getElementById('select_image_search_hidden').value = selectedImage;


    });

</script>
