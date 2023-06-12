<script>
    //script for DataTable for categories
    $(document).ready(function() {
        $('#productsTable').DataTable();
        $('#mediaTable').DataTable();
    });
//script form categories modals
    window.addEventListener('show-delete-modal-category', event =>{
  document.getElementById('confirmationmodalcategory').style.display = 'flex';
})
window.addEventListener('show-delete-modal-category-multiple', event =>{
  document.getElementById('confirmationmodalcategorymultiple').style.display = 'flex';
})


    //delete script
    $(document).on('click', '#deletecat', function(event) {
        event.preventDefault();
        const input = document.getElementById('hidden_id');
        var id = input.value;
        document.getElementById('confirmmodal-category').style.display = 'flex';

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
            var tableimages = document.getElementById("imageTable");
            if (tableimages) {
                if (addMediaCat) {
                    if (addMediaCat.style.display === 'none') {
                        addMediaCat.style.display = 'block';
                    }
                }
            }

            tableimages.addEventListener('change', function() {
                var files = imgUpload.files;
                if (files.length > 0) {
                    var tableimages = document.getElementById("imageTable");
                    if (tableimages && tableimages.getElementsByTagName('tr').length > 0) {
                        if (addMediaCat) {
                            addMediaCat.style.display = 'block';
                        }
                    }
                }
            });

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
            const selectClass = document.querySelectorAll(
                '.item__form-input-close'
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

            selectClass.forEach(selectClas => {
                selectClas.classList.replace("item__form-input-close", "item__form-input");
            })

            sequenceElements.forEach(sequenceElement => {
                const inputSeq = document.createElement("input");
                inputSeq.value = sequenceElement.innerText;
                inputSeq.setAttribute("class", sequenceElement.getAttribute("class"));
                inputSeq.setAttribute("name", sequenceElement.getAttribute("id"));
                inputSeq.setAttribute("type", "number");
                inputSeq.setAttribute("required", true);
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

    function Addproductsfromcheck(event) {
        const productId = event.target.value; // Get the ID of the checked/unchecked product
        const productIdsInput = document.querySelector('input[name="productIdsadd"]');

        // Get the array of existing product IDs from the hidden input value
        const productIds = productIdsInput.value ? JSON.parse(productIdsInput.value) : [];

        if (event.target.checked) {
            // Add the checked product ID to the array
            productIds.push(productId);
        } else {
            // Remove the unchecked product ID from the array
            const index = productIds.indexOf(productId);
            if (index > -1) {
                productIds.splice(index, 1);
            }
        }

        // Update the hidden input value with the updated array of product IDs
        productIdsInput.value = JSON.stringify(productIds);
        const count = productIds.length;
        const addButton = document.querySelector('#addfromcheckbox');
        if (count > 0) {
            addButton.style.display = 'block';
            addButton.value = `Add ${count} product${count > 1 ? 's' : ''}`;
        } else {
            addButton.style.display = 'none';
        }
    }

    function createtable(productsData) {
        // Create the table element
        const table = document.createElement('table');
        table.setAttribute('id', 'productTable');

        // Create the table header
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        // Add a checkbox column header for selecting all products
        const selectAllCheckboxHeader = document.createElement('th');
        const selectAllCheckbox = document.createElement('input');
        selectAllCheckbox.setAttribute('type', 'checkbox');
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.addproduct-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
                if (checkbox.checked) {
                    // Add the value of the checked checkbox to the selectedProducts array
                    selectedProducts.push(checkbox.value);
                } else {
                    // Remove the value from the selectedProducts array if the checkbox is unchecked
                    const index = selectedProducts.indexOf(checkbox.value);
                    if (index !== -1) {
                        selectedProducts.splice(index, 1);
                    }
                }
            });
        });
        selectAllCheckboxHeader.appendChild(selectAllCheckbox);
        headerRow.appendChild(selectAllCheckboxHeader);

        // Add Name column header
        const nameHeader = document.createElement('th');
        nameHeader.innerHTML = 'Name <span class="sortable"></span>';
        nameHeader.setAttribute('data-sort', 'name');
        headerRow.appendChild(nameHeader);

        // Add Status column header
        const statusHeader = document.createElement('th');
        statusHeader.innerHTML = 'Status <span class="sortable"></span>';
        statusHeader.setAttribute('data-sort', 'status');
        headerRow.appendChild(statusHeader);

        // Add Quantity column header
        const quantityHeader = document.createElement('th');
        quantityHeader.innerHTML = 'Quantity <span class="sortable"></span>';
        quantityHeader.setAttribute('data-sort', 'quantity');
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
            checkbox.setAttribute('class', 'addproduct-checkbox');
            checkbox.value = product.id;
            checkbox.addEventListener('change', Addproductsfromcheck);
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
            $('#productTable').DataTable({
                columnDefs: [{
                    orderDataType: 'dom-checkbox',
                    targets: 'sort-checkbox'
                }, ],
                order: [
                    [1, 'asc']
                ], // Sort by the second column (Name) in ascending order by default
            });

            document.querySelector('#addProductButton').style.display = 'none';
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
                let productIds = [];
            },
            error: function(error) {},
        });
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
    if (calceladdproducts) {
        document.getElementById('calceladdproducts').addEventListener('click', hideTableAndForm);
    }

    const addProductButton = document.querySelector('#addProductButton');
    if (addProductButton) {
        addProductButton.addEventListener('click', fetchAllProductsData);
    }
</script>
