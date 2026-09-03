<?php

include_once __DIR__ . '/../Controller/FoundItem.php';

?>
<html >

<head>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Browse Found Items</title>

    <link rel="stylesheet" href="Style/showlist.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Special+Elite&family=IBM+Plex+Mono:wght@400;500;600&family=Public+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body>


<nav class="navbar">

    <a href="landing.php" class="navbar-logo">

        <span class="logo-icon">LF</span>

        <span class="logo-text">
            <span class="logo-title">Lost &amp; Found</span>
            <span class="logo-subtitle">MANAGEMENT SYSTEM</span>
        </span>

    </a>

    <div class="nav-links">

        <a href="landing.php" class="nav-link">
            Home
        </a>

        <a href="ShowList.php" class="nav-link active">
            Found Items
        </a>

        <a href="PostItem.php" class="nav-link">
            Post Found Item
        </a>

        <a href="SolvedCase.php" class="nav-link">
            Solved Cases
        </a>

    </div>

    <a href="AdminLogin.php" class="admin-login-btn">
        Admin Login
    </a>

</nav>
<!-- Successful claim message -->
<?php if (
    isset($_GET['message']) &&
    $_GET['message'] === 'claim_submitted'
): ?>

    <div class="success-message">
        Your claim was submitted successfully.
    </div>

<?php endif; ?>

    <div class="found-container">

        <div class="page-title">
            Browse Found Items
        </div>

        <div class="divider"></div>

        <!-- Search area -->
        <div class="search-area">

            <div class="search-box">

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search by item, location or description">

            </div>

            <div class="category-box">

                <select id="categorySelect">

                    <option value="all">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="bags">Bags</option>
                    <option value="documents">Documents</option>
                    <option value="accessories">Accessories</option>
                    <option value="clothing">Clothing</option>
                    <option value="personal items">Personal Items</option>
                    <option value="stationery">Stationery</option>

                </select>

            </div>

        </div>

        <!-- Items -->
        <div class="items-grid" id="itemsGrid">

            <?php if (!empty($items)): ?>

                <?php foreach ($items as $item): ?>

                    <?php

                    $itemId = (int) $item['id'];

                    $category = strtolower(
                        trim($item['category'])
                    );

                    $formattedDate = date(
                        "M d, Y",
                        strtotime($item['date_found'])
                    );

                    $searchText = strtolower(
                        $item['item_title'] . ' ' .
                            $item['description'] . ' ' .
                            $item['location_found']
                    );

                    ?>

                    <div
                        class="item-card"
                        data-search="<?= htmlspecialchars(
                                            $searchText,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                        data-category="<?= htmlspecialchars(
                                            $category,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>">

                        <div class="pin"></div>

                        <!-- Image -->
                        <div class="item-image">

                            <?php if (!empty($item['photo'])): ?>

                                <img
                                    src="../uploads/<?= htmlspecialchars(
                                                        $item['photo'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                                $item['item_title'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                    onerror="showNoPhoto(this)">

                            <?php else: ?>

                                <span>NO PHOTO</span>

                            <?php endif; ?>

                        </div>

                        <!-- Category -->
                        <div class="category">

                            <?= htmlspecialchars(
                                strtoupper($item['category']),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                        <!-- Item title -->
                        <div class="item-title">

                            <?= htmlspecialchars(
                                $item['item_title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                        <!-- Item information -->
                        <div class="item-info">

                            <div>
                                📍 &nbsp;

                                <?= htmlspecialchars(
                                    $item['location_found'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </div>

                            <div>
                                🗓️ &nbsp;

                                Found <?= htmlspecialchars(
                                            $formattedDate,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                            </div>

                            <div class="item-description">

                                <?= htmlspecialchars(
                                    $item['description'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        </div>

                        <!-- Card bottom -->
                        <div class="card-bottom">

                            <div class="claimed">

                                <?= (int) $item['claim_count'] ?>

                                <?= (int) $item['claim_count'] === 1
                                    ? " person claimed this"
                                    : " people claimed this" ?>

                            </div>

                            <a
                                class="mine-btn"
                                href="ClaimForm.php?item_id=<?= (int) $item['id'] ?>">
                                This is mine
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="no-items">
                    No found items available.
                </div>

            <?php endif; ?>

        </div>

        <div
            id="filterNoItems"
            class="no-items"
            style="display: none;">

            No matching items found.

        </div>

    </div>

    <script>
        const searchInput =
            document.getElementById("searchInput");

        const categorySelect =
            document.getElementById("categorySelect");

        const itemCards =
            document.querySelectorAll(".item-card");

        const filterNoItems =
            document.getElementById("filterNoItems");


        function filterItems() {
            const search =
                searchInput.value.toLowerCase().trim();

            const selectedCategory =
                categorySelect.value.toLowerCase();

            let visibleItems = 0;

            itemCards.forEach(function(card) {
                const searchText =
                    card.dataset.search || "";

                const cardCategory =
                    card.dataset.category || "";

                const searchMatches =
                    searchText.includes(search);

                const categoryMatches =
                    selectedCategory === "all" ||
                    cardCategory === selectedCategory;

                if (searchMatches && categoryMatches) {
                    card.style.display = "";
                    visibleItems++;
                } else {
                    card.style.display = "none";
                }
            });

            if (visibleItems === 0 && itemCards.length > 0) {
                filterNoItems.style.display = "block";
            } else {
                filterNoItems.style.display = "none";
            }
        }


        function showNoPhoto(image) {
            const imageContainer = image.parentElement;

            image.remove();
            imageContainer.textContent = "NO PHOTO";
        }


        searchInput.addEventListener(
            "input",
            filterItems
        );

        categorySelect.addEventListener(
            "change",
            filterItems
        );
    </script>

</body>

</html>