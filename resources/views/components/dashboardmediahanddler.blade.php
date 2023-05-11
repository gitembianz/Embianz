<script type="text/javascript">
    allFiles = new DataTransfer();

    function previewFile(file) {
        let imageType = /^image\/.*|^video\/.*/;
        if (file.type.match(imageType)) {
            let fReader = new FileReader();
            let imageTable = document.getElementById('imageTable');
            let tableHeader = document.getElementById('tableHeader');

            //create table header if it doesn't exist
            if (!tableHeader) {
                tableHeader = document.createElement('thead');
                tableHeader.setAttribute('id', 'tableHeader');

                let tr = document.createElement('tr');
                tr.setAttribute("class", "font-md");
                let thImage = document.createElement('th');
                let thFileLocation = document.createElement('th');
                let thFileSequence = document.createElement('th');
                let thRemoveBtn = document.createElement('th');

                thImage.innerHTML = "Media";
                thFileLocation.innerHTML = "Location";
                thFileSequence.innerHTML = "Sequence";
                thRemoveBtn.innerHTML = "Action";

                tr.appendChild(thImage);
                tr.appendChild(thFileLocation);
                tr.appendChild(thFileSequence);
                tr.appendChild(thRemoveBtn);
                tableHeader.appendChild(tr);
                imageTable.appendChild(tableHeader);
            }
            fReader.readAsDataURL(file);

            fReader.onloadend = function() {
                //create <tr> and <td>
                let tr = document.createElement('tr');
                let tdImage = document.createElement('td');
                let tdFileLocation = document.createElement('td');
                let tdFileSequence = document.createElement('td');
                let tdRemoveBtn = document.createElement('td');
                if (file.type.includes('image')) {
                    // create an <img> element and set its src attribute to the file's contents (from read operation)
                    let img = document.createElement('img');
                    img.src = fReader.result;
                    img.width = 150;
                    tdImage.appendChild(img);
                } else if (file.type.includes('video')) {
                    // create a <video> element and set its src attribute to the file's contents (from read operation)
                    let video = document.createElement('video');
                    video.src = fReader.result;
                    video.width = 150;
                    video.controls = true; // add controls to the video player
                    tdImage.appendChild(video);
                }

                // Add image size and extension info
                let fileInfo = document.createTextNode(file.size / 1000 + " KB, " + file.type.split("/")[1] +
                    " file");
                allFiles.items.add(file);
                document.getElementById('imgUpload').files = allFiles.files;

                let fileSize = document.createElement('input');
                fileSize.setAttribute("type", "hidden");
                fileSize.setAttribute("name", "file_size[]");
                fileSize.value = file.size;


                //create select with location value
                let fileLocation = document.createElement('select');
                fileLocation.setAttribute("class", "p-1");
                fileLocation.setAttribute("name", "file_location[]"); // add name attribute
                const medialocations = {!! json_encode($medialocations) !!};

                medialocations.forEach((medialocation, categoryIndex) => {
                    const option = document.createElement("option");
                    option.text = medialocation.location;
                    option.value = medialocation.location;
                    fileLocation.add(option);
                });

                //create inputs for sequence
                let fileSequence = document.createElement('input');
                fileSequence.setAttribute("type", "number");
                fileSequence.setAttribute("class", " wid-6 p-1");
                fileSequence.setAttribute("name", "file_sequence[]"); // add name attribute

                //add remove button
                let removeBtn = document.createElement('button');
                removeBtn.innerHTML = 'x';
                removeBtn.setAttribute("class", "buttonremove" + " font-xl");
                removeBtn.onclick = function() {
                    tr.remove();
                    if (imageTable.rows.length === 1) {
                        tableHeader.remove();
                    }
                    let name = file.name;
                    for (let i = 0; i < allFiles.items.length; i++) {
                        if (name === allFiles.items[i].getAsFile().name) {
                            allFiles.items.remove(i);
                            continue;
                        }
                    }
                    document.getElementById('imgUpload').files = allFiles.files;

                };
                tdImage.setAttribute("class", "font-md");

                tdImage.appendChild(fileInfo);
                tdImage.appendChild(fileSize);
                tdFileLocation.appendChild(fileLocation);
                tdFileSequence.appendChild(fileSequence);
                tdRemoveBtn.appendChild(removeBtn);
                tr.appendChild(tdImage);
                tr.appendChild(tdFileLocation);
                tr.appendChild(tdFileSequence);
                tr.appendChild(tdRemoveBtn);
                imageTable.appendChild(tr);
            };
        } else {
            console.error("Only images are allowed!", file);
        }
    }

    function filesManager(files) {
        files = [...files];
        files.forEach(previewFile);
        document.getElementById('imgUpload').files = allFiles.files;

    }
    function removeFile(fileId) {
  // Make an AJAX request to remove the file with the given ID
  $.ajax({
    url: '/filesd/' + fileId,
    success: function(data) {
       // Add a success message to the session

      // Refresh the page
      location.reload();
      sessionStorage.setItem('message', 'Media are deleted!');
    },
    error: function(xhr, status, error) {
      console.error(error);
    }
  });
}


</script>
