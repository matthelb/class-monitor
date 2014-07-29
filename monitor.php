<?php
require_once('./lib/functions.php');

define('DATABASE_NAME', 'monitor.sqlite');

function notify($email, $registered, $available, $sectionId, $courseId, $semesterId) {
	$subject = "USC Class Monitor - %s [%s](%s) Available!";
	$message = "There are %d spots available for section %s of course %s during semester %s.";
	return mail($email, sprintf($subject, $courseId, $sectionId, $semesterId), sprintf($message, $available - $registered, $sectionId, $courseId, $semesterId)); 
}

$db = new SQLite3(DATABASE_NAME);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$statement = $db->prepare("INSERT INTO sections (section_id, course_id, semester_id, email) VALUES (:secid, :couid, :semid, :email)");
	$statement->bindValue(':secid', $_POST['sectionId'], SQLITE3_INTEGER);
	$statement->bindValue(':couid', $_POST['courseId'], SQLITE3_TEXT);
	$statement->bindValue(':semid', $_POST['semesterId'], SQLITE3_INTEGER);
	$statement->bindValue(':email', $_POST['email'], SQLITE3_TEXT);
	if ($statement->execute()) {
		echo 'Success.';
	} else {
		echo 'Failure.';
	}
} else {
	$statement = $db->prepare("SELECT * FROM sections;");
	$result = $statement->execute();
	while($row = $result->fetchArray()){
		$sectionId = $row['section_id'];
		$courseId = $row['course_id'];
		$semesterId = $row['semester_id'];
		$email = $row['email'];
		$section = get_section($sectionId, $courseId, $semesterId);
		if ($section->numberRegistered < $section->spacesAvailable) {
			echo sprintf('%s [%s](%s) has %d spots available.<br/>', $sectionId, $courseId, $semesterId, $section->spacesAvailable - $section->numberRegistered);
			notify($email, $section->numberRegistered, $section->spacesAvailable, $sectionId, $courseId, $semesterId);
		}
	}
}
?>