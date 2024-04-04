// Formulele validarilor
const firstNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Te rugăm completează numele",
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
    validation: (value) => /^[a-zA-ZăâîșțĂÂÎȘȚ\s]*$/.test(value),
    message: "Numele poate conține doar litere și spații",
  },
];
const lastNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Te rugăm completează prenumele",
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
    validation: (value) => /^[a-zA-ZăâîșțĂÂÎȘȚ\s]*$/.test(value),
    message: "Prenumele poate conține doar litere și spații",
  },
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
const phoneValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Te rugăm completează numărul de telefon",
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
    message: "Te rugăm completează adresa",
  },
  {
    validation: (value) => value.length >= 1,
    message: "Adresa este prea scurtă. Te rog introdu o adresă mai lungă.",
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
    message: "Te rugăm completează numele companiei",
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
    message: "Te rugăm completează codul de înregistrare",
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
    message: "Te rugăm completează numărul de înregistrare",
  },
  {
    validation: (value) => /^[A-Za-z0-9/]*$/.test(value),
    message: "Numărul de înregistrare poate conține doar litere, cifre și caracterul '/'",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Numărul de înregistrare nu poate conține spații consecutive.",
  }
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
  applyValidations("individualShippingPostalParent", addressValidations, true);
  applyValidations("individualShippingCityParent", addressValidations, true);
  applyValidations("individualShippingCountyParent", addressValidations, true);
  applyValidations("individualShippingAddressParent", addressValidations, true);
  applyValidations("individualShippingPhoneParent", phoneValidation, true);
  applyValidations("individualShippingEmailParent", emailValidation, true);
  applyValidations("individualShippingLastNameParent", lastNameValidation, true);
  applyValidations("individualShippingFirstNameParent", firstNameValidation, true);
}

function validateIndividualIdentic() {
  applyValidations("individualBillingPostalParent", addressValidations, true);
  applyValidations("individualBillingCityParent", addressValidations, true);
  applyValidations("individualBillingCountyParent", addressValidations, true);
  applyValidations("individualBillingAddressParent", addressValidations, true);
  applyValidations("individualBillingPhoneParent", phoneValidation, true);
  applyValidations("individualBillingEmailParent", emailValidation, true);
  applyValidations("individualBillingLastNameParent", lastNameValidation, true);
  applyValidations("individualBillingFirstNameParent", firstNameValidation, true);
  applyValidations("individualShippingPostalParent", addressValidations, true);
  applyValidations("individualShippingCityParent", addressValidations, true);
  applyValidations("individualShippingCountyParent", addressValidations, true);
  applyValidations("individualShippingAddressParent", addressValidations, true);
  applyValidations("individualShippingPhoneParent", phoneValidation, true);
  applyValidations("individualShippingEmailParent", emailValidation, true);
  applyValidations("individualShippingLastNameParent", lastNameValidation, true);
  applyValidations("individualShippingFirstNameParent", firstNameValidation, true);
}
function validateJuridic() {
  applyValidations("registerCodeParent", registerCode, true);
  applyValidations("registerNumberParent", registerNumber, true);
  applyValidations("companyNameParent", companyName, true);
  applyValidations("juridicShippingPostalParent", addressValidations, true);
  applyValidations("juridicShippingCityParent", addressValidations, true);
  applyValidations("juridicShippingCountyParent", addressValidations, true);
  applyValidations("juridicShippingAddressParent", addressValidations, true);
  applyValidations("juridicShippingPhoneParent", phoneValidation, true);
  applyValidations("juridicShippingEmailParent", emailValidation, true);
  applyValidations("juridicShippingLastNameParent", lastNameValidation, true);
  applyValidations("juridicShippingFirstNameParent", firstNameValidation, true);
}
function validateJuridicIdentic() {
  applyValidations("juridicBillingPostalParent", addressValidations, true);
  applyValidations("juridicBillingCityParent", addressValidations, true);
  applyValidations("juridicBillingCountyParent", addressValidations, true);
  applyValidations("juridicBillingAddressParent", addressValidations, true);
  applyValidations("juridicBillingPhoneParent", phoneValidation, true);
  applyValidations("juridicBillingEmailParent", emailValidation, true);
  applyValidations("juridicBillingLastNameParent", lastNameValidation, true);
  applyValidations("juridicBillingFirstNameParent", firstNameValidation, true);
  applyValidations("registerCodeParent", registerCode, true);
  applyValidations("registerNumberParent", registerNumber, true);
  applyValidations("companyNameParent", companyName, true);
  applyValidations("juridicShippingPostalParent", addressValidations, true);
  applyValidations("juridicShippingCityParent", addressValidations, true);
  applyValidations("juridicShippingCountyParent", addressValidations, true);
  applyValidations("juridicShippingAddressParent", addressValidations, true);
  applyValidations("juridicShippingPhoneParent", phoneValidation, true);
  applyValidations("juridicShippingEmailParent", emailValidation, true);
  applyValidations("juridicShippingLastNameParent", lastNameValidation, true);
  applyValidations("juridicShippingFirstNameParent", firstNameValidation, true);
}
