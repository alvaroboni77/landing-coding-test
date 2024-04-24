$(document).ready(function() {
    $('.show-more').click(function() {
        var target = $(this).data('target');
        $(target).slideDown();
        $(this).hide();
        $(this).siblings('.show-less').show();
    });

    $('.show-less').click(function() {
        var target = $(this).data('target');
        $(target).slideUp();
        $(this).hide();
        $(this).siblings('.show-more').show();
    });
})