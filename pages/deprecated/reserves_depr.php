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
                        <!-- FORMA CORRECTA -->
                        <!-- 15h -->
                        <tr>
                            <td>15:00</td>
                            <?php

                            while ($row = $result->fetch_assoc()) {
                                // echo "<td>a</td>";
                                // echo "<td>a</td>";
                                if (date('H', strtotime($row["data"])) == "15") {
                                    foreach ($diesSetmana as $dia) {
                                        // Dia y hora en concreto
                            ?>
                                        <td>
                                            <?php
                                            // echo "dia(row:arraydia):" . date('l', strtotime($row["data"])) . ":" . $dia . "</td>";
                                            // echo "<td>" . date('l', strtotime($row["data"])) . ":" . $dia . "</td>";
                                            // echo 'dia: ' . date('l', strtotime($row["data"]));
                                            $sqlField = "SELECT * FROM reserves WHERE data = '$row[data]'"; // en una fecha concreta tantas reservas
                                            $resultField = $conn->query($sqlField);
                                            // echo $resultField->num_rows;
                                            if ($resultField->num_rows > 0) {
                                                while ($rowField = $resultField->fetch_assoc()) {
                                                    echo $rowField["idclient"] . " " . $rowField["idclient"] . ": " . $rowField["idpista"] . " | ";
                                                    // Si no funciona bien quitar el break
                                                    break;
                                                }
                                            }
                                            // $resultField->free();
                                            ?>
                                        </td>
                            <?php
                                        break;
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <!-- 16h -->
                        <tr>
                            <td>16:00</td>
                            <?php

                            while ($row = $result->fetch_assoc()) {
                                if (date('H', strtotime($row["data"])) == "16") {
                                    foreach ($diesSetmana as $dia) {
                            ?>
                                        <td>
                                            <?php
                                            $sqlField = "SELECT * FROM reserves WHERE data = '$row[data]'"; // en una fecha concreta tantas reservas
                                            $resultField = $conn->query($sqlField);
                                            if ($resultField->num_rows > 0) {
                                                while ($rowField = $resultField->fetch_assoc()) {
                                                    echo $rowField["idclient"] . " " . $rowField["idclient"] . ": " . $rowField["idpista"] . " | ";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                            <?php
                                        break;
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <!-- 17h -->
                        <tr>
                            <td>17:00</td>
                            <?php

                            while ($row = $result->fetch_assoc()) {
                                if (date('H', strtotime($row["data"])) == "17") {
                                    foreach ($diesSetmana as $dia) {
                            ?>
                                        <td>
                                            <?php
                                            $sqlField = "SELECT * FROM reserves WHERE data = '$row[data]'"; // en una fecha concreta tantas reservas
                                            $resultField = $conn->query($sqlField);
                                            if ($resultField->num_rows > 0) {
                                                while ($rowField = $resultField->fetch_assoc()) {
                                                    echo $rowField["idclient"] . " " . $rowField["idclient"] . ": " . $rowField["idpista"] . " | ";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                            <?php
                                        break;
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <!-- 18h -->
                        <tr>
                            <td>18:00</td>
                            <?php

                            while ($row = $result->fetch_assoc()) {
                                if (date('H', strtotime($row["data"])) == "18") {
                                    foreach ($diesSetmana as $dia) {
                            ?>
                                        <td>
                                            <?php
                                            $sqlField = "SELECT * FROM reserves WHERE data = '$row[data]'"; // en una fecha concreta tantas reservas
                                            $resultField = $conn->query($sqlField);
                                            if ($resultField->num_rows > 0) {
                                                while ($rowField = $resultField->fetch_assoc()) {
                                                    echo $rowField["idclient"] . " " . $rowField["idclient"] . ": " . $rowField["idpista"] . " | ";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                            <?php
                                        break;
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <!-- 19h -->
                        <tr>
                            <td>19:00</td>
                            <?php

                            while ($row = $result->fetch_assoc()) {
                                if (date('H', strtotime($row["data"])) == "19") {
                                    foreach ($diesSetmana as $dia) {
                            ?>
                                        <td>
                                            <?php
                                            $sqlField = "SELECT * FROM reserves WHERE data = '$row[data]'"; // en una fecha concreta tantas reservas
                                            $resultField = $conn->query($sqlField);
                                            if ($resultField->num_rows > 0) {
                                                while ($rowField = $resultField->fetch_assoc()) {
                                                    echo $rowField["idclient"] . " " . $rowField["idclient"] . ": " . $rowField["idpista"] . " | ";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                            <?php
                                        break;
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <!-- 20h -->
                        <tr>
                            <td>20:00</td>
                            <?php

                            while ($row = $result->fetch_assoc()) {
                                if (date('H', strtotime($row["data"])) == "20") {
                                    foreach ($diesSetmana as $dia) {
                            ?>
                                        <td>
                                            <?php
                                            $sqlField = "SELECT * FROM reserves WHERE data = '$row[data]'"; // en una fecha concreta tantas reservas
                                            $resultField = $conn->query($sqlField);
                                            if ($resultField->num_rows > 0) {
                                                while ($rowField = $resultField->fetch_assoc()) {
                                                    echo $rowField["idclient"] . " " . $rowField["idclient"] . ": " . $rowField["idpista"] . " | ";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                            <?php
                                        break;
                                    }
                                }
                            }
                            ?>
                        </tr>
                        <!-- END FORMA CORRECTA -->
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