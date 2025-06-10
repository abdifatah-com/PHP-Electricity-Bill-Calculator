<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electricity Bill Calculator</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,  #462d81,  #462d81);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #ffffff;
            display: flex;
            gap: 30px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 90%;
        }

        .bill-form, .result {
            flex: 1;
        }

        h2 {
            text-align: left;
            color: #462d81;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1.5px solid  #462d81;
            background-color: #f8f9fa;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #462d81, #462d81);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right,  #462d81, #462d81);
        }

        .result-box {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 10px;
            border-left: 6px solid  #462d81;
        }

        .result-box strong {
            color: #222;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="bill-form">
        <h2>💡 Electricity Bill</h2>
        <form method="post">
            <label>Client Name:</label>
            <input type="text" name="name" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Meter Reading (units):</label>
            <input type="number" name="reading" required>

            <input type="submit" name="submit" value="Calculate Bill">
        </form>
    </div>

    <div class="result">
       <?php
if (isset($_POST['submit'])) {
    include 'dbcon.php'; // Include database connection

    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $reading = isset($_POST['reading']) ? (int)$_POST['reading'] : 0;
    $price_per_unit = 0;

    if ($reading <= 10) {
        $price_per_unit = 0.82;
    } elseif ($reading >= 11 && $reading <= 30) {
        $price_per_unit = 0.72;
    } else {
        $price_per_unit = 0.30;
    }

    $total_bill = $reading * $price_per_unit;

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO bills (name, location, reading, price_per_unit, total_bill) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidd", $name, $location, $reading, $price_per_unit, $total_bill);

    if ($stmt->execute()) {
        echo "<div class='result-box'>";
        echo "<strong>Name:</strong> $name<br>";
        echo "<strong>Location:</strong> $location<br>";
        echo "<strong>Meter Reading:</strong> $reading units<br>";
        echo "<strong>Price per Unit:</strong> $$price_per_unit<br>";
        echo "<strong>Total Bill:</strong> $" . number_format($total_bill, 2) . "<br>";
        echo "<span style='color: green;'>✅ Bill saved to database successfully!</span>";
        echo "</div>";
    } else {
        echo "<div class='result-box' style='color: red;'>❌ Error saving to database: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

    </div>
</div>

</body>
</html>
