$(document).ready(function () {
    // Initialize AutoNumeric for all input fields with the data-type="currency" attribute
 
    AutoNumeric.multiple('input[data-type="currency"]', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2,
        minimumValue: '0',
        maximumValue: '9999999999999999999999999999.99',
        modifyValueOnWheel: false,
        allowDecimalPadding: true,
        alwaysAllowDecimalCharacter: true,
        currencySymbolPlacement: 'p',
        reverse: true,
        unformatOnHover: true,
        selectOnFocus: false // Disable selection on focus
    });

    AutoNumeric.multiple('.currencyMasking', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2,
        minimumValue: '0',
        maximumValue: '9999999999999999999999999999.99',
        modifyValueOnWheel: false,
        allowDecimalPadding: true,
        alwaysAllowDecimalCharacter: true,
        currencySymbolPlacement: 'p',
        reverse: true,
        unformatOnHover: true,
        selectOnFocus: false // Disable selection on focus

    });


    // Ensure two decimal places on blur if not entered
    $('input[data-type="currency"]').on('blur', function () {
        var value = $(this).val();
        if (value && !value.includes(',')) {
            $(this).val(value + ',00');
        } else if (value && value.includes(',')) {
            var decimalPart = value.split(',')[1];
            if (decimalPart.length === 1) {
                $(this).val(value + '0');
            }
        }
    });
});