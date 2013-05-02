<?php

  if(!isset($_COOKIE['wb_auth']) || $_COOKIE['wb_auth'] == ""){
    header('Location: /oauth2.php');
  }

  $success = isset($_COOKIE['wb_succes']) && $_COOKIE['wb_succes'] == "top";
  setcookie("wb_succes", '', time()+60, "/", "overeemtelecom.nl", true, true);

?><!DOCTYPE html>

<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no" />

  <title>Werkbonnen</title>
  
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
  <link rel="stylesheet" href="signaturepad/jquery.signaturepad.css">
  <link rel="stylesheet" href="stylesheets/app.css">

  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="apple-touch-icon-precomposed" href="images/apple.png"/>
  <link rel="apple-touch-startup-image" href="images/splash.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)">
  
  <script type="text/javascript" language="javascript">

    function updateOrientation() {
      
      $('.sigWrapper').removeClass("orientleft").removeClass("orientright");
      if (window.orientation == -90) {
        $('.sigWrapper').addClass("orientright");
      }
      if (window.orientation == 90) {
        $('.sigWrapper').addClass("orientleft");
      }
      if (window.orientation == 0) {
      }
    }
  </script>

</head>
<body onorientationchange="updateOrientation();">

  <div class="fixed">
    <nav class="top-bar">
      <ul class="title-area">

    <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="step1 active"><a href="#"><span>Wie</span></a></li>
        <li class="step2"><a href="#"><span>Wat</span></a></li>
        <li class="step3"><a href="#"><span>Notities</span></a></li>
        <li class="step4"><a href="#"><span>Verstuur</span></a></li>
        
      </ul>
    </nav>
  </div>

  <form method="post" action="mailer.php" class="werkbon">
    <input type="hidden" name="from_email" value="" />
    <input type="hidden" name="from_name" value="" />
  
    <div class="row">

        <div class="twelve columns step1">
          <input type="text" name="Bedrijf" placeholder="Bedrijf" />
          <input type="text" name="Adres" placeholder="Adres" />
          <input type="text" class="postcode" name="Postcode" placeholder="Postcode" />
          <input type="text" class="plaats" name="Plaats" placeholder="Plaats" />
          <input type="tel" name="Telefoonnummer" placeholder="Telefoonnummer" />
          <input type="text" name="Contactpersoon" placeholder="Contactpersoon" />
          <input type="email" name="Email" placeholder="E-mail adres" />
          <input type="text" name="Referentie" placeholder="Referentie" />
          
        </div>

        <div class="twelve columns step2">
          <textarea name="Opmerkingen" placeholder="Overig"></textarea>
        </div>

        <div class="twelve columns step2 momenten">
          <input class="momenten" type="date" name="Datum[]" placeholder="Datum" />
          <input class="momenten start" type="time" name="Begintijd[]" placeholder="08:00" />
          <input class="momenten eind" type="time" class="end" name="Eindtijd[]" placeholder="17:00" />
          
          <div class="clear"> </div>

          <input class="momenten" type="date" name="Datum[]" placeholder="Datum" />
          <input class="momenten start" type="time" name="Begintijd[]" placeholder="08:00" />
          <input class="momenten eind" type="time" class="end" name="Eindtijd[]" placeholder="17:00" />
          
          <div class="clear"> </div>
        </div>

        <div class="twelve columns step2 onderdelen">
          <input class="onderdelen" type="number" name="Aantal[]" placeholder="Nr" />
          <input class="onderdelen" type="text" name="Omschrijving[]" placeholder="Omschrijving" value="Voorrijtarief" />

          <div class="clear"> </div>

          <input class="onderdelen" type="number" name="Aantal[]" placeholder="Nr" />
          <input class="onderdelen" type="text" name="Omschrijving[]" placeholder="Omschrijving" />

          <div class="clear"> </div>
        </div>

        <div class="twelve columns step3">
          <textarea class="high" name="Notities" placeholder="Notities"></textarea>
          <input type="hidden" name="BedrijfsCode"/>
        </div>

        <div class="twelve columns step4">

          <div class="sig" id="sig">

            <ul class="sigNav">
              <li class="clearButton"><a href="#clear">Opnieuw</a></li>
            </ul>

            <div class="sig sigWrapper">
              <div class="typed"></div>
              <canvas class="pad" width="290" height="330"></canvas>
              <input type="hidden" name="output" class="output">
            </div>

            <input class="button secondary" type="submit" id="verstuur" value="Versturen" />
          </div>
        </div>

    </div>
  </form>


  <script src="javascripts/jquery-1.9.0.min.js"></script>
  <script src="javascripts/bootstrap.min.js"></script>
  <script src="signaturepad/jquery.signaturepad.min.js"></script>
  <script>

    var user = JSON.parse("<?php echo $_COOKIE['wb_auth']; ?>");
    $("input[name='from_name']").val(user.name);
    $("input[name='from_email']").val(user.email);

    function addProductTypeahead() {

      //$("input.onderdelen[type='text']").typeahead('destroy');

      $("input.onderdelen[type='text']").typeahead({
        source: function (query, process) {
          return $.getJSON('/wefact.php', { product: query }, function (data) {

            var products = [];
            $.each(data, function (i, p) {
              products.push(p.name);
            });

            return process(products);
          });
        }
      });
    }

    function getCompanyInfo(code) {
      $("input[name='BedrijfsCode']").val("");
      $.getJSON('/wefact.php', { bedrijfcode: code }, function (data) {

        //$("input[name='Bedrijf']").val(data.name);
        $("input[name='Adres']").val(data[0].adres);
        $("input[name='Postcode']").val(data[0].postcode);
        $("input[name='Plaats']").val(data[0].plaats);
        $("input[name='Email']").val(data[0].email);
        $("textarea[name='Notities']").val(data[0].notities);
        $("input[name='BedrijfsCode']").val(code);
        $("input[name='Telefoonnummer']").val(data[0].telefoon);
        if(data[0].voorletters && data[0].achternaam) {
          $("input[name='Contactpersoon']").val(data[0].voorletters + " " + data[0].achternaam);
        } else {
          $("input[name='Contactpersoon']").val("");
        }
      });
    }

    function addCompanyTypeahead() {

      //$("input.onderdelen[type='text']").typeahead('destroy');
      var bedrijvenMap = {};
      $("input[name='Bedrijf']").typeahead({
        source: function (query, process) {
          return $.getJSON('/wefact.php', { bedrijf: query }, function (data) {

            var bedrijven = [];
            $.each(data, function (i, p) {
              bedrijven.push(p.name);
              bedrijvenMap[p.name] = p.id;
            });

            return process(bedrijven);
          });
        },
        updater: function (item) {
          getCompanyInfo(bedrijvenMap[item]);
          return item;
        }
      });
    }

    function addOnderdeel() {
      if($("input.onderdelen[type='number']").last().val() != ""
          || $("input.onderdelen[type='text']").last().val() != "")
      {
        $("<input class='onderdelen' type='number' name='Aantal[]' placeholder='Nr' />" +
          "<input class='onderdelen' type='text' name='Omschrijving[]' placeholder='Omschrijving' />" +
          "<div class='clear'> </div>").appendTo($("div.onderdelen"));

        addProductTypeahead();
      }
    }

    function addMoment() {

      if($("input.momenten[type='date']").last().val() != ""
          || $("input.momenten.start").last().val() != ""
          || $("input.momenten.eind").last().val() != "")
      {
        $("<input class='momenten' type='date' name='Datum[]' placeholder='Datum' />" +
          "<input class='momenten start' type='time' name='Begintijd[]' placeholder='08:00' />" +
          "<input class='momenten eind' type='time' class='end' name='Eindtijd[]' placeholder='17:00' />" +
          "<div class='clear'> </div>").appendTo($("div.momenten"));
      }
    }

    $(document).ready(function() {
      
      $("nav li a").click(function (event) {
        if(!$(this).parent().hasClass("active")){
          $("div.twelve.columns").hide();
          $("nav li").removeClass("active");
          if($(this).parent().hasClass("step1")){
            $("div.twelve.columns.step1").show();
          } else if($(this).parent().hasClass("step2")){
            $("div.twelve.columns.step2").show();
          } else if($(this).parent().hasClass("step3")){
            $("div.twelve.columns.step3").show();
          } else if($(this).parent().hasClass("step4")){
            $("div.twelve.columns.step4").show();
          }
          $(this).parent().addClass("active");
        }
        event.preventDefault();
      });


      addProductTypeahead();
      addCompanyTypeahead();

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
      $('.sig').show();

      //$("#akkoord").click(function (event) {
      //  event.stopPropagation();
      //  event.preventDefault();
        
        
        //$('.sig').get(0).scrollIntoView();
      //});

      $("input.momenten[type='date']").first().val(currentDate);
      $("input.momenten.eind").first().val(currentTime);

      $("div.onderdelen").on("change", "input.onderdelen", function (event) {
        addOnderdeel();
      });

      $("div.momenten").on("change", "input.momenten", function (event) {
        addMoment();
      });
    });

    function serializeForm($form) {
      var data = {};
      $form.find("input, textarea").each(function(i, e){
        var $e = $(e);
        var ename = $e.prop("name");
        if(data[ename]){
          if(!data[ename].push) {
            var val = data[ename];
            data[ename] = [val];
          }
          data[ename].push($e.val());
        } else {
          data[ename] = $e.val();
        }
      });
      return data;
    }

    function deserializeForm($form, data) {
      for(var key in data){
        if(data[key].push){
          for(var i = 0; i < data[key].length; i++){
            var val = data[key][i];
            var elem = $form.find("*[name='"+key+"']:eq("+i+")");
            if(elem.length == 0) {
              addMoment();
              addOnderdeel();
              elem = $form.find("*[name='"+key+"']:eq("+i+")");
            }
            elem.val(val);
          }
        } else {
          $form.find("*[name='"+key+"']").val(data[key]);
        }
      }
    }

    function saveForm() {
      var data = serializeForm($("form"));
      window.localStorage.setItem("werkbon", JSON.stringify(data));
    }

    <?php
    if($success){
      ?>
      window.localStorage.removeItem("werkbon");
      <?php
    }
    ?>


    if(location.hash === "#goed"){
      window.localStorage.removeItem("werkbon");
    } else {
      deserializeForm($("form"), JSON.parse(window.localStorage.getItem("werkbon"))); 
    }
    window.setInterval(saveForm, 500);
  </script> 
  
</body>
</html>
