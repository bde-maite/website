<h1 style="text-align: center;">Nouveau sondage</h1>
<form action="./" method="POST">

<label>
    <span>Question <sup>*</sup> <br /></span>
    <input type="text" name="question" required /> <br />
</label>


<input type="radio" id="unique" name="type[]" value="Unique" required />
<label for="unique">Question à choix unique</label>

<input type="radio" id="multiple" name="type[]" value="Multiple" required />
<label for="multiple">Question à choix multiple</label>

<br />
<br />

<label>
    <span>Reponse 1 <sup>*</sup> <br /></span>
    <input type="text" name="response[]" required /> <br />
</label>

<label>
    <span>Reponse 2 <sup>*</sup> <br /></span>
    <input type="text" name="response[]" required /> <br />
</label>

<?php
for ($i = 3; $i<=10; $i++) {
    echo("<label>\n");
    echo("    <span>Reponse " . $i . "<br /></span>\n");
    echo("    <input type='text' name='response[]' /> <br />\n");
    echo("</label>\n");
}
?>

<input type="hidden" name="action" value="poll-new" />
<br />
<input type="submit" value="Ajouter le sondage" />


</form>