<?php
include("../../../fn/config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM estimate WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../list_edit_score.php?msg=success");
    } else {
        header("Location: ../list_edit_score.php?msg=error");
    }
} else {
    header("Location: ../list_edit_score.php?msg=invalid_id");
}
?>
