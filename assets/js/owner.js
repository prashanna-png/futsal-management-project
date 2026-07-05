const deleteButtons = document.querySelectorAll('.delete-btn');
const popupOverlay = document.getElementById('popup-overlay');
const cancelBtn = document.getElementById('cancel-btn');
const deleteContainer = document.querySelector('.delete-container');

deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
        popupOverlay.style.display = 'flex';
        deleteContainer.style.display = 'flex';
    });
});

cancelBtn.addEventListener('click', () => {
    popupOverlay.style.display = 'none';
});

const message = document.getElementById('error-success-msg');

setTimeout(() => {
    message.style.display = 'none';
}, 3000);
