<script>
    //script for DataTable for categories
    $(document).ready(function() {
        $('#productsTable').DataTable();
        $('#mediaTable').DataTable();
        var table = $('#category_table').DataTable({
            processing: true,
            serverSide: true,
            orderable: true,
            ajax: "{{ route('category') }}",
            columns: [{
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<img src="' + data + '" width="50" height="50">';
                    }
                },
                {
                    data: 'id',
                    name: 'id'
                },

                {
                    data: 'name',
                    name: 'name',
                    render: function(data, type, row) {
                        return '<a href="/show_category/' + row.id + '" class="link-name">' +
                            data + '</a>';
                    }
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
                    data: 'sequence',
                    name: 'sequence'
                },
            ]
        });
        // Script for toggle columns
        var columnState = localStorage.getItem('columnState');
        if (columnState) {
            var columnStates = JSON.parse(columnState);
            $.each(columnStates, function(columnIndex, visible) {
                var column = table.column(columnIndex);
                column.visible(visible);
                $('#' + columnIndex).prop('checked', visible);
            });
        }
        $('.checkbox').on('click', function() {
            var columnIndex = $(this).attr('id');
            var column = table.column(columnIndex);
            column.visible(!column.visible());
            var columnStates = {};
            $('.checkbox').each(function() {
                var columnIndex = $(this).attr('id');
                var column = table.column(columnIndex);
                columnStates[columnIndex] = column.visible();
            });
            localStorage.setItem('columnState', JSON.stringify(columnStates));
        });
    });


    //script for droplist with checkboxes
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
    $(document).on('click', '#deletecat', function(event) {
        event.preventDefault();
        const input = document.getElementById('hidden_id');
        var id = input.value;
        document.getElementById('confirmmodal-category').style.display = 'block';
        $('#hiddenid').val(id);
    });

    //Script on click outside of modal
    var modalconfirmcategory = document.getElementById("confirmmodal-category");
    window.onclick = function(event) {
        if (event.target == modalconfirmcategory) {
            modalconfirmcategory.style.display = "none";
        }
    }

    // Handdle media on view category
    var imgUpload = document.getElementById('imgUpload');
    var addMediaCat = document.getElementById('addmediacat');
    if (imgUpload) {
        imgUpload.addEventListener('click', function() {
            if (addMediaCat) {
                if (addMediaCat.style.display === 'none') {
                    addMediaCat.style.display = 'block';
                }
            }
        });
    }

    //edit category script
    const editt = document.getElementById("edit");
    if (editt) {
        document.getElementById("edit").addEventListener("click", function() {
            opentab(event, 'Details');
            document.querySelector(".tablinks#defaultOpen").classList.add("active");
            document.getElementById("edit").style.display = "none";
            document.getElementById("Update").style.display = "block";

            const spanElements = document.querySelectorAll(
                '#category_name, #category_parrent,#category_short_description, #seo_title'
            );
            const selectElements = document.querySelectorAll(
                '#category_parrent'
            );
            const textareaElements = document.querySelectorAll(
                '#category_long_description'
            );
            const dateElements = document.querySelectorAll(
                '#category_start_date, #category_end_date'
            );
            const sequenceElements = document.querySelectorAll(
                '#category_sequence'
            );
            const imageElements = document.querySelectorAll(
                '#category_image_main, #category_image_search'
            );

            selectElements.forEach((selectElement, index) => {
                const selectp = document.createElement("select");
                const categories = {!! json_encode($categories) !!};
                selectp.setAttribute("class", selectElement.getAttribute("class"));
                selectp.setAttribute("name", selectElement.getAttribute("id"));
                categories.forEach((category, categoryIndex) => {
                    const option = document.createElement("option");
                    option.text = category;
                    option.value = category;

                    if (category === selectElement.innerText) {
                        option.selected =
                            true;
                    }
                    selectp.add(option);
                });
                selectElement.replaceWith(selectp);
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
            textareaElements.forEach(textareaElements => {
                const inputTextarea = document.createElement("textarea");
                inputTextarea.value = textareaElements.innerText;
                inputTextarea.setAttribute("class", textareaElements.getAttribute("class"));
                inputTextarea.setAttribute("name", textareaElements.getAttribute("id"));
                inputTextarea.setAttribute("required", true);
                textareaElements.replaceWith(inputTextarea);
            });
        });
    }

    // Script for product tables

    function createtable(productsData) {
        // Create the table element
        const table = document.createElement('table');
        table.setAttribute('id', 'productTable');

        // Create the table header
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        // Add a checkbox column header
        const checkboxHeader = document.createElement('th');
        checkboxHeader.textContent = 'Action';
        headerRow.appendChild(checkboxHeader);

        // Add Name column header
        const nameHeader = document.createElement('th');
        nameHeader.textContent = 'Name';
        headerRow.appendChild(nameHeader);

        // Add Status column header
        const statusHeader = document.createElement('th');
        statusHeader.textContent = 'Status';
        headerRow.appendChild(statusHeader);

        // Add Quantity column header
        const quantityHeader = document.createElement('th');
        quantityHeader.textContent = 'Quantity';
        headerRow.appendChild(quantityHeader);

        // Add the header row to the table
        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Create the table body
        const tbody = document.createElement('tbody');

        // Iterate over the products data and create a row for each product
        for (const product of productsData) {
            const row = document.createElement('tr');

            // Add a checkbox column for selecting the product
            const checkboxCell = document.createElement('td');
            const checkbox = document.createElement('input');
            checkbox.setAttribute('type', 'checkbox');
            checkbox.setAttribute("class", 'addproduct-checkbox');
            checkbox.value = product.id;
            checkboxCell.appendChild(checkbox);
            row.appendChild(checkboxCell);

            // Add Name cell
            const nameCell = document.createElement('td');
            nameCell.textContent = product.name;
            row.appendChild(nameCell);

            // Add Status cell
            const statusCell = document.createElement('td');
            statusCell.textContent = product.product_status;
            row.appendChild(statusCell);

            // Add Quantity cell
            const quantityCell = document.createElement('td');
            quantityCell.textContent = product.quantity;
            row.appendChild(quantityCell);

            // Add the row to the table body
            tbody.appendChild(row);
        }

        // Add the table body to the table
        table.appendChild(tbody);

        // Append the table to a container element in your HTML
        const container = document.querySelector('#tableContainerproducts');
        container.innerHTML = '';
        container.appendChild(table);

        // Initialize DataTables
        $(document).ready(function() {
            $('#productTable').DataTable();
            console.log('datatable created');
            document.querySelector('#addProductButton').style.display= 'none';

        });

        var contentDiv = document.getElementById('contentDivp');
        if (contentDiv) {
            contentDiv.style.maxHeight = '100%';
            contentDiv.style.height = '100%';
            window.addEventListener('resize', function() {
                contentDiv.style.height = '100%';
            });
        }
    }

    //get all products
    function fetchAllProductsData() {
        $.ajax({
            url: '/get_all_products',
            type: 'GET',
            success: function(response) {
                document.getElementById('tableContainerproducts').style.display = 'block';
                document.getElementById('addProductsForm').style.display = 'block';
                const productsData = response.products;
                createtable(productsData);
                const checkboxes = document.querySelectorAll('.addproduct-checkbox');
                let productIds = [];
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateAddButton);
                console.log('in foreach');
            });

            },
            error: function(error) {},
        });
    }


    function updateAddButton() {
  const selectedProducts = document.querySelectorAll('.addproduct-checkbox:checked');
  const checkedProducts = Array.from(selectedProducts);
  const count = checkedProducts.length;
  const addButton = document.querySelector('#addfromcheckbox');

  // Update the button text and visibility
  if (count > 0) {
    addButton.style.display = 'block';
    addButton.value = `Add ${count} product${count > 1 ? 's' : ''}`;
  } else {
    addButton.style.display = 'none';
  }

  const productIds = checkedProducts.map(checkbox => checkbox.value);

  // Create a hidden input element
  const hiddenInputt = document.createElement('input');
  hiddenInputt.setAttribute('type', 'hidden');
  hiddenInputt.setAttribute('name', 'productIdsp');
  hiddenInputt.setAttribute('value', productIds.join(',')); // Join the productIds array into a comma-separated string
  hiddenInputt.setAttribute('id', 'sssssss');
  console.log(productIds);

  // Remove any existing hidden input elements before appending the new one
  const existingHiddenInputs = document.querySelectorAll('#addProductsForm input[type="hidden"][name="productIdsp"]');
  existingHiddenInputs.forEach(input => input.remove());

  // Append the hidden input to the desired location
  const formElement = document.querySelector('#addProductsForm');
  formElement.appendChild(hiddenInputt);
}


    function hideTableAndForm(event) {
  event.preventDefault(); // Prevent the default form submission behavior

  const tableContainer = document.getElementById('tableContainerproducts');
  if (tableContainer) {
    tableContainer.style.display = 'none';
  }

  const addProductsForm = document.getElementById('addProductsForm');
  if (addProductsForm) {
    addProductsForm.style.display = 'none';
  }

  document.querySelector('#addProductButton').style.display = 'block';

  const selectedProducts = document.querySelectorAll('.addproduct-checkbox:checked');
  selectedProducts.forEach(function(checkbox) {
    checkbox.checked = false; // Uncheck each selected checkbox
  });
  document.querySelector('#addfromcheckbox').style.display = 'none';

}
let calceladdproducts = document.getElementById('calceladdproducts');
if(calceladdproducts){
document.getElementById('calceladdproducts').addEventListener('click', hideTableAndForm);
}

const addProductButton = document.querySelector('#addProductButton');
    if (addProductButton) {
        addProductButton.addEventListener('click', fetchAllProductsData);
    }


</script>
