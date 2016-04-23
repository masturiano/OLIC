<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("logs_ap_obj.php");

$logsApObj = new logsApObj();

$arrLogList = $logsApObj->viewLogs();

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
        
        <link href="../includes/showLoading/css/showLoading.css" rel="stylesheet" media="screen" /> 
        <!--<script type="text/javascript" src="../../../includes/showLoading/js/jquery-1.3.2.min.js"></script>-->
        <script type="text/javascript" src="../includes/showLoading/js/jquery.showLoading.js"></script>
		
        <script src="../includes/toastmessage/src/main/javascript/jquery.toastmessage.js"></script>
        <link rel="stylesheet" type="text/css" href="../includes/toastmessage/src/main/resources/css/jquery.toastmessage.css" />
		

        
		
        
        <script type="text/javascript">
			
			$('document').ready(function(){
                $('#userList').dataTable({
                "bJQueryUI": true,
                "bPaginate": false,
                "sScrollY": "400",
                "bScrollCollapse": true,
                "sPaginationType": "full_numbers"
                });
            });
			
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
			#dataTable{
				width:90%; 
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
					<h3 align="center"><font style="font-family:Lucida Handwriting"> AP Logs </font></h3>
					
					<div id="dataTable">
						<table id="userList" align="center">
							<thead>
								<tr>
									<td>Request Id</td>
									<td>System Amount</td>
									<td>System Count</td>
									<td>Oracle Amount</td>
									<td>Oracle Count</td>
									<td>Amount Difference</td>
									<!-- <td>ACTION</td> -->
								</tr>
							</thead>
						<tbody>
								<?php foreach ($arrLogList as $val) {?>
								<tr>
									<td align="left"><?=str_pad($val['request_id'], 8, '0', STR_PAD_LEFT)?></td>
									<td align="left"><?=$val['system_amount']?></td>
									<td align="left"><?=$val['system_invoice_count']?></td>
									<td align="left"><?=$val['oracle_amount']?></td>
									<td align="left"><?=$val['oracle_invoice_count']?></td>
									<td align="left"><?=$val['amount_diff']?></td>
								</tr>
								<?php }?>
						</tbody>
						<tfoot></tfoot>
						</table>

                 </div>
            </center>
            <center>
                <div id='footer'></div>
            </center>
        </body>
</html>