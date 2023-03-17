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
        chosenImage.setAttribute("src", reader.result);
    }
    fileName.textContent = uploadButton.files[0].name;
}

uploadButtonsearch.onchange = () => {
    let readersearch = new FileReader();
    readersearch.readAsDataURL(uploadButtonsearch.files[0]);
    readersearch.onload = () =>{
        chosenImagesearch.setAttribute("src", readersearch.result);
    }
    fileNamesearch.textContent = uploadButtonsearch.files[0].name;
}
