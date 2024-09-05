export function tabs(detailsButtonID, relatedButtonID, detailsContentID, relatedContentID) {
  const detailsButton = document.getElementById(detailsButtonID);
  const relatedButton = document.getElementById(relatedButtonID);
  const detailsContent = document.getElementById(detailsContentID);
  const relatedContent = document.getElementById(relatedContentID);


  if (!detailsButton || !relatedButton || !detailsContent || !relatedContent) {
    // console.error("One of the elements from tabs is not found...");
    return;
  }

  // Funcția pentru activarea detaliilor
  detailsButton.addEventListener('click', function () {
    detailsContent.classList.add('active');
    detailsButton.classList.add('button--active');
    relatedContent.classList.remove('active');
    relatedButton.classList.remove('button--active');
  });

  // Funcția pentru activarea conținutului asociat
  relatedButton.addEventListener('click', function () {
    relatedContent.classList.add('active');
    relatedButton.classList.add('button--active');
    detailsContent.classList.remove('active');
    detailsButton.classList.remove('button--active');
  });
}
