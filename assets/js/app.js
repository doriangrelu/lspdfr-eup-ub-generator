$(document).ready(() => {
    $('input[type="file"]').change(function (e) {
        const fileName = e.target.files[0].name;
        const $labelElement = $(this).closest('.custom-file').find('label');
        $labelElement.html(fileName);
    });
});