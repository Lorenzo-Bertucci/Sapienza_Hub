document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("search-button").addEventListener("click", function () {
        const searchInput = document.getElementById("search-input").value.trim();
        if (searchInput) {
            window.location.href = `html/corsi.php?search=${encodeURIComponent(searchInput)}`;
        }
    });

    document.getElementById("search-input").addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            const searchInput = document.getElementById("search-input").value.trim();
            if (searchInput) {
                window.location.href = `html/corsi.php?search=${encodeURIComponent(searchInput)}`;
            }
        }
    });
});