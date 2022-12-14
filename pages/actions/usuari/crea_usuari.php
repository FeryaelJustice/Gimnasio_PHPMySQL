<?php

require __DIR__ . '../../../../partials/header.php';

?>

<form name="newuser" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>/pages/actions/usuari/crea_usuari.php" method="POST">
    <label for="name">Nom:</label>
    <input type="text" name="name" id="name">
    <label for="surname">Llinatges:</label>
    <input type="text" name="surname" id="surname">
    <label for="phone">Telefon:</label>
    <input type="number" name="phone" id="phone">
    <input type="submit" value="Enviar">
</form>

<?php
require  __DIR__ . '../../../../partials/footer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Comprobamos si venimos de otra pagina
    if($_POST["id"] != "id"){
        require __DIR__ . '../../../../database/db.php';

        try {
            $stmt = $conn->prepare("INSERT INTO clients (nom, llinatges, telefon) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $_POST["name"], $_POST["surname"], $_POST["phone"]);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Commit transaction failed";
            die();
        } finally {
            $stmt->close();
        }
    
        $_SESSION['message'] = 'Task Added Successfully';
        $_SESSION['message_type'] = 'warn';
        header('Location: /projects/tasku3dawes/index.php?page=usuaris');
    }
}
