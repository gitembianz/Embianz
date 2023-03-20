let uploadButton = document.getElementById("upload-button");
let chosenImage =document.getElementById("chosen-image");
let fileName = document.getElementById("file-name");

let uploadButtonsearch = document.getElementById("upload-button-search");
let chosenImagesearch =document.getElementById("chosen-image-search");
let fileNamesearch = document.getElementById("file-name-search");

uploadButton.onchange = () => {
    let reader = new FileReader();
    reader.readAsDataURL(uploadButton.files[0]);
    reader.onload = () =>{
        chosenImage.classList.remove("display-n");
        chosenImage.setAttribute("src", reader.result);
    }
    fileName.textContent = uploadButton.files[0].name;
}

uploadButtonsearch.onchange = () => {
    let readersearch = new FileReader();
    readersearch.readAsDataURL(uploadButtonsearch.files[0]);
    readersearch.onload = () =>{
        chosenImagesearch.classList.remove("display-n");
        chosenImagesearch.setAttribute("src", readersearch.result);
    }
    fileNamesearch.textContent = uploadButtonsearch.files[0].name;
}

//reset from button
document.getElementById("resetform").addEventListener("click", function() {

    chosenImage.classList.add("display-n");
    chosenImagesearch.classList.add("display-n");
    fileName.textContent = "No image uploaded";
    fileNamesearch.textContent = "No image uploaded";
});


//Browse for main!!! start

//Get image from browse for main
const button = document.getElementById("browse_main");
button.addEventListener("click", function() {
    fetch('/get-images')
        .then(response => response.json())
        .then(data => {
            document.getElementById('imageModal').style.display = 'block';
            const imagesContainer = document.getElementById("image-container");

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
                    document.getElementById("selected-image").value = image.filename;
                });
                imagesContainer.appendChild(img);
            });
        });
});

//close the image modals and destroy
document.getElementById("closeselection").addEventListener("click", function() {
    document.getElementById('imageModal').style.display = 'none';
    const imagesContainer = document.getElementById("image-container");
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }
});
document.getElementById("closeselection1").addEventListener("click", function() {
    document.getElementById('imageModal').style.display = 'none';
    const imagesContainer = document.getElementById("image-container");
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }

});



//remove browse value for main image
document.getElementById("upload-button").addEventListener("change", function() {
    const categoryImage = document.getElementById("upload-button").value;
    if (categoryImage) {
        document.getElementById('select_image_main_hidden').value = "";
    }
});

//script on press confirm image main
document.getElementById("confirmimageselection").addEventListener("click", function() {
    const selectedImage = document.getElementById("selected-image").value;
    const fileInput = document.getElementById("upload-button");
    fileInput.value = "";
    if (!selectedImage) {
        // Show an error message
        alert("Please select an image");
        return;
    }


    const imagesContainer = document.getElementById("image-container");
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }
    chosenImage.setAttribute("src", "/categories/" + selectedImage);
    //chosenImage.src = "/categories/" + selectedImage;
    document.getElementById('imageModal').style.display = 'none';
    fileName.textContent = selectedImage;
    document.getElementById('select_image_main_hidden').value = selectedImage;



});

//Browse for main!!! end

//Browse for search!!! start

//close and destroy for image search
document.getElementById("closeselection2").addEventListener("click", function() {
    document.getElementById('imageModal1').style.display = 'none';
    const imagesContainer = document.getElementById("image-container1");
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }

});
document.getElementById("closeselection3").addEventListener("click", function() {
    document.getElementById('imageModal1').style.display = 'none';
    const imagesContainer = document.getElementById("image-container1");
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }

});

//Browse for search!!! end
