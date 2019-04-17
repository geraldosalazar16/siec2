<?php

session_start();
// ------ Verifica que el usuario haya iniciado sesión -------

if ($_SESSION["logged_in"] != "true") {
  header("location: ./login");
}

// -----------------------------------------------------------

// ------ Establece un tiempo límite para la sesión -------

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// -----------------------------------------------------------

// ------ Regenera la sesión para evitar ataques de sesión fija -------

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 600) {
    // session started more than 10 minutes ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

// -----------------------------------------------------------

?>

<?php include "./diff/selector.php"; ?>
<?php include "./diff/".$global_diffname."/strings.php"; ?>

<?php
  $contollerName = $_REQUEST["pagina"];
  if ($_REQUEST["pagina"] == "") {
    $contollerName = "dashboard";
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="./diff/<?php echo $global_diffname; ?>/logo.ico">

  <title><?php echo $global_diffname; ?> </title>

  <!-- Bootstrap core CSS -->

  <link href="css/bootstrap.min.css" rel="stylesheet">

  <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">
  

  <!-- Custom styling plus plugins -->
  <link href="./diff/<?php echo $global_diffname; ?>/custom.css" rel="stylesheet"/>
  <link href="./diff/<?php echo $global_diffname; ?>/this.css" rel="stylesheet"/>
  <link rel="stylesheet" type="text/css" href="css/maps/jquery-jvectormap-2.0.3.css" />
  <link href="css/icheck/flat/green.css" rel="stylesheet" />
  <link href="css/floatexamples.css" rel="stylesheet" type="text/css" />

  <link href="css/uploadfile.css" rel="stylesheet"/>
  <link href="css/calendar/fullcalendar.css" rel="stylesheet"/>
  <link href="css/calendar/fullcalendar.print.css" rel="stylesheet" media="print"/>

   <!-- select2 -->
  <link href="css/select/select2.min.css" rel="stylesheet"/>
  <!-- multiselect -->
  <link href="css/multiselect/multiple-select.min.css" rel="stylesheet"/>
<!-- datepicker -->
  <link href="css/datepicker/angular-datepicker.css" rel="stylesheet"/>
  <link rel="stylesheet" type="text/css" href="css/bootstrap-duallistbox/bootstrap-duallistbox.css"/>
  <link href="css/common.css" rel="stylesheet" type="text/css" />
  <!-- air-datepicker -->
  <link href="js/air-datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  
  <script src="js/angular.min.js"></script>
  <script src="js/certificando-app.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.js"></script>
  <script src="js/nprogress.js"></script>
  <script src="js/input_mask/jquery.inputmask.js"></script>
  <script src="js/jquery.uploadfile.min.js"></script>

  <script type="text/javascript" src="js/MultiDatesPicker/jquery-ui.multidatespicker.js"></script>
  <link rel="stylesheet" type="text/css" href="css/MultiDatesPicker/mdp.css">
  <link rel="stylesheet" type="text/css" href="css/MultiDatesPicker/prettify.css">
  <script type="text/javascript" src="js/MultiDatesPicker/prettify.js"></script>
  <script type="text/javascript" src="js/MultiDatesPicker/lang-css.js"></script>
 <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.1.0/jquery-confirm.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.1.0/jquery-confirm.min.js"></script>-->
  <link rel="stylesheet" href="css/jquery-confirm.min.css"/>
  <script src="js/jquery-confirm.min.js"></script>
  <script src="js/modalStatic.js"></script>
  
  <script type="text/javascript">
  $(function() {
    prettyPrint();
  });
  </script>

  <!-- PNotify -->
  <script type="text/javascript" src="js/notify/pnotify.core.js"></script>
  <script type="text/javascript" src="js/notify/pnotify.buttons.js"></script>
  <script type="text/javascript" src="js/notify/pnotify.nonblock.js"></script>
  <?php include "./common/apiserver.php" ?>  
  
  <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>


<body class="nav-md" ng-app="certificandoApp">

  <div id="img-loading" class="loading">
    <img src="./diff/<?php echo $global_diffname; ?>/loading.gif" >
    <p>Espere un momento por favor.</p>
  </div>
  <div class="container body">


    <div class="main_container">

      <!-- side navigation -->
      <?php
        include "./side-nav.php";
      ?>
      <!-- /side navigation -->      

      <!-- top navigation -->
      <?php
        include "./top-nav.php";
      ?>
      <!-- /top navigation -->


      <!-- page content -->
      <?php
        if ($_REQUEST["pagina"] == "") {
          include "./contents/dashboard.php";
        }
        else{
          include "./contents/".$_REQUEST["pagina"].".php";
        }
        
      ?>
      <!-- /page content -->

    </div>

  </div>

  <div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
  </div>

  <script src="js/bootstrap.min.js"></script>

  <!-- gauge js -->
  <script type="text/javascript" src="js/gauge/gauge.min.js"></script>
  <!-- <script type="text/javascript" src="js/gauge/gauge_demo.js"></script> -->
  <!-- bootstrap progress js -->
  <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="js/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- icheck -->
  <script src="js/icheck/icheck.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="js/moment/moment.min.js"></script>
  <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>
  <!-- chart js -->
  <script src="js/chartjs/2.8.0/chart.min.js"></script>

  <!-- select2 -->
  <script src="js/select/select2.full.js"></script>
  <script src="js/select/select2/i18n/es.js"></script>
  <!-- multiselect-->
  <script src="js/multiselect/multiple-select.min.js"></script>
  <!-- datepicker -->
   <script src="js/datepicker/angular-datepicker.js"></script>
   <!-- air-datepicker -->
   <script src="js/air-datepicker/dist/js/datepicker.min.js"></script>
   <script src="js/air-datepicker/dist/js/i18n/datepicker.es.js"></script>
  <!--checklist-model-->
   <script src="js/checklist-model/checklist-model.js"></script>
    <!-- Autocomplete -->
  <script src="js/autocomplete/countries.js"></script>
  <script src="js/autocomplete/jquery.autocomplete.js"></script>

  <script src="js/custom.js"></script>
   <!-- form wizard -->
  <script type="text/javascript" src="js/wizard/jquery.smartWizard.js"></script>

  <!-- flot js -->
  <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
  <script type="text/javascript" src="js/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="js/flot/jquery.flot.pie.js"></script>
  <script type="text/javascript" src="js/flot/jquery.flot.orderBars.js"></script>
  <script type="text/javascript" src="js/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="js/flot/date.js"></script>
  <script type="text/javascript" src="js/flot/jquery.flot.spline.js"></script>
  <script type="text/javascript" src="js/flot/jquery.flot.stack.js"></script>
  <script type="text/javascript" src="js/flot/curvedLines.js"></script>
  <script type="text/javascript" src="js/flot/jquery.flot.resize.js"></script>

  <!-- worldmap -->
  <script type="text/javascript" src="js/maps/jquery-jvectormap-2.0.3.min.js"></script>
  <script type="text/javascript" src="js/maps/gdp-data.js"></script>
  <script type="text/javascript" src="js/maps/jquery-jvectormap-world-mill-en.js"></script>
  <script type="text/javascript" src="js/maps/jquery-jvectormap-us-aea-en.js"></script>
  <script type="text/javascript" src="js/calendar/fullcalendar.min.js"></script>
  <script type="text/javascript" src="js/calendar/lang/es.js"></script>
  <script type="text/javascript" src="js/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js"></script>

  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  <!-- apiserver -->
  <script src="./common/apiserver.js"></script>
  <!-- skycons -->
  <script src="js/skycons/skycons.min.js"></script>

<!-- Tabbed Notification -->
<script type="text/javascript">
  $(function() {
      var cnt = 10; //$("#custom_notifications ul.notifications li").length + 1;
      TabbedNotification = function(options) {
        var message = "<div id='ntf" + cnt + "' class='text alert-" + options.type + "' style='display:none'><h2><i class='fa fa-bell'></i> " + options.title +
          "</h2><div class='close'><a href='javascript:;' class='notification_close'><i class='fa fa-close'></i></a></div><p>" + options.text + "</p></div>";

        if (document.getElementById('custom_notifications') == null) {
          alert('doesnt exists');
        } else {
          $('#custom_notifications ul.notifications').append("<li><a id='ntlink" + cnt + "' class='alert-" + options.type + "' href='#ntf" + cnt + "'><i class='fa fa-bell animated shake'></i></a></li>");
          $('#custom_notifications #notif-group').append(message);
          cnt++;
          CustomTabs(options);
        }
      }

      CustomTabs = function(options) {
        $('.tabbed_notifications > div').hide();
        $('.tabbed_notifications > div:first-of-type').show();
        $('#custom_notifications').removeClass('dsp_none');
        $('.notifications a').click(function(e) {
          e.preventDefault();
          var $this = $(this),
            tabbed_notifications = '#' + $this.parents('.notifications').data('tabbed_notifications'),
            others = $this.closest('li').siblings().children('a'),
            target = $this.attr('href');
          others.removeClass('active');
          $this.addClass('active');
          $(tabbed_notifications).children('div').hide();
          $(target).show();
        });
      }

      CustomTabs();

      var tabid = idname = '';
      $(document).on('click', '.notification_close', function(e) {
        idname = $(this).parent().parent().attr("id");
        tabid = idname.substr(-2);
        $('#ntf' + tabid).remove();
        $('#ntlink' + tabid).parent().remove();
        $('.notifications a').first().addClass('active');
        $('#notif-group div').first().css('display', 'block');
      });
    })
</script>

  <!-- datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {

      var cb = function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
      }

      var optionSet1 = {
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        minDate: '01/01/2012',
        maxDate: '12/31/2015',
        dateLimit: {
          days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
          applyLabel: 'Submit',
          cancelLabel: 'Clear',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom',
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          firstDay: 1
        }
      };
      $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
      $('#reportrange').daterangepicker(optionSet1, cb);
      $('#reportrange').on('show.daterangepicker', function() {
        console.log("show event fired");
      });
      $('#reportrange').on('hide.daterangepicker', function() {
        console.log("hide event fired");
      });
      $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
      });
      $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
        console.log("cancel event fired");
      });
      $('#options1').click(function() {
        $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
      });
      $('#options2').click(function() {
        $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
      });
      $('#destroy').click(function() {
        $('#reportrange').data('daterangepicker').remove();
      });
    });
  </script>

   <!-- input_mask -->
  <script>
    $(document).ready(function() {
      $(":input").inputmask();
    });


  // Jquery draggable
  $('.modal-dialog').draggable({
      handle: ".modal-header"
  });
  </script>

  <script>
    <?php
      echo "sessionStorage.setItem('id_usuario','".$_SESSION["id_usuario"]."');";
      echo "sessionStorage.setItem('nombre_usuario','".$_SESSION["nombre_usuario"]."');";
    ?>
  </script>
  <script type="text/javascript">
    var global_permisos = JSON.parse(  '<?php echo json_encode($modulo_permisos);  ?>' );
  </script>

 
  <script src="./controllers/<?php echo $contollerName; ?>.js"></script>

  <footer>
    <div class="copyright-info">
      <p class="pull-right version"> Versión Desarrollo </p>
    </div>
    <div class="clearfix"></div>
  </footer>

  </body>

</html>
<script type="text/javascript">
modalStatico();
</script>

