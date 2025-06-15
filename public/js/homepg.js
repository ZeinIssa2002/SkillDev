
$(document).ready(function() {
    const menuIcon = $('#menuIcon');
    const dropdownMenu = $('#dropdownMenu');

    menuIcon.click(function() {
        dropdownMenu.toggle();
    });

    const stars = $('.rating .star');

    const updateStars = (rating) => {
        stars.each(function() {
            $(this).toggleClass('selected', $(this).data('value') <= rating);
        });
    };

    $.get('/rating', function(response) {
        if (response.rating) {
            updateStars(response.rating);
        }
    });

    stars.on('click', function() {
        const rating = $(this).data('value');
        $.post('/rate', {
            rating: rating,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(response) {
            if (response.success) {
                updateStars(rating);
            } else {
                alert('Failed to submit rating. Please try again.');
            }
        });
    });

    $.get('/ratings/average', function(response) {
        if (response.success) {
            $('#average-rating-value').text(response.average_rating);
        } else {
            $('#average-rating-value').text('N/A');
        }
    });
});
