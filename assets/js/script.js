const modal = document.getElementById("reservationModal");
const closeModal = document.querySelector(".close-modal");
const fieldIdInput = document.getElementById("fieldId");
const selectedFieldName = document.getElementById("selectedFieldName");

document.querySelectorAll(".open-reservation-modal").forEach((button) => {
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
const reservationDate = document.getElementById("date");
const slotSelect = document.getElementById("slot");
const slotMessage = document.getElementById("slotMessage");
const confirmReservationBtn = document.getElementById("confirmReservationBtn");
const fieldIdInputForSlots = document.getElementById("fieldId");
if (
  reservationDate &&
  slotSelect &&
  slotMessage &&
  confirmReservationBtn &&
  fieldIdInputForSlots
) {
  const today = new Date().toISOString().split("T")[0];
  reservationDate.setAttribute("min", today);
  reservationDate.addEventListener("change", function () {
    const fieldId = fieldIdInputForSlots.value;
    const selectedDate = reservationDate.value;
    slotSelect.innerHTML = '<option value="">Chargement...</option>';
    slotSelect.disabled = true;
    confirmReservationBtn.disabled = true;
    slotMessage.textContent = "";
    slotMessage.className = "slot-message";
    if (!fieldId || !selectedDate) {
      slotSelect.innerHTML =
        '<option value="">Choisissez d’abord une date</option>';
      return;
    }
    fetch(`get_available_slots.php?field_id=${fieldId}&date=${selectedDate}`)
      .then((response) => response.json())
      .then((data) => {
        slotSelect.innerHTML = "";
        if (!data.success || data.slots.length === 0) {
          slotSelect.innerHTML =
            '<option value="">Aucun creneau disponible</option>';
          slotMessage.textContent = data.message || "Aucun creneau disponible.";
          slotMessage.classList.add("slot-error");
          return;
        }
        slotSelect.innerHTML = '<option value="">Choisir un creneau</option>';
        data.slots.forEach((slot) => {
          const option = document.createElement("option");
          option.value = slot.value;
          option.textContent = slot.label;
          slotSelect.appendChild(option);
        });
        slotSelect.disabled = false;
        slotMessage.textContent = data.message;
        slotMessage.classList.add("slot-success");
      })
      .catch(() => {
        slotSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        slotMessage.textContent = "Impossible de charger les creneaux.";
        slotMessage.classList.add("slot-error");
      });
  });
  slotSelect.addEventListener("change", function () {
    confirmReservationBtn.disabled = slotSelect.value === "";
  });
}
