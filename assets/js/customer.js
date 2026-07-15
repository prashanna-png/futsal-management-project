const dateInput = document.getElementById("booking_date");
const summaryDate = document.getElementById("summary-date");

dateInput.addEventListener("change", function () {
    summaryDate.textContent = this.value;
});

const slots = document.querySelectorAll("input[name='slotid']");
const summarySlot = document.getElementById("summary-slot");

slots.forEach(slot => {
    slot.addEventListener("change", function () {
        summarySlot.textContent = this.dataset.time;
    });
});



function cancelBooking(bookingid) {
    const confirmDelete = confirm(
        "Are you sure you want to cancel this booking?\n\nThis action cannot be undone."
    );

    if (confirmDelete) {
        window.location.href =
            "cancel_booking.php?bookingid=" + bookingid;
    }
}


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