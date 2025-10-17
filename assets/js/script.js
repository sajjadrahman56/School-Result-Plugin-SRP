/**
 * School Result Plugin - Frontend Script
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Smooth animations for result display
        $('.srp-student-result, .srp-term-rankings, .srp-student-card').each(function() {
            $(this).css('opacity', 0).animate({ opacity: 1 }, 500);
        });
        
        // Highlight top 3 positions
        $('.srp-position-1, .srp-position-2, .srp-position-3').closest('tr').addClass('srp-highlight');
        
    });
    
})(jQuery);
