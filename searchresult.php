<?php
include "config.php";


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/searchres.css">
    <link rel="icon" href="images/abashon_logo.png">
    <title>Abashon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#ajaxdata").load("search_allrecords.php");
            $("#rent_dropdown").change(function() {
                var selected = $(this).val();
                $("#ajaxdata").load("search.php", {
                    selected_rent: selected
                });
            });
            $("#block_dropdown").change(function() {
                var selected = $(this).val();
                $("#ajaxdata").load("search.php", {
                    selected_block: selected
                });
            });
            $("#fac_dropdown").change(function() {
                var selected = $(this).val();
                $("#ajaxdata").load("search.php", {
                    selected_fac: selected
                });
            });
            $("#refresh").click(function() {
                $("#ajaxdata").load("search_allrecords.php");
            });
        });
    </script>
</head>

<body>
    <nav class="nav" id="nav">
        <a href="index.php"><img class="nav-logo" id="nav-logo" src="images/abashon_logo.png" alt="logo"></a>
        <div class="nav-right">
            <div class="hamburger">
                <div class="line1" id="line1"></div>
                <div class="line2" id="line2"></div>
                <div class="line3" id="line3"></div>
            </div>
            <div class="menu-overlay">
                <div class="nav-links">
                    <a href="index.php">Home</a><br>
                    <a href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="topdiv">
        <div class="maindiv">
            <div class="upperdiv">
                <div class="round1">
                </div>
                <div class="round2">

                </div>
            </div>
        </div>
        <div class="searchbox">
            
                <form method="POST" class="searchform">
                    <label for="rent" class=""></label>
                    <div class="col-sm-2">
                        <select name="rent" class="searchbutton" id="rent_dropdown">
                            <option>Select max rent</option>
                            <option val="10000">10000</option>
                            <option val="15000">15000</option>
                            <option val="20000">20000</option>
                            <option val="30000">30000</option>
                            <option val="40000">40000</option>
                            <option val="50000">50000</option>
                            <option val="60000">60000</option>
                        </select>

                        <label for="block" class=""></label>
                        <select name="block" class="searchbutton" id="block_dropdown">
                            <option>Select block</option>
                            <option val="A">A</option>
                            <option val="B">B</option>
                            <option val="C">C</option>
                            <option val="D">D</option>
                            <option val="E">E</option>
                            <option val="F">F</option>
                            <option val="G">G</option>
                            <option val="H">H</option>
                            <option val="I">I</option>
                            <option val="J">J</option>
                            <option val="K">K</option>
                            <option val="L">L</option>
                            <option val="M">M</option>
                            <option val="N">N</option>
                        </select>

                        <label for="fac" class=""></label>
                        <select name="fac" class="searchbutton" id="fac_dropdown">
                            <option>Select Facilities</option>
                            <option val="1">Generator</option>
                            <option val="2">Lift</option>
                            <option val="3">Both</option>

                        </select>
                    </div>
                    <button type="button" name="refresh" id="refresh" class="searchbutton">Refresh</button>
            
            </form>
        </div>

    </div>
    <div class="bottomdiv">
    <div align="center"><span style="font-size:15pt; color: green;">Click on the column titles to sort them in ascending/descending order</span></div>
    <br>

    <!-- Showing the table -->
    <div align="center" id="ajaxdata">

    </div>
    </div>


    <!-- Sorting table -->
    <script>
        function sortTable(n, isNum) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("all");
            switching = true;
            //Set the sorting direction to ascending:
            dir = "asc";
            /*Make a loop that will continue until
            no switching has been done:*/
            while (switching) {
                //start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /*Loop through all table rows (except the
                first, which contains table headers):*/
                for (i = 1; i < (rows.length - 1); i++) {
                    //start by saying there should be no switching:
                    shouldSwitch = false;
                    /*Get the two elements you want to compare,
                    one from current row and one from the next:*/
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];

                    //If the values of the column are numbers
                    if (isNum) {
                        if (dir == "asc") {
                            if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        }
                    } else {
                        /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
                        if (dir == "asc") {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                }
                if (shouldSwitch) {
                    /*If a switch has been marked, make the switch
                    and mark that a switch has been done:*/
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    //Each time a switch is done, increase this count by 1:
                    switchcount++;
                } else {
                    /*If no switching has been done AND the direction is "asc",
                    set the direction to "desc" and run the while loop again.*/
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
    <script src="js/home.js"></script>
</body>

</html>