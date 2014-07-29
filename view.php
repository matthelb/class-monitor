<html>
	<body>
		<ul>
<?php
define('DATABASE_NAME', 'monitor.sqlite');

$db = new SQLite3(DATABASE_NAME);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (array_key_exists('deleteId', $_GET)) {
		$statement = $db->prepare('DELETE FROM sections WHERE rowid = :id');
		$statement->bindValue(':id', $_GET['deleteId'], SQLITE3_INTEGER);
		if ($result = $statement->execute()) {
			echo 'Success.';
		} else {
			echo 'Failure.';
		}
	} else if (array_key_exists('email', $_GET)) {
		$statement = $db->prepare('SELECT rowid, * FROM sections WHERE email = :email');
		$statement->bindValue(':email', $_GET['email'], SQLITE3_TEXT);
		$result = $statement->execute();
		while($row = $result->fetchArray()){
			$sectionId = $row['section_id'];
			$courseId = $row['course_id'];
			$semesterId = $row['semester_id'];
			$rowId = $row['rowid'];
			echo sprintf('<li>%s [%s](%s) - <a href="./view.php?deleteId=%s">Delete</a></li>', $sectionId, $courseId, $semesterId, $rowId);
		}
	}
}
?>
		</ul>
	</body>
</html>