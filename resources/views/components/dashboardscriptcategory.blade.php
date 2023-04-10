<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    //script for DataTable for categories
    $(document).ready(function() {
        var table = $('#category_table').DataTable();


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
    $(document).on('click', '.delete', function(event) {
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


    //edit category script
    const editt = document.getElementById("edit");
    if(editt){
    document.getElementById("edit").addEventListener("click", function() {
        //document.getElementById("new").style.display = "none";
        document.getElementById("delete").style.display = "none";
        document.getElementById("edit").style.display = "none";
        document.getElementById("Update").style.display = "block";

        document.getElementById("uploadcontrollerss").classList.remove("display-n");
        document.getElementById("uploadcontrollersm").classList.remove("display-n");

        const spanElements = document.querySelectorAll(
            '#category_name, #category_parrent,#category_short_description'
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
        selectElements.forEach((selectElement, index) => {
    const selectp = document.createElement("select");
    const categories = {!! json_encode($categories) !!};

    selectp.setAttribute("class", "ml-1 font-lg talign-c text-bg p-1 bg-white");
    selectp.setAttribute("name", selectElement.getAttribute("id"));

    categories.forEach((category, categoryIndex) => {
        const option = document.createElement("option");
        option.text = category;
        option.value = category;
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
            inputElement.setAttribute('style', 'width: 200%');
            textareaElements.replaceWith(inputElement);
        });
    });
}
</script>
