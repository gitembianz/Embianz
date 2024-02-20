// Formulele validarilor
const firstNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "First name cannot be empty",
  },
  {
    validation: (value) => value.length >= 2,
    message: "First name must be at least 2 characters long",
  },
  {
    validation: (value) => value.length <= 20,
    message: "The number of characters entered for the name is too long.",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "First name cannot contain consecutive spaces.",
  },
  {
    validation: (value) => /^[a-zA-Z\s]*$/.test(value),
    message: "First name can only contain letters and spaces",
  },
];
const lastNameValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Last name cannot be empty",
  },
  {
    validation: (value) => value.length >= 2,
    message: "Last name must be at least 2 characters long",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Last name cannot contain consecutive spaces.",
  },
  {
    validation: (value) => value.length <= 20,
    message: "The number of characters entered for the last name is too long.",
  },
  {
    validation: (value) => /^[a-zA-Z\s]*$/.test(value),
    message: "Last name can only contain letters and spaces",
  },
];
const emailValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Email cannot be empty",
  },
  {
    validation: (value) =>
      /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value),
    message: "Please enter a valid email address.",
  },
  {
    validation: (value) => !/\s{2,}/.test(value),
    message: "Email cannot contain consecutive spaces.",
  },
  {
    validation: (value) => value.length >= 6,
    message: "Email address is too short. Please enter a longer email address.",
  },
  {
    validation: (value) => value.length <= 255,
    message: "Email address is too long. Please enter a shorter email address.",
  },
];
const phoneValidation = [
  {
    validation: (value) => value.trim() !== "",
    message: "Phone cannot be empty",
  },
  {
    validation: (value) => /^[\d\-().+\s]+$/.test(value),
    message:
      "Phone number can only contain digits, spaces, and characters +, -, (, and ).",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Phone number cannot contain consecutive spaces.",
  },
  {
    validation: (value) =>
      value.replace(/[\s+\-.()]/g, "").length >= 5 &&
      value.replace(/[\s+\-.()]/g, "").length <= 15,
    message: "Phone number length should be between 5 and 15 characters.",
  },
];
const addressValidations = [
  {
    validation: (value) => value.trim() !== "",
    message: "Address cannot be empty",
  },
  {
    validation: (value) => value.length >= 5,
    message: "Address is too short. Please enter a longer address.",
  },
  {
    validation: (value) => /^[a-zA-Z0-9/., _'\-`]*$/.test(value),
    message:
      "Address can only contain letters, digits, and the symbols: ( ), , .",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Address cannot contain consecutive spaces.",
  },
  {
    validation: (value) => value.length <= 100,
    message: "Address is too long. Please enter a shorter address.",
  },
];
const companyName = [
  {
    validation: (value) => value.trim() !== "",
    message: "Company Name cannot be empty",
  },
  {
    validation: (value) => /^[a-zA-Z0-9/., _'\-`]*$/.test(value),
    message: "Company Name can only contain letters and digits",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Company Name cannot contain consecutive spaces.",
  },
];
const registerCode = [
  {
    validation: (value) => value.trim() !== "",
    message: "Register code cannot be empty",
  },
  {
    validation: (value) => /^[a-zA-Z0-9]*$/.test(value),
    message: "Register code can only contain letters and digits",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Register code cannot contain consecutive spaces.",
  },
];
const registerNumber = [
  {
    validation: (value) => value.trim() !== "",
    message: "Register number cannot be empty",
  },
  {
    validation: (value) => /^[0-9]*$/.test(value),
    message: "Register number can only contain digits",
  },
  {
    validation: (value) => !/\s{3,}/.test(value),
    message: "Register number cannot contain consecutive spaces.",
  },
];

function applyValidations(elementId, validations, buttonId) {
  const element = document.getElementById(elementId);
  const input = element.querySelector("input");
  const span = element.querySelector("span");
  const button = document.getElementById(buttonId);

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
  button.addEventListener("click", validate);
  input.addEventListener("input", validate);
}

// Aplicăm validările și le asociem butonului dorit


// individual
applyValidations(
  "individualShippingFirstName",
  firstNameValidation,
  "individual"
);
applyValidations(
  "individualShippingLastName",
  lastNameValidation,
  "individual"
);
applyValidations("individualShippingEmail", emailValidation, "individual");
applyValidations("individualShippingPhone", phoneValidation, "individual");
applyValidations(
  "individualShippingAddress",
  addressValidations,
  "individual"
);
applyValidations(
  "individualShippingCounty",
  addressValidations,
  "individual"
);
applyValidations(
  "individualShippingCity",
  addressValidations,
  "individual"
);
applyValidations(
  "individualShippingPostal",
  addressValidations,
  "individual"
);





// individualIdentic
applyValidations(
  "individualShippingFirstName",
  firstNameValidation,
  "individualIdentic"
);
applyValidations(
  "individualShippingLastName",
  lastNameValidation,
  "individualIdentic"
);
applyValidations("individualShippingEmail", emailValidation, "individualIdentic");
applyValidations("individualShippingPhone", phoneValidation, "individualIdentic");
applyValidations(
  "individualShippingAddress",
  addressValidations,
  "individualIdentic"
);
applyValidations(
  "individualShippingCounty",
  addressValidations,
  "individualIdentic"
);
applyValidations(
  "individualShippingCity",
  addressValidations,
  "individualIdentic"
);
applyValidations(
  "individualShippingPostal",
  addressValidations,
  "individualIdentic"
);
applyValidations(
  "individualBillingFirstName",
  firstNameValidation,
  "individualIdentic"
);
applyValidations(
  "individualBillingLastName",
  lastNameValidation,
  "individualIdentic"
);
applyValidations("individualBillingEmail", emailValidation, "individualIdentic");
applyValidations("individualBillingPhone", phoneValidation, "individualIdentic");
applyValidations(
  "individualBillingAddress",
  addressValidations,
  "individualIdentic"
);
applyValidations(
  "individualBillingCounty",
  addressValidations,
  "individualIdentic"
);
applyValidations(
  "individualBillingCity",
  addressValidations,
  "individualIdentic"
);
applyValidations(
  "individualBillingPostal",
  addressValidations,
  "individualIdentic"
);




// juridic
applyValidations(
  "juridicShippingFirstName",
  firstNameValidation,
  "juridic"
);
applyValidations(
  "juridicShippingLastName",
  lastNameValidation,
  "juridic"
);
applyValidations("juridicShippingEmail", emailValidation, "juridic");
applyValidations("juridicShippingPhone", phoneValidation, "juridic");
applyValidations(
  "juridicShippingAddress",
  addressValidations,
  "juridic"
);
applyValidations(
  "juridicShippingCounty",
  addressValidations,
  "juridic"
);
applyValidations("juridicShippingCity", addressValidations, "juridic");
applyValidations(
  "juridicShippingPostal",
  addressValidations,
  "juridic"
);
applyValidations("companyName", registerCode, "juridic");
applyValidations("registerNumber", companyName, "juridic");
applyValidations("registerCode", registerNumber, "juridic");







// juridicIdentic
applyValidations(
  "juridicShippingFirstName",
  firstNameValidation,
  "juridicIdentic"
);
applyValidations(
  "juridicShippingLastName",
  lastNameValidation,
  "juridicIdentic"
);
applyValidations("juridicShippingEmail", emailValidation, "juridicIdentic");
applyValidations("juridicShippingPhone", phoneValidation, "juridicIdentic");
applyValidations(
  "juridicShippingAddress",
  addressValidations,
  "juridicIdentic"
);
applyValidations(
  "juridicShippingCounty",
  addressValidations,
  "juridicIdentic"
);
applyValidations("juridicShippingCity", addressValidations, "juridicIdentic");
applyValidations(
  "juridicShippingPostal",
  addressValidations,
  "juridicIdentic"
);
applyValidations("companyName", registerCode, "juridicIdentic");
applyValidations("registerNumber", companyName, "juridicIdentic");
applyValidations("registerCode", registerNumber, "juridicIdentic");
applyValidations(
  "juridicBillingFirstName",
  firstNameValidation,
  "juridicIdentic"
);
applyValidations(
  "juridicBillingLastName",
  lastNameValidation,
  "juridicIdentic"
);
applyValidations("juridicBillingEmail", emailValidation, "juridicIdentic");
applyValidations("juridicBillingPhone", phoneValidation, "juridicIdentic");
applyValidations(
  "juridicBillingAddress",
  addressValidations,
  "juridicIdentic"
);
applyValidations("juridicBillingCounty", addressValidations, "juridicIdentic");
applyValidations("juridicBillingCity", addressValidations, "juridicIdentic");
applyValidations("juridicBillingPostal", addressValidations, "juridicIdentic");
