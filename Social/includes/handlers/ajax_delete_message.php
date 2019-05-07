<?php
include("../../config/config.php");
// Viestien poistaminen
if(isset($_POST['id'])) {
	$id = $_POST['id'];

	$query = mysqli_query($con, "DELETE FROM messages WHERE id='$id'");
}

?>