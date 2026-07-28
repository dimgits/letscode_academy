// =========================================================
// LETSCODE! SIGN UP PAGE SCRIPT
// =========================================================

document.addEventListener("DOMContentLoaded", function () {

  const form = document.getElementById("signupForm");
  const signupBtn = document.getElementById("signupBtn");
  const btnLabel = signupBtn.querySelector(".btn-label");
  const btnSpinner = signupBtn.querySelector(".btn-spinner");

  const successModal = document.getElementById("successModal");
  const closeModalBtn = document.getElementById("closeModalBtn");

  const fields = {
    fullName: document.getElementById("fullName"),
    email: document.getElementById("email"),
    phone: document.getElementById("phone"),
    age: document.getElementById("age"),
    course: document.getElementById("course"),
  };

  // -----------------------------------------
  // Generic validation helpers
  // -----------------------------------------

  function markInvalid(field) {
    field.classList.add("is-invalid");
  }

  function markValid(field) {
    field.classList.remove("is-invalid");
  }

  function isValidPhone(value) {
    // Accepts digits, spaces, +, -, ( ) with at least 8 digits total
    const digitsOnly = value.replace(/\D/g, "");
    return digitsOnly.length >= 8 && digitsOnly.length <= 15;
  }

  // -----------------------------------------
  // Email validation (format + Gmail-only + duplicate check)
  // -----------------------------------------

  const emailField = fields.email;
  const emailFeedback = emailField.parentElement.querySelector(".invalid-feedback");

  const EMAIL_MESSAGES = {
    format: "Please enter a valid email address.",
    gmailOnly: "email credentials be used at this time is only @gmail!",
    duplicate: "Email is already registered. Please try another one.",
  };

  function isValidEmailFormat(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function setEmailError(message) {
    emailField.classList.add("is-invalid");
    if (emailFeedback) emailFeedback.textContent = message;
  }

  function clearEmailError() {
    emailField.classList.remove("is-invalid");
    if (emailFeedback) emailFeedback.textContent = EMAIL_MESSAGES.format;
  }

  // Cache the last checked value so we don't hammer the server if
  // blur + submit both trigger a check for the same unchanged value.
  let lastCheckedEmail = null;
  let lastCheckResult = null;

  async function checkEmailAvailability(value) {
    if (lastCheckedEmail === value && lastCheckResult) {
      return lastCheckResult;
    }

    try {
      const res = await fetch("check_email.php?email=" + encodeURIComponent(value));
      const data = await res.json();
      lastCheckedEmail = value;
      lastCheckResult = data;
      return data;
    } catch (err) {
      // If the live check itself fails (offline, etc.), don't block the
      // user here -- register.php still validates this authoritatively.
      return { validDomain: true, registered: false };
    }
  }

  async function validateEmailField() {
    const value = emailField.value.trim();

    if (!isValidEmailFormat(value)) {
      setEmailError(EMAIL_MESSAGES.format);
      return false;
    }

    const result = await checkEmailAvailability(value);

    if (!result.validDomain) {
      setEmailError(EMAIL_MESSAGES.gmailOnly);
      return false;
    }

    if (result.registered) {
      setEmailError(EMAIL_MESSAGES.duplicate);
      return false;
    }

    clearEmailError();
    return true;
  }

  // Check as soon as the user leaves the email field, so they see the
  // warning immediately instead of only after hitting Submit.
  emailField.addEventListener("blur", () => {
    if (emailField.value.trim()) {
      validateEmailField();
    }
  });

  // -----------------------------------------
  // Full form validation (runs on submit)
  // -----------------------------------------

  async function validateForm() {
    let isValid = true;

    // Full name
    if (fields.fullName.value.trim().length < 3) {
      markInvalid(fields.fullName);
      isValid = false;
    } else {
      markValid(fields.fullName);
    }

    // Email (format + Gmail-only + duplicate)
    const emailValid = await validateEmailField();
    if (!emailValid) isValid = false;

    // Phone
    if (!isValidPhone(fields.phone.value.trim())) {
      markInvalid(fields.phone);
      isValid = false;
    } else {
      markValid(fields.phone);
    }

    // Age
    const ageValue = parseInt(fields.age.value, 10);
    if (isNaN(ageValue) || ageValue < 10 || ageValue > 100) {
      markInvalid(fields.age);
      isValid = false;
    } else {
      markValid(fields.age);
    }

    // Course
    if (!fields.course.value) {
      markInvalid(fields.course);
      isValid = false;
    } else {
      markValid(fields.course);
    }

    return isValid;
  }

  // Clear invalid state as the user types/selects (email has its own
  // dedicated handling above, so skip it here to avoid wiping out its
  // custom message on every keystroke).
  Object.values(fields).forEach((field) => {
    if (field === emailField) return;
    const eventName = field.tagName === "SELECT" ? "change" : "input";
    field.addEventListener(eventName, () => markValid(field));
  });

  emailField.addEventListener("input", () => {
    emailField.classList.remove("is-invalid");
  });

  // -----------------------------------------
  // Modal helpers
  // -----------------------------------------

  function openModal() {
    successModal.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    successModal.classList.remove("active");
    document.body.style.overflow = "";
  }

  closeModalBtn.addEventListener("click", closeModal);

  successModal.addEventListener("click", (e) => {
    if (e.target === successModal) closeModal();
  });

  // -----------------------------------------
  // Button loading state
  // -----------------------------------------

  function setLoading(isLoading) {
    signupBtn.disabled = isLoading;
    btnLabel.classList.toggle("d-none", isLoading);
    btnSpinner.classList.toggle("d-none", !isLoading);
  }

  // -----------------------------------------
  // Submit handler
  // -----------------------------------------
  // Always prevent the default submit first, run full validation
  // (including the async email checks), and only then submit the form
  // for real -- register.php still re-validates everything server-side
  // as the source of truth.

  form.addEventListener("submit", async function (e) {

    e.preventDefault();

    setLoading(true);

    const isValid = await validateForm();

    if (!isValid) {
      setLoading(false);
      const firstInvalid = form.querySelector(".is-invalid");
      if (firstInvalid) firstInvalid.focus();
      return;
    }

    form.submit();
  });
});
