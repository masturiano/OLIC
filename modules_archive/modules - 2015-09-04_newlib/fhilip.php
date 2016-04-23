<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("fhilip_obj.php");

$fhilipObj = new fhilipObj();

switch($_POST['action']){
	
	case "go":
		$fhilipObj->fhilipData();
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
        
        <meta charset="utf-8">
        <link rel="stylesheet" href="../includes/jquery/development-bundle/themes/base/jquery.ui.all.css">
        <link type="text/css" href="../includes/media/jquery/css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
		<link type="text/css" href="../includes/media/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
        
        <link type="text/css" rel="stylesheet" href="../includes/jquery/css/ui-lightness/jquery-ui-1.8.16.custom.css"/>
        <link type="text/css" rel="stylesheet" href="../includes/jquery/css/ui-lightness/demos.css"/>
        
        <script src="../includes/jquery/js/jquery-1.6.2.min.js"></script>
        <script src="../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="../includes/bootbox/bootbox.js"></script>
        
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.button.js"></script>
        <script src="../includes/jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
        
        <link href="../includes/showLoading/css/showLoading.css" rel="stylesheet" media="screen" /> 
        <!--<script type="text/javascript" src="../../../includes/showLoading/js/jquery-1.3.2.min.js"></script>-->
        <script type="text/javascript" src="../includes/showLoading/js/jquery.showLoading.js"></script>
        
        <script src="../includes/toastmessage/src/main/javascript/jquery.toastmessage.js"></script>
        <link rel="stylesheet" type="text/css" href="../includes/toastmessage/src/main/resources/css/jquery.toastmessage.css" />
        
        <script type="text/javascript">
			function process()
			{
				
				$.ajax
				({
					url: 'fhilip.php',
					type: 'POST',
					data: 'action=go',
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
					},
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Copy 301 (RCR) data success!',
							sticky: true,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process2(monYr);
					}
				});	
			}
		</script>
        
        <style type="text/css" title="currentStyle">
			@import "media/jquery/css/demo_page.css";
			@import "media/jquery/css/demo_table_jui.css";
        </style>
        
        <style type="text/css">
			.ui-datepicker-calendar 
			{
				display: none;
			}
			
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
		</style>
    </head>    
        <body>
            <center>
                <div id='header'>
                </div>
            </center>
        	<? include('menu.php'); ?>
            <center>
            	<div id="activity_pane" style="height: 100vh;">
                    <table border="0">
                    	<th colspan="2">
                        	<h3 align="center"><font style="font-family:Lucida Handwriting"> Fhilip Data </font></h3>
                        </th>
                        <tr>
                            <td>
                                MMS Data:
                            </td>
                            <td>
                                <input type="text" name="txtMonYr" id="txtMonYr" readonly="readonly"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center>
                                    <br />
                                    <button class="btn btn-success" onClick="process();" value="Analyze"> Process </button>
                                </center>
                            </td>
                        </tr>
                    </table>
                 </div>
            </center>
            <center>
                <div id='footer'></div>
            </center>
        </body>
</html>