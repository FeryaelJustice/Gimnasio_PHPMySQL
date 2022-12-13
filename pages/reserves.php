    <script type="text/javascript">
        function previous_four_days() {
            <?php
            echo $_SESSION["dateFrom"];
            $_SESSION["dateFrom"] = date('Y-m-d H:i:s', strtotime($_SESSION["dateFrom"] . ' - 4 days'));
            $_SESSION["dateTo"] = date('Y-m-d H:i:s', strtotime($_SESSION["dateTo"] . ' - 4 days'));

            if (isset($_COOKIE[$dateFromCookie])) {
                setcookie($dateFromCookie, date('Y-m-d H:i:s', strtotime($_COOKIE[$dateFromCookie] . ' - 4 days')), time() + (86400 * 30), "/");
            }
            if (isset($_COOKIE[$dateToCookie])) {
                setcookie($dateToCookie, date('Y-m-d H:i:s', strtotime($_COOKIE[$dateToCookie] . ' - 4 days')), time() + (86400 * 30), "/");
            }
            echo $_SESSION["dateFrom"];
            ?>
            location.reload();
        }

        function next_four_days() {
            <?php
            $_SESSION["dateFrom"] = date('Y-m-d H:i:s', strtotime($_SESSION["dateFrom"] . ' + 4 days'));
            $_SESSION["dateTo"] = date('Y-m-d H:i:s', strtotime($_SESSION["dateTo"] . ' + 4 days'));

            if (isset($_COOKIE[$dateFromCookie])) {
                setcookie($dateFromCookie, date('Y-m-d H:i:s', strtotime($_COOKIE[$dateFromCookie] . ' + 4 days')), time() + (86400 * 30), "/");
            }
            if (isset($_COOKIE[$dateToCookie])) {
                setcookie($dateToCookie, date('Y-m-d H:i:s', strtotime($_COOKIE[$dateToCookie] . ' + 4 days')), time() + (86400 * 30), "/");
            }
            ?>
        }
    </script>
    <div class="row">
        <div class="col-sm-8">
            <div class="alert alert-primary" role="alert">
                <div class="row">
                    <div class="col-sm-2">
                        <img src="./static/left.png" style="cursor:pointer" onclick="previous_four_days()">
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
                        <img src="./static/right.png" style="cursor:pointer" onclick="next_four_days()">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <?php
                $dateFrom = $_SESSION["dateFrom"];
                $dateTo = $_SESSION["dateTo"];
                $sql = "SELECT data, idpista, idclient FROM reserves WHERE data BETWEEN '$dateFrom' AND '$dateTo'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table id=\"userList\" class=\"table table-bordered table-hover table-striped\"><tr><th>Data</th><th>ID Pista</th><th>ID Client</th></tr>";
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["data"] . "</td><td>" . $row["idpista"] . "</td><td>" . $row["idclient"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
                ?>
            </div>
        </div>
    </div>