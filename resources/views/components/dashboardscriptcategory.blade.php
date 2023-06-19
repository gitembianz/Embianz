<script>
    //script form modals
    window.addEventListener('show-delete-modal-category', event => {
        document.getElementById('confirmationmodalcategory').style.display = 'flex';
    });
    window.addEventListener('show-delete-modal-category-multiple', event => {
        document.getElementById('confirmationmodalcategorymultiple').style.display = 'flex';
    });
    window.addEventListener('show-delete-modal-media', event => {
        document.getElementById('confirmationmodalmedia').style.display = 'flex';
    });
    window.addEventListener('show-delete-modal-media-multiple', event => {
        document.getElementById('confirmationmodalmediamultiple').style.display = 'flex';
    });

    //edit category script
    const editt = document.getElementById("edit");
    if (editt) {
        document.getElementById("edit").addEventListener("click", function() {
            // opentab(event, 'Details');

            let tabs = document.querySelectorAll(".tabs__page");
            let tabContents = document.querySelectorAll(".tabs__content");

            tabs.forEach((tab, index) => {
                tabContents.forEach((content) => {
                    content.classList.remove("active");
                });
                tabs.forEach((tab) => {
                    tab.classList.remove("active");
                });

                // Add the following lines to activate the first tab and its content
                tabs[0].classList.add("active");
                tabContents[0].classList.add("active");
            });


            document.querySelector("#Details").classList.add("active");
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
</script>
