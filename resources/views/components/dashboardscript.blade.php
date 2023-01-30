<script>
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

    //edit category script
    // $(document).on('click', '.editt', function(event) {
    //     event.preventDefault();
    //     const input = document.getElementById('hidden_id');
    //     var id = input.value;

    //     $.ajax({
    //         url: "/edit_category/" + id + "/",
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         dataType: "json",
    //         success: function(data) {
    //             $('#categoryname').val(data.result.name);
    //             $('#categoryparrent').val(data.result.parrent);
    //             $('#categorylong_description').val(data.result.long_description);
    //             $('#categoryshort_description').val(data.result.short_description);
    //             $('#categorysequence').val(data.result.sequence);
    //             $('#categorystart_date').val(data.result.start_date);
    //             $('#categoryend_date').val(data.result.end_date);
    //             $('#idupdate').val(id);
    //             $('.modaltitle').text('Edit - ' + data.result.name + ' - category');
    //             document.getElementById('editmodal-category').style.display = 'block';

    //         },
    //         error: function(data) {
    //             var errors = data.responseJSON;
    //             console.log(errors);
    //         }
    //     });
    // });

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
    document.getElementById("edit").addEventListener("click", function() {

        document.getElementById("new").style.display = "none";
        document.getElementById("delete").style.display = "none";
        document.getElementById("edit").style.display = "none";
        document.getElementById("Update").style.display = "block";
        const spanElements = document.querySelectorAll(
            '#category_name, #category_parrent, #category_long_description,#category_short_description');
        const dates = document.querySelectorAll('#category_start_date, #category_end_date');
        dates.forEach(date => {
            const inpdate = document.createElement('input');
            inpdate.value = date.innerText;
            inpdate.setAttribute("class", date.getAttribute("class"));
            inpdate.setAttribute("type", "date");
            date.replaceWith(inpdate);
        });

        spanElements.forEach(spanElement => {
            const inputElement = document.createElement('input');
            inputElement.value = spanElement.innerText;
            inputElement.setAttribute("class", spanElement.getAttribute("class"));
            spanElement.replaceWith(inputElement);
        });
    });
</script>
