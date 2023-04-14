

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
const buttons = document.getElementById("browse_main");
if(buttons){
buttons.addEventListener("click", function() {
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

let dropBox = document.getElementById('dropBox');

	// modify all of the event types needed for the script so that the browser
	// doesn't open the image in the browser tab (default behavior)
	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
		dropBox.addEventListener(evt, prevDefault, false);
	});
	function prevDefault (e) {
		e.preventDefault();
		e.stopPropagation();
	}

	// remove and add the hover class, depending on whether something is being
	// actively dragged over the box area
	['dragenter', 'dragover'].forEach(evt => {
		dropBox.addEventListener(evt, hover, false);
	});
	['dragleave', 'drop'].forEach(evt => {
		dropBox.addEventListener(evt, unhover, false);
	});
	function hover(e) {
		dropBox.classList.add('hover');
	}
	function unhover(e) {
		dropBox.classList.remove('hover');
	}

	// the DataTransfer object holds the data being dragged. it's accessible
	// from the dataTransfer property of drag events. the files property has
	// a list of all the files being dragged. put it into the filesManager function
	dropBox.addEventListener('drop', mngDrop, false);
	function mngDrop(e) {
		let dataTrans = e.dataTransfer;
		let files = dataTrans.files;
		filesManager(files);
	}

	// use FormData browser API to create a set of key/value pairs representing
	// form fields and their values, to send using XMLHttpRequest.send() method.
	// Uses the same format a form would use with multipart/form-data encoding
	function upFile(file) {
		//only allow images to be dropped
		let imageType = /image.*/;
		if (file.type.match(imageType)) {
			let url = 'HTTP/HTTPS URL TO SEND THE DATA TO';
			// create a FormData object
			let formData = new FormData();
			// add a new value to an existing key inside a FormData object or add the
			// key if it doesn't exist. the filesManager function will loop through
			// each file and send it here to be added
			formData.append('file', file);

			// standard file upload fetch setup
			fetch(url, {
				method: 'put',
				body: formData
			})
			.then(response => response.json())
			.then(result => { console.log('Success:', result); })
			.catch(error => { console.error('Error:', error); });
		} else {
			console.error("Only images are allowed!", file);
		}
	}


	// use the FileReader API to get the image data, create an img element, and add
	// it to the gallery div. The API is asynchronous so onloadend is used to get the
	// result of the API function
	function previewFile(file) {
        // only allow images to be dropped
        let imageType = /image.*/;
        if (file.type.match(imageType)) {
            let fReader = new FileReader();
            let gallery = document.getElementById('gallery');
            // reads the contents of the specified Blob. the result attribute of this
            // with hold a data: URL representing the file's data
            fReader.readAsDataURL(file);
            // handler for the loadend event, triggered when the reading operation is
            // completed (whether success or failure)
            fReader.onloadend = function() {
                let wrap = document.createElement('div');
                let img = document.createElement('img');
                // set the img src attribute to the file's contents (from read operation)
                img.src = fReader.result;
                // create a delete button
                let delBtn = document.createElement('button');
                delBtn.innerHTML = 'X';
                // add an event listener to the delete button
                delBtn.addEventListener('click', function() {
                    // remove the parent div element from the gallery div
                    gallery.removeChild(wrap);
                });
                // append the img and delete button to the parent div element
                wrap.appendChild(img);
                wrap.appendChild(delBtn);
                gallery.appendChild(wrap);
            }
        } else {
            console.error("Only images are allowed!", file);
        }
    }

	function filesManager(files) {
		// spread the files array from the DataTransfer.files property into a new
		// files array here
		files = [...files];
		// send each element in the array to both the upFile and previewFile
		// functions
		files.forEach(upFile);
		files.forEach(previewFile);
	}
