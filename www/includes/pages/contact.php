<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
var onloadCallback = function() {
    grecaptcha.render('html_element', {
    'sitekey' : '<?php echo($recaptcha['public']); ?>'
    });
};
</script>

<main>

    <div class="box" style="text-align: left; margin: 50px auto;">

        <h1 style="text-align: center;">NOUS CONTACTER</h1>


            <form action="./" method="POST">


                    <label>Votre nom  <sup>*</sup><br />
                        <input type="text" name="name" required />
                    </label><br /><br />

                    <label>Votre adresse e-mail <sup>*</sup><br />
                        <input type="email" name="email" required />
                    </label><br /><br />

                    <label>Votre message  <sup>*</sup><br />
                        <textarea name="message" required></textarea>
                    </label><br /><br />

                    <div class="g-recaptcha" data-sitekey="<?php echo($recaptcha['public']); ?>"></div>
                    <br />

                    <input type="hidden" name="action" value="contact" />

                    <input type="submit" value="Envoyer" />

                </form>


                <?php
                if (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "contact" && $_SESSION['Hints']['type'] == "warning") {
                    echo("<br /><br /><div class='alert alert-warning'><p> <i class='fas fa-exclamation-triangle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                } elseif (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "contact" && $_SESSION['Hints']['type'] == "info") {
                    echo("<br /><br /><div class='alert alert-info'><p> <i class='fas fa-info-circle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                }
                ?>

    </div>


</main>