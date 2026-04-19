const authShell = document.querySelector("[data-auth-shell]");
const overlayCopies = document.querySelectorAll(".overlay-copy");
const mobileSwitchLabel = document.querySelector("[data-mobile-switch-label]");
const mobileSwitchButton = document.querySelector("[data-mobile-switch]");
const passwordToggles = document.querySelectorAll("[data-password-toggle]");
const mockForms = document.querySelectorAll("[data-mock-form]");
const socialButtons = document.querySelectorAll("[data-social]");
const toastHost = document.querySelector(".toast-host");
const strengthInput = document.querySelector("[data-strength-source]");
const strengthChecks = {
    length: document.querySelector('[data-strength-check="length"]'),
    uppercase: document.querySelector('[data-strength-check="uppercase"]'),
    number: document.querySelector('[data-strength-check="number"]'),
    symbol: document.querySelector('[data-strength-check="symbol"]'),
};

function showToast(title, text) {
    if (!toastHost) {
        return;
    }

    const toast = document.createElement("div");
    toast.className = "toast";
    toast.innerHTML = `
        <p class="toast__title">${title}</p>
        <p class="toast__text">${text}</p>
    `;

    toastHost.appendChild(toast);

    window.setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(12px)";
        toast.style.transition = "opacity 200ms ease, transform 200ms ease";

        window.setTimeout(() => toast.remove(), 220);
    }, 3200);
}

function updateOverlay(mode) {
    if (!authShell) {
        return;
    }

    authShell.dataset.mode = mode;

    overlayCopies.forEach((copy) => {
        const shouldShow = copy.classList.contains(`overlay-copy--${mode}`);
        copy.classList.toggle("is-visible", shouldShow);
    });

    if (mobileSwitchLabel) {
        mobileSwitchLabel.textContent = mode === "signin"
            ? "Need an account? Sign Up"
            : "Already have an account? Sign In";
    }
}

function toggleMode() {
    if (!authShell) {
        return;
    }

    updateOverlay(authShell.dataset.mode === "signin" ? "signup" : "signin");
}

document.querySelectorAll("[data-switch-mode]").forEach((button) => {
    button.addEventListener("click", () => {
        updateOverlay(button.dataset.switchMode);
    });
});

if (mobileSwitchButton) {
    mobileSwitchButton.addEventListener("click", toggleMode);
}

passwordToggles.forEach((toggle) => {
    toggle.addEventListener("click", () => {
        const input = toggle.parentElement.querySelector("input");
        const isVisible = input.type === "text";
        input.type = isVisible ? "password" : "text";
        toggle.textContent = isVisible ? "Show" : "Hide";
        toggle.setAttribute("aria-label", isVisible ? "Show password" : "Hide password");
    });
});

socialButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const provider = button.dataset.social;
        showToast(`${provider} is ready for hookup`, `This demo keeps social auth mocked for now, but the ${provider} flow can plug in next.`);
    });
});

mockForms.forEach((form) => {
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        if (!form.reportValidity()) {
            return;
        }

        if (form.dataset.mockForm === "reset-password") {
            const password = form.querySelector('input[name="password"]')?.value ?? "";
            const confirm = form.querySelector('input[name="confirm_password"]')?.value ?? "";

            if (password !== confirm) {
                showToast("Passwords do not match", "Please make sure both password fields match before continuing.");
                return;
            }
        }

        const submitButton = form.querySelector(".submit-button");
        const buttonText = submitButton?.textContent ?? "";
        const loadingText = submitButton?.dataset.loadingText ?? buttonText;

        if (submitButton) {
            submitButton.classList.add("is-loading");
            submitButton.innerHTML = `<span class="submit-button__inner">${loadingText}</span>`;
        }

        window.setTimeout(() => {
            if (submitButton) {
                submitButton.classList.remove("is-loading");
                submitButton.innerHTML = `<span class="submit-button__inner">${buttonText}</span>`;
            }

            const formType = form.dataset.mockForm;

            if (formType === "signin") {
                showToast("Signed in", "Welcome back to BazaarHub. Your marketplace dashboard is ready.");
            } else if (formType === "signup") {
                showToast("Account created", "Your BazaarHub account is ready. Start exploring artisan stories.");
                updateOverlay("signin");
            } else if (formType === "forgot-password") {
                showToast("Reset link sent", "A reset link is on its way to your inbox in this demo flow.");
            } else if (formType === "reset-password") {
                showToast("Password updated", "Your password has been reset successfully in this frontend demo.");
            }
        }, 1200);
    });
});

function updateStrengthChecklist() {
    if (!strengthInput) {
        return;
    }

    const value = strengthInput.value;
    const tests = {
        length: value.length >= 8,
        uppercase: /[A-Z]/.test(value),
        number: /\d/.test(value),
        symbol: /[^A-Za-z0-9]/.test(value),
    };

    Object.entries(tests).forEach(([key, passed]) => {
        const item = strengthChecks[key];

        if (item) {
            item.classList.toggle("is-met", passed);
        }
    });
}

if (strengthInput) {
    strengthInput.addEventListener("input", updateStrengthChecklist);
    updateStrengthChecklist();
}

updateOverlay(authShell?.dataset.mode ?? "signin");
