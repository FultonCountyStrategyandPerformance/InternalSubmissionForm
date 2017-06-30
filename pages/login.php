<?php
echo '<h1 style="text-align:center">Departmental Performance Reporting System</h1>
<div id="img"><img src="images/logo.png" /></div>
<div class="form-style-5">
<h1 id="login">LOGIN</h1><br />
<form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
<input type="email" name="user" required placeholder="Enter email address"  />
<input type="password" name="password" required placeholder="Password"  />
<input type="submit"  value="Submit"/>
</form></div>';
?>
