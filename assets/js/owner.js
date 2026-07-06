const deleteButtons = document.querySelectorAll('.delete-btn');
const popupOverlay = document.getElementById('popup-overlay');
const cancelBtn = document.getElementById('cancel-btn');
const deleteContainer = document.querySelector('.delete-container');
const hiddenInput = document.getElementById('deleteFutsalId');

deleteButtons.forEach(button => {
    button.addEventListener('click', () => {

        // Store the futsal id in the hidden input
        hiddenInput.value = button.dataset.id;

        popupOverlay.style.display = 'flex';
        deleteContainer.style.display = 'flex';
    });
});

cancelBtn.addEventListener('click', () => {
    popupOverlay.style.display = 'none';
});

const message = document.getElementById('error-success-msg');

if (message) {
    setTimeout(() => {
        message.style.display = 'none';
    }, 3000);
}