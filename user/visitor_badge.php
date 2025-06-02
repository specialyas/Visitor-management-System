<?php
include '../database/db_connection.php';

if (!isset($_GET['visitor_id'])) {
    die("Error: Visitor ID is missing.");
}

$visitorID = $_GET['visitor_id']; 

$stmt = $conn->prepare("SELECT visitor_name, phone_number, visit_purpose, visit_date, visitor_id, signed_in_by FROM visitors WHERE id = ?");
$stmt->bind_param("i", $visitorID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Visitor not found.");
}

$visitor = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Badge</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #aacc9d;
            margin: 0;
            padding: 0;
        }

        .badge-container {
            max-width: 360px;
            background-color: #ffffff;
            padding: 30px 25px;
            margin: 60px auto;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.06);
            text-align: center;
        }

        .badge-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .badge-details p {
            font-size: 15px;
            color: #555;
            margin: 8px 0;
            line-height: 1.5;
        }

        .badge-details strong {
            color: #222;
        }

        .print-btn {
            margin-top: 25px;
            padding: 10px 20px;
            font-size: 14px;
            border: 1px solid #444;
            background-color: #fff;
            color: #444;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .print-btn:hover {
            background-color: #444;
            color: #fff;
        }

        .home-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #444;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .home-link:hover {
            color: #000;
        }
    </style>
</head>
<body>

<div class="badge-container">
    <div class="badge-title">Visitor Badge</div>

    <div class="badge-details">
        <p><strong>Name:</strong> <?= htmlspecialchars($visitor['visitor_name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($visitor['phone_number']) ?></p>
        <p><strong>Purpose:</strong> <?= htmlspecialchars($visitor['visit_purpose']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($visitor['visit_date']) ?></p>
        <p><strong>Visitor ID:</strong> <?= htmlspecialchars($visitor['visitor_id']) ?></p>
        <p><strong>Signed In By:</strong> <?= htmlspecialchars($visitor['signed_in_by']) ?></p>
    </div>

    <button class="print-btn" onclick="window.print()">Print Badge</button>
</div>

<a class="home-link" href="./index.php">← Back to Home</a>

</body>
</html>
