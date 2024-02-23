// Formulele validarilor
const firstNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Numele nu poate fi gol",
  },
  {
    validation: (value) => value.length >= 2,
    message: "Numele trebuie să aibă cel puțin 2 caractere",
  },
  {
    validation: (value) => value.length <= 20,
    message: "Numărul de caractere introduse pentru nume este prea mare.",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Numele nu poate conține spații consecutive.",
  },
  {
    validation: (value) => /^[a-zA-Z\s]*$/.test(value),
    message: "Numele poate conține doar litere și spații",
  },
];
const lastNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Prenumele nu poate fi gol",
  },
  {
    validation: (value) => value.length >= 2,
    message: "Prenumele trebuie să aibă cel puțin 2 caractere",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Prenumele nu poate conține spații consecutive.",
  },
  {
    validation: (value) => value.length <= 20,
    message: "Numărul de caractere introduse pentru prenume este prea mare.",
  },
  {
    validation: (value) => /^[a-zA-Z\s]*$/.test(value),
    message: "Prenumele poate conține doar litere și spații",
  },
];
const emailValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Emailul nu poate fi gol",
  },
  {
    validation: (value) =>
      /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value),
    message: "Te rog introdu o adresă de email validă.",
  },
  {
    validation: (value) => !/\s{2,}/.test(value),
    message: "Emailul nu poate conține spații consecutive.",
  },
  {
    validation: (value) => value.length >= 6,
    message:
      "Adresa de email este prea scurtă. Te rog introdu o adresă de email mai lungă.",
  },
  {
    validation: (value) => value.length <= 255,
    message:
      "Adresa de email este prea lungă. Te rog introdu o adresă de email mai scurtă.",
  },
];
const phoneValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Numărul de telefon nu poate fi gol",
  },
  {
    validation: (value) => /^[\d\-().+\s]+$/.test(value),
    message:
      "Numărul de telefon poate conține doar cifre, spații și caracterele +, -, (, și ).",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Numărul de telefon nu poate conține spații consecutive.",
  },
  {
    validation: (value) =>
      value.replace(/[\s+\-.()]/g, "").length >= 5 &&
      value.replace(/[\s+\-.()]/g, "").length <= 15,
    message:
      "Lungimea numărului de telefon trebuie să fie între 5 și 15 caractere.",
  },
];
const addressValidations = [
  {
    validation: (value) => value.trim() !== "",
    message: "Adresa nu poate fi goală",
  },
  {
    validation: (value) => value.length >= 1,
    message: "Adresa este prea scurtă. Te rog introdu o adresă mai lungă.",
  },
  {
    validation: (value) => /^[a-zA-Z0-9/., _'\-`]*$/.test(value),
    message: "Adresa poate conține doar litere, cifre și simbolurile: ( ), , .",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Adresa nu poate conține spații consecutive.",
  },
  {
    validation: (value) => value.length <= 100,
    message: "Adresa este prea lungă. Te rog introdu o adresă mai scurtă.",
  },
];
const companyName = [
  {
    validation: (value) => value.trim() !== "",
    message: "Numele companiei nu poate fi gol",
  },
  {
    validation: (value) => /^[a-zA-Z0-9/., _'\-`]*$/.test(value),
    message: "Numele companiei poate conține doar litere și cifre",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Numele companiei nu poate conține spații consecutive.",
  },
];
const registerCode = [
  {
    validation: (value) => value.trim() !== "",
    message: "Codul de înregistrare nu poate fi gol",
  },
  {
    validation: (value) => /^[a-zA-Z0-9]*$/.test(value),
    message: "Codul de înregistrare poate conține doar litere și cifre",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Codul de înregistrare nu poate conține spații consecutive.",
  },
];
const registerNumber = [
  {
    validation: (value) => value.trim() !== "",
    message: "Numărul de înregistrare nu poate fi gol",
  },
  {
    validation: (value) => /^[0-9]*$/.test(value),
    message: "Numărul de înregistrare poate conține doar cifre",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Numărul de înregistrare nu poate conține spații consecutive.",
  },
];

function applyValidations(elementId, validations, autoValidate) {
  const element = document.getElementById(elementId);
  const input = element.querySelector("input");
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

  // Adăugăm ascultătorul de eveniment pentru click pe buton
  input.addEventListener("input", validate);

  if (autoValidate) {
    validate();
  }
}

function validateIndividual() {
  applyValidations("individualShippingPostal", addressValidations, true);
  applyValidations("individualShippingCity", addressValidations, true);
  applyValidations("individualShippingCounty", addressValidations, true);
  applyValidations("individualShippingAddress", addressValidations, true);
  applyValidations("individualShippingPhone", phoneValidation, true);
  applyValidations("individualShippingEmail", emailValidation, true);
  applyValidations("individualShippingLastName", lastNameValidation, true);
  applyValidations("individualShippingFirstName", firstNameValidation, true);
}

function validateIndividualIdentic() {
  applyValidations("individualBillingPostal", addressValidations, true);
  applyValidations("individualBillingCity", addressValidations, true);
  applyValidations("individualBillingCounty", addressValidations, true);
  applyValidations("individualBillingAddress", addressValidations, true);
  applyValidations("individualBillingPhone", phoneValidation, true);
  applyValidations("individualBillingEmail", emailValidation, true);
  applyValidations("individualBillingLastName", lastNameValidation, true);
  applyValidations("individualBillingFirstName", firstNameValidation, true);
  applyValidations("individualShippingPostal", addressValidations, true);
  applyValidations("individualShippingCity", addressValidations, true);
  applyValidations("individualShippingCounty", addressValidations, true);
  applyValidations("individualShippingAddress", addressValidations, true);
  applyValidations("individualShippingPhone", phoneValidation, true);
  applyValidations("individualShippingEmail", emailValidation, true);
  applyValidations("individualShippingLastName", lastNameValidation, true);
  applyValidations("individualShippingFirstName", firstNameValidation, true);
}
function validateJuridic() {
  applyValidations("registerCode", registerCode, true);
  applyValidations("registerNumber", registerNumber, true);
  applyValidations("companyName", companyName, true);
  applyValidations("juridicShippingPostal", addressValidations, true);
  applyValidations("juridicShippingCity", addressValidations, true);
  applyValidations("juridicShippingCounty", addressValidations, true);
  applyValidations("juridicShippingAddress", addressValidations, true);
  applyValidations("juridicShippingPhone", phoneValidation, true);
  applyValidations("juridicShippingEmail", emailValidation, true);
  applyValidations("juridicShippingLastName", lastNameValidation, true);
  applyValidations("juridicShippingFirstName", firstNameValidation, true);
}
function validateJuridicIdentic() {
  applyValidations("juridicBillingPostal", addressValidations, true);
  applyValidations("juridicBillingCity", addressValidations, true);
  applyValidations("juridicBillingCounty", addressValidations, true);
  applyValidations("juridicBillingAddress", addressValidations, true);
  applyValidations("juridicBillingPhone", phoneValidation, true);
  applyValidations("juridicBillingEmail", emailValidation, true);
  applyValidations("juridicBillingLastName", lastNameValidation, true);
  applyValidations("juridicBillingFirstName", firstNameValidation, true);
  applyValidations("registerCode", registerCode, true);
  applyValidations("registerNumber", registerNumber, true);
  applyValidations("companyName", companyName, true);
  applyValidations("juridicShippingPostal", addressValidations, true);
  applyValidations("juridicShippingCity", addressValidations, true);
  applyValidations("juridicShippingCounty", addressValidations, true);
  applyValidations("juridicShippingAddress", addressValidations, true);
  applyValidations("juridicShippingPhone", phoneValidation, true);
  applyValidations("juridicShippingEmail", emailValidation, true);
  applyValidations("juridicShippingLastName", lastNameValidation, true);
  applyValidations("juridicShippingFirstName", firstNameValidation, true);
}
