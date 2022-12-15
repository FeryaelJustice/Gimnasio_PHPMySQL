<?php

require __DIR__ . '../../../../database/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM reserves WHERE idclient=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
    } catch (Exception $e) {
        echo "Delete transaction reserves failed";
        die();
    } finally {
        $stmt->close();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM clients WHERE idclient=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
    } catch (Exception $e) {
        echo "Delete transaction clients failed";
        die();
    } finally {
        $stmt->close();
    }

    $stmt = $conn->prepare("DELETE FROM clients WHERE idclient=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $_SESSION['message'] = 'Task Removed Successfully';
    $_SESSION['message_type'] = 'danger';
    header('Location: /projects/tasku3dawes/index.php?page=usuaris');
}
