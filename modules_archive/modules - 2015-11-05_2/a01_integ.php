<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("a01_file_obj.php");

$a01FileObj = new a01FileObj();

switch($_POST['action']){
	
	case "a01DataMMS":
		//$a01FileObj->mmsArGetData();
        
		$mnth = $_POST['month'];
		$yr = $_POST['year'];
		$nDate = $yr."-".$mnth."-01";
		// $frmDate =  strtotime( '-3 day', strtotime($nDate));
		// $frmDate = date('Y-M-d',$frmDate);
		// $toDate =  strtotime( '+1 day', strtotime($nDate));
		// $toDate = date('Y-M-d',$toDate);
		
		$nqry = $a01FileObj->mmsArGetData(date('M-Y',strtotime($nDate)));
		
		if($nqry){
			echo TRUE;
		}else{
			echo FALSE;
		}
		
		
	exit();
	break;	
	
	case "arOracleData":
		$mnth = $_POST['month'];
		$yr = $_POST['year'];
		$nDate = $yr."-".$mnth."-01";
		$frmDate =  strtotime( '-3 day', strtotime($nDate));
		$frmDate = date('Y-M-d',$frmDate);
		$toDate =  strtotime( '+1 day', strtotime(date('Y-m-d')));
		$toDate = date('Y-M-d',$toDate);
		
		$a01FileObj->oracleArGetData($frmDate,$toDate);
		
		
	exit();
	break;	
	
	case "createTextfile":
		
		$unloadedInv = $a01FileObj->compareARlocaltoOracle();
		echo $unloadedInv;
		
	exit();
	break;	
	    
	/*
	case "oracleData":
		$a01FileObj->oracleData($_POST['orgId']);
	exit();
	
	case "remvLoadedInv":
		$a01FileObj->remvLoadedInv();
	exit();
	break;	
	
	case "updFileName":
		$a01FileObj->updFileName($_POST['orgId']);
	exit();
	break;	
	case "createTxtFile":
	
		$arrFileName = $a01FileObj->viewFileName();
		
		foreach($arrFileName as $valFileName){
			
			$fileName = $valFileName['filename'];
			
			$arrFileCont = $a01FileObj->viewA01($fileName);
			
			$fCont = "";
			
			foreach($arrFileCont as $valFileCont){
				
				$fCont .= trim($valFileCont['invoice'])."|";
				$fCont .= trim($valFileCont['invoice_date'])."|";
				$fCont .= trim($valFileCont['trxn_type'])."|";
				$fCont .= trim($valFileCont['strshrt'])."|";
				$fCont .= trim($valFileCont['col005'])."|";
				$fCont .= trim($valFileCont['strshrt2'])."|";
				$fCont .= trim($valFileCont['seq'])."|";
				$fCont .= trim($valFileCont['col008'])."|";
				$fCont .= trim($valFileCont['type'])."|";
				$fCont .= trim($valFileCont['gl_date'])."|";
				$fCont .= trim($valFileCont['col011'])."|";
				$fCont .= trim($valFileCont['col012'])."|";
				$fCont .= trim($valFileCont['col013'])."|";
				$fCont .= trim($valFileCont['amount'])."|";
				$fCont .= trim($valFileCont['col015'])."|";
				$fCont .= trim($valFileCont['curency'])."|";
				$fCont .= trim($valFileCont['col017'])."|";
				$fCont .= trim($valFileCont['filename'])."|";
				$fCont .= trim($valFileCont['col019']);
				$fCont .= "\r\n";
			}
			
			$destiFoldr = "../exported_file/".$fileName; 
		
			if (file_exists($destiFoldr)) {
				unlink($destiFoldr);
			}
			
			$handleFromFileName = fopen ($destiFoldr, "x");
			
			fwrite($handleFromFileName, $fCont);
			fclose($handleFromFileName) ;
		}
		
		
	exit();
	break;
*/	
}

$arrOrgId = array('0'=>"Select Company",'87'=>"Junior",'85'=>"PPCI",'133'=>"Subic",'153'=>"DCI",'113'=>"FLS");
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
                var month = $("#nMonth").val();
                var year = $("#nYear").val();
				
				$.ajax
				({
					url: 'a01_integ.php',
					type: 'POST',
					data: 'action=a01DataMMS&month='+month+'&year='+year,
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
					},
					success: function(data1){
						if(data1){
							$().toastmessage('showToast', {
								text: 'Copy A01 data success!',
								sticky: false,
								position: 'middle-center',
								type: 'success',
								closeText: '',
								close: function () 
								{
								console.log("toast is closed ...");
								}
							});
							process2(month,year);
							
						}else{
							$().toastmessage('showToast', {
								text: 'Could Not Copy AR data!',
								sticky: false,
								position: 'middle-center',
								type: 'warning',
								closeText: '',
								close: function () 
								{
								console.log("toast is closed ...");
								jQuery('#activity_pane').hideLoading();
								}
							});
						}
						eval(data1);
					}
				});	
			}
			
			function process2(month,year)
			{
				$.ajax
				({
					url: 'a01_integ.php',
					type: 'POST',
					data: 'action=arOracleData&month='+month+'&year='+year,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'COPY Oracle data!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process3();
						jQuery('#activity_pane').hideLoading();
                        $('#process').html('Process');
					}
				});	
			}
			
			function process3()
			{
				//create textfile for unloaded Inv
				$.ajax
				({
					url: 'a01_integ.php',
					type: 'POST',
					data: 'action=createTextfile',
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Creation of TextFile Done',
							sticky: true,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
								console.log("toast is closed ...");
							}
						});
						//process4(orgId);
						jQuery('#activity_pane').hideLoading();
                        $('#process').html('Process');
					}
				});	
			}
			
			
			$(function(){
				$('#txtMonYr').datepicker
				({
					changeMonth: true,
					changeYear: true,
					showButtonPanel: true,
					dateFormat: 'M-yy',
					onClose: function(dateText, inst) 
					{ 
						var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
						var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
						$(this).datepicker('setDate', new Date(year, month, 1));
					}
				});	
			});
			
			$(function(){
				$( "#datepicker" ).datepicker
				({
					changeMonth: true,
					changeYear: true
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
                width: 220px;
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
                <br />
                <table border="0">
                    <tr>
                        <td colspan="3" align="center">  
                            <font style="font-family:Lucida Handwriting; font-size: 15px;"> 
                                <b>A01 - (Textfiles)</b>
                            </font>   
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center">&nbsp;   
                        </td>
                    </tr>
                    <tr>
                        <td><font style="font-size:13px"><b> Month </b></font></td>
                        <td><font style="font-size:13px"><b> : </b></font></td>
                        <td>
							<select name="nMonth" id="nMonth">
								<option value="01" >January</option>
								<option value="02" >February</option>
								<option value="03" >March</option>
								<option value="04" >April</option>
								<option value="05" >May</option>
								<option value="06" >June</option>
								<option value="07" >July</option>
								<option value="08" >August</option>
								<option value="09" >September</option>
								<option value="10" >October</option>
								<option value="11" >November</option>
								<option value="12" >December</option>
							</select>
						</td>
                    </tr>
                    <tr>
                        <td><font style="font-size:13px"><b> Year </b></font></td>
                        <td><font style="font-size:13px"><b> : </b></font></td>
                        <td>
							<select name="nYear" id="nYear">
								<option value="<?php echo date('Y')-2; ?>"><?php echo date('Y')-3; ?></option>
								<option value="<?php echo date('Y')-2; ?>"><?php echo date('Y')-2; ?></option>
								<option value="<?php echo date('Y')-1; ?>"><?php echo date('Y')-1; ?></option>
								<option value="<?php echo date('Y'); ?>" selected="true"><?php echo date('Y'); ?></option>
								<option value="<?php echo date('Y')+1; ?>"><?php echo date('Y')+1; ?></option>
							<select>
						</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <center>
                                <br />
                                <button class="btn btn-success" id="process" onClick="process();" class="btn" value="Analyze"> Process </button>
                            </center>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
</html>