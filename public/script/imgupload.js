let uploadButton = document.getElementById("upload-button");
let chosenImage =document.getElementById("chosen-image");
let fileName = document.getElementById("file-name");
const imagesContainer = document.getElementById("image-container");

let uploadButtonsearch = document.getElementById("upload-button-search");
let chosenImagesearch =document.getElementById("chosen-image-search");
let fileNamesearch = document.getElementById("file-name-search");
const imagesContainers = document.getElementById("image-container1");
if(uploadButton){
uploadButton.onchange = () => {
    let reader = new FileReader();
    reader.readAsDataURL(uploadButton.files[0]);
    reader.onload = () =>{
        chosenImage.classList.remove("display-n");
        chosenImage.setAttribute("src", reader.result);
    }
    fileName.textContent = uploadButton.files[0].name;
}
}
if(uploadButtonsearch){
uploadButtonsearch.onchange = () => {
    let readersearch = new FileReader();
    readersearch.readAsDataURL(uploadButtonsearch.files[0]);
    readersearch.onload = () =>{
        chosenImagesearch.classList.remove("display-n");
        chosenImagesearch.setAttribute("src", readersearch.result);
    }
    fileNamesearch.textContent = uploadButtonsearch.files[0].name;
}}

//reset from button
const resetbutton = document.getElementById("resetform");
if(resetbutton){
document.getElementById("resetform").addEventListener("click", function() {

    chosenImage.classList.add("display-n");
    chosenImagesearch.classList.add("display-n");
    fileName.textContent = "No image uploaded";
    fileNamesearch.textContent = "No image uploaded";
});
}


//Browse for main!!! start

//Get image from browse for main
const button = document.getElementById("browse_main");
if(button){
button.addEventListener("click", function() {
    fetch('/get-images')
        .then(response => response.json())
        .then(data => {
            document.getElementById('imageModal').style.display = 'block';
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

}

//close the image modals and destroy
document.getElementById("closeselection").addEventListener("click", function() {
    document.getElementById('imageModal').style.display = 'none';
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }
});
document.getElementById("closeselection1").addEventListener("click", function() {
    document.getElementById('imageModal').style.display = 'none';
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }

});



//remove browse value for main image
const uploadbutton = document.getElementById("upload-button");
if(uploadbutton){
document.getElementById("upload-button").addEventListener("change", function() {
    const categoryImage = document.getElementById("upload-button").value;
    if (categoryImage) {
        document.getElementById('select_image_main_hidden').value = "";
    }
});}

//script on press confirm image main
document.getElementById("confirmimageselection").addEventListener("click", function() {
    const selectedImage = document.getElementById("selected-image").value;
    uploadButton.value = "";
    if (!selectedImage) {
        // Show an error message
        alert("Please select an image");
        return;
    }

    chosenImage.classList.remove("display-n");
    while (imagesContainer.firstChild) {
        imagesContainer.removeChild(imagesContainer.firstChild);
    }
    chosenImage.setAttribute("src", "/categories/" + selectedImage);
    document.getElementById('imageModal').style.display = 'none';
    fileName.textContent = selectedImage;
    document.getElementById('select_image_main_hidden').value = selectedImage;



});

//Browse for main!!! end

//Browse for search!!! start

//close and destroy for image search
document.getElementById("closeselection2").addEventListener("click", function() {
    document.getElementById('imageModal1').style.display = 'none';

    while (imagesContainers.firstChild) {
        imagesContainers.removeChild(imagesContainers.firstChild);
    }

});
document.getElementById("closeselection3").addEventListener("click", function() {
    document.getElementById('imageModal1').style.display = 'none';
    while (imagesContainers.firstChild) {
        imagesContainers.removeChild(imagesContainers.firstChild);
    }

});

const buttonn = document.getElementById("browse_search");
if(buttonn){
buttonn.addEventListener("click", function() {
    fetch('/get-images')
        .then(response => response.json())
        .then(data => {
            document.getElementById('imageModal1').style.display = 'block';
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
                imagesContainers.appendChild(img);
            });
        });
});}

//remove browse value for main image
const uploadsearch = document.getElementById("upload-button-search");
if(uploadsearch){
uploadsearch.addEventListener("change", function() {
    const categoryImage = document.getElementById("upload-button-search").value;
    if (categoryImage) {
        document.getElementById('select_image_search_hidden').value = "";
    }
});}

//script on press confirm image search
document.getElementById("confirmimageselection1").addEventListener("click", function() {
    const selectedImages = document.getElementById("selected-image1").value;
    uploadButtonsearch.value = "";
    if (!selectedImages) {
        // Show an error message
        alert("Please select an image");
        return;
    }

    chosenImagesearch.classList.remove("display-n");
    while (imagesContainers.firstChild) {
        imagesContainers.removeChild(imagesContainers.firstChild);
    }
    chosenImagesearch.setAttribute("src", "/categories/" + selectedImages);
    document.getElementById('imageModal1').style.display = 'none';
    fileNamesearch.textContent = selectedImages;
    document.getElementById('select_image_search_hidden').value = selectedImages;


});

// Script for upload product Images
// !!!!!!!!!!!!!!!!!!! Script for Upload single Image

// !!!!!!!!!!!!!!!!!!! Script for Upload multiple Images
let fileInputmutiple = document.getElementById("upload-button-multiple");
let imagesContainermutiple = document.getElementById("images");
let numoffiles = document.getElementById("num-of-files");


console.log(fileInputmutiple, imagesContainermutiple, numoffiles);

function preview(){
    imagesContainermutiple.innerHTML = "";
    numoffiles.textContent = `${fileInputmutiple.files} Files Selected`;

}
