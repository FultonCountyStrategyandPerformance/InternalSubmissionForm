<?php
echo '<div class="form-style-5">
<h1>LOGIN</h1><br />
<form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
<input type="email" name="user" required placeholder="Enter email address"  />
<input type="submit"  />
</form></div>';
?>
