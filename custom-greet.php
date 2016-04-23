<html>
<form>
    <label>Name: <input name="name"/></label>
    <label for="greeting">Greeting:
        <select id="greeting" name="greeting" onchange="changeGreet(event)">
            <option>Hello</option>
            <option>Hi</option>
            <option>Yo</option>
            <option>Hola</option>
        </select>
    </label>
    <button id="submit" name="submit">Say Hello!</button>
</form>
<?php
if (isset($_GET['name']) && isset($_GET['greeting'])) {
    echo "<h1>{$_GET['greeting']} {$_GET['name']}!</h1>";
}
?>
<script type="text/javascript">
    function changeGreet(e) {
        var chosen = e.target.value;
        var newLabel = 'Say ' + chosen + '!';
        console.log(newLabel);
        document.getElementById('submit').innerHTML = newLabel;
    }
</script>
</html>
