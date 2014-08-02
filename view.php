<html>
	<body>
		<ul>
<?php
require_once('./dbconfig.php');

$db = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (array_key_exists('deleteId', $_GET)) {
		$statement = $db->prepare('DELETE FROM sections WHERE id = ?');
		$statement->bind_param('i', $_GET['deleteId']);
		if ($result = $statement->execute()) {
			echo 'Success.';
		} else {
			echo 'Failure.';
		}
	} else if (array_key_exists('email', $_GET)) {
		$statement = $db->prepare('SELECT `id`, `section_id`, `course_id`, `semester_id` FROM sections WHERE email = ?');
		$statement->bind_param('s', $_GET['email']);
		if ($statement->execute()) {
			$statement->bind_result($rowId, $sectionId, $courseId, $semesterId);
			while($statement->fetch()){
				echo sprintf('<li>%s [%s](%s) - <a href="./view.php?deleteId=%s">Delete</a></li>', $sectionId, $courseId, $semesterId, $rowId);
			}
		}
	}
}
?>
		</ul>
	</body>
</html>