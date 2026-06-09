<?php
include_once("config.php");
requireLogin();

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query_select = "SELECT foto FROM mahasiswa WHERE id = '$id'";
    $result_select = mysqli_query($conn, $query_select);

    if (mysqli_num_rows($result_select) == 1) {
        $row = mysqli_fetch_assoc($result_select);
        
        if (!empty($row['foto'])) {
            deleteFile($row['foto']); 
        }

        $query_delete = "DELETE FROM mahasiswa WHERE id = '$id'";
        if (mysqli_query($conn, $query_delete)) {
            header("Location: index.php?msg=deleted");
            exit();
        }
    }
}

header("Location: index.php");
exit();
?>