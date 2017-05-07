<!DOCTYPE html>
<html>
<head>
	<title>Welcome</title>
</head>
<body>
<h1>Welcome, <?php echo htmlspecialchars($name) ?></h1>
Which one do you prefer?
<ul>
	<?php foreach ($platforms as $platform): ?>
		<li>
			<?= $platform; ?>
		</li>
	<?php endforeach ?>
</ul>
</body>
</html>