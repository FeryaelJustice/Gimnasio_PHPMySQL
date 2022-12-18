    <?php
    // Session variables para cambiar de semana
    if (!isset($_SESSION["dateFrom"]) && !isset($_SESSION["dateTo"])) {
        $_SESSION["dateFrom"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("2022-11-22 00:00:00"));
        $_SESSION["dateTo"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("2022-11-26 23:59:59"));
    }
    // COOKIES para cambiar de semana
    if (!isset($_COOKIE["dateFrom"]) && !isset($_COOKIE["dateTo"])) {
        setcookie("dateFrom", gmdate("Y-m-d\TH:i:s\Z", strtotime("2022-11-22 00:00:00")), time() + 3600, "/");
        setcookie("dateTo", gmdate("Y-m-d\TH:i:s\Z", strtotime("2022-11-26 23:59:59")), time() + 3600, "/");
    }
    ?>
    <div class="row">
        <div class="col-sm-8">
            <div class="alert alert-primary" role="alert">
                <div class="row">
                    <div class="col-sm-2">
                        <form name="downForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?page=reserves"; ?>">
                            <input type="hidden" name="operation" value="down">
                            <button type="submit"><img src="./static/left.png" style="cursor:pointer"></button>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <h2> Reserves setmana <?php
                                                $time = strtotime($_COOKIE["dateFrom"]);
                                                $day = date("d", $time);
                                                $month = date("m", $time);
                                                echo ($day . "/" . $month) ?> a <?php
                                                                                $timeTo = strtotime($_COOKIE["dateTo"]);
                                                                                $dayTo = date("d", $timeTo);
                                                                                $monthTo = date("m", $timeTo);
                                                                                echo ($dayTo . "/" . $monthTo) ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <form name="upForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?page=reserves"; ?>">
                            <input type="hidden" name="operation" value="up">
                            <button type="submit"><img src="./static/right.png" style="cursor:pointer"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <?php
                $dateFrom = $_COOKIE["dateFrom"];
                $dateTo = $_COOKIE["dateTo"];

                if (isset($_POST["operation"]) && $_POST["operation"] == "up") {
                    $_COOKIE["dateFrom"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("+4 day", strtotime($_COOKIE["dateFrom"])));
                    $_COOKIE["dateTo"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("+4 day", strtotime($_COOKIE["dateTo"])));

                    // strtotime returns UNIT datetime, gmdate returns universal datetime from unix format
                    // $_SESSION["dateFrom"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("+4 day", strtotime($_SESSION["dateFrom"])));
                    // $_SESSION["dateTo"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("+4 day", strtotime($_SESSION["dateTo"])));

                    $dateFrom = $_COOKIE["dateFrom"];
                    $dateTo = $_COOKIE["dateTo"];


                    header('Location: /projects/tasku3dawes/index.php?page=reserves');
                } else if (isset($_POST["operation"]) && $_POST["operation"] == "down") {
                    // $_SESSION["dateFrom"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("-4 day", strtotime($_SESSION["dateFrom"])));
                    // $_SESSION["dateTo"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("-4 day", strtotime($_SESSION["dateTo"])));
                    if (isset($_COOKIE["dateFrom"]) && isset($_COOKIE["dateTo"])) {
                        $_COOKIE["dateFrom"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("-4 day", strtotime($_COOKIE["dateFrom"])));
                        $_COOKIE["dateTo"] = gmdate("Y-m-d\TH:i:s\Z", strtotime("-4 day", strtotime($_COOKIE["dateTo"])));
                    }

                    $dateFrom = $_COOKIE["dateFrom"];
                    $dateTo = $_COOKIE["dateTo"];


                    header('Location: /projects/tasku3dawes/index.php?page=reserves');
                }

                $sql = "SELECT * FROM reserves WHERE data BETWEEN '$dateFrom' AND '$dateTo'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                ?>
                    <table id="reserves" class="table table-bordered table-hover table-striped">
                        <caption>Els dissabtes i diumenges no están disponibles.</caption>
                        <tr>
                            <th></th>
                            <th>Dilluns</th>
                            <th>Dimarts</th>
                            <th>Dimecres</th>
                            <th>Dijous</th>
                            <th>Divendres</th>
                        </tr>
                        <?php
                        // output data of each row

                        $diesSetmana = array("1", "2", "3", "4", "5");
                        // $horesDisponibles = ["15:00", "16:00", "17:00", "18:00", "19:00", "20:00"];
                        // $index = 0; // per el while i anar posant els dies de la setmana
                        // echo "<tr><td>" . $horesDisponibles[$index] . "</td><td>" . $row["data"] . "</td><td>" . $row["idpista"] . "</td><td>" . $row["idclient"] . "</td></tr>";

                        // 15:00
                        echo "<tr>";
                        echo "<td>15:00</td>";
                        while ($row = $result->fetch_assoc()) {
                            if (date('H', strtotime($row["data"])) == "15") { // Si el registro es hora 15
                                foreach ($diesSetmana as $dia) { // Recorremos lunes a viernes
                                    if ($dia == date('N', strtotime($row["data"]))) { // 'N' es para dias de la semana (1 = dilluns, 7 = diumenge) Si el dia es igual al dia de la semana del registro
                                        // Dia de la semana especifico con hora especifica (registros)
                                        echo "<td>";
                                        $sqlField = "SELECT * FROM reserves WHERE reserves.data = '$row[data]'"; // en una fecha concreta tantas reservas
                                        $resultField = $conn->query($sqlField);
                                        if ($resultField->num_rows > 0) {
                                            while ($rowField = $resultField->fetch_assoc()) {
                                                // obtener los datos de cada reserva
                                                $sqlDatosReserva = "SELECT pistes.tipo, clients.nom, clients.llinatges FROM reserves INNER JOIN pistes ON pistes.idpista = '$rowField[idpista]' INNER JOIN clients ON clients.idclient = '$rowField[idclient]' WHERE reserves.data = '$rowField[data]'";
                                                $resultDatosReserva = $conn->query($sqlDatosReserva);
                                                if ($resultDatosReserva->num_rows > 0) {
                                                    while ($rowDatosReserva = $resultDatosReserva->fetch_assoc()) {
                                                        echo $rowDatosReserva["nom"] . " " . $rowDatosReserva["llinatges"] . ": " . $rowDatosReserva["tipo"] . " | ";
                                                        // Si no funciona bien quitar el break
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                break;
                            }
                        }
                        echo "</tr>";
                        // 16:00
                        echo "<tr>";
                        echo "<td>16:00</td>";
                        while ($row = $result->fetch_assoc()) {
                            if (date('H', strtotime($row["data"])) == "16") {
                                foreach ($diesSetmana as $dia) { // Recorremos lunes a viernes
                                    if ($dia == date('N', strtotime($row["data"]))) { // 'N' es para dias de la semana (1 = dilluns, 7 = diumenge) Si el dia es igual al dia de la semana del registro
                                        // Dia de la semana especifico con hora especifica (registros)
                                        echo "<td>";
                                        $sqlField = "SELECT * FROM reserves WHERE reserves.data = '$row[data]'"; // en una fecha concreta tantas reservas
                                        $resultField = $conn->query($sqlField);
                                        if ($resultField->num_rows > 0) {
                                            while ($rowField = $resultField->fetch_assoc()) {
                                                // obtener los datos de cada reserva
                                                $sqlDatosReserva = "SELECT pistes.tipo, clients.nom, clients.llinatges FROM reserves INNER JOIN pistes ON pistes.idpista = '$rowField[idpista]' INNER JOIN clients ON clients.idclient = '$rowField[idclient]' WHERE reserves.data = '$rowField[data]'";
                                                $resultDatosReserva = $conn->query($sqlDatosReserva);
                                                if ($resultDatosReserva->num_rows > 0) {
                                                    while ($rowDatosReserva = $resultDatosReserva->fetch_assoc()) {
                                                        echo $rowDatosReserva["nom"] . " " . $rowDatosReserva["llinatges"] . ": " . $rowDatosReserva["tipo"] . " | ";
                                                        // Si no funciona bien quitar el break
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                break;
                                break;
                            }
                        }
                        echo "</tr>";
                        // 17:00
                        echo "<tr>";
                        echo "<td>17:00</td>";
                        while ($row = $result->fetch_assoc()) {
                            if (date('H', strtotime($row["data"])) == "17") {
                                foreach ($diesSetmana as $dia) { // Recorremos lunes a viernes
                                    if ($dia == date('N', strtotime($row["data"]))) { // 'N' es para dias de la semana (1 = dilluns, 7 = diumenge) Si el dia es igual al dia de la semana del registro
                                        // Dia de la semana especifico con hora especifica (registros)
                                        echo "<td>";
                                        $sqlField = "SELECT * FROM reserves WHERE reserves.data = '$row[data]'"; // en una fecha concreta tantas reservas
                                        $resultField = $conn->query($sqlField);
                                        if ($resultField->num_rows > 0) {
                                            while ($rowField = $resultField->fetch_assoc()) {
                                                // obtener los datos de cada reserva
                                                $sqlDatosReserva = "SELECT pistes.tipo, clients.nom, clients.llinatges FROM reserves INNER JOIN pistes ON pistes.idpista = '$rowField[idpista]' INNER JOIN clients ON clients.idclient = '$rowField[idclient]' WHERE reserves.data = '$rowField[data]'";
                                                $resultDatosReserva = $conn->query($sqlDatosReserva);
                                                if ($resultDatosReserva->num_rows > 0) {
                                                    while ($rowDatosReserva = $resultDatosReserva->fetch_assoc()) {
                                                        echo $rowDatosReserva["nom"] . " " . $rowDatosReserva["llinatges"] . ": " . $rowDatosReserva["tipo"] . " | ";
                                                        // Si no funciona bien quitar el break
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                break;
                                break;
                            }
                        }
                        echo "</tr>";
                        // 18:00
                        echo "<tr>";
                        echo "<td>18:00</td>";
                        while ($row = $result->fetch_assoc()) {
                            if (date('H', strtotime($row["data"])) == "18") {
                                foreach ($diesSetmana as $dia) { // Recorremos lunes a viernes
                                    if ($dia == date('N', strtotime($row["data"]))) { // 'N' es para dias de la semana (1 = dilluns, 7 = diumenge) Si el dia es igual al dia de la semana del registro
                                        // Dia de la semana especifico con hora especifica (registros)
                                        echo "<td>";
                                        $sqlField = "SELECT * FROM reserves WHERE reserves.data = '$row[data]'"; // en una fecha concreta tantas reservas
                                        $resultField = $conn->query($sqlField);
                                        if ($resultField->num_rows > 0) {
                                            while ($rowField = $resultField->fetch_assoc()) {
                                                // obtener los datos de cada reserva
                                                $sqlDatosReserva = "SELECT pistes.tipo, clients.nom, clients.llinatges FROM reserves INNER JOIN pistes ON pistes.idpista = '$rowField[idpista]' INNER JOIN clients ON clients.idclient = '$rowField[idclient]' WHERE reserves.data = '$rowField[data]'";
                                                $resultDatosReserva = $conn->query($sqlDatosReserva);
                                                if ($resultDatosReserva->num_rows > 0) {
                                                    while ($rowDatosReserva = $resultDatosReserva->fetch_assoc()) {
                                                        echo $rowDatosReserva["nom"] . " " . $rowDatosReserva["llinatges"] . ": " . $rowDatosReserva["tipo"] . " | ";
                                                        // Si no funciona bien quitar el break
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                break;
                                break;
                            }
                        }
                        echo "</tr>";
                        // 19:00
                        echo "<tr>";
                        echo "<td>19:00</td>";
                        while ($row = $result->fetch_assoc()) {
                            if (date('H', strtotime($row["data"])) == "19") {
                                foreach ($diesSetmana as $dia) { // Recorremos lunes a viernes
                                    if ($dia == date('N', strtotime($row["data"]))) { // 'N' es para dias de la semana (1 = dilluns, 7 = diumenge) Si el dia es igual al dia de la semana del registro
                                        // Dia de la semana especifico con hora especifica (registros)
                                        echo "<td>";
                                        $sqlField = "SELECT * FROM reserves WHERE reserves.data = '$row[data]'"; // en una fecha concreta tantas reservas
                                        $resultField = $conn->query($sqlField);
                                        if ($resultField->num_rows > 0) {
                                            while ($rowField = $resultField->fetch_assoc()) {
                                                // obtener los datos de cada reserva
                                                $sqlDatosReserva = "SELECT pistes.tipo, clients.nom, clients.llinatges FROM reserves INNER JOIN pistes ON pistes.idpista = '$rowField[idpista]' INNER JOIN clients ON clients.idclient = '$rowField[idclient]' WHERE reserves.data = '$rowField[data]'";
                                                $resultDatosReserva = $conn->query($sqlDatosReserva);
                                                if ($resultDatosReserva->num_rows > 0) {
                                                    while ($rowDatosReserva = $resultDatosReserva->fetch_assoc()) {
                                                        echo $rowDatosReserva["nom"] . " " . $rowDatosReserva["llinatges"] . ": " . $rowDatosReserva["tipo"] . " | ";
                                                        // Si no funciona bien quitar el break
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                break;
                                break;
                            }
                        }
                        echo "</tr>";
                        // 20:00
                        echo "<tr>";
                        echo "<td>20:00</td>";
                        while ($row = $result->fetch_assoc()) {
                            if (date('H', strtotime($row["data"])) == "20") {
                                foreach ($diesSetmana as $dia) { // Recorremos lunes a viernes
                                    if ($dia == date('N', strtotime($row["data"]))) { // 'N' es para dias de la semana (1 = dilluns, 7 = diumenge) Si el dia es igual al dia de la semana del registro
                                        // Dia de la semana especifico con hora especifica (registros)
                                        echo "<td>";
                                        $sqlField = "SELECT * FROM reserves WHERE reserves.data = '$row[data]'"; // en una fecha concreta tantas reservas
                                        $resultField = $conn->query($sqlField);
                                        if ($resultField->num_rows > 0) {
                                            while ($rowField = $resultField->fetch_assoc()) {
                                                // obtener los datos de cada reserva
                                                $sqlDatosReserva = "SELECT pistes.tipo, clients.nom, clients.llinatges FROM reserves INNER JOIN pistes ON pistes.idpista = '$rowField[idpista]' INNER JOIN clients ON clients.idclient = '$rowField[idclient]' WHERE reserves.data = '$rowField[data]'";
                                                $resultDatosReserva = $conn->query($sqlDatosReserva);
                                                if ($resultDatosReserva->num_rows > 0) {
                                                    while ($rowDatosReserva = $resultDatosReserva->fetch_assoc()) {
                                                        echo $rowDatosReserva["nom"] . " " . $rowDatosReserva["llinatges"] . ": " . $rowDatosReserva["tipo"] . " | ";
                                                        // Si no funciona bien quitar el break
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        echo "</td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                }
                                break;
                                break;
                            }
                        }
                        echo "</tr>";
                        ?>
                    </table>";
                <?php
                } else {
                    echo "0 results";
                }
                $result->free();
                $resultField->free();
                $resultDatosReserva->free();
                ?>

                <!-- EJEMPLO TIMETABLE -->
                <!--
                <table class="table table-bordered table-hover table-striped">
                    <caption>Tabla de ejemplo</caption>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Dilluns</th>
                            <th>Dimarts</th>
                            <th>Dimecres</th>
                            <th>Dijous</th>
                            <th>Divendres</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>15:00</td>
                            <td>Hola</td>
                            <td>Que</td>
                            <td>Tal</td>
                            <td>Estas</td>
                            <td>uwu</td>
                        </tr>
                        <tr>
                            <td>16:00</td>
                            <td>Hola</td>
                            <td>Que</td>
                            <td>Tal</td>
                            <td>Estas</td>
                            <td>uwu</td>
                        </tr>
                        <tr>
                            <td>17:00</td>
                            <td>Hola</td>
                            <td>Que</td>
                            <td>Tal</td>
                            <td>Estas</td>
                            <td>uwu</td>
                        </tr>
                        <tr>
                            <td>18:00</td>
                            <td>Hola</td>
                            <td>Que</td>
                            <td>Tal</td>
                            <td>Estas</td>
                            <td>uwu</td>
                        </tr>
                    </tbody>
                </table>
                -->
            </div>
        </div>
    </div>