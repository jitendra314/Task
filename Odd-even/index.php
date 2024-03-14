<!DOCTYPE html>
<html>
<head>
    <title>Even or Odd</title>
</head>
<body>
    <h2>Even or Odd</h2>
    <form method="post">
        Enter a number: <input type="number" name="number" required><br>
        <input type="submit" name="submit" value="Check">
    </form>
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if input is provided
        if (isset($_POST['number'])) {
            $number = $_POST['number'];

            // Check if number is even or odd
            if ($number % 2 == 0) {
                echo "<p>$number is an even number.</p>";
            } else {
                echo "<p>$number is an odd number.</p>";
            }
        }
    }
    ?>
</body>
</html>
