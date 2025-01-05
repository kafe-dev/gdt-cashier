function timeConverter() {
    let timeSpans = $('.x-has-time-converter');

    if (timeSpans.length > 0) {
        timeSpans.each(function () {
            if (Date.parse($(this).html())) {
                const time = (new Date($(this).html())).toUTCString();

                let convertBtn = document.createElement('i');
                let localTimeSpan = document.createElement('span');

                $(convertBtn).attr('type', 'button');
                $(convertBtn).attr('class', 'fa fa-clock text-info x-convert-time-btn');
                $(convertBtn).attr('title', 'Convert to UTC');
                $(convertBtn).css('margin-left', '2px');

                $(localTimeSpan).attr('class', 'x-local-time text-danger fw-bold');
                $(localTimeSpan).text(' | ' + time);
                $(localTimeSpan).css('display', 'none');

                $(this).append(localTimeSpan);
                $(this).append(convertBtn);

                $(convertBtn).click(() => {
                    $(localTimeSpan).toggle();
                });
            }
        });
    }
}

function activeMenu() {
    let activeItems = $('.active');

    if (activeItems.length > 0) {
        activeItems.each((idx, elm) => {
            if ($(elm).hasClass('nav-link')) {
                $(elm).addClass('text-primary');
                $(elm).parent().css('background-color', 'rgba(42, 118, 244, 0.025)');
            }
        });
    }
}

function dropdownToggle() {
    let dropdownBtn = $('.dropdown-toggle-btn');

    if (dropdownBtn.length > 0) {
        dropdownBtn.each((inx, elm) => {
            $(elm).click(() => {
                let dataXToggle = $(elm).attr('data-x-toggle')

                $(dataXToggle).toggle();
            });
        });
    }
}

$(document).ready(() => {
    activeMenu();
    timeConverter();
    dropdownToggle();
});
