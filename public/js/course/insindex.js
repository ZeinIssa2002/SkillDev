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

function editCard(event, element) {
    event.preventDefault();
    event.stopPropagation();
    const courseId = element.getAttribute('data-course-id');
    window.location.href = `/course/${courseId}/edit`;
}

function deleteCard(event, element) {
    event.preventDefault();
    event.stopPropagation();
    const card = element.closest('.course-card');
    const courseId = element.getAttribute('data-course-id');

    if (confirm('Are you sure you want to delete this course?')) {
        fetch(`/course/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    card.remove();
                } else {
                    alert(data.message || 'An error occurred while deleting the course.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the course.');
            });
    }
}