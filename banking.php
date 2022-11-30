

<?php

require('db.php');
session_start();


function get_balance($id)
{
	global $db;
	$id = (int)$id;
	$query = "select balance from users where id =" . $id;
	$result = $db->query($query);

	while ($res = $result->fetchArray(SQLITE3_ASSOC)) {
		if ($res['name'] != "sqlite_sequence") {
			return (int)$res['balance'];
		}
	}
}


function account_exist($id)
{
	global $db;
	$id = (int)$id;

	$query = "SELECT * FROM `users` WHERE id='$id' ";
	$rows = $db->querySingle($query);
	if ($rows > 0) {
		return true;
	}
	return false;
}

function transfere_money($amount, $receiver_account)
{
	global $db;
	$amount = (int)$amount;
	$receiver_account = (int)$receiver_account;
	$id = (int)$_SESSION['username'];
	if (!account_exist($receiver_account)) {
		die("receiver_account doesn't exist");
	}
	if ($amount < 0) {
		die("Negative numbers are not allowed");
	}
	if ($amount > get_balance($id)) {
		die('Insufficient funds !');
	}
	$sql = "UPDATE users SET balance=balance-" . $amount . " WHERE id=" . $id;

	if ($db->exec($sql) === TRUE && get_balance($id) >= 0) {
		$sql = "UPDATE users SET balance=balance+" . $amount . " WHERE id=" . $receiver_account;
		$db->exec($sql);
		$sql = "insert into transactions (from_account,to_account,amount) values ('" . $id . "','" . $receiver_account . "','" . $amount . "');";
		$db->exec($sql);
		echo "Transfere complete!" . "<br>";
		echo "Amount: $" . $amount . "<br>";
		echo "From Account: " . $id . "<br>";
		echo "To Account: " . $receiver_account . "<br>";
		echo "<br> <button onclick='history.back()'>Go Back</button>";
	} else {
		echo "Error updating record";
		echo "<br> <button onclick='history.back()'>Go Back</button>";
	}
}



function get_transactions($id, $order)
{
	global $db;
	$id = (int)$id;
	$query = "SELECT * FROM `transactions` where from_account=$id or to_account=$id order by trn_date $order";
	$result = $db->query($query);
	$str_result = "";
	if ($result and $result > 0) {
		// output data of each row
		while ($res = $result->fetchArray(SQLITE3_ASSOC)) {
			$str_result = $str_result .  "<tr><td>" . $res["trn_date"] . "</td><td>" . $res["from_account"] . "</td><td> " . $res["to_account"] . " </td><td>  $" . $res["amount"] . "</td>\n </tr>";
		}
	} else {
		$str_result = $str_result .  "<tr> <td></td> <td></td> <td></td> <td></td> </tr>";
	}
	return $str_result;
}



function is_vip()
{
	if (get_balance($_SESSION['username']) < 1000000) {
		return false;
	}
	return true;
}




?>



