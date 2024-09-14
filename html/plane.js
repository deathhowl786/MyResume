$(document).ready(function() {
    function flyPlane() {
        $('#plane').animate({
            left: '100vw'
        }, 5000, function() {
            // Reset position when animation completes
            $('#plane').css('left', '-100px');
            flyPlane(); // Restart the animation for continuous flying
        });
    }

    flyPlane();
});
