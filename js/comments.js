jQuery(document).ready(function() {
    jQuery('#commentform').submit(function() {
        var comment_author = jQuery('#author').val();
        var comment_email = jQuery('#email').val();
        var comment_content = jQuery('#comment').val();
        var email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        jQuery('.comment-form-error').remove();

        if(jQuery('#author').length > 0 && comment_author === '') {
            jQuery('#author').after('<p class="comment-form-error text-danger">Please enter your name.</p>');
            return false;
        }

        if(jQuery('#email').length > 0 && comment_email === '') {
            jQuery('#email').after('<p class="comment-form-error text-danger">Please enter your email address.</p>');
            return false;
        } else if(jQuery('#email').length > 0 && !email_regex.test(comment_email)) {
            jQuery('#email').after('<p class="comment-form-error text-danger">Please enter a valid email address.</p>');
            return false;
        }

        if(comment_content === '') {
            jQuery('#comment').after('<p class="comment-form-error text-danger">Please enter your comment.</p>');
            return false;
        }
    });
});
