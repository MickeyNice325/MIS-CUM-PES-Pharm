<?php 
include("../../../fn/config.php");

if (isset($_REQUEST['id'])) {
    // Retrieve the staff code from the request
    $id = $_REQUEST['id'];

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE final_score SET status = 'waitcomplete' WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the parameter
    $stmt->bind_param("s", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to another page if the update is successful
        echo"
            <script>
                window.location = '../being_evaluated.php';
            </script>
        ";
    } else {
        // Handle errors if the query fails
        echo "Error executing query: " . htmlspecialchars($stmt->error);
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle the case where 'id' is not provided in the request
    echo "Error: 'id' not provided in the request.";
}

// Close the database connection
$conn->close();
?>
