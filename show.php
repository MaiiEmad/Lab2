<?php
if (isset($_REQUEST["show"]) && $_COOKIE["login"] == "login") {

    echo "<table border='2'>";
    echo "<tr>
            <th> id </th>
            <th> User Name </th>
            <th> Email </th>
            <th> Password </th>
            <th> Room Number </th>
            <th>profile Picture</th>
            <tr>";
    $result = json_decode($_REQUEST["data"]);
    $counter = 0;

    foreach ($result as $val) {
        $counter++;
        if ($counter == 6) {
            echo "<td><img width='200px' height='200px' src='profilePicture/$val'/></td>";
        } else {
            echo "<td>" . $val . "</td>";
        }
    }
    echo "</tr>";
    echo "</table>";
    echo "<form  class='container' action='home.php'>
        <input type='submit' name='backToHome' value='Back to Home'>
        </form>";
}
