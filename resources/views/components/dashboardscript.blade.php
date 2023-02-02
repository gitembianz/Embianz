<script>
    //script for DataTable for categories
    $(document).ready(function() {
        var table = $('#category_table').DataTable({
            processing: true,
            serverSide: true,
            orderable: true,
            ajax: "{{ route('category') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'parrent',
                    name: 'parrent'
                },
                {
                    data: 'short_description',
                    name: 'short_description'
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<img src="categories/' + data + '" width="50" height="50">';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('.checkbox').on('click', function() {
            var column = table.column($(this).attr(
                'data-column')); // get the column number from the checkbox's data-column attribute
            if (column.visible()) {
                column.visible(false); // hide the column
                $(this).attr('checked', false); // uncheck the checkbox
            } else {
                column.visible(true); // show the column
                $(this).attr('checked', true); // check the checkbox
            }
        });
    });

    var expanded = false;

    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }

    //delete script
    $(document).on('click', '.delete', function(event) {
        event.preventDefault();
        const input = document.getElementById('hidden_id');
        var id = input.value;

        document.getElementById('confirmmodal-category').style.display = 'block';
        $('#hiddenid').val(id);

    });


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

    var modaluser = document.getElementById("modal-user");
    window.onclick = function(event) {
        if (event.target == modaluser) {
            modaluser.style.display = "none";
        }
    }
    var modalcategory = document.getElementById("modal-category");
    window.onclick = function(event) {
        if (event.target == modalcategory) {
            modalcategory.style.display = "none";
        }
    }
    var modalupdate = document.getElementById("editmodal-category");
    window.onclick = function(event) {
        if (event.target == modalupdate) {
            modalupdate.style.display = "none";
        }
    }
    var modalconfirmcategory = document.getElementById("confirmmodal-category");
    window.onclick = function(event) {
        if (event.target == modalconfirmcategory) {
            modalconfirmcategory.style.display = "none";
        }
    }
</script>
<script>
    //edit category script
    document.getElementById("edit").addEventListener("click", function() {
  document.getElementById("new").style.display = "none";
  document.getElementById("delete").style.display = "none";
  document.getElementById("edit").style.display = "none";
  document.getElementById("Update").style.display = "block";

  const spanElements = document.querySelectorAll(
    '#category_name, #category_parrent, #category_long_description,#category_short_description'
  );
  const dateElements = document.querySelectorAll(
    '#category_start_date, #category_end_date'
  );
  const sequenceElements = document.querySelectorAll(
    '#category_sequence, #category_image_sequence'
  );
  const imageElements = document.querySelectorAll(
    '#category_image_main, #category_image_search'
  );

  imageElements.forEach(imageElement => {
    const inputFile = document.createElement("input");
    const file = imageElement.value;
    inputFile.value = file || "";
    inputFile.setAttribute("class", "ml-1 font-lg talign-c text-secondary p-1 bg-bg");
    inputFile.setAttribute("type", "file");
    inputFile.setAttribute("name", imageElement.getAttribute("id"));
    imageElement.replaceWith(inputFile);
  });

  sequenceElements.forEach(sequenceElement => {
    const inputSeq = document.createElement("input");
    inputSeq.value = sequenceElement.innerText;
    inputSeq.setAttribute("class", sequenceElement.getAttribute("class"));
    inputSeq.setAttribute("name", sequenceElement.getAttribute("id"));
    inputSeq.setAttribute("type", "number");
    sequenceElement.replaceWith(inputSeq);
  });

  dateElements.forEach(dateElement => {
    const inputDate = document.createElement("input");
    inputDate.value = dateElement.innerText;
    inputDate.setAttribute("class", dateElement.getAttribute("class"));
    inputDate.setAttribute("name", dateElement.getAttribute("id"));
    inputDate.setAttribute("type", "date");
    inputDate.setAttribute("required", true);
    dateElement.replaceWith(inputDate);
  });

  spanElements.forEach(spanElement => {
    const inputElement = document.createElement("input");
    inputElement.value = spanElement.innerText;
    inputElement.setAttribute("class", spanElement.getAttribute("class"));
    inputElement.setAttribute("name", spanElement.getAttribute("id"));
    inputElement.setAttribute("required", true);
    spanElement.replaceWith(inputElement);
  });
});

//Get image from browse
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
        imagesContainer.appendChild(img);
      });
    });
});

//close the modal and distroy all images
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



</script>
