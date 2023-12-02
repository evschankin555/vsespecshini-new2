jQuery(document).ready(function($) {
    // Функция для удаления атрибута "required" и звездочки
    function removeRequiredAndStar(field) {
        field.find('label abbr').remove();
        field.find('input').removeAttr('required');
    }

    // Ожидаем, пока DOM станет доступным
    var waitForDOM = setInterval(function() {
        var billingPostcodeField = $('#billing_postcode_field');
        if (billingPostcodeField.length > 0) {
            clearInterval(waitForDOM);

            // Убираем required и звездочку из поля "billing_postcode"
            removeRequiredAndStar(billingPostcodeField);

            // Определите список полей, для которых вы хотите убрать required и звездочки
            var fieldsToChange = [
                'billing_state',
                'billing_city',
                'billing_address_1',
                'billing_email',
                'shipping_postcode',
                'shipping_state',
                'shipping_city',
                'shipping_address_1'
            ];

            // Переберите поля и уберите required и звездочки
            fieldsToChange.forEach(function(field) {
                removeRequiredAndStar($('#' + field + '_field'));
            });

            // Обновите чекаут форму
            $('body').trigger('update_checkout');
        }
    }, 100); // Проверка каждые 100 миллисекунд
});
