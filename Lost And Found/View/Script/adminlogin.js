document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("adminLoginForm");
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const loginError = document.getElementById("loginError");

    loginForm.addEventListener("submit", function (event) {
        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        loginError.textContent = "";

        if (username === "" || password === "") {
            event.preventDefault();
            loginError.textContent =
                "Please enter your username and password.";
            return;
        }

        if (username.length > 50) {
            event.preventDefault();
            loginError.textContent =
                "Username cannot contain more than 50 characters.";
        }
    });
});