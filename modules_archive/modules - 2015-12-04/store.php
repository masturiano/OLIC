<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("store_obj.php");

$storeObj = new storeObj();

switch($_GET['action']){
	
	case "update":
                               
        $storeObj->updateStore();   
        
    exit();
    break; 
      
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OLIC</title>
<meta name="keywords" content="" />
<meta name="description" content="" />

<html>
	<head>
    	<link rel="stylesheet" href="../includes/style.css" />
        <link rel="stylesheet" href="../includes/skeleton/skeleton.css" />
        <link rel="icon" type="image/ico" href="../includes/images/oraicon.gif">
        
        <meta charset="utf-8">
        <link rel="stylesheet" href="../includes/jquery/development-bundle/themes/base/jquery.ui.all.css">
        <link type="text/css" href="../includes/media/jquery/css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
		<link type="text/css" href="../includes/media/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
        
        <link type="text/css" rel="stylesheet" href="../includes/jquery/css/ui-lightness/jquery-ui-1.8.16.custom.css"/>
        <link type="text/css" rel="stylesheet" href="../includes/jquery/css/ui-lightness/demos.css"/>
        
        <script src="../includes/jquery/js/jquery-1.6.2.min.js"></script>
        <script src="../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="../includes/bootbox/bootbox.js"></script>
		<script src="../includes/jquery/js/jquery.dataTables.js"></script>
        
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.button.js"></script>
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>

        <script src="../includes/modal/modal.js"></script>
        <link rel="stylesheet" href="../includes/modal/modal.css">
        
        <link href="../includes/showLoading/css/showLoading.css" rel="stylesheet" media="screen" /> 
        <!--<script type="text/javascript" src="../../../includes/showLoading/js/jquery-1.3.2.min.js"></script>-->
        <script type="text/javascript" src="../includes/showLoading/js/jquery.showLoading.js"></script>
        
        <script src="../includes/toastmessage/src/main/javascript/jquery.toastmessage.js"></script>
        <link rel="stylesheet" type="text/css" href="../includes/toastmessage/src/main/resources/css/jquery.toastmessage.css" />
        
        <script src="../includes/jquery/js/jquery.dataTables.min.js"></script>   
        <script src="../includes/jquery/js/dataTables.bootstrap.js"></script>   
        <style type="text/css" title="currentStyle">
            @import "../includes/jquery/css/jquery.dataTables_themeroller.css";
        </style>
        
        <script type="text/javascript">
			function process()
			{         
				$.ajax
                ({
                    url: 'store.php',
                    type: 'GET',
                    data: 'action=update',
                    beforeSend: function()
                    {
                        jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
                    },
                    success: function(data1){
                        jQuery('#activity_pane').hideLoading();
                        $().toastmessage('showToast', {
                            text: 'Store already updated',
                            sticky: true,
                            position: 'middle-center',
                            type: 'success',
                            closeText: '',
                            close: function () 
                            {
                            console.log("toast is closed ...");
                            $('#process').html('Process');
                            }
                        }); 
                    }
                });    
			}     
		</script>
        
        <style type="text/css" title="currentStyle">
			@import "media/jquery/css/demo_page.css";
			@import "media/jquery/css/demo_table_jui.css";
        </style>
        
        <style type="text/css">
			.textBox 
            {
                border: solid 1px #222; 
                border-width: 1px; 
                width:130px; 
                height:18px;
                font-size: 11px;
            }
            
            .selectBox 
            {
                border: 1px solid #222; 
                width:132px; 
                height:22px;
                font-size: 11px;
            }
            
            .hd 
            {
                font-size: 11px;
                font-family: Verdana;
                font-weight: bold;
            }
            
            .btn {
            background: #3498db;
            background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
            background-image: -moz-linear-gradient(top, #3498db, #2980b9);
            background-image: -ms-linear-gradient(top, #3498db, #2980b9);
            background-image: -o-linear-gradient(top, #3498db, #2980b9);
            background-image: linear-gradient(to bottom, #3498db, #2980b9);
            -webkit-border-radius: 60;
            -moz-border-radius: 60;
            border-radius: 60px;
            font-family: Courier New;
            color: #ffffff;
            font-size: 20px;
            padding: 10px 20px 10px 20px;
            text-decoration: none;
            }

            .btn:hover {
            background: #3cb0fd;
            text-decoration: none;
            }
            
            .selectBox {
                background: skyblue;
                width: 230px;
                padding: 5px;
                font-size: 12px;
                line-height: 1;
                border: 0;
                border-radius: 0;
                height: 25px;
                -webkit-appearnace: none;
            }
            
            .inputBox {
                background: skyblue;
                width: 320px;
                padding: 5px;
                font-size: 12px;
                line-height: 1;
                border: 0;
                border-radius: 0;
                height: 15px;
                -webkit-appearnace: none;
            } 
            
            .inputBoxClear {
                background: transparent;
                width: 320px;
                padding: 5px;
                font-size: 12px;
                line-height: 1;
                border: 0;
                border-radius: 0;
                height: 15px;
                -webkit-appearnace: none;
            } 
            
            #activity_pane {
                width: 100%;
                height: 100%;
                border: 0px solid #CCCCCC;
                background-color:none;
                padding-top: 0px;
                overflow: visible;   
            }
            
		</style>
    </head>    
        <body>
            <div id='header' align="center"></div>
            <? include('menu.php'); ?>
            <div id="activity_pane" align="center">

                <div id="activity_pane" style="height: 100vh;">
                    <br>
                    <div id="ndtSuppliersBorder">
                        <form>
                            <table border="0">
                                <tr>
                                    <td colspan="3" align="center">  
                                        <font style="font-family:Lucida Handwriting; font-size: 15px;"> 
                                            <b>Update Store</b>
                                        </font>   
                                    </td>
                                </tr>   
                            </table>
                        </form>
                            <table border="0">        
                                <tr>
                                    <td colspan="3">
                                        <center>
                                            <br />
                                            <button class="btn btn-success" id="process" onClick="process();" class="btn" value="Analyze"> Process </button>
                                        </center>
                                    </td>
                                </tr>
                            </table>
                        
                        <br>
  
                </div>

            </div>    
            <br />
            <br />
        </body>
</html>