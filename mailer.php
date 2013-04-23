<?php
  error_reporting(E_ALL);

  // defines
  // $from = array('test@example.nl' => "Example");
  // $cc = array('test@example.nl' => "Example");
  require_once 'config.php';
  require_once 'swiftmailer/swift_required.php';
  require_once 'signature-to-image.php';

  try
  {

    if(isset($_POST["Bedrijf"])) {

      $json = $_POST['output'];
      $img = sigJsonToImage($json, array('imageSize' => array(730, 400),'drawMultiplier'=> 4));

      $signaturefilename = uniqid() . ".png";
      imagepng($img, $signaturefilename);
      imagedestroy($img);

      $tijden = '<table>';
      foreach ($_POST["Datum"] as $i => $datum) { 
        $tijden .= '<tr>' .
                   '  <th style="text-align: left;">' . $datum . '</th>' .
                   '  <td>' . $_POST["Begintijd"][$i] . ' - ' . $_POST['Eindtijd'][$i] . '</td>' .
                   '</tr>';
      }
      $tijden .= '</table>';

      $materialen = '<table>';
      foreach ($_POST["Aantal"] as $i => $aantal) { 
        $materialen .= '<tr>' .
                             '  <td>' . $aantal . '</td>' .
                             '  <th style="text-align: left;">' . $_POST['Omschrijving'][$i] . '</th>' .
                             '</tr>';
      }
      $materialen .= '</table>';

      $to = $from;
      if(isset($_POST["Email"])) {
        $to = array($_POST["Email"] => $_POST["Contactpersoon"]);
      }

      $message = Swift_Message::newInstance();
      $message = $message
        ->setSubject('Werkbon voor ' . $_POST["Bedrijf"])
        ->setFrom(array($_POST["from_email"] => $_POST["from_name"]))
        ->setTo($to)
        ->setCc($cc)
        ->setBody(

'<html>'.
	'<head>'.
	'<title></title>'.
	'</head>'.
	'<body>'.
		'<table align="left" border="0" cellpadding="0" cellspacing="0" height="100%" width="700">'.
		'<tbody>'.
		'<tr><td valign="top"><table border="0"><tbody><tr>'.
			'<td colspan="2" style="font-weight: normal;">
			Hierbij ontvangt u een digitale werkbon van Overeem Telecom. De werkbon heeft betrekking op de door Overeem Telecom aan u, of aan uw relaties geleverde diensten en/of services.&nbsp;<br />
			&nbsp;</td></tr>'.
		'<tr><td>Opdrachtgever:</td><td><b>' . $_POST["Bedrijf"] . '</b></td></tr>'.
		'<tr><td>Referentie:</td><td><b>' . $_POST["Referentie"] . '</b></td></tr>'.
		'<tr><td>Adres</td><td><b>' . $_POST["Adres"] . '</b></td></tr>'.
		'<tr><td>&nbsp;</td><td><b>' . $_POST["Postcode"] . ' ' . $_POST["Plaats"] . '</b></td></tr>'.
				'<tr><td>Telefoon</td><td><b>' . $_POST["Telefoonnummer"] . '</b></td></tr>'.
		'<tr><td>Contactpersoon</td><td><b>' . $_POST["Contactpersoon"] . '</b></td></tr>'.
		'<tr><td>Email adres</td><td><b>' . $_POST["Email"] . '</b></td></tr>'.
		'<tr><td colspan="2"><Hr/></td></tr>'.
		'<tr><td colspan="2">Aankomst en - vertrektijd:</td><td></tr>'.
		'<tr><td colspan="2">' . $tijden . '</td><td></tr>'.
		'<tr><td colspan="2"><Hr/></td></tr>'.
		'<tr><td colspan="2">Gebruikte materialen:</td><td></tr>'.
		'<tr><td colspan="2">' . $materialen . '</td><td></tr>'.
		'<tr><td colspan="2"><Hr/></td></tr>'.
		'<tr><td>Werkzaamheden:</td><td><b>' . $_POST["Opmerkingen"] . '</b></td></tr>'.
		'<tr><td colspan="2"><Hr/></td></tr>'.
		'<tr><td colspan="2">Handtekening voor akkoord:</td><td></tr>'.
		'<tr><td colspan="2"><img style="width: 300px;" src="' . $message->embed(Swift_Image::fromPath($signaturefilename)) . '"/></td><td></tr>'.
		'<tr><td colspan="2"><Hr/></td></tr>'.
		'<tr><td colspan="2" style="color: rgb(255, 128, 0);">Belangrijke mededeling:</td></tr>'.
		'<tr><td colspan="2" style="font-weight: normal;">
			U dient deze digitale werkbon als originele werkbon te beschouwen en deze conform de reglementen van de Belastingdienst zelf uit te printen en toe te voegen aan uw eigen administratie. Mocht u hier niet toe in staat zijn of liever de originele werkbon per post te ontvangen neemt u dan even contact met ons op via het e-mailadres: <a href="mailto:info@overeemtelecom.nl">info@overeemtelecom.nl</a> .</td>'.
		'</tr>'.
		'<tr><td colspan="2" style="color: rgb(255, 128, 0);">Reclamaties:</td></tr>'.
		'<tr><td colspan="2" style="font-weight: normal;">
			Indien u fouten constateert in de werkbon, dan dient u deze (binnen 5 dagen) bij Overeem Telecom te reclameren. Reclamaties kunt u melden via het e-mailadres: <a href="mailto:info@overeemtelecom.nl">info@overeemtelecom.nl</a> . De verichtte werkzaamheden overeenkomstig de algemene voorwaarden van Overeem Telecom blijven bestaan. Eventuele verschillen zullen op een nieuwe werkbon worden gecorrigeerd.</td>'.
		'</tr>'.
		'<tr><td colspan="2" style="color: rgb(255, 128, 0);">Vragen?</td></tr>'.
		'<tr><td colspan="2" style="font-weight: normal;">
			Indien u vragen heeft over deze digitale werkbon kunt u contact opnemen met Overeem Telecom, telefoon: 088-6837336 of stuur een e-mail naar <a href="mailto:planning@overeemtelecom.nl">planning@overeemtelecom.nl</a></td>'.
		'</tr>'.
		'<tr><td colspan="2" style="line-height: 11px; font-family: arial,helvetica,sans-serif; color: rgb(170, 170, 170); font-size: 9px; font-weight: normal; text-decoration: none;"><br />
			De informatie verzonden met dit e-mailbericht is uitsluitend bestemd voor de geadresseerde. Gebruik van deze informatie door anderen dan de geadresseerde is verboden. Openbaarmaking, vermenigvuldiging, verspreiding en/of verstrekking van deze informatie aan derden is niet toegestaan.<br /><br />
			The information contained in this communication is confidential and may be legally privileged. It is intended solely for the use of the individual or entity to whom it is addressed and others authorized to receive it. If you are not the intended recipient, you are hereby notified that any disclosure, copying, distribution, or taking any action in reliance of the contents of this information is strictly prohibited and may be unlawful. Overeem Telecom is neither liable for the proper and&nbsp; complete transmission of the information contained in this communication nor for any delay in its receipt.<br />
			&nbsp;</td>'.
		'</tr>'.
		'<tr><td align="center" colspan="2" style="font-style: italic; font-family: helvetica; color: rgb(255, 128, 0); font-size: 10px;" valign="top">
			Overeem Telecom | Eemweg 31-24 |&nbsp;3755 LC &nbsp;Eemnes | T. 088-6837336 | F. 088-6837337 | E. <a href="mailto:info@overeemtelecom.nl">info@overeemtelecom.nl</a></td>'.
		'</tr>'.
	'</tbody>'.
	'</table>'.
	'</td>'.
	'</tr>'.
	'</tbody>'.
	'</table>'.
	'<br />'.
	'</body>'.
'</html>', 'text/html')
        ->addPart('Helaas ondersteunen wij alleen HTML e-mails.', 'text/plain');

      $transport = Swift_MailTransport::newInstance();
      $mailer = Swift_Mailer::newInstance($transport);
      $mailer->send($message);
      
      unlink($signaturefilename);

      header('Location: /#goed');
    } else {
      header('Location: /#fout');
    }

  } catch (Exception $e) {
    echo $e->getMessage();
  } 
?>