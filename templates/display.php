<?
	ksort($files);
?>
<!doctype html>
<html>
<head>
<style>
dl {
	margin: 0;
	padding: 0;
}
dt {
	cursor: pointer;
}
dd {
	background-color: #ccc;
	border-radius: 10px;
	font-family: monospace;
	margin: 0;
	padding: 0.5em;
	white-space: pre;
}
</style>
</head>
<body>
	<dl>
	<? foreach ($files as $file => $content): ?>
		<dt><?= $file ?></dt>
		<dd><?= htmlentities($content) ?></dd>
	<? endforeach; ?>
	</dl>
<script>
	var elements = document.querySelectorAll('dd'),
		i;
	for (i in elements) {
		elements[i].style.display = 'none';
	}

	document.addEventListener("click", function (event) {
		if (event.target.nodeName === 'DT') {
			var sibling = event.target.nextSibling;
			while (sibling.nodeName !== 'DD') {
				sibling = sibling.nextSibling;
			}
			if (sibling.style.display === 'none') {
				sibling.style.display = '';
				event.target.style.fontWeight = 'bold';
			} else {
				sibling.style.display = 'none';
				event.target.style.fontWeight = 'normal';
			}
		}
	});
</script>
</body>
</html>
