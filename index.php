<html>
	<body>
		<h1>Add Class Monitor</h1>
		<form method="post" action="monitor.php">
			<label>Section ID: </label><input type="text" name="sectionId"/> <span>(#####)</span><br/>
			<label>Course ID: </label><input type="text" name="courseId"/>	<span>(CODE-###)</span><br/>
			<label>Semester ID: </label><input type="text" name="semesterId"/>	<span>(#####)</span><br/>
			<label>Email: </label><input type="text" name="email"/><br/>
			<input type="submit" value="Submit"/>
		</form>
		<br/>
		<h1>Review Monitored Classes</h1>
		<form method="get" action="view.php">
			<label>Email: </label><input type="text" name="email"/><br/>
			<input type="submit" value="View"/>
		</form>
	</body>
</html>