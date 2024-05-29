// Formulele validarilor
const nameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Te rugăm completează numele",
  },
  {
    validation: (value) => value.length >= 2,
    message: "Numele trebuie să aibă cel puțin 2 caractere",
  },
  {
    validation: (value) => value.length <= 70,
    message: "Numărul de caractere introduse pentru nume este prea mare.",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Numele nu poate conține spații consecutive.",
  },
  // {
  //   validation: (value) => /^[a-zA-ZăâîșțĂÂÎȘȚ\s]*$/.test(value),
  //   message: "Numele poate conține doar litere și spații",
  // },
];

const emailValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Te rugăm completează adresa de email",
  },
  {
    validation: (value) =>
    /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+(\.[a-zA-Z]{2,})+$/.test(value),
    message: "Te rog introdu o adresă de email validă.",
  },
  {
    validation: (value) => !/\s{2,}/.test(value),
    message: "Adresa de e-mail nu poate conține spații consecutive.",
  },
  {
    validation: (value) => value.length >= 6,
    message:
      "Adresa de email este prea scurtă. Te rog introdu o adresă de email mai lungă.",
  },
  {
    validation: (value) => value.length <= 255,
    message:
      "Adresa de email este prea lungă. Te rugăm introdu o adresă de email mai scurtă.",
  },
];

const companyValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Te rugăm completează numele companiei",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Numele companiei nu poate conține spații consecutive.",
  },
];

const messageValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Te rugăm completează mesajul",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Mesajul nu poate conține spații consecutive.",
  },
];


function applyValidations(elementId, validations, autoValidate) {
  const element = document.getElementById(elementId);
  const input = element.querySelector("input, textarea"); // Caută atât input cât și textarea
  const span = element.querySelector("span");
  // const button = document.getElementById(buttonId);

  // Funcție care va fi apelată la apăsarea butonului
  function validate() {
    let errorMessage = ""; // Inițializăm mesajul de eroare ca fiind gol

    for (const validation of validations) {
      if (!validation.validation(input.value)) {
        errorMessage = validation.message; // Salvează primul mesaj de eroare nevalid
        break; // Ieși din bucla de validare la primul mesaj de eroare nevalid găsit
      }
    }

    // Actualizarea textului spanului cu mesajul de eroare sau cu un mesaj gol dacă totul este valid
    if (errorMessage) {
      element.classList.add("error"); // Adaugă clasa de eroare dacă există un mesaj de eroare
      input.focus();
    } else {
      element.classList.remove("error"); // Elimină clasa de eroare dacă nu există un mesaj de eroare
    }
    span.textContent = errorMessage;
  }

  // Adăugăm ascultătorul de eveniment pentru input la schimbare
  input.addEventListener("input", validate);

  if (autoValidate) {
    validate();
  }
}


function validateIndividual() {
  applyValidations("nameParent", nameValidation, true);
  applyValidations("emailParent", emailValidation, true);
  applyValidations("companyParent", companyValidation, true);
  applyValidations("message", messageValidation, true);
}
