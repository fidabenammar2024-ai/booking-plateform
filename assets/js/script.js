const modal = document.getElementById("reservationModal");
const closeModal = document.querySelector(".close-modal");
const fieldIdInput = document.getElementById("fieldId");
const selectedFieldName = document.getElementById("selectedFieldName");

document.querySelectorAll(".open-reservation-modal").forEach(button => {
    button.addEventListener("click", function () {
        const fieldId = this.dataset.fieldId;
        const fieldName = this.dataset.fieldName;

        fieldIdInput.value = fieldId;
        selectedFieldName.textContent = "Terrain : " + fieldName;

        modal.style.display = "block";
    });
});

if (closeModal) {
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });
}

window.addEventListener("click", function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

const profileButton = document.getElementById("profileButton");
const profileDropdown = document.getElementById("profileDropdown");

if (profileButton && profileDropdown) {
    profileButton.addEventListener("click", function () {
        profileDropdown.classList.toggle("show");
    });

    window.addEventListener("click", function (event) {
        if (!event.target.closest(".profile-menu")) {
            profileDropdown.classList.remove("show");
        }
    });
}