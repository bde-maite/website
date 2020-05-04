<?php

$bdd_prefix = $db['prefix'];

$query = $connection->prepare("SELECT * FROM {$bdd_prefix}Polls WHERE ID = ?");
$query->bind_param("i", $_GET['sondage']);
$query->execute();
$result = $query->get_result();
$query->close();
$pollData = $result->fetch_assoc();

if (!isset($pollData['Question']) || $pollData['Question'] == "") {
    echo("<h3>Erreur : Sondage inexistant.</h3>");
} else {
?>


<div style="display: flex;">
    <!-- Vote -->
    <div style="width: 50%;">

        <h3><?php echo(htmlspecialchars($pollData['Question'])); ?></h3>
        <br />
        <form action="./" method="POST">
            <?php

            $hasParticipated = hasParticipated($pollData['Results'], $_SESSION['Data']['ID']);

            $responses = explode("$", $pollData['Responses']);
            $n = 0;
            $type = ($pollData['Type'] == "Unique") ? "radio" : "checkbox";
            $disabled = ($hasParticipated || $pollData['Status'] == "Clos") ? "disabled" : "";
            foreach ($responses as $response) {

                echo("<input class='poll' type='" . $type . "' id='" . $n . "' name='poll-response[]' value='" . $n . "' " . $disabled . " />");
                echo("<label class='poll' for='" . $n . "'>" . htmlspecialchars($response) . "</label><br />");

                $n++;
            }

            ?>
            <br />
            <br />
            <input type="hidden" name="action" value="poll" />
            <input type="hidden" name="poll-id" value="<?php echo($pollData['ID']); ?>" />

            <input type="submit" value="Voter" <?php echo($disabled); ?> />

        </form>

        <?php if (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "poll" && $_SESSION['Hints']['type'] == "warning") {
            echo("<br /><br /><div class='alert alert-warning'><p> <i class='fas fa-exclamation-triangle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
        } elseif (isset($_SESSION['Hints']) && $_SESSION['Hints']['form'] == "poll" && $_SESSION['Hints']['type'] == "info") {
            echo("<br /><br /><div class='alert alert-info'><p> <i class='fas fa-info-circle'></i> &nbsp; " . $_SESSION['Hints']['message'] . "</p></div>");
        } elseif ($hasParticipated)  {
            echo("<br /><br /><div class='alert alert-info'><p> <i class='fas fa-info-circle'></i> &nbsp; Vous avez participé à ce sondage.</p></div>");
        } ?>

    </div>

    <!-- Resultats -->
    <div style="width: 50%;">
        <h3>Resultats</h3><br />
        <?php
            if ($pollData['Status'] == "Clos") {

                #Labels de réponses
                $responses = explode("$", $pollData['Responses']);
                $responseLabel = "";
                foreach ($responses as $response) {

                    $responseLabel .= "'" . $response . "',";
                }
                $responseLabel = substr($responseLabel, 0, strlen($responseLabel) - 1);


                #Resultats des votes
                $responseData = "";
                $pollDataArray = json_decode($pollData['Results'], true);
                $pollArray = array_fill(0, sizeof($responses), 0);
                $x = 0;
                foreach ($pollDataArray as $user => $vote) {
                    $pollArray[$x] = 0;
                    $x++;
                }
                foreach ($pollDataArray as $user => $vote) {
                    $pollArray[$vote] += 1;
                }
                foreach ($pollArray as $nbVote) {
                    $responseData .= $nbVote . ",";
                }
                $responseData = substr($responseData, 0, strlen($responseData) - 1);

            ?>

            <canvas style="" id="results"></canvas>

            <script>
                let results = document.getElementById('results').getContext('2d');

                Chart.defaults.global.defaultFontSize = 9;
                Chart.defaults.global.defaultFontColor = '#333';

                let keyStatsChart1 = new Chart(results, {
                type:'pie',
                data:{
                    labels:[<?php echo($responseLabel); ?>],
                    datasets:[{
                    label:' Resultats ',
                    data:[<?php echo($responseData); ?>],
                    backgroundColor: ['rgba(26, 188, 156,1.0)', 'rgba(46, 204, 113,1.0)', 'rgba(52, 152, 219,1.0)', 'rgba(155, 89, 182,1.0)', 'rgba(241, 196, 15,1.0)', 'rgba(230, 126, 34,1.0)', 'rgba(231, 76, 60,1.0)', 'rgba(253, 121, 168,1.0)', 'rgba(179, 55, 113,1.0)'],
                    hoverBorderWidth:1,
                    }]
                },
                options:{
                    title:{
                    display:false,
                    },
                    legend:{
                    display:true,
                    },
                    layout:{
                    padding:{
                        left:0,
                        right:0,
                        bottom:0,
                        top:0
                    }
                    },
                    tooltips:{
                    enabled:true
                    }
                }
                });
            </script>

            <?php

            } else {
                echo("<p>Les resultats seront disponibles une fois le sondage clos.</p>");
            }
        ?>
    </div>
</div>



<?php } ?>