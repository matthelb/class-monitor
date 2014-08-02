<?php
require_once('./lib/functions.php');
require_once('./lib/aws/aws-autoloader.php');
require_once('./config.php');
require_once('./dbconfig.php');

use Aws\Ses\SesClient;

function notify($email, $registered, $available, $sectionId, $courseId, $semesterId) {
	$client = SesClient::factory(array(
	  'key' => AWS_ACCESS_KEY_ID,
	  'secret' => AWS_SECRET_ACCESS_KEY,
	  'region'  => 'us-east-1'
	));
	$subject = "USC Class Monitor - %s [%s](%s) Available!";
	$message = "There are %d spots available for section %s of course %s during semester %s.";
	return $client->sendEmail(array(
    'Source' => DEFAULT_FROM_ADDRESS,
    'Destination' => array(
      'ToAddresses' => array($email)
    ),
    'Message' => array(
      'Subject' => array(
        'Data' => sprintf($subject, $courseId, $sectionId, $semesterId)
      ),
      'Body' => array(
        'Text' => array(
          'Data' => sprintf($message, $available - $registered, $sectionId, $courseId, $semesterId)
        )
      ),
    ),
    'ReturnPath' => DEFAULT_RETURN_ADDRESS
	));
}

$db = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
if ($_SERVER && array_key_exists('REQUEST_METHOD', $_SERVER) && $_SERVER['REQUEST_METHOD'] == 'POST') {
	$statement = $db->prepare("INSERT INTO sections (section_id, course_id, semester_id, email) VALUES (?, ?, ?, ?)");
	$statement->bind_param('isis', $_POST['sectionId'], $_POST['courseId'], $_POST['semesterId'], $_POST['email']);
	if ($statement->execute()) {
		echo 'Success.';
	} else {
		echo 'Failure.';
	}
} else {
	$statement = $db->prepare("SELECT `section_id`, `course_id`, `semester_id`, `email` FROM sections;");
	if($statement->execute()) {
		$statement->bind_result($sectionId, $courseId, $semesterId, $email);
		while($statement->fetch()){
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