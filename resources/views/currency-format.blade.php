<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Formatter</title>
</head>
<body>
    <input type="text" id="numberInput" placeholder="Enter a number">
    
    <script>
        function formatNumberToDutch(number) {
            return new Intl.NumberFormat('nl-NL').format(number);
        }

        document.getElementById('numberInput').addEventListener('input', function() {
            let input = this.value;
            let cursorPosition = this.selectionStart; // Get the cursor position

            // Remove non-numeric characters except commas
            input = input.replace(/[^\d,]/g, '');

            // Format the number
            let formattedNumber = '';
            if (input.includes(',')) {
                let parts = input.split(',');
                formattedNumber = formatNumberToDutch(parts[0]) + ',' + parts.slice(1).join('');
            } else {
                formattedNumber = formatNumberToDutch(input);
            }
            this.value = formattedNumber;
        });
    </script>
</body>
</html>
