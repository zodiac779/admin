<?php 
require_once("../includes/restriction.php");
ob_start(); 
if (!isset($_SESSION)) { session_start(); }
?>
<?php require_once('../../Connections/conn_database.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(conn_database(),$theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if(isset($_POST['data-id']) and isset($_POST['state_showhome'])){
    
    $query_rs_update = "UPDATE news_events SET news_events.".$_POST['data-mode']." = ".$_POST['state_showhome']." WHERE news_events.id = ".$_POST['data-id'];
    $rs_update = mysqli_query(conn_database(),$query_rs_update) or die(mysql_error());
    exit();
}

if(isset($_POST['data-id']) and isset($_POST['state_related'])){
    
    $query_rs_update = "UPDATE news_events SET news_events.".$_POST['data-mode']." = ".$_POST['state_related']." WHERE news_events.id = ".$_POST['data-id'];
    $rs_update = mysqli_query(conn_database(),$query_rs_update) or die(mysql_error());
    exit();
}

if(isset($_POST['data-id']) and isset($_POST['state_status'])){
    
    $query_rs_update = "UPDATE news_events SET news_events.".$_POST['data-mode']." = ".$_POST['state_status']." WHERE news_events.id = ".$_POST['data-id'];
    $rs_update = mysqli_query(conn_database(),$query_rs_update) or die(mysql_error());
    exit();
}



$query_rs_index = "SELECT * FROM news_events ORDER BY date DESC";
$rs_index = mysqli_query(conn_database(),$query_rs_index) or die(mysql_error());
$row_rs_index = mysqli_fetch_assoc($rs_index);
$totalRows_rs_index = mysqli_num_rows($rs_index);


$query_rs_lang = "SELECT * FROM languages WHERE status = 1 ORDER BY rank DESC LIMIT 3";
$rs_lang = mysqli_query(conn_database(),$query_rs_lang) or die(mysql_error());
$row_rs_lang = mysqli_fetch_assoc($rs_lang);
$totalRows_rs_lang = mysqli_num_rows($rs_lang);
?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>WOW III : Administrator</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="../assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="../assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="icon" href="../../images/favicon.png" type="image/png" />
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.html">
                        <img src="../assets/layouts/layout/img/logo.png" alt="logo" class="logo-default" /> </a>
                    <div class="menu-toggler sidebar-toggler"> </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
           <?php include('../includes/header.php'); ?>         
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <?php include('../includes/left.php'); ?>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL -->
                    
                    <!-- END THEME PANEL -->
                    <!-- BEGIN PAGE BAR --><!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet">
                                <div class="portlet-title">
                                    <div class="caption">
                                       NEWS & EVENTS
                                    </div>
                                    <div style="float:right;">
                                          <a href="insert.php" alt="Insert" title="Insert" class="btn btn-outline dark btn-sm blue"> <i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_1" data-rankDarg>
                                        <thead>
                                            <tr>
                                                <th width="40"><center><strong>#</strong></center></th>
                                                <?php do{ ?>
                                                    <th class="no-sort"><center><strong>Title <?php echo $row_rs_lang['lang_abbr'];?></strong></center></th>
                                                <?php }while ($row_rs_lang = mysqli_fetch_assoc($rs_lang)); mysqli_data_seek($rs_lang, 0); ?>
                                                <th class="no-sort" width="50"><center><strong>Date</strong></center></th>
                                                <th class="no-sort" width="50"><center><strong>Status</strong></center></th>
                                                <th class="no-sort" width="70"><center><strong>Action</strong></center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($totalRows_rs_index > 0){
                                                $counter = 1;
                                                do { ?>
                                                <tr data-id="<?php echo $row_rs_index['id']; ?>" data-rank="<?php echo $row_rs_index['rank']; ?>">
                                                    <td style="vertical-align:middle;" align="center"><?php echo $counter; ?></td>
                                                    <?php
                                                    while ($row_rs_lang = mysqli_fetch_assoc($rs_lang)){
                                                        
                                                        $query_rs_news_events = sprintf("SELECT * FROM news_events_localization pl WHERE pl.lang_id = %d AND pl.news_events_id = %d", GetSQLValueString($row_rs_lang['id'],'int'),GetSQLValueString($row_rs_index['id'],'int'));
                                                        $rs_news_events = mysqli_query(conn_database(),$query_rs_news_events) or die(mysql_error());
                                                        $row_rs_news_events = mysqli_fetch_assoc($rs_news_events);
                                                        $totalRows_rs_news_events = mysqli_num_rows($rs_news_events);
                                                    ?>
                                                    <td style="vertical-align:middle;"><?php echo $row_rs_news_events['title1']; ?></td>
                                                    <?php } mysqli_data_seek($rs_lang, 0); ?>
                                                    <td style="vertical-align:middle;"><?php echo date("d/m/Y", strtotime($row_rs_index['date'])); ?></td>
                                                    <td style="vertical-align:middle;" align="center" class="nodarg">
                                                        <i data-id="<?php echo $row_rs_index['id']; ?>" data-mode="showhome" class="<?php if($row_rs_index['showhome'] == 1){ echo 'font-blue fa fa-home'; }else{ echo 'font-grey-salsa fa fa-home'; } ?> icon-showhome" title="Show Home" style="cursor:default;"></i>
                                                        <i data-id="<?php echo $row_rs_index['id']; ?>" data-mode="related" class="<?php if($row_rs_index['related'] == 1){ echo 'font-yellow-lemon fa fa-star'; }else{ echo 'font-grey-salsa fa fa-star'; } ?> icon-related" title="Reated" style="cursor:default;"></i>
                                                        <i data-id="<?php echo $row_rs_index['id']; ?>" data-mode="status" class="<?php if($row_rs_index['status'] == 1){ echo 'font-green-jungle fa fa-check'; }else{ echo 'font-red-mint fa fa-times'; } ?> icon-status" title="Status" style="cursor:default;"></i>
                                                    </td>
                                                    <td style="vertical-align:middle;" align="center" class="nodarg">
                                                        <a alt="Edit" title="Edit" href="update.php?id=<?php echo $row_rs_index['id']; ?>" class="btn btn-outline btn-sm green"><i class="fa fa-edit"></i></a>
                                                        <button alt="Delete" title="Delete" data-placement="left" data-singleton="true" data-popout="true" class="btn btn-outline dark btn-sm red bs_confirmation_delete" data-toggle="confirmation" data-val="<?php echo $row_rs_index['id']; ?>" id="bs_confirmation_delete"><i class="fa fa-trash-o"></i></button>
                                                    </td>
                                                  </tr>
                                                  <?php $counter++;}  while ($row_rs_index = mysqli_fetch_assoc($rs_index));
                                            }else{ ?>
                                                <tr>
                                                    <td colspan="4" align="center">no data found!</td>
                                                </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->
                        </div>
                        
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <?php include('../includes/footer.php'); ?>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js" type="text/javascript"></script>
        <script src="../assets/pages/scripts/ui-buttons.min.js" type="text/javascript"></script>


        <script src="../assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="../assets/pages/scripts/table-datatables-fixedheader.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="../assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script type="text/javascript">

        $('#sample_1').on('draw.dt', function() { 
          setTimeout( 
            function() { 
                UIConfirmations.init(); 
                //alert('draw'); 
            }, 500); 
        });
        
            var UIConfirmations = function () {

    var handleSample = function () {
        
        $('.bs_confirmation_delete').on('confirmed.bs.confirmation', function () {
            window.location.href = "delete.php?id="+$(this).attr('data-val');
        });
    }


    return {
        //main function to initiate the module
        init: function () {

           handleSample();

        }

    };

}();

jQuery(document).ready(function() {    
   UIConfirmations.init();
});

            $(document).find('.icon-showhome[data-id]').on('click', function(){
                console.log('showhome change');
                if($(this).hasClass('font-blue fa fa-home')){
                    $(this).removeClass('font-blue fa fa-home').addClass('font-grey-salsa fa fa-home');
                }else{
                    $(this).removeClass('font-grey-salsa fa fa-home').addClass('font-blue fa fa-home');
                }

                $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: $('<form>').append($('<input>').prop({'name':'data-id','value':$(this).data('id')})).append($('<input>').prop({'name':'state_showhome','value':$(this).hasClass('font-blue fa fa-home') ? 1 : 0})).append($('<input>').prop({'name':'data-mode','value':$(this).attr('data-mode')})).serialize(),
                    success: function(result) {

                    },
                    dataType: 'json',
                    async:false
                });
            });
            $(document).find('.icon-related[data-id]').on('click', function(){
                console.log('related change');
                if($(this).hasClass('font-yellow-lemon fa fa-star')){
                    $(this).removeClass('font-yellow-lemon fa fa-star').addClass('font-grey-salsa fa fa-star');
                }else{
                    $(this).removeClass('font-grey-salsa fa fa-star').addClass('font-yellow-lemon fa fa-star');
                }

                $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: $('<form>').append($('<input>').prop({'name':'data-id','value':$(this).data('id')})).append($('<input>').prop({'name':'state_related','value':$(this).hasClass('font-yellow-lemon fa fa-star') ? 1 : 0})).append($('<input>').prop({'name':'data-mode','value':$(this).attr('data-mode')})).serialize(),
                    success: function(result) {

                    },
                    dataType: 'json',
                    async:false
                });
            });
            $(document).find('.icon-status[data-id]').on('click', function(){
                console.log('status change');
                if($(this).hasClass('font-green-jungle fa fa-check')){
                    $(this).removeClass('font-green-jungle fa fa-check').addClass('font-red-mint fa fa-times');
                }else{
                    $(this).removeClass('font-red-mint fa fa-times').addClass('font-green-jungle fa fa-check');
                }

                $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: $('<form>').append($('<input>').prop({'name':'data-id','value':$(this).data('id')})).append($('<input>').prop({'name':'state_status','value':$(this).hasClass('font-green-jungle fa fa-check') ? 1 : 0})).append($('<input>').prop({'name':'data-mode','value':$(this).attr('data-mode')})).serialize(),
                    success: function(result) {

                    },
                    dataType: 'json',
                    async:false
                });
            });
        </script>
    </body>

</html>
<?php

?>
