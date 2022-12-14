    <div class="row">
        <div class="col-sm-8">
            <div class="alert alert-primary" role="alert">
                <div class="row">
                    <div class="col-sm-2">
                        <form name="downForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?page=reserves"; ?>">
                            <input type="hidden" name="operation" value="down">
                            <button type="submit"><img src="./static/left.png" style="cursor:pointer"></button>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <h2> Reserves setmana <?php
                                                $time = strtotime($_SESSION["dateFrom"]);
                                                $day = date("d", $time);
                                                $month = date("m", $time);
                                                echo ($day . "/" . $month) ?> a <?php
                                                                                $timeTo = strtotime($_SESSION["dateTo"]);
                                                                                $dayTo = date("d", $timeTo);
                                                                                $monthTo = date("m", $timeTo);
                                                                                echo ($dayTo . "/" . $monthTo) ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <form name="upForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?page=reserves"; ?>">
                            <input type="hidden" name="operation" value="up">
                            <button type="submit"><img src="./static/right.png" style="cursor:pointer"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <?php
                $dateFrom = $_SESSION["dateFrom"];
                $dateTo = $_SESSION["dateTo"];
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if ($_POST["operation"] == "up") {
                        $dateFrom = $_SESSION["dateFrom"];
                        $dateTo = $_SESSION["dateTo"];
                    } else {
                        $dateFrom = $_SESSION["dateFrom"];
                        $dateTo = $_SESSION["dateTo"];
                    }
                }
                $sql = "SELECT data, idpista, idclient FROM reserves WHERE data BETWEEN '$dateFrom' AND '$dateTo'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                ?>
                    <table id="userList" class="table table-bordered table-hover table-striped">
                        <tr>
                            <th>Data</th>
                            <th>ID Pista</th>
                            <th>ID Client</th>
                        </tr>
                        <?php
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row["data"] . "</td><td>" . $row["idpista"] . "</td><td>" . $row["idclient"] . "</td></tr>";
                        }
                        ?>
                    </table>";
                <?php
                } else {
                    echo "0 results";
                }
                ?>
            </div>
        </div>
    </div>