'use strict';

function getLabel(key) {
    return (typeof window.labels !== "undefined" && typeof window.labels[key] !== "undefined") ? window.labels[key] : "";
}

const firstNameValidation = [
    { validation: value => value.trim() !== "", message: getLabel("name_require") },
    { validation: value => value.length >= 2, message: getLabel("name_min") },
    { validation: value => value.length <= 100, message: getLabel("name_max") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("name_space") },
    { validation: value => /^[a-zA-Z\u0103\u00e2\u00ee\u0219\u021b\u0102\u00c2\u00ce\u0218\u021a\s-]*$/.test(value), message: getLabel("name_special") }
];

const lastNameValidation = [
    { validation: value => value.trim() !== "", message: getLabel("lastname_require") },
    { validation: value => value.length >= 2, message: getLabel("lastname_min") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("lastname_space") },
    { validation: value => value.length <= 100, message: getLabel("lastname_max") },
    { validation: value => /^[a-zA-Z\u0103\u00e2\u00ee\u0219\u021b\u0102\u00c2\u00ce\u0218\u021a\s-]*$/.test(value), message: getLabel("lastname_special") }
];

const emailValidation = [
    { validation: value => value.trim() !== "", message: getLabel("email_require") },
    { validation: value => /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+(\.[a-zA-Z]{2,})+$/.test(value), message: getLabel("email_valid") },
    { validation: value => !/\s{2,}/.test(value), message: getLabel("email_space") },
    { validation: value => value.length >= 3, message: getLabel("email_min") },
    { validation: value => value.length <= 100, message: getLabel("email_max") }
];

const phoneValidation = [
    { validation: value => value.trim() !== "", message: getLabel("phone_require") },
    { validation: value => /^[\d\-().+\s]+$/.test(value), message: getLabel("phone_special") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("phone_space") },
    {
        validation: value => {
            const cleaned = value.replace(/[\s+\-.()]/g, "");
            return cleaned.length >= 5 && cleaned.length <= 15;
        }, message: getLabel("phone_dimension")
    }
];

const addressValidations = [
    { validation: value => value.trim() !== "", message: getLabel("address_require") },
    { validation: value => value.length >= 2, message: getLabel("address_min") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("address_space") },
    { validation: value => value.length <= 100, message: getLabel("address_max") }
];

const cityValidations = [
    { validation: value => value.trim() !== "", message: getLabel("city_require") },
    { validation: value => value.length >= 2, message: getLabel("city_min") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("city_space") },
    { validation: value => value.length <= 40, message: getLabel("city_max") }
];

const countyValidations = [
    { validation: value => value.trim() !== "", message: getLabel("county_require") },
    { validation: value => value.length >= 2, message: getLabel("county_min") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("county_space") },
    { validation: value => value.length <= 40, message: getLabel("county_max") }
];

const companyName = [
    { validation: value => value.trim() !== "", message: getLabel("company_require") },
    { validation: value => /^[a-zA-Z0-9/., _'\-`]*$/.test(value), message: getLabel("company_special") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("company_space") }
];

const registerCode = [
    { validation: value => value.trim() !== "", message: getLabel("code_require") },
    { validation: value => /^[a-zA-Z0-9]*$/.test(value), message: getLabel("code_special") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("code_space") }
];

const registerNumber = [
    { validation: value => value.trim() !== "", message: getLabel("number_require") },
    { validation: value => /^[A-Za-z0-9/]*$/.test(value), message: getLabel("number_special") },
    { validation: value => !/\s{3,}/.test(value), message: getLabel("number_space") }
];

function applyValidations(parentId, validations, triggerImmediately) {
    const parentElement = document.getElementById(parentId);
    const inputElement = parentElement.querySelector("input");
    const errorElement = parentElement.querySelector("span");

    function validate() {
        let errorMessage = "";
        for (const validation of validations) {
            if (!validation.validation(inputElement.value)) {
                errorMessage = validation.message;
                break;
            }
        }
        if (errorMessage) {
            parentElement.classList.add("error");
            inputElement.focus();
        } else {
            parentElement.classList.remove("error");
        }
        errorElement.textContent = errorMessage;
    }

    inputElement.addEventListener("input", validate);
    if (triggerImmediately) {
        validate();
    }
}

function validateIndividual() {
    applyValidations("individualBillingCityParent", cityValidations, true);
    applyValidations("individualBillingCountyParent", countyValidations, true);
    applyValidations("individualBillingAddressParent", addressValidations, true);
    applyValidations("individualBillingPhoneParent", phoneValidation, true);
    applyValidations("individualBillingEmailParent", emailValidation, true);
    applyValidations("individualBillingLastNameParent", lastNameValidation, true);
    applyValidations("individualBillingFirstNameParent", firstNameValidation, true);
}

function validateIndividualIdentic() {
    applyValidations("individualShippingCityParent", cityValidations, true);
    applyValidations("individualShippingCountyParent", countyValidations, true);
    applyValidations("individualShippingAddressParent", addressValidations, true);
    applyValidations("individualShippingPhoneParent", phoneValidation, true);
    applyValidations("individualShippingEmailParent", emailValidation, true);
    applyValidations("individualShippingLastNameParent", lastNameValidation, true);
    applyValidations("individualShippingFirstNameParent", firstNameValidation, true);
    validateIndividual();
}

function validateJuridic() {
    applyValidations("registerCodeParent", registerCode, true);
    applyValidations("juridicShippingCountyParent", countyValidations, true);
    applyValidations("registerNumberParent", registerNumber, true);
    applyValidations("companyNameParent", companyName, true);
    applyValidations("juridicShippingCityParent", cityValidations, true);
    applyValidations("juridicShippingAddressParent", addressValidations, true);
    applyValidations("juridicShippingPhoneParent", phoneValidation, true);
    applyValidations("juridicShippingEmailParent", emailValidation, true);
    applyValidations("juridicShippingLastNameParent", lastNameValidation, true);
    applyValidations("juridicShippingFirstNameParent", firstNameValidation, true);
}

function validateJuridicIdentic() {
    validateJuridic();
    applyValidations("juridicBillingCityParent", cityValidations, true);
    applyValidations("juridicBillingCountyParent", countyValidations, true);
    applyValidations("juridicBillingAddressParent", addressValidations, true);
    applyValidations("juridicBillingPhoneParent", phoneValidation, true);
    applyValidations("juridicBillingEmailParent", emailValidation, true);
    applyValidations("juridicBillingLastNameParent", lastNameValidation, true);
    applyValidations("juridicBillingFirstNameParent", firstNameValidation, true);
}

window.addEventListener("update-validation", (event) => {
    const { parentId, newValidation } = event.detail;
    console.log("Update validation for: ", parentId, newValidation);

    let validationArray = window[parentId + "_validations"] || [];

    validationArray.push({
        validation: new Function("value", `return ${newValidation.validation}`),
        message: newValidation.message,
    });

    window[parentId + "_validations"] = validationArray;

    applyValidations(parentId, validationArray, true);
});
