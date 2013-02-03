<?php
  error_reporting(E_ALL);

  require_once 'swiftmailer/swift_required.php';
  require_once 'signature-to-image.php';

  try
  {

    if(isset($_POST["Email"])) {

      $json = $_POST['output'];
      $img = sigJsonToImage($json, array('imageSize' => array(905, 400),'drawMultiplier'=> 4));

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

      $message = Swift_Message::newInstance();
      $message = $message
        ->setSubject('Werkbon')
        ->setFrom(array('info@overeemtelecom.nl' => "Overeem Telecom B.V."))
        //->setTo(array('overeemm@gmail.com' => "Michiel Overeem"))
        ->setTo(array($_POST["Email"] => $_POST["Contactpersoon"]))
        ->setBody(
'<html>'.
'<head></head>'.
'<body style="background-color: #fff;font-family: sans-serif; font-size: 13px;"> ' .
'<div style="background-color: #f8f8f8; font-weight: bold; font-size: 16px;"> '.
'  <img src="' . $message->embed(Swift_Image::fromPath('images/logo.jpg')) . '" style="float:left; width: 50px;" /> '.
'<p style="font-size: 16px; margin-left: 20px; float: left;vertical-align: middle;">Werkbon - Overeem Telecom B.V.</p> '.
'<div style="clear:both;"> </div>'.
'</div>'.
'<br /><br />'.
'<h4>Klantgegevens</h4>'.
'<table>'.
'<tr><th style="text-align: left;">Bedrijf</th><td>' . $_POST["Bedrijf"] . '</td></tr>' .
'<tr><th style="text-align: left; vertical-align:top;">Adres</th><td style="vertical-align:top;">' . $_POST["Adres"] . '<br />' . $_POST["Postcode"] . '<br />' . $_POST["Plaats"] . '</td></tr>' .
'<tr><th style="text-align: left;">Telefoonnummer</th><td>' . $_POST["Telefoonnummer"] . '</td></tr>' .
'<tr><th style="text-align: left;">Contactpersoon</th><td>' . $_POST["Contactpersoon"] . '</td></tr>' .
'<tr><th style="text-align: left;">Email</th><td>' . $_POST["Email"] . '</td></tr>' .
'</table>'.
'<br /><hr /><br />'.
'<h4>Gewerkte tijden</h4>'.
$tijden .
'<br /><hr /><br />'.
'<h4>Gebruikte materialen</h4>'.
$materialen .
'<br /><hr /><br />'.
'<h4>Handtekening voor akkoord<</h4>'.
'<img style="width: 400px;" src="' . $message->embed(Swift_Image::fromPath($signaturefilename)) . '"/>'.
'</body>'.
'</html>', 'text/html')
        ->addPart('Helaas ondersteunen wij alleen HTML e-mails.', 'text/plain');

      $transport = Swift_MailTransport::newInstance();
      $mailer = Swift_Mailer::newInstance($transport);
      $mailer->send($message);
      
      unlink($signaturefilename);

      header('Location: http://wb.overeemtelecom.nl/');
    } else {
      header('Location: http://wb.overeemtelecom.nl/#fout');
    }

  } catch (Exception $e) {
    echo $e->getMessage();
  } 
?>