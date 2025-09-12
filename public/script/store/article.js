   document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("imageModal");
            const modalImg = document.getElementById("modalImage");
            const closeBtn = document.querySelector(".article-image-modal-close");

            const img = document.getElementById("show-article-image");
            img.addEventListener("click", function() {
                const imgSrc = this.getAttribute("data-img-src");
                if (imgSrc) {
                    modal.style.display = "block";
                    modalImg.src = imgSrc;
                }
            });

            closeBtn.addEventListener("click", function() {
                modal.style.display = "none";
                modalImg.src = "";
            });

            modal.addEventListener("click", function(e) {
                if (e.target === modal) {
                    modal.style.display = "none";
                    modalImg.src = "";
                }
            });

            document.addEventListener("keydown", function(e) {
                if (e.key === "Escape") {
                    modal.style.display = "none";
                    modalImg.src = "";
                }
            });
        });
