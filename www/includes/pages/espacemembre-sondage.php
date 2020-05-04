<main>
    <div style="display: flex;">

        <?php include("./includes/pages/espacemembre-nav.php"); ?>

        <div class="box" style="width: 80%; text-align: left; display: inline-block; margin: 75px 50px 75px 20px;max-height: 800px; overflow-y: overlay;">
            
            <!-- SONDAGE -->

            <?php

                if (isset($_GET['sondage']) && $_GET['sondage'] != "" && $_GET['sondage'] != "-1") {
                    include("./includes/pages/espacemembre-sondage-view.php");
                } elseif (isset($_GET['sondage']) && $_GET['sondage'] != "" && $_GET['sondage'] == "-1") {
                    include("./includes/pages/espacemembre-sondage-create.php");
                } else {
                    include("./includes/pages/espacemembre-sondage-list.php");
                }

            ?>

        </div>

    </div>
</main>