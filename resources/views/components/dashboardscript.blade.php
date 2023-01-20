<script>
    $(document).ready(function() {
        $('#category_table').DataTable({
            processing: true,
            serverSide: true,
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

    });
</script>
<script>
    //view category script
    $(document).on('click', '.view', function(event) {
        event.preventDefault();
        var id = $(this).attr('id');

        $.ajax({
            url: "/show_category/" + id + "/",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function(data) {
                $('#category_name').val(data.result.name);
                $('#category_parrent').val(data.result.parrent);
                $('#category_long_description').val(data.result.long_description);
                $('#category_short_description').val(data.result.short_description);
                $('#category_sequence').val(data.result.sequence);
                $('#category_start_date').val(data.result.start_date);
                $('#category_end_date').val(data.result.end_date);
                $('#hidden_id').val(id);
                $('.modal-title').text('View - ' + data.result.name + ' - category');
                document.getElementById('viewmodal-category').style.display = 'block';

            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        });
    });

    //edit category script
    $(document).on('click', '.edit', function(event) {
        event.preventDefault();
        const input = document.getElementById('hidden_id');
        var id = input.value;

        $.ajax({
            url: "/edit_category/" + id + "/",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function(data) {
                $('#categoryname').val(data.result.name);
                $('#categoryparrent').val(data.result.parrent);
                $('#categorylong_description').val(data.result.long_description);
                $('#categoryshort_description').val(data.result.short_description);
                $('#categorysequence').val(data.result.sequence);
                $('#categorystart_date').val(data.result.start_date);
                $('#categoryend_date').val(data.result.end_date);
                $('#idupdate').val(id);
                $('.modaltitle').text('Edit - ' + data.result.name + ' - category');
                document.getElementById('editmodal-category').style.display = 'block';

            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        });
    });

    //delete script
    $(document).on('click', '.delete', function(event) {
        event.preventDefault();
        const input = document.getElementById('hidden_id');
        var id = input.value;

        document.getElementById('confirmmodal-category').style.display = 'block';
        $('#hiddenid').val(id);

    });

    $('#browse_main').on('click', function(event) {
        event.preventDefault();
        document.getElementById('imageModal').style.display = 'block';

        $.ajax({
            url: "/images",
            dataType: "json",
            success: function(data) {

                document.getElementById('imageModal').style.display = 'block';

            }
        });
    });
</script>
<script>
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
    var modaleditcategory = document.getElementById("viewmodal-category");
    window.onclick = function(event) {
        if (event.target == modaleditcategory) {
            modaleditcategory.style.display = "none";
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






{{-- <script>
    $('#browse_main').on('click', function(event) {
        event.preventDefault();
        $.get('/images', function(images) {
            images.map(function(image) {
                var img = $('<img>').attr('src', image.result.img_main_path);
                $('#image-container').append(img);
            });
            document.getElementById('imageModal').style.display = 'block';
        });
    });
</script> --}}
