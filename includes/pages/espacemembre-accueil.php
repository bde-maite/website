<main>
    <div style="display: flex;">

        <?php include("./includes/pages/espacemembre-nav.php"); ?>

        <div class="box" style="width: 80%; text-align: left; display: inline-block; margin: 75px 50px 75px 20px;">
            
            <!-- ACCUEIL -->
            <h1 style="text-align:center;">ESPACE MEMBRE</h1>
            <br />
            <br />
            <p>Bonjour et bienvenue <b><?php echo($_SESSION['Data']['FirstName'] . " " . $_SESSION['Data']['LastName']); ?></b>.</p>
            <p>Dans cet espace, vous pourrez participer aux sondages, consulter les resultats des sondage et lire les e-mails entrants et sortants de la boîte mail du secretaire.</p>
            <br />
            <br />
            <p>Merci de noter que cet outil est en phase de test. Veuillez rapporter tout problème à <a target="_blank" href="mailto:contact@bde-maite.fr">contact@bde-maite.fr</a>.</p>

            <?php

                $bdd_prefix = $db['prefix'];

                $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Polls");
                $query->execute();
                $result = $query->get_result();
                $query->close();
                $count = 0;
                while ($row = $result->fetch_assoc()) {
                    $hasParticipated = hasParticipated($row['Results'], $_SESSION['Data']['ID']);
                    
                    if (!$hasParticipated && $row['Status'] == "Ouvert") {
                        $count++;
                    }
                }
                if ($count > 0) {
                    
            ?>
                <br />
                <br />
                <div class="alert alert-warning">
                    <p><i class="fas fa-exclamation-triangle"></i> &nbsp; Vous n'avez pas répondu à <b><?php echo($count); ?></b> sondage en cours !</p>
                </div>
            <?php } ?>

        </div>

    </div>
</main>