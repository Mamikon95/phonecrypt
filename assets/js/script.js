(function($) {
    $(document).ready(function() {
        var $addForm = $('#add'),
            $retrieveForm = $('#retrieve');
        /**
         * "add phone" form
         */
        $addForm.submit(function submitAdding () {
            var data = $(this).serialize(),
                $status = $addForm.find('.status');
            $status.html('');
            $.post('/add.php', data, function(response) {
                if (!response.success && typeof response.errors !== 'undefined' && response.errors.length) {
                    $status.html(response.errors.join('<br>'));
                } else if (response.success === true) {
                    $status.html('Success!');
                    resetAll();
                } else {
                    $status.html('Error');
                }
            });
        });
        /**
         * "retrieve phone" form
         */
        $retrieveForm.submit(function submitRetrieval () {
            var data = $(this).serialize(),
                $status = $retrieveForm.find('.status');
            $status.html('');
            $.post('/retrieve.php', data, function(response) {
                if (!response.success && typeof response.errors !== 'undefined' && response.errors.length) {
                    $status.html(response.errors.join('<br>'));
                } else if (response.success === true) {
                    $status.html('Success!');
                    resetAll();
                } else {
                    $status.html('Error');
                }
            });
        });
    });

    function resetAll() {
        $('body').find('input').val('');
    }
})(jQuery);