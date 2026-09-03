const claimForm =
    document.getElementById("claimForm");

const claimantName =
    document.getElementById("claimantName");

const claimantContact =
    document.getElementById("claimantContact");

const claimDescription =
    document.getElementById("claimDescription");

const clientError =
    document.getElementById("clientError");


claimForm.addEventListener(
    "submit",
    function (event) {
        const nameValue =
            claimantName.value.trim();

        const contactValue =
            claimantContact.value.trim();

        const descriptionValue =
            claimDescription.value.trim();

        let errorMessage = "";

        if (nameValue.length < 3) {
            errorMessage =
                "Your name must contain at least 3 characters.";
        } else if (contactValue.length < 5) {
            errorMessage =
                "Please enter valid contact information.";
        } else if (descriptionValue.length < 10) {
            errorMessage =
                "Please provide more details to prove ownership.";
        }

        if (errorMessage !== "") {
            event.preventDefault();

            clientError.textContent =
                errorMessage;

            clientError.style.display =
                "block";

            return;
        }

        clientError.textContent = "";
        clientError.style.display = "none";
    }
);