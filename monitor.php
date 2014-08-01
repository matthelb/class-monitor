<?php
require_once('./lib/functions.php');
require_once('./config.php');
require_once('./dbconfig.php');

function notify($email, $registered, $available, $sectionId, $courseId, $semesterId) {
	$subject = "USC Class Monitor - %s [%s](%s) Available!";
	$message = "There are %d spots available for section %s of course %s during semester %s.";
	return mail($email, sprintf($subject, $courseId, $sectionId, $semesterId), sprintf($message, $available - $registered, $sectionId, $courseId, $semesterId)); 
}

$db = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$statement = $db->prepare("INSERT INTO sections (section_id, course_id, semester_id, email) VALUES (?, ?, ?, ?)");
	$statement->bind_param('isis', $_POST['sectionId'], $_POST['courseId'], $_POST['semesterId'], $_POST['email']);
	if ($statement->execute()) {
		echo 'Success.';
	} else {
		echo 'Failure.';
	}
} else {
	$statement = $db->prepare("SELECT * FROM sections;");
	if($statement->execute()) {
		$result = $statement->get_result();
		while($row = $result->fetch_row()){
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
	} else {
		echo 'Failure.';
	}
}
?>