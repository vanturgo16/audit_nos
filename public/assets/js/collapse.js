$(document).on('click', '.toggle-collapse', function () {
    const targetSelector = $(this).attr('data-target');
    const $targetElement = $(targetSelector);
    $targetElement.toggleClass('show');

    const $arrowIcon = $(this).find('.arrow-icon');
    $arrowIcon.toggleClass('rotate-up');
});