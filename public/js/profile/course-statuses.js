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
                    element.classList.add('active'); // Add active class to button
                    showSuccessMessage('Course added to favorites successfully');
                } else {
                    icon.classList.remove('fas'); // Remove filled heart
                    icon.classList.add('far'); // Add empty heart
                    element.classList.remove('active'); // Remove active class from button
                    showSuccessMessage('Course removed from favorites successfully');
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
                showSuccessMessage('Course applied successfully');
            } else {
                alert(data.message || "An error occurred.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred.");
        });
}

// Function to show success message
function showSuccessMessage(message) {
    // Create success message element if it doesn't exist
    let successMessage = document.getElementById('success-message');
    if (!successMessage) {
        successMessage = document.createElement('div');
        successMessage.id = 'success-message';
        successMessage.className = 'alert alert-success';
        successMessage.style.position = 'fixed';
        successMessage.style.top = '100px'; // Position below header
        successMessage.style.left = '50%';
        successMessage.style.transform = 'translateX(-50%)';
        successMessage.style.zIndex = '999';
        successMessage.style.maxWidth = '90%';
        successMessage.style.width = '600px';
        successMessage.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        successMessage.style.opacity = '0';
        successMessage.style.transition = 'opacity 0.3s ease-in-out';
        document.body.appendChild(successMessage);
    }
    
    // Set message with icon and show
    successMessage.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
    successMessage.style.opacity = '1';
    
    // Hide after 3 seconds
    setTimeout(() => {
        successMessage.style.opacity = '0';
    }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    // Get all course cards
    const courseCards = document.querySelectorAll('.course-card');
    
    // If there are no course cards, no need to make the API call
    if (courseCards.length === 0) return;
    
    // Create a map of course IDs to their respective buttons
    const courseMap = {};
    
    // Collect all course IDs and their buttons
    courseCards.forEach(card => {
        const favoriteButton = card.querySelector('.favorite-button');
        const applyButton = card.querySelector('.apply-button');
        
        if (!favoriteButton || !applyButton) return;
        
        const courseId = favoriteButton.getAttribute('data-course-id');
        
        courseMap[courseId] = {
            favoriteButton,
            applyButton
        };
    });
    
    // Make a single API call to get statuses for all courses
    fetch('/courses/all-statuses')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error("Error fetching course statuses:", data.message);
                return;
            }
            
            // Process the statuses for each course
            const statuses = data.statuses;
            
            // Update UI for each course based on its status
            Object.keys(courseMap).forEach(courseId => {
                const buttons = courseMap[courseId];
                const status = statuses[courseId] || { apply: false, favorite: false };
                
                // Update favorite button
                if (buttons.favoriteButton) {
                    const favoriteIcon = buttons.favoriteButton.querySelector('i');
                    if (status.favorite) {
                        favoriteIcon.classList.remove('far');
                        favoriteIcon.classList.add('fas');
                        favoriteIcon.style.color = 'red';
                        buttons.favoriteButton.classList.add('active');
                    } else {
                        favoriteIcon.classList.remove('fas');
                        favoriteIcon.classList.add('far');
                        favoriteIcon.style.color = '';
                        buttons.favoriteButton.classList.remove('active');
                    }
                }
                
                // Update apply button
                if (buttons.applyButton) {
                    const applyIcon = buttons.applyButton.querySelector('i');
                    if (status.apply) {
                        applyIcon.classList.remove('far', 'fa-square');
                        applyIcon.classList.add('fas', 'fa-check-square');
                        buttons.applyButton.classList.add('active');
                        buttons.applyButton.style.pointerEvents = "none";
                    }
                }
            });
        })
        .catch(error => console.error("Error fetching all course statuses:", error));
});
