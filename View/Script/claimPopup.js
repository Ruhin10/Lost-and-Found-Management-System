const claimsModal =
    document.getElementById("claimsModal");

const claimsContent =
    document.getElementById("claimsContent");

function viewClaims(itemId) {
    claimsModal.classList.add("show");

    claimsContent.innerHTML =
        '<div class="loading-message">Loading claims...</div>';

    fetch(
        "../Controller/ViewClaims.php?item_id=" +
        encodeURIComponent(itemId)
    )
        .then(function (response) {
            if (!response.ok) {
                throw new Error("Unable to load claims.");
            }

            return response.text();
        })
        .then(function (html) {
            claimsContent.innerHTML = html;
        })
        .catch(function () {
            claimsContent.innerHTML =
                '<div class="popup-error">Unable to load claims.</div>';
        });
}

function closeClaimsModal() {
    claimsModal.classList.remove("show");
    claimsContent.innerHTML = "";
}

claimsModal.addEventListener("click", function (event) {
    if (event.target === claimsModal) {
        closeClaimsModal();
    }
});

document.addEventListener("keydown", function (event) {
    if (
        event.key === "Escape" &&
        claimsModal.classList.contains("show")
    ) {
        closeClaimsModal();
    }
});

async function openEditModal(itemId) {
    const modal = document.getElementById("editModal");

    if (!modal) {
        alert("Edit popup HTML was not found.");
        return;
    }

    modal.classList.add("show");

    try {
        const response = await fetch(
            "../Controller/GetItem.php?id=" +
            encodeURIComponent(itemId)
        );

        const responseText = await response.text();

        let data;

        try {
            data = JSON.parse(responseText);
        } catch (error) {
            throw new Error(
                "GetItem.php did not return valid JSON: " +
                responseText
            );
        }

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || "Unable to load item."
            );
        }

        const item = data.item;

        document.getElementById("editItemId").value =
            item.id ?? "";

        document.getElementById("editItemTitle").value =
            item.item_title ?? "";

        document.getElementById("editCategory").value =
            item.category ?? "";

        document.getElementById("editDateFound").value =
            item.date_found ?? "";

        document.getElementById("editLocation").value =
            item.location_found ?? "";

        document.getElementById("editFinderName").value =
            item.finder_name ?? "";

        document.getElementById("editContact").value =
            item.contact ?? "";

        document.getElementById("editStatus").value =
            item.status ?? "Pending";

        document.getElementById("editDescription").value =
            item.description ?? "";

    } catch (error) {
        console.error(error);
        alert(error.message);
        closeEditModal();
    }
}


function closeEditModal() {
    const modal = document.getElementById("editModal");

    if (modal) {
        modal.classList.remove("show");
    }
}


// Close by clicking outside
window.addEventListener("click", function (event) {
    if (event.target.classList.contains("popup-overlay")) {
        event.target.classList.remove("show");
    }
});


// Close with Escape
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeClaimsModal();
        closeEditModal();
    }
});