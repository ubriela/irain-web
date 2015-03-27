<!DOCTYPE html>
<head>
<meta charset="utf-8">
</head>
<body>
	<div style="width: 90%">
		<div style="font-size: 1.5emcolor:#808080">
			<p>
				Please use the link below reset your password:<br/>
				<?php echo anchor("user/reset_password/" . $reset_pass, 'Click to Reset Password', 'title="Reset Password"')?>
			</p>
		</div>
	</div>
</body>
</html>