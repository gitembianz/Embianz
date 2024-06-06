// Formulele validarilor
function getLabel(key) {
  return typeof window.labels !== 'undefined' && typeof window.labels[key] !== 'undefined' ? window.labels[key] : '';
}
const firstNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('name_require'),
  },
  {
    validation: (value) => value.length >= 2,
    message: getLabel('name_min'),
  },
  {
    validation: (value) => value.length <= 255,
    message: getLabel('name_max'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('name_space'),
  },
  {
    validation: (value) => /^[a-zA-ZăâîșțĂÂÎȘȚ\s-]*$/.test(value),
    message: getLabel('name_special'),
  },
];
const lastNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('lastname_require'),
  },
  {
    validation: (value) => value.length >= 2,
    message: getLabel('lastname_min'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('lastname_space'),
  },
  {
    validation: (value) => value.length <= 255,
    message: getLabel('lastname_max'),
  },
  {
    validation: (value) => /^[a-zA-ZăâîșțĂÂÎȘȚ\s-]*$/.test(value),
    message: getLabel('lastname_special'),
  },
];
const emailValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('email_require'),
  },
  {
    validation: (value) =>
      /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+(\.[a-zA-Z]{2,})+$/.test(value),
    message: getLabel('email_valid'),
  },
  {
    validation: (value) => !/\s{2,}/.test(value),
    message: getLabel('email_space'),
  },
  {
    validation: (value) => value.length >= 3,
    message: getLabel('email_min'),
  },
  {
    validation: (value) => value.length <= 255,
    message: getLabel('email_max'),
  },
];
const phoneValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('phone_require'),
  },
  {
    validation: (value) => /^[\d\-().+\s]+$/.test(value),
    message: getLabel('phone_special'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('phone_space'),
  },
  {
    validation: (value) =>
      value.replace(/[\s+\-.()]/g, "").length >= 5 &&
      value.replace(/[\s+\-.()]/g, "").length <= 15,
    message: getLabel('phone_dimension'),
  },
];
const addressValidations = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('address_require'),
  },
  {
    validation: (value) => value.length >= 2,
    message: getLabel('address_min'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('address_space'),
  },
  {
    validation: (value) => value.length <= 100,
    message: getLabel('address_max'),
  },
];
const countyValidations = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('county_require'),
  },
  {
    validation: (value) => value.length >= 2,
    message: getLabel('county_min'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('county_space'),
  },
  {
    validation: (value) => value.length <= 100,
    message: getLabel('county_max'),
  },
];
const cityValidations = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('city_require'),
  },
  {
    validation: (value) => value.length >= 2,
    message: getLabel('city_min'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('city_space'),
  },
  {
    validation: (value) => value.length <= 100,
    message: getLabel('city_max'),
  },
];
const zipcodeValidations = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('zipcode_require'),
  },
  {
    validation: (value) => value.length >= 2,
    message: getLabel('zipcode_min'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('zipcode_space'),
  },
  {
    validation: (value) => value.length <= 100,
    message: getLabel('zipcode_max'),
  },
];
// Stass de revizuit validarile pentru Cod de inregistrare si numar de inregistrare
const companyName = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('company_require'),
  },
  {
    validation: (value) => /^[a-zA-Z0-9/., _'\-`]*$/.test(value),
    message: getLabel('company_special'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('company_space'),
  },
];
const registerCode = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('code_require'),
  },
  {
    validation: (value) => /^[a-zA-Z0-9]*$/.test(value),
    message: getLabel('code_special'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('code_space'),
  },
];
const registerNumber = [
  {
    validation: (value) => value.trim() !== "",
    message: getLabel('number_require'),
  },
  {
    validation: (value) => /^[A-Za-z0-9/]*$/.test(value),
    message: getLabel('number_special'),
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: getLabel('number_space'),
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
  applyValidations("individualShippingPostalParent", zipcodeValidations, true);
  applyValidations("individualShippingCityParent", cityValidations, true);
  applyValidations("individualShippingCountyParent", countyValidations, true);
  applyValidations("individualShippingAddressParent", addressValidations, true);
  applyValidations("individualShippingPhoneParent", phoneValidation, true);
  applyValidations("individualShippingEmailParent", emailValidation, true);
  applyValidations("individualShippingLastNameParent", lastNameValidation, true);
  applyValidations("individualShippingFirstNameParent", firstNameValidation, true);
}

function validateIndividualIdentic() {
  applyValidations("individualBillingPostalParent", zipcodeValidations, true);
  applyValidations("individualBillingCityParent", cityValidations, true);
  applyValidations("individualBillingCountyParent", countyValidations, true);
  applyValidations("individualBillingAddressParent", addressValidations, true);
  applyValidations("individualBillingPhoneParent", phoneValidation, true);
  applyValidations("individualBillingEmailParent", emailValidation, true);
  applyValidations("individualBillingLastNameParent", lastNameValidation, true);
  applyValidations("individualBillingFirstNameParent", firstNameValidation, true);
  applyValidations("individualShippingPostalParent", zipcodeValidations, true);
  applyValidations("individualShippingCityParent", cityValidations, true);
  applyValidations("individualShippingCountyParent", countyValidations, true);
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
  applyValidations("juridicShippingPostalParent", zipcodeValidations, true);
  applyValidations("juridicShippingCityParent", cityValidations, true);
  applyValidations("juridicShippingCountyParent", countyValidations, true);
  applyValidations("juridicShippingAddressParent", addressValidations, true);
  applyValidations("juridicShippingPhoneParent", phoneValidation, true);
  applyValidations("juridicShippingEmailParent", emailValidation, true);
  applyValidations("juridicShippingLastNameParent", lastNameValidation, true);
  applyValidations("juridicShippingFirstNameParent", firstNameValidation, true);
}
function validateJuridicIdentic() {
  applyValidations("juridicBillingPostalParent", zipcodeValidations, true);
  applyValidations("juridicBillingCityParent", cityValidations, true);
  applyValidations("juridicBillingCountyParent", addressValidations, true);
  applyValidations("juridicBillingAddressParent", addressValidations, true);
  applyValidations("juridicBillingPhoneParent", phoneValidation, true);
  applyValidations("juridicBillingEmailParent", emailValidation, true);
  applyValidations("juridicBillingLastNameParent", lastNameValidation, true);
  applyValidations("juridicBillingFirstNameParent", firstNameValidation, true);
  applyValidations("registerCodeParent", registerCode, true);
  applyValidations("registerNumberParent", registerNumber, true);
  applyValidations("companyNameParent", companyName, true);
  applyValidations("juridicShippingPostalParent", zipcodeValidations, true);
  applyValidations("juridicShippingCityParent", cityValidations, true);
  applyValidations("juridicShippingCountyParent", countyValidations, true);
  applyValidations("juridicShippingAddressParent", addressValidations, true);
  applyValidations("juridicShippingPhoneParent", phoneValidation, true);
  applyValidations("juridicShippingEmailParent", emailValidation, true);
  applyValidations("juridicShippingLastNameParent", lastNameValidation, true);
  applyValidations("juridicShippingFirstNameParent", firstNameValidation, true);
}
