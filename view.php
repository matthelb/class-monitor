<html>
	<body>
		<ul>
<?php
require_once('./dbconfig.php');

$db = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (array_key_exists('deleteId', $_GET)) {
		$statement = $db->prepare('DELETE FROM sections WHERE rowid = ?');
		$statement->bind_param('i', $_GET['deleteId']);
		if ($result = $statement->execute()) {
			echo 'Success.';
		} else {
			echo 'Failure.';
		}
	} else if (array_key_exists('email', $_GET)) {
		$statement = $db->prepare('SELECT rowid, * FROM sections WHERE email = ?');
		$statement->bindValue('s', $_GET['email']);
		if ($statement->execute()) {
			$result = $statement->get_result();
			while($row = $result->fetch_row()){
				$sectionId = $row['section_id'];
				$courseId = $row['course_id'];
				$semesterId = $row['semester_id'];
				$rowId = $row['rowid'];
				echo sprintf('<li>%s [%s](%s) - <a href="./view.php?deleteId=%s">Delete</a></li>', $sectionId, $courseId, $semesterId, $rowId);
			}
		}
	}
}
?>
		</ul>
	</body>
</html>