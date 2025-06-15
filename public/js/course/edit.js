$(document).ready(function() {
    const menuIcon = $('#menuIcon');
    const dropdownMenu = $('#dropdownMenu');

    // عند النقر على أيقونة القائمة
    menuIcon.click(function(event) {
        event.stopPropagation(); // منع انتشار الحدث
        dropdownMenu.toggle(); // تبديل عرض القائمة
    });

    // إغلاق القائمة عند النقر خارجها
    $(document).click(function(event) {
        if (!$(event.target).closest('#menuIcon').length && !$(event.target).closest('#dropdownMenu').length) {
            dropdownMenu.hide();
        }
    });

    // منع إغلاق القائمة عند النقر داخلها
    dropdownMenu.click(function(event) {
        event.stopPropagation();
    });
});

// عرض الصورة المختارة داخل الدائرة
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('previewImage');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // إظهار الصورة
        };

        reader.readAsDataURL(input.files[0]);
    }
}