export function tabs(detailsButtonID, relatedButtonID, detailsContentID, relatedContentID) {
  const detailsButton = document.getElementById(detailsButtonID);
  const relatedButton = document.getElementById(relatedButtonID);
  const detailsContent = document.getElementById(detailsContentID);
  const relatedContent = document.getElementById(relatedContentID);


  if (!detailsButton || !relatedButton || !detailsContent || !relatedContent) {
    // console.error("One of the elements from tabs is not found...");
    return;
  }

  if (typeof Livewire !== 'undefined') {
    // Function to activate the details content
    detailsButton.addEventListener('click', function () {
      detailsContent.classList.add('active');
      Livewire.emit('refreshComponent'); // Replace 'refreshComponent' with your Livewire method/event name
      detailsButton.classList.add('button--active');
      relatedContent.classList.remove('active');
      relatedButton.classList.remove('button--active');
    });

    // Function to activate the related content
    relatedButton.addEventListener('click', function () {
      relatedContent.classList.add('active');
      relatedButton.classList.add('button--active');
      detailsContent.classList.remove('active');
      detailsButton.classList.remove('button--active');
    });
  }
}
