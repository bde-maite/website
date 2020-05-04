            <table style="max-height: 800px;">
				<thead>
                    <tr class="table-head">
                        <td style="width: 55%; color: #fff;"><b>Sondage</b> &nbsp; <a href="./nouveau/#scroll" title="Créer un sondage" style="color: white;"><i class="fas fa-plus"></i></a></td>
                        <td style="width: 15%; color: #fff;"><b>Statut</b></td>
                        <td style="width: 15%; color: #fff;"><b>Réponses</b></td>
                        <td style="width: auto; color: #fff;"><b>Actions</b></td>
                    </tr>
				</thead>
				<tbody>
										
                    <?php 

                        $bdd_prefix = $db['prefix'];

                        $query = $connection->prepare("SELECT * FROM {$bdd_prefix}Polls ORDER BY ID DESC");
                        $query->execute();
                        $result = $query->get_result();
                        $query->close();
                        while ($row = $result->fetch_assoc()) {
                            
                            $participantCount = sizeof(json_decode($row['Results'], true));
                            $hasParticipated = hasParticipated($row['Results'], $_SESSION['Data']['ID']);
                            $participationWarning = (!$hasParticipated && $row['Status'] == "Ouvert") ? " &nbsp; <i style='color: #e74c3c;' title=\"Vous n'avez pas répondu à ce sondage.\" class='fas fa-exclamation-triangle'></i>" : "";

                            echo("<tr><td><a href='/espace-membre/sondage/" . $row['ID'] . "/#scroll'>" . htmlspecialchars($row['Question']) . "</a></td>");
                            echo("<td>" . $row['Status'] . "</td>\n");
                            echo("<td>" . $participantCount . "/" . getMemberCount($db, $connection) . $participationWarning . "</td>\n");
                            echo("<td><a href='/espace-membre/sondage/" . $row['ID'] . "/#scroll' title='Voir le sondage'><i class='fas fa-eye'></i></a></tr>");

                        }

                    ?>

                </tbody>
            </table>