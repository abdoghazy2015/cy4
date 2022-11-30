<!DOCTYPE html>
<html>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />
</head>

<body>
        <?php
        require('db.php');
        // If form submitted, insert values into the database.
        if (isset($_REQUEST['password'])) {
                // removes backslashes
                //escapes special characters in a string
                $email = stripslashes($_REQUEST['email']);
                $email = $db->escapeString( $email);
                $password = stripslashes($_REQUEST['password']);
                $password = $db->escapeString( $password);
                $trn_date = date("Y-m-d H:i:s");
                $query = "INSERT into `users` ( password, email, trn_date)
                          VALUES ( '" . md5($password) . "', '$email', '$trn_date');";

                try {
                        $result = $db->exec($query);
                } catch (Exception $e) {

                        echo "Error Creating User!";
                }
                if ($result) {
                        $last_id = $db->lastInsertRowID();
                        echo "Congratulations! , You are the user number ".$last_id." to join out platform  <br> you can login with your Account ID: " . $last_id;
                        echo "<br> <button onclick='history.back()'>Go Back</button>";
                }
        } else {}
        ?>
          

</body>

</html>