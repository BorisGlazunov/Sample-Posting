<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SimplePosting</title>
		<?php
		include_once("config.php");
		?>
		<style>
		<?php 
        include_once("style.css");
		?>
		</style>
	</head>
	<body>
		<form action="index.php" method="post" id="MainForm">
			<h2 align="center"><pre>Sending Protected Messages</pre></h2>
			<input type="text" name="nick" id="MainForm_username" placeholder="username"><br />
			<textarea name="msg" id="MainForm_message" placeholder="message"></textarea><br />
			<input type="submit" value="Forward" name="add" id="MainForm_submit">
			<input type="reset" value="Сancel" id="MainForm_reset">
		</form>
		<table>
			<tr>
				<td><div class="BaseMenu" align="center">
					<pre class="MainMenu"><a href="index.php" style="text-decoration: none;">Index page</a></pre>
					<pre class="MainMenu"><a href="http://GenerateRandomAnswers/index.php" style="text-decoration: none;">Random Answers</a></pre>
					<pre class="MainMenu"><a href="http://Striptegs/index.php" style="text-decoration: none;">Strip Tags</a></pre>
					<pre class="MainMenu"><a href="http://NewDataBase/index.php" style="text-decoration: none;">Database</a></pre>
					<pre class="MainMenu"><a href="https://ideone.com/myrecent" style="text-decoration: none;">Ideon</a></pre>
					<pre class="MainMenu"><a href="https://github.com/BorisGlazunov?tab=repositories" style="text-decoration: none;">GitHub repositorie</a></pre>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="outputMessages">
							<?php
			 				$objSql = new Posting();
							$objSql->inputData();
							$objSql->outputData();
							?>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>