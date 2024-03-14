<!DOCTYPE html>
<html>
<head>
    <title>Sum of Digits Calculator</title>
</head>
<body>
    <h2>Sum of Digits Calculator</h2>
    <form method="post">
        Enter a positive integer: <input type="number" name="number" required><br>
        <input type="submit" name="submit" value="Calculate">
    </form>
    <?php
    // Function to calculate sum of digits
    function sumOfDigits($number) {
        $sum = 0;
        while ($number > 0) {
            $digit = $number % 10;
            $sum += $digit;
            $number = (int)($number / 10);
        }
        return $sum;
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if input is provided
        if (isset($_POST['number'])) {
            $number = $_POST['number'];

            // Check if input is positive
            if ($number > 0) {
                $sum = sumOfDigits($number);
                echo "<p>The sum of digits of $number is $sum.</p>";
            } else {
                echo "<p>Please enter a positive integer.</p>";
            }
        }
    }
    ?>
</body>
</html>
