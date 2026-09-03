document.addEventListener("DOMContentLoaded", function () {
    const searchInput =
        document.getElementById("searchInput");

    const categorySelect =
        document.getElementById("categorySelect");

    const itemsGrid =
        document.getElementById("itemsGrid");

    let searchTimer = null;
    let currentRequest = null;

    function escapeHtml(value) {
        return String(value ?? "").replace(
            /[&<>"']/g,
            function (character) {
                const characters = {
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#039;"
                };

                return characters[character];
            }
        );
    }

    function formatDate(dateValue) {
        if (!dateValue) {
            return "Date unavailable";
        }

        const date = new Date(
            dateValue + "T00:00:00"
        );

        if (Number.isNaN(date.getTime())) {
            return dateValue;
        }

        return date.toLocaleDateString(
            "en-US",
            {
                month: "short",
                day: "2-digit",
                year: "numeric"
            }
        );
    }

    function createItemCard(item) {
        const itemId = Number(item.id);

        const claimCount =
            Number(item.claim_count);

        const claimText =
            claimCount === 1
                ? "person claimed this"
                : "people claimed this";

        let imageHtml =
            "<span>NO PHOTO</span>";

        if (item.photo) {
            const photoName = encodeURIComponent(
                String(item.photo)
                    .split("/")
                    .pop()
            );

            imageHtml = `
                <img
                    src="../uploads/${photoName}"
                    alt="${escapeHtml(item.item_title)}">
            `;
        }

        return `
            <div class="item-card">

                <div class="pin"></div>

                <div class="item-image">
                    ${imageHtml}
                </div>

                <div class="category">
                    ${escapeHtml(
                        String(item.category).toUpperCase()
                    )}
                </div>

                <div class="item-title">
                    ${escapeHtml(item.item_title)}
                </div>

                <div class="item-info">

                    <div>
                        📍 &nbsp;
                        ${escapeHtml(
                            item.location_found
                        )}
                    </div>

                    <div>
                        🗓️ &nbsp;
                        Found ${escapeHtml(
                            formatDate(item.date_found)
                        )}
                    </div>

                    <div class="item-description">
                        ${escapeHtml(item.description)}
                    </div>

                </div>

                <div class="card-bottom">

                    <div class="claimed">
                        ${claimCount} ${claimText}
                    </div>

                    <a
                        class="mine-btn"
                        href="ClaimForm.php?item_id=${itemId}">
                        This is mine
                    </a>

                </div>

            </div>
        `;
    }

    function displayItems(items) {
        if (!Array.isArray(items) || items.length === 0) {
            itemsGrid.innerHTML = `
                <div class="no-items">
                    No matching found items.
                </div>
            `;

            return;
        }

        itemsGrid.innerHTML =
            items.map(createItemCard).join("");

        const images =
            itemsGrid.querySelectorAll(
                ".item-image img"
            );

        images.forEach(function (image) {
            image.addEventListener(
                "error",
                function () {
                    const imageContainer =
                        image.parentElement;

                    image.remove();

                    imageContainer.textContent =
                        "NO PHOTO";
                }
            );
        });
    }

    function searchItems() {
        const search =
            searchInput.value.trim();

        const category =
            categorySelect.value;

        if (currentRequest) {
            currentRequest.abort();
        }

        currentRequest =
            new AbortController();

        const query = new URLSearchParams({
            search: search,
            category: category
        });

        itemsGrid.innerHTML = `
            <div class="loading-message">
                Searching items...
            </div>
        `;

        fetch(
            "../Controller/FoundItem.php?" +
            query.toString(),
            {
                method: "GET",
                headers: {
                    Accept: "application/json"
                },
                signal: currentRequest.signal
            }
        )
            .then(function (response) {
                return response
                    .json()
                    .then(function (data) {
                        if (!response.ok) {
                            throw new Error(
                                data.message ||
                                "Search failed."
                            );
                        }

                        return data;
                    });
            })
            .then(function (data) {
                if (!data.success) {
                    throw new Error(
                        data.message ||
                        "Search failed."
                    );
                }

                displayItems(data.items);
            })
            .catch(function (error) {
                if (error.name === "AbortError") {
                    return;
                }

                itemsGrid.innerHTML = `
                    <div class="search-error">
                        ${escapeHtml(error.message)}
                    </div>
                `;
            });
    }

    searchInput.addEventListener(
        "input",
        function () {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(
                searchItems,
                300
            );
        }
    );

    categorySelect.addEventListener(
        "change",
        searchItems
    );

    /* Initial AJAX load */
    searchItems();
});