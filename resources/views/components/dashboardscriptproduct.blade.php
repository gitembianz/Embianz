<script>

    //script for DataTable for products
    $(document).ready(function() {
        var table = $('#product-table').DataTable({
            processing: true,
            serverSide: true,
            orderable: true,
            ajax: "{{ route('products') }}",
            columns: [{
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


</script>
