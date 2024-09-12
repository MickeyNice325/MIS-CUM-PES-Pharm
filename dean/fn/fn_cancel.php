<?php 
include("../../../fn/config.php");


if (isset($_REQUEST['id'])) {
 
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("DELETE FROM estimate_score WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("s", $id);


    if ($stmt->execute()) {

        echo"
            <script>
                window.location = '../being_evaluated.php';
            </script>
        ";
    } else {

        echo "Error executing query: " . htmlspecialchars($stmt->error);
    }


    $stmt->close();
} else {
    echo "Error: 'id' not provided in the request.";
}


$conn->close();
?>
