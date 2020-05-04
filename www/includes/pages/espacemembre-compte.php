<div id='editemail' class='modal'><a data-modal='editemail' class='close-modal' href='#scroll'><i class='fas fa-times'></i></a>
    <div>
        <b>Changer mon adresse e-mail : </b><br /><br /><hr /><br /><br />
        <form action="./" method="POST">
            
            <label>
                <span>Mot de passe actuel <sup>*</sup> <br /></span>
                <input type="password" name="passwd" required /> <br />
            </label>
            
            <label>
                <span>Nouvelle adresse e-mail <sup>*</sup> <br /></span>
                <input type="email" name="newemail" required /> <br />
            </label>

            <input type="hidden" name="action" value="edit-email" />
            <br />
            <br />
            <input type="submit" value="VALIDER" />

        </form>
    </div>
</div>

<div id='editpasswd' class='modal'><a data-modal='editpasswd' class='close-modal' href='#scroll'><i class='fas fa-times'></i></a>
    <div>
        <b>Changer mon mot de passe : </b><br /><br /><hr /><br /><br />
        <form action="./" method="POST">
            
            <label>
                <span>Mot de passe actuel <sup>*</sup> <br /></span>
                <input type="password" name="passwd" required /> <br />
            </label>

            <label>
                <span>Nouveau mot de passe <sup>*</sup> <br /></span>
                <input type="password" name="passwd1" required /> <br />
            </label>

            <label>
                <span>Nouveau mot de passe (confirmation) <sup>*</sup> <br /></span>
                <input type="password" name="passwd2" required /> <br />
            </label>

            <input type="hidden" name="action" value="edit-passwd" />
            <br />
            <br />
            <input type="submit" value="VALIDER" />

        </form>
    </div>
</div>

<div id='downloaddata' class='modal'><a data-modal='downloaddata' class='close-modal' href='#scroll'><i class='fas fa-times'></i></a>
    <div>
        <b>Télécharger mes données : </b><br /><br />
        <p>Conformément aux dispositions du Règlement Général sur la Protection des Données, vous disposez d'un droit d'accès et de rectification de vos données personnelles. <br />
        Vous pouvez ici accéder à vos données personnelles. Pour exercer votre droit de rectification, merci de bien vouloir contacter <a target="_blank" href="mailto:contact@bde-maite.fr">contact@bde-maite.fr</a></p>
        <br /><br /><hr /><br /><br />
        <form action="./" method="POST">
            
            <label>
                <span>Mot de passe actuel <sup>*</sup> <br /></span>
                <input type="password" name="passwd" required /> <br />
            </label>

            <input type="hidden" name="action" value="download-data" />
            <br />
            <br />
            <input type="submit" value="VALIDER" />

        </form>
    </div>
</div>

<main>
    <div style="display: flex;">

        <?php include("./includes/pages/espacemembre-nav.php"); ?>

        <div class="box" style="width: 80%; text-align: left; display: inline-block; margin: 75px 50px 75px 20px;">
            
            <!-- PROFIL -->
            <h1 style="text-align:center;">PROFIL</h1>
            <br />
            <br />
            
            <table>
				<thead>
				</thead>
				<tbody>
										
                    <tr>
                        <td><b>Nom et prenom</b></td>
                        <td><?php echo($_SESSION['Data']['LastName'] . " " . $_SESSION['Data']['FirstName']); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>Adresse e-mail</b></td>
                        <td><?php echo($_SESSION['Data']['EMail']); ?></td>
                        <td><a class="show-modal" data-modal="editemail" href="#scroll">Modifier</a></td>
                    </tr>	
                    <tr>
                        <td><b>Mot de passe</b></td>
                        <td>************</td>
                        <td><a class="show-modal" data-modal="editpasswd" href="#scroll">Modifier</a></td>
                    </tr>

                </tbody>
            </table>

            <?php
                if (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "edit" && $_SESSION['Hints']['type'] == "warning") {
                    echo("<br /><br /><div class='alert alert-warning'><p> <i class='fas fa-exclamation-triangle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                } elseif (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "edit" && $_SESSION['Hints']['type'] == "info") {
                    echo("<br /><br /><div class='alert alert-info'><p> <i class='fas fa-info-circle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
                }
            ?>

            <br />
            <br />

            <div style="text-align: center;">
                    <a href="#scroll" class="button-link show-modal" data-modal="downloaddata" >TÉLÉCHARGER MES DONNÉES</a>
            </div>

            <br />
            <br />
            <br />
            <h1 style="text-align:center;">NOTIFICATIONS EMAILS</h1>
            <br />
            <br />
            
            <input type="checkbox" id="cb1" />
            <label for="cb1">Recevoir un e-mail lors de la création d'un sondage</label><br /><br />

            <input type="checkbox" id="cb2" />
            <label for="cb2">Recevoir un e-mail 24h avant la fin d'un sondage si je n'y ai pas participé</label><br /><br />

            <input type="checkbox" id="cb3" />
            <label for="cb3">Recevoir un e-mail lorsque les resultats d'un sondage sont disponibles</label><br /><br />

        </div>

    </div>
</main>