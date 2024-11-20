document.addEventListener("DOMContentLoaded", function () {
    const scrollNav = document.querySelector(".scroll-nav");
    const footer = document.querySelector("footer");

    // Function to check if the footer is in view
    function toggleScrollNav() {
        const footerRect = footer.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        // Check if the footer is visible in the viewport
        if (footerRect.top < windowHeight) {
            scrollNav.classList.add("hidden");
        } else {
            scrollNav.classList.remove("hidden");
        }
    }

    // Listen for scroll events
    window.addEventListener("scroll", toggleScrollNav);
});



document.addEventListener("DOMContentLoaded", function () {
    const galleryImages = document.querySelectorAll(".gallery-image");
    const modalImage = document.getElementById("modalImage");
    const modal = new bootstrap.Modal(document.getElementById("imageModal"));
    const closeModalButton = document.getElementById("closeModalButton");
    const scrollNav = document.querySelector(".scroll-nav");

    let lastScrollPosition = 0;

    // Open modal and display the clicked image
    galleryImages.forEach(image => {
        image.addEventListener("click", function () {
            lastScrollPosition = window.scrollY; // Save scroll position before opening modal

            modalImage.src = this.src; // Set the clicked image as the modal image
            modal.show(); // Show modal

            // Hide the scroll-nav but keep it fixed
            scrollNav.classList.add("hidden");

            // Lock scroll position and disable page scroll
            document.body.classList.add("no-scroll");
            document.body.style.top = `-${lastScrollPosition}px`;
        });
    });

    // Close the modal and restore scroll position
    closeModalButton.addEventListener("click", function () {
        modal.hide(); // Hide modal

        // Show the scroll-nav again while keeping it fixed at the bottom
        scrollNav.classList.remove("hidden");

        // Unlock scroll position and re-enable scrolling
        document.body.classList.remove("no-scroll");
        document.body.style.top = '';
        window.scrollTo(0, lastScrollPosition); // Restore scroll position
    });

    // Clear the image source when the modal is hidden to free up memory (optional)
    document.getElementById("imageModal").addEventListener("hidden.bs.modal", function () {
        modalImage.src = ""; // Clear the image source
        scrollNav.classList.remove("hidden"); // Ensure scroll-nav is shown when modal is hidden
    });
});


