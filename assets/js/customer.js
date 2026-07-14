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


const futsalGrids = document.getElementById("futsal-grids");
const futsalCards = futsalGrids.querySelectorAll(".futsal-card");
const searchBox = document.querySelector("input[name='search']");

searchBox.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();

    futsalCards.forEach(card => {
        const text = card.textContent.toLowerCase();

        card.style.display = text.includes(searchTerm) ? "" : "none";
    });
});