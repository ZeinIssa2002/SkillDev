$(document).ready(function() {
    const menuIcon = $('#menuIcon');
    const dropdownMenu = $('#dropdownMenu');

    menuIcon.click(function() {
        dropdownMenu.toggle();
    });

    // Close dropdown when clicking outside
    $(document).click(function(event) {
        if (!$(event.target).closest('#menuIcon').length && !$(event.target).closest('#dropdownMenu').length) {
            dropdownMenu.hide();
        }
    });
});

function toggleFavorite(event, element) {
    event.preventDefault();
    event.stopPropagation(); // Prevent the card click event from firing
    const courseId = element.getAttribute('data-course-id');

    fetch(`/toggle-favorite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                course_id: courseId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = element.querySelector('i');
                if (data.favorite) {
                    icon.classList.remove('far'); // Remove empty heart
                    icon.classList.add('fas'); // Add filled heart
                    icon.style.color = 'red'; // Change color to red
                } else {
                    icon.classList.remove('fas'); // Remove filled heart
                    icon.classList.add('far'); // Add empty heart
                    icon.style.color = ''; // Reset color
                }
            } else {
                alert(data.message || "An error occurred.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred.");
        });
}

function applyCourse(event, element) {
    event.preventDefault();
    event.stopPropagation(); // Prevent the card click event from firing
    const courseId = element.getAttribute('data-course-id');

    fetch(`/apply-course`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                course_id: courseId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = element.querySelector('i');
                icon.classList.remove('far', 'fa-square'); // Remove empty square
                icon.classList.add('fas', 'fa-check-square'); // Add filled check square
                element.style.pointerEvents = "none"; // Disable further clicks
            } else {
                alert(data.message || "An error occurred.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred.");
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const favoriteButtons = document.querySelectorAll('.favorite-button');
    favoriteButtons.forEach(button => {
        const courseId = button.getAttribute('data-course-id');
        checkFavoriteStatus(courseId, button);
    });

    const applyButtons = document.querySelectorAll('.apply-button');
    applyButtons.forEach(button => {
        const courseId = button.getAttribute('data-course-id');
        checkApplyStatus(courseId, button);
    });
});

function checkFavoriteStatus(courseId, buttonElement) {
    fetch(`/course/${courseId}/favorite-status`)
        .then(response => response.json())
        .then(data => {
            const icon = buttonElement.querySelector('i');
            if (data.favorite) {
                icon.classList.remove('far'); // Empty heart
                icon.classList.add('fas'); // Filled heart
                icon.style.color = 'red'; // Set red color
            } else {
                icon.classList.remove('fas'); // Filled heart
                icon.classList.add('far'); // Empty heart
                icon.style.color = ''; // Reset color
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
}

function checkApplyStatus(courseId, buttonElement) {
    fetch(`/course/${courseId}/apply-status`)
        .then(response => response.json())
        .then(data => {
            const icon = buttonElement.querySelector('i');
            if (data.apply) {
                icon.classList.remove('far', 'fa-square'); // Remove empty square
                icon.classList.add('fas', 'fa-check-square'); // Add filled check square
                buttonElement.style.pointerEvents = "none"; // Disable further clicks
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
}