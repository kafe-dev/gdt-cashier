$(document).ready(() => {
    let activeItems = $('.active');

    if (activeItems.length > 0) {
        activeItems.each((idx, elm) => {
            if ($(elm).hasClass('nav-link')) {
                $(elm).addClass('text-primary');
                $(elm).parent().css('background-color', 'rgba(42, 118, 244, 0.025)');
            }
        });
    }
});
