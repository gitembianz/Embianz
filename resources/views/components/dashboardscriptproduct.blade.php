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

    });
</script>
