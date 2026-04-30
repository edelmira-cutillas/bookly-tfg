$(document).ready(function() {
        $('#userMenuTrigger').hover(
            function() {
                $('#userDropdown').stop(true, true).fadeIn(200).css('display', 'block');
            }, 
            function() {
                $('#userDropdown').stop(true, true).fadeOut(200);
            }
        );
    });