/**
 * School Result Plugin - Admin Script
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Subject selection handler - update max marks
        $('#subject_id').on('change', function() {
            var fullMarks = $(this).find(':selected').data('full-marks');
            if (fullMarks) {
                $('#marks_obtained').attr('max', fullMarks);
            }
        });
        
        // Confirmation for delete actions
        $('.srp-delete-action').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Photo preview
        $('#student_photo').on('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = '<img src="' + e.target.result + '" class="srp-photo-preview" alt="Preview">';
                    $('#student_photo').after(preview);
                };
                reader.readAsDataURL(file);
            }
        });
        
    });
    
})(jQuery);
