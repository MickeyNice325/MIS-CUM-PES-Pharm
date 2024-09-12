<?php 
include("../../../fn/config.php");

// Check if 'id' is provided in the request
if (isset($_REQUEST['id'])) {
    // Retrieve the staff code from the request
    $id = $_REQUEST['id'];

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM final_score WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("s", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to another page if needed
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
    echo "Error: 'id' not provided in the request.";
}

// Close the database connection
$conn->close();
?>
