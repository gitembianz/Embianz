<script>
    let uploadButton = document.getElementById("upload-button");
    let chosenImage = document.getElementById("chosen-image");
    let fileName = document.getElementById("file-name");
    const imagesContainer = document.getElementById("image-container");

    if (uploadButton) {
        uploadButton.onchange = () => {
            let reader = new FileReader();
            reader.readAsDataURL(uploadButton.files[0]);
            reader.onload = () => {
                chosenImage.classList.remove("display-n");
                chosenImage.setAttribute("src", reader.result);
            }
            fileName.textContent = uploadButton.files[0].name;
        }
    }

    //script for products- modals
    window.addEventListener('show-delete-modal', event =>{
  document.getElementById('confirmationmodal').style.display = 'flex';
})
window.addEventListener('show-delete-modal-multiple', event =>{
  document.getElementById('confirmationmodalmultiple').style.display = 'flex';
})
</script>
