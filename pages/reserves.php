<div class="alert alert-info" role="alert">
    Tenir en compte que les fletxes van amb retràs, és a dir, que quan fas click a la fletxa de la dreta, no es veuen els canvis fins que no fas click a la fletxa de l'esquerra o dreta una altra vegada.
    Per exemple: esteim a 20/04, quan fas click a la dreta, la cookie s'estableix a 24/04, però no es veuen els canvis fins que dones click una altra vegada (en aquest moment la cookie estará a 28/04 si dones a la dreta, si es a l'esquerra estará a 20/04, i així)
</div>
<?php
if (!isset($_COOKIE["dateFrom"]) && !isset($_COOKIE["dateTo"])) {
    setcookie("dateFrom", gmdate("Y-m-d\TH:i:s\Z", time()), time() + 3600, "/");
    setcookie("dateTo", gmdate("Y-m-d\TH:i:s\Z", strtotime('+4 day', time())), time() + 3600, "/");
} else {
    setcookie("dateFrom", gmdate("Y-m-d\TH:i:s\Z", time()), time() + 3600, "/");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST["operation"] == "up") {
            setcookie("dateFrom", gmdate("Y-m-d\TH:i:s\Z", strtotime('+4 day', strtotime($_COOKIE["dateFrom"]))), time() + 3600, "/");
            setcookie("dateTo", gmdate("Y-m-d\TH:i:s\Z", strtotime('+4 day', strtotime($_COOKIE["dateTo"]))), time() + 3600, "/");

            /*
            if (empty($_GET['status'])) {
                header('/projects/tasku3dawes/index.php?page=reserves&status=1');
            }
            */
        } else if ($_POST["operation"] == "down") {
            setcookie("dateFrom", gmdate("Y-m-d\TH:i:s\Z", strtotime('-4 day', strtotime($_COOKIE["dateFrom"]))), time() + 3600, "/");
            setcookie("dateTo", gmdate("Y-m-d\TH:i:s\Z", strtotime('-4 day', strtotime($_COOKIE["dateTo"]))), time() + 3600, "/");

            /*
            if (empty($_GET['status'])) {
                header('/projects/tasku3dawes/index.php?page=reserves&status=1');
            }
            */
        }
    } else {
        setcookie("dateFrom", gmdate("Y-m-d\TH:i:s\Z", time()), time() + 3600, "/");
        setcookie("dateTo", gmdate("Y-m-d\TH:i:s\Z", strtotime('+4 day', time())), time() + 3600, "/");
    }
}
?>
<div class="row">
    <div class="col-sm-8">
        <div class="alert alert-primary" role="alert">
            <div class="row">
                <div class="col-sm-2">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?page=reserves"; ?>">
                        <input type="hidden" name="operation" value="down">
                        <button type="submit"><img src="./static/left.png" style="cursor:pointer"></button>
                    </form>
                </div>
                <div class="col-sm-8">
                    <h2> Reserves setmana <?php
                                            $time = null;
                                            if (isset($_COOKIE["dateFrom"])) {
                                                $time = strtotime($_COOKIE["dateFrom"]);
                                            }
                                            $day = date("d", $time);
                                            $month = date("m", $time);
                                            echo ($day . "/" . $month) ?> a <?php
                                                                            $timeTo = null;
                                                                            if (isset($_COOKIE["dateTo"])) {
                                                                                $timeTo = strtotime($_COOKIE["dateTo"]);
                                                                            }
                                                                            $dayTo = date("d", $timeTo);
                                                                            $monthTo = date("m", $timeTo);
                                                                            echo ($dayTo . "/" . $monthTo) ?></h2>
                </div>
                <div class="col-sm-2">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?page=reserves"; ?>">
                        <input type="hidden" name="operation" value="up">
                        <button type="submit"><img src="./static/right.png" style="cursor:pointer"></button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        $diesSetmana = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
        $dateFrom = null;
        $dateTo = null;
        if (isset($_COOKIE["dateFrom"])) {
            $dateFrom = $_COOKIE["dateFrom"];
        }
        if (isset($_COOKIE["dateTo"])) {
            $dateTo = $_COOKIE["dateTo"];
        }
        $sql = "SELECT * FROM reserves WHERE data BETWEEN '$dateFrom' AND '$dateTo'";
        $result = $conn->query($sql);
        echo $result->num_rows . ' resultats.   ';
        if ($result->num_rows > 0) {
        ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <caption>Els dissabtes i diumenges no están disponibles.</caption>
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
                        </tr>
                        <!-- 17h -->
                        <tr>
                            <td>17:00</td>
                        </tr>
                        <!-- 18h -->
                        <tr>
                            <td>18:00</td>
                        </tr>
                        <!-- 19h -->
                        <tr>
                            <td>19:00</td>
                        </tr>
                        <!-- 20h -->
                        <tr>
                            <td>20:00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php
            $result->free();
        }
        ?>
    </div>
</div>