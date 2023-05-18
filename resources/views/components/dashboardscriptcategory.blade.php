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
                        return '<a href="/show_category/' + row.id + '" class="link-name">' + data + '</a>';
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
    if(imgUpload){
    imgUpload.addEventListener('click', function() {
        if(addMediaCat){
        if (addMediaCat.style.display === 'none') {
            addMediaCat.style.display = 'block';
        }}
    });}

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
                const inputElement = document.createElement("textarea");
                inputElement.value = textareaElements.innerText;
                inputElement.setAttribute("class", textareaElements.getAttribute("class"));
                inputElement.setAttribute("name", textareaElements.getAttribute("id"));
                inputElement.setAttribute("required", true);
                textareaElements.replaceWith(inputElement);
            });
        });
    }

    // Add products related
    function fetchAllProductsData() {
        $.ajax({
            url: '/get_all_products',
            type: 'GET',
            success: function(response) {
                const productsData = response.products;
                populateSelectWithOptions(productsData);
            },
            error: function(error) {
            },
        });
    }

    function populateSelectWithOptions(productsData) {
        const selectElement = document.querySelector('select');
        productsData.forEach(function(product) {
            const option = document.createElement('option');
            const checkbox = document.createElement('input');
            checkbox.setAttribute('type', 'checkbox');
            checkbox.setAttribute('value', product.id);
            option.appendChild(document.createTextNode(product.name));
            option.appendChild(checkbox);
            selectElement.appendChild(option);
        });
    }

    const addProductButton = document.querySelector('#addProductButton');
    if(addProductButton){
    addProductButton.addEventListener('click', fetchAllProductsData);
    }

</script>
