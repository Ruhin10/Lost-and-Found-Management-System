 const postFoundButton =
    document.getElementById("postFoundBtn");

const browseFoundButton =
    document.getElementById("browseFoundBtn");

const pageMessage =
    document.getElementById("pageMessage");


postFoundButton.addEventListener(
    "click",
    function () {
        pageMessage.textContent =
            "Opening the found-item form...";

        window.location.href =
            "PostItem.php";
    }
);


browseFoundButton.addEventListener(
    "click",
    function () {
        pageMessage.textContent =
            "Opening found items...";

        window.location.href =
            "ShowList.php";
    }
);