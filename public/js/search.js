document.addEventListener('DOMContentLoaded', function() {
    const menuIcon = document.getElementById('menuIcon');
    const dropdownMenu = document.getElementById('dropdownMenu');

    // Toggle dropdown menu on menu icon click
    menuIcon.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event from bubbling up
        console.log("Menu icon clicked!"); // Debugging
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Hide dropdown menu when clicking outside
    document.addEventListener('click', function() {
        console.log("Document clicked!"); // Debugging
        dropdownMenu.style.display = 'none';
    });

    // Prevent dropdown menu from closing when clicking inside it
    dropdownMenu.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});