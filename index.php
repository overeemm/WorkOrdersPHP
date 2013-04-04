<?php

  session_start();

  if(!isset($_SESSION['access_token']) && !isset($_SESSION['email'])){
    header('Location: /oauth2.php');
  }

?><!DOCTYPE html>

<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>werkbonnen | overeemtelecom.nl | <?php echo $_SESSION['email'] ?> | <?php echo $_SESSION['name'] ?></title>
  
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="signaturepad/jquery.signaturepad.css">

  <!--
  <?php
    echo $_SESSION['access_token'];
  ?>
  -->
</head>
<body>

  <div class="row">
  
    <div class="twelve columns">
    
      <form method="post" action="mailer.php" class="werkbon">
        <input type="hidden" name="from_email" value="<?php echo $_SESSION['email'] ?>" />
        <input type="hidden" name="from_name" value="<?php echo $_SESSION['name'] ?>" />

        <div class="twelve columns step1">
          <input type="text" name="Bedrijf" placeholder="Bedrijf" />
          <input type="text" name="Adres" placeholder="Adres" />
          <input type="text" class="postcode" name="Postcode" placeholder="Postcode" />
          <input type="text" class="plaats" name="Plaats" placeholder="Plaats" />
          <input type="tel" name="Telefoonnummer" placeholder="Telefoonnummer" />
          <input type="text" name="Contactpersoon" placeholder="Contactpersoon" />
          <input type="email" name="Email" placeholder="E-mail adres" />
          <input type="text" name="Referentie" placeholder="Referentie" />
          <textarea name="Opmerkingen" placeholder="Overig"></textarea>
        </div>

        <div class="six columns step1 momenten">
          <input class="momenten" type="date" name="Datum[]" placeholder="Datum" />
          <input class="momenten start" type="time" name="Begintijd[]" placeholder="08:00" />
          <input class="momenten eind" type="time" class="end" name="Eindtijd[]" placeholder="17:00" />
          
          <div class="clear"> </div>

          <input class="momenten" type="date" name="Datum[]" placeholder="Datum" />
          <input class="momenten start" type="time" name="Begintijd[]" placeholder="08:00" />
          <input class="momenten eind" type="time" class="end" name="Eindtijd[]" placeholder="17:00" />
          
          <div class="clear"> </div>
        </div>

        <div class="six columns step1 onderdelen">
          <input class="onderdelen" type="number" name="Aantal[]" placeholder="Nr" />
          <input class="onderdelen" type="text" name="Omschrijving[]" placeholder="Omschrijving" />

          <div class="clear"> </div>

          <input class="onderdelen" type="number" name="Aantal[]" placeholder="Nr" />
          <input class="onderdelen" type="text" name="Omschrijving[]" placeholder="Omschrijving" />

          <div class="clear"> </div>
        </div>

        <div class="clear"> </div>

        <button class="button secondary step1" id="akkoord">Akkoord</button>

        <div class="sig" id="sig">

          <ul class="sigNav">
            <li class="clearButton"><a href="#clear">Opnieuw</a></li>
          </ul>

          <div class="sig sigWrapper">
            <div class="typed"></div>
            <canvas class="pad" width="730" height="400"></canvas>
            <input type="hidden" name="output" class="output">
          </div>

          <input class="button secondary" type="submit" id="verstuur" value="Versturen" />
        </div>

      </form>

    </div>
  </div>

  <script src="javascripts/jquery-1.9.0.min.js"></script>
  <script src="signaturepad/jquery.signaturepad.min.js"></script>
  <script>
    $(document).ready(function() {
      
      var now = new Date();
      var minutes = now.getMinutes();
      var hours = now.getHours();
      var days = now.getDate();
      var months = now.getMonth() + 1;
      while((minutes % 15) != 0) {
        minutes++;
      }
      if(minutes == 60){
        minutes = 0;
        hours++;
      }
      if(minutes < 10){ minutes = "00"; }
      if(days < 10){ days = "0" + days; }
      if(months < 10){ months = "0" + months; }
      var currentDate = now.getFullYear() + "-" + months + "-" + days;
      var currentTime = hours + ":" + minutes;

      $('.werkbon').signaturePad({ defaultAction: "drawIt", drawOnly: true, lineWidth: 0 });
      $('.sig').hide();

      $("#akkoord").click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        
        $('.sig').show();
        $('.sig').get(0).scrollIntoView();
      });

      $("input.momenten[type='date']").first().val(currentDate);
      $("input.momenten.eind").first().val(currentTime);

      $("div.onderdelen").on("keyup", "input.onderdelen", function (event) {
        if($("input.onderdelen[type='number']").last().val() != ""
          || $("input.onderdelen[type='text']").last().val() != "")
        {
          $("<input class='onderdelen' type='number' name='Aantal[]' placeholder='Nr' />" +
            "<input class='onderdelen' type='text' name='Omschrijving[]' placeholder='Omschrijving' />" +
            "<div class='clear'> </div>").appendTo($("div.onderdelen"));
        }
      });

      $("div.momenten").on("keyup", "input.momenten", function (event) {
        if($("input.momenten[type='date']").last().val() != ""
          || $("input.momenten.start").last().val() != ""
          || $("input.momenten.eind").last().val() != "")
        {
          $("<input class='momenten' type='date' name='Datum[]' placeholder='Datum' />" +
            "<input class='momenten start' type='time' name='Begintijd[]' placeholder='08:00' />" +
            "<input class='momenten eind' type='time' class='end' name='Eindtijd[]' placeholder='17:00' />" +
            "<div class='clear'> </div>").appendTo($("div.momenten"));
        }
      });
    });
  </script> 
  
</body>
</html>
