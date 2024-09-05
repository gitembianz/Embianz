<script type="text/javascript">
	//Script fot Table with media
	allFiles = new DataTransfer();
	//function for simple add
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
				let tr = document.createElement('tr');
				let tdImage = document.createElement('td');
				let tdFileLocation = document.createElement('td');
				let tdFileSequence = document.createElement('td');
				let tdRemoveBtn = document.createElement('td');
				if (file.type.includes('image')) {
					let img = document.createElement('img');
					img.src = fReader.result;
					img.width = 100;
					tdImage.appendChild(img);
				} else if (file.type.includes('video')) {
					let video = document.createElement('video');
					video.src = fReader.result;
					video.width = 100;
					video.controls = true;
					tdImage.appendChild(video);
				}

				let fileInfo = document.createTextNode(file.size / 1000 + " KB, " + file.type.split("/")[1] +
					" file");
				allFiles.items.add(file);
				document.getElementById('imgUpload').files = allFiles.files;
				let fileSize = document.createElement('input');
				fileSize.setAttribute("type", "hidden");
				fileSize.setAttribute("name", "file_size[]");
				fileSize.value = file.size;

				let fileLocation = document.createElement('select');
				fileLocation.setAttribute("required", "required");
				fileLocation.setAttribute("name", "file_location[]");
				const medialocations = {!! json_encode($medialocations) !!};

				medialocations.forEach((medialocation, categoryIndex) => {
					const option = document.createElement("option");
					option.text = medialocation.location;
					option.value = medialocation.location;
					fileLocation.add(option);
				});

				let fileSequence = document.createElement('input');
				fileSequence.setAttribute("type", "number");
				fileSequence.setAttribute("min", "0");
				fileSequence.setAttribute("required", "required");
				fileSequence.setAttribute("name", "file_sequence[]");

				let removeBtn = document.createElement('button');
				removeBtn.innerHTML =
					'<svg><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
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
		var contentDiv = document.getElementById('contentDiv');
		if (contentDiv) {
			contentDiv.style.maxHeight = '100%';
			contentDiv.style.height = '100%';
			window.addEventListener('resize', function() {
				contentDiv.style.height = '100%';
			});
		}
	}
</script>
