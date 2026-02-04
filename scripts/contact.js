
document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.querySelector(".contact-form");

    if (contactForm) {
        contactForm.addEventListener("submit", function (event) {
            const submitBtn = contactForm.querySelector(".form-submit-btn");
            const originalText = submitBtn.textContent;
            submitBtn.textContent = "Sending...";
            submitBtn.disabled = true;

            const name = contactForm.querySelector('[name="name"]').value.trim();
            const email = contactForm.querySelector('[name="email"]').value.trim();
            const message = contactForm
                .querySelector('[name="message"]')
                .value.trim();

            if (!name || !email || !message) {
                alert("Please fill in all required fields");
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                event.preventDefault();
                return;
            }
        });
    }
    const formInputs = document.querySelectorAll(".form-input");
    formInputs.forEach((input) => {
        input.addEventListener("blur", function () {
            if (this.hasAttribute("required") && !this.value.trim()) {
                this.style.borderColor = "#ff6b6b";
            } else {
                this.style.borderColor = "";
            }
        });
    });
});
