
<?php
$boite = "INBOX";

switch ($_GET['boite']) {
    case "sortant":
        $boite = "INBOX.INBOX.Sent";
        break;
    case "brouillon":
        $boite = "INBOX.INBOX.Drafts";
        break;
    case "entrant":
    default:
        $boite = "INBOX";
        break;
}


$emailsToEcho = "";

$emailhandle = new Imap();
$connect = $emailhandle->connect(
    $em['server'] . $boite,
    $em['address'],
    $em['password']
);



if ($connect){
    $inbox = $emailhandle->getMessages();
    
    if ($inbox['status'] == 'success') {
        
        $emails = $inbox['data'];

        foreach ($emails as $email) {

            $expName = (isset($email['from']['name'])) ? "<b>" . htmlspecialchars($email['from']['name']) . "</b> &nbsp;-&nbsp; " : "";

            $destName = (isset($email['to']['address'])) ? $email['to']['address'] : "Inconnu" ;
            $destName = ($boite == "INBOX") ? "<b>Secretaire BDE</b> - secretaire@bde-maite.fr" : $destName;


            $emailToEcho = "<tr><td>" . htmlspecialchars($email['subject']) . "</td>";
            $emailToEcho .= "<td>" . $expName . $email['from']['address'] . "</td>";
            $emailToEcho .= "<td>" . $destName . "</td>";
            $emailToEcho .= "<td>" . $email['date'] . "</td>";
            $emailToEcho .= "<td><a data-modal='" . $email['uid'] . "' class='show-modal' href='#scroll' title='Consulter'><i class='fas fa-eye'></i></a></td></tr>";

            $emailsToEcho .= $emailToEcho;

            #Modals
            echo("<div id='" . $email['uid'] . "' class='modal'><a data-modal='" . $email['uid'] . "' class='close-modal' href='#scroll'><i class='fas fa-times'></i></a><div>");
            echo("<b>" . htmlspecialchars($email['subject']) . "</b><br /><br />De : " . $expName . $email['from']['address'] . "<br />A : " . $destName . "<br />Le : " . $email['date'] . "<br /><br /><hr /><br />" . $email['message']);
            echo("</div></div>");

        }
    }
}
?>


<main>
    <div style="display: flex;">

        <?php include("./includes/pages/espacemembre-nav.php"); ?>

        <div class="box" style="width: 80%; text-align: center; display: inline-block; margin: 75px 50px 75px 20px;">
                       
            <!-- EMAILS -->


            <a class="button-link-white" href="/espace-membre/emails/entrant/#scroll" style="width: 20%;"><i class="fas fa-arrow-down"></i> &nbsp; E-mails entrants</a> &nbsp; &nbsp; &nbsp;
            <a class="button-link-white" href="/espace-membre/emails/sortant/#scroll" style="width: 20%;"><i class="fas fa-arrow-up"></i> &nbsp; E-mails sortants</a> &nbsp; &nbsp; &nbsp;
            <a class="button-link-white" href="/espace-membre/emails/brouillon/#scroll" style="width: 20%;"><i class="fab fa-firstdraft"></i> &nbsp; Brouillons</a>

            <br />
            <br />

            
            <table style="max-height: 800px;">
				<thead>
                    <tr class="table-head">
                        <td style="width: 24%; color: #fff;"><b>Sujet</b></td>
                        <td style="width: 28%; color: #fff;"><b>Expediteur</b></td>
                        <td style="width: 28%; color: #fff;"><b>Destinataire</b></td>
                        <td style="width: 15%; color: #fff;"><b>Date</b></td>
                        <td style="width: auto; color: #fff;"><b>Actions</b></td>
                    </tr>
				</thead>
				<tbody style="font-size: 15px;">
										
                   <?php echo($emailsToEcho); ?>

                </tbody>
            </table>


        </div>

    </div>
</main>