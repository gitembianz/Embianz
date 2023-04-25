<script type="text/javascript">
    let dropBox = document.getElementById('dropBox');

function upFile(file) {
  let imageType = /image.*/;
  if (file.type.match(imageType)) {
    let url = 'HTTP/HTTPS URL TO SEND THE DATA TO';
    let formData = new FormData();
    formData.append('file', file);
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

function previewFile(file) {
  let imageType = /image.*/;
  if (file.type.match(imageType)) {
    let fReader = new FileReader();
    let imageTable = document.getElementById('imageTable');
    let tableHeader = document.getElementById('tableHeader');

    //create table header if it doesn't exist
    if(!tableHeader){
      tableHeader = document.createElement('thead');
      tableHeader.setAttribute('id', 'tableHeader');

      let tr = document.createElement('tr');
      tr.setAttribute("class", "font-lg");
      let thImage = document.createElement('th');
      let thFileLocation = document.createElement('th');
      let thFileSequence = document.createElement('th');
      let thRemoveBtn = document.createElement('th');

      thImage.innerHTML = "Image";
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

    // reads the contents of the specified Blob. the result attribute of this
    // with hold a data: URL representing the file's data
    fReader.readAsDataURL(file);
    // handler for the loadend event, triggered when the reading operation is
    // completed (whether success or failure)
    fReader.onloadend = function() {

      //create <tr> and <td>
      let tr = document.createElement('tr');

      let tdImage = document.createElement('td');
      let tdFileLocation = document.createElement('td');
      let tdFileSequence = document.createElement('td');
      let tdRemoveBtn = document.createElement('td');
      let img = document.createElement('img');

      // set the img src attribute to the file's contents (from read operation)
      img.src = fReader.result;
      img.width = 150;

       // Add image size and extension info
       let fileInfo = document.createTextNode(file.size/1000 + " KB, " + file.type.split("/")[1] + " file");


      //create select with location value
      let fileLocation = document.createElement('select');
      fileLocation.setAttribute("class", "wid-60 p-1");
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
      fileSequence.setAttribute("class", "wid-60 p-1");
      fileSequence.setAttribute("placeholder", "image sequence");

      //add remove button
      let removeBtn = document.createElement('button');
      removeBtn.innerHTML = 'x';
      removeBtn.setAttribute("class", "buttonremove" + " font-xl");
      removeBtn.onclick = function() {
        tr.remove();
        if (imageTable.rows.length === 1) { //remove table header if there are no images in the table
          tableHeader.remove();
        }
      };
      tdImage.setAttribute("class", "font-md")
      tdImage.appendChild(img);
      tdImage.appendChild(fileInfo);
      tdFileLocation.appendChild(fileLocation);
      tdFileSequence.appendChild(fileSequence);
      tdRemoveBtn.appendChild(removeBtn);
      tr.appendChild(tdImage);
      tr.appendChild(tdFileLocation);
      tr.appendChild(tdFileSequence);
      tr.appendChild(tdRemoveBtn);
      imageTable.appendChild(tr);
    }
  } else {
    console.error("Only images are allowed!", file);
  }
}

function filesManager(files) {
  files = [...files];
  files.forEach(upFile);
  files.forEach(previewFile);
}

</script>

