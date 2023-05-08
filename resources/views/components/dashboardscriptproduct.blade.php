
<script>

    //script for DataTable for products
    $(document).ready(function() {
        var table = $('#product-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: true,
            ajax: "{{ route('products') }}",
            columns: [
                {
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
                    name: 'name'
                },
                {
                    data: 'short_description',
                    name: 'short_description'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'product_status',
                    name: 'product_status'
                },
                {
                    data: 'category',
                    name: 'category',
                    render: function(data, type, row) {
                        return  data;
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

        var columnState = localStorage.getItem('columnState');

        // Restore the state of the columns
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

            // Toggle the visibility of the column based on the checkbox status
            column.visible(!column.visible());

            // Save the state of the columns to localStorage
            var columnStates = {};
            $('.checkbox').each(function() {
                var columnIndex = $(this).attr('id');
                var column = table.column(columnIndex);
                columnStates[columnIndex] = column.visible();
            });
            localStorage.setItem('columnState', JSON.stringify(columnStates));
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

    let uploadButton = document.getElementById("upload-button");
let chosenImage =document.getElementById("chosen-image");
let fileName = document.getElementById("file-name");
const imagesContainer = document.getElementById("image-container");

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


</script>
