<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Screenshot</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
</head>
<body>

<button id="screenshotBtn">Take Screenshot</button>

<script>
$(document).ready(function() {
    $('#screenshotBtn').click(function() {
        html2canvas(document.html, {
            onrendered: function(canvas) {
                // Convert canvas to image
                var imgData = canvas.toDataURL('image/png');

                // Create a link element
                var link = document.createElement('a');
                link.href = imgData;
                link.download = 'screenshot.png';
                document.body.appendChild(link);

                // Trigger the download
                link.click();

                // Clean up
                document.body.removeChild(link);
            }
        });
    });
});
</script>

</body>
</html>
