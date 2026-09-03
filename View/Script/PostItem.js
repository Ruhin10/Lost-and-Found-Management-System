const postItemForm =
    document.getElementById("postItemForm");

const itemTitle =
    document.getElementById("itemTitle");

const category =
    document.getElementById("category");

const dateFound =
    document.getElementById("dateFound");

const locationFound =
    document.getElementById("locationFound");

const finderName =
    document.getElementById("finderName");

const contact =
    document.getElementById("contact");

const description =
    document.getElementById("description");

const photo =
    document.getElementById("photo");

const formError =
    document.getElementById("formError");

const imagePreview =
    document.getElementById("imagePreview");

const imagePreviewContainer =
    document.getElementById("imagePreviewContainer");


const today = new Date().toISOString().split("T")[0];

dateFound.max = today;


function showError(message) {
    formError.textContent = message;
    formError.style.display = "block";
}


function hideError() {
    formError.textContent = "";
    formError.style.display = "none";
}


photo.addEventListener("change", function () {
    hideError();

    const file = photo.files[0];

    if (!file) {
        imagePreview.src = "";
        imagePreviewContainer.style.display = "none";
        return;
    }

    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    const maximumSize = 5 * 1024 * 1024;

    if (!allowedTypes.includes(file.type)) {
        photo.value = "";
        imagePreviewContainer.style.display = "none";

        showError(
            "Only JPG, PNG and WEBP images are allowed."
        );

        return;
    }

    if (file.size > maximumSize) {
        photo.value = "";
        imagePreviewContainer.style.display = "none";

        showError(
            "The selected image must be smaller than 5 MB."
        );

        return;
    }

    imagePreview.src = URL.createObjectURL(file);
    imagePreviewContainer.style.display = "block";
});


postItemForm.addEventListener(
    "submit",
    function (event) {
        hideError();

        if (itemTitle.value.trim().length < 3) {
            event.preventDefault();

            showError(
                "Item title must contain at least 3 characters."
            );

            return;
        }

        if (category.value === "") {
            event.preventDefault();

            showError("Please select a category.");

            return;
        }

        if (dateFound.value === "") {
            event.preventDefault();

            showError("Please select the date found.");

            return;
        }

        if (dateFound.value > today) {
            event.preventDefault();

            showError(
                "The found date cannot be in the future."
            );

            return;
        }

        if (locationFound.value.trim().length < 2) {
            event.preventDefault();

            showError(
                "Please enter the location where the item was found."
            );

            return;
        }

        if (finderName.value.trim().length < 3) {
            event.preventDefault();

            showError(
                "Please enter the finder's full name."
            );

            return;
        }

        if (contact.value.trim().length < 5) {
            event.preventDefault();

            showError(
                "Please enter valid contact information."
            );

            return;
        }

        if (description.value.trim().length < 10) {
            event.preventDefault();

            showError(
                "Description must contain at least 10 characters."
            );
        }
    }
);