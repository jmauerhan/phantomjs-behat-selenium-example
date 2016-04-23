<html>
<form>
    <label>Name: <input name="name"/></label>
    <button name="submit">Say Hello!</button>
</form>
<?php
if (isset($_GET['name'])) {
    echo '<h1> Hello ' . $_GET['name'] . '!</h1>';
}
?>
</html>
