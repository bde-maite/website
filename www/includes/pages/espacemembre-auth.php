<main style="display: flex;">

    <div style="text-align: center; width: 100%;">


        <div class="box" style="width: 30%; text-align: center; display: inline-block; margin: 75px 10px; height: 600px;">
            <h1>Se connecter</h1><br />

            <form action="./" method="POST">

                <label>
                    <span>Adresse e-mail <sup>*</sup> <br /></span>
                    <input type="email" name="auth-email" required /> <br />
                </label>

                <br />

                <label>
                    <span>Mot de passe <sup>*</sup> <br /></span>
                    <input type="password" name="auth-passwd" required /> <br />
                </label>

                <br />

                <input type="checkbox" id="remember" name="auth-remember" />
                <label for="remember">Se souvenir de moi <i class="fas fa-info-circle" title="Ne pas utiliser sur un ordinateur publique."></i></label>

                <br />
                <br />
                
                <input type="hidden" name="action" value="login" />

                <input type="submit" value="CONNEXION" />

                <?php
                if (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "login" && $_SESSION['Hints']['type'] == "warning") {
                    echo("<br /><br /><div class='alert alert-warning'><p> <i class='fas fa-exclamation-triangle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                } elseif (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "login" && $_SESSION['Hints']['type'] == "info") {
                    echo("<br /><br /><div class='alert alert-info'><p> <i class='fas fa-info-circle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                }
                ?>

                <p class="asterisks">
                    <sup>*</sup> Champs obligatoires.<br />
                    <sup>**</sup>En vous connectant, vous acceptez l'utilisation des cookies nécessaires à l'enregistrement de votre session. Plus d'informations sur les <a target="_blank" href="/cgu">Conditions Génerales d'Utilisation</a>.
                </p>


            </form>

        </div>

        <div class="box" style="width: 30%; text-align: center; display: inline-block; margin: 75px 10px; height: 600px;">
            <h1>S'enregistrer</h1><br />
            
            <form action="./" method="POST">

                <label>
                    <span>Code d'enregistrement <sup>*</sup> <br /></span>
                    <input type="text" name="register-code" required /> <br />
                </label>

                <br />

                <label>
                    <span>Adresse e-mail <sup>*</sup> <br /></span>
                    <input type="email" name="register-email" required /> <br />
                </label>

                <br />

                <label>
                    <span>Mot de passe <sup>*</sup> <br /></span>
                    <input type="password" name="register-passwd1" required /> <br />
                </label>

                <br />

                <label>
                    <span>Mot de passe (confirmation) <sup>*</sup> <br /></span>
                    <input type="password" name="register-passwd2" required /> <br />
                </label>

                <br />

                <input type="hidden" name="action" value="register" />

                <input type="submit" value="ENREGISTREMENT" />

                <?php
                if (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "register" && $_SESSION['Hints']['type'] == "warning") {
                    echo("<br /><br /><div class='alert alert-warning'><p> <i class='fas fa-exclamation-triangle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                } elseif (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "register" && $_SESSION['Hints']['type'] == "info") {
                    echo("<br /><br /><div class='alert alert-info'><p> <i class='fas fa-info-circle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                }
                ?>

            </form>
        </div>
    
    </div>


</main>