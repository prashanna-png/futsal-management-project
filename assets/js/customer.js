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

