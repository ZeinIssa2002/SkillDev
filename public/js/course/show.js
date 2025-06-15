$(document).ready(function() {
    const stars = $('.rating .star');

    // Function to update star colors based on the rating
    const updateStars = (rating) => {
        stars.each(function() {
            $(this).toggleClass('selected', $(this).data('value') <= rating);
        });
    };

    // Fetch the user's current rating (if any)
    $.get('/ratings/{{ $course->id }}', function(response) {
        if (response.rating) {
            updateStars(response.rating);
            $('.rating').data('user-rating', response.rating);
        }
    });

    // Handle star click event to submit or update the rating
    stars.on('click', function() {
        const rating = $(this).data('value');
        $.post('/rate/{{ $course->id }}', {
            rating: rating,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(response) {
            if (response.success) {
                updateStars(rating);
                $('.rating').data('user-rating', rating);
            } else {
                alert('Failed to submit rating. Please try again.');
            }
        });
    });

    // Fetch the average rating for this course
    $.get('/ratings/average/{{ $course->id }}', function(response) {
        if (response.success) {
            $('#average-rating-value').text(response.average_rating);
        } else {
            $('#average-rating-value').text('N/A');
        }
    });
});