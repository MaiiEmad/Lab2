<?php
if (isset($_REQUEST["backToHome"]) && $_COOKIE["login"]=="login") {
    try {
        $connect = new pdo("mysql:dbname=student;host=localhost", "maii", "");
        $allUserData = $connect->query("select * from student");

        echo "<form action='login.php' >
        <input type='submit'  class='logout' name='backToHome' value='Log Out'>
        </form>";
        echo "<table border='2' ";
        echo "<tr>
            <th>id</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>profile Picture</th>
            <th></th>
            <th>Actions</th>
            <th></th>
            <tr>";

        while ($result = $allUserData->fetch(PDO::FETCH_ASSOC)) {
            $counter = 0;
            foreach ($result as $val) {
                $counter++;
                if ($counter == count($result)) { 
                    echo "<td><img width='200px' height='200px' src='profilePicture/$val'/></td>";
                } else {
                    echo "<td>" . $val . "</td>";
                }
            }
            echo "<td><form action='studentControl.php' method='get'>
                  <input type='hidden' name='id' value='{$result['id']}'/>
                  <input type='submit' name='show' value='show'>
                 </form></td>";
            echo "<td><form action='edit.php' method='get'>
                  <input type='hidden' name='id' value='{$result['id']}'/>
                  <input type='submit' name='edit' value='Edit'>
                 </form></td>";
            echo "<td><form action='studentControl.php' method='get'>
                  <input type='hidden' name='id' value='{$result['id']}'/>
                  <input type='submit' name='delete' value='Delete'>
                 </form></td>";
            echo "</tr>";
        }
        echo "</table>";

    } catch (PDOException $e) {
        die($e->getMessage());
    }
    $connect = null;
}