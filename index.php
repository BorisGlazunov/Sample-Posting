<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SimplePosting</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<form action="config.php" method="post" id="MainForm">
			<h2 align="center"><pre>Sending Protected Messages</pre></h2>
			<input type="text" name="nick" id="MainForm_username" placeholder="username"><br />
			<textarea name="msg" id="MainForm_message" placeholder="message"></textarea><br />
			<input type="submit" value="Forward" name="add" id="MainForm_submit">
			<input type="reset" value="Сancel" id="MainForm_reset">
		</form>
	</body>
</html>