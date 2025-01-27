<?php

if (isset($_POST['id'])) {
    // Sanitize input
    $id = intval($_POST['id']); // Ensure `id` is an integer

    include 'includes/session.php'; // Database connection

    $conn = $pdo->open();

    try {
        // Update the `confirmed` column for the specific sale
        $stmt = $conn->prepare("UPDATE sales SET confirmed = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo "Sale confirmed successfully.";
        } else {
            echo "No rows were updated. Please check the sale ID.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $pdo->close();
} else {
    echo "No sale ID provided.";
}
