<?php 
	ob_start (); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>SimplePosting</title>
		<?php
			include_once ("config.php");
		?>
		<style>
			<?php
				include_once ("style.css");
			?>
		</style>
	</head>
	<body>
		<?php 
			$PostingModel = new Posting ("sql213.epizy.com","epiz_23141096","rXfP2pgOX","epiz_23141096_hidden_post");
			print_r ($PostingModel->outputMainForm ());
		?>
		<table>
			<tr>
				<td><div class="BaseMenu" align="center">
					<pre class="MainMenu"><a href="index.php" style="text-decoration: none;">Index page</a></pre>
					<pre class="MainMenu"><a href="http://yodaa.epizy.com/GenerateRandomAnswers/index.php" style="text-decoration: none;">Random Answers</a></pre>
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
							$PostingModel->outputAccess ();
							$PostingModel->mainNewUsr ();
						?>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php
	ob_end_flush (); 
?>