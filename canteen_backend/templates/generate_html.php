<?php
// Start buffering output to capture HTML content
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample HTML Page</title>
    <style>
        /* CSS styles can be included inline or in an external stylesheet */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to my PHP-generated HTML page!</h1>
        <p>This is a sample HTML content generated using PHP.</p>
        <p>You can dynamically generate HTML content using PHP and output it to the browser or store it in a variable for further processing.</p>
    </div>
</body>

</html>

<?php
// Get the buffered HTML content and clear the buffer
$htmlContent = ob_get_clean();

// Output the HTML content
echo $htmlContent;
?>