<?php
session_start();

include("../includes/db.inc.php");
include("../includes/common.php");
include("con_file_obj.php");

$olicDailyObj = new olicDailyObj();

switch($_POST['action']){
	
	case "olicData":
    
         if($_POST['orgId'] == '87'){
            $directory="C:/wamp/www/OLIC/imported_file/conc/conc_pj_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/conc/conc_pj_archive/"; 
            $orgName = "PJ";   
        }else if($_POST['orgId'] == '85'){
            $directory="C:/wamp/www/OLIC/imported_file/conc/conc_pg_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/conc/conc_pg_archive/";  
            $orgName = "PG";
        }else if($_POST['orgId'] == '133'){
            $directory="C:/wamp/www/OLIC/imported_file/conc/conc_sbc_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/conc/conc_sbc_archive/";  
            $orgName = "PC";
        }else if($_POST['orgId'] == '153'){
            $directory="C:/wamp/www/OLIC/imported_file/conc/conc_di_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/conc/conc_di_archive/";
            $orgName = "DI";  
        }else if($_POST['orgId'] == '113'){
            $directory="C:/wamp/www/OLIC/imported_file/conc/conc_fl_data/";  
            $arch_directory="C:/wamp/www/OLIC/imported_file/conc/conc_fl_archive/";
            $orgName = "FL";  
        }else{
            $directory="";  
            $arch_directory="";
        }
			// create a handler to the directory
			//$dirhandler = opendir($directory);
			// read all the files from directory
			$nofiles=0;
			$checkEmpty  = (count(glob($directory.'*')) === 0) ? 'Empty' : 'Not empty';

			if ($checkEmpty == "Empty"){
				echo "0";
				exit();
			}else{
				$olicDailyObj->clearTblOlicInvoice();
				if ($dirhandler = opendir($directory)) {
					while ($file = readdir($dirhandler)) {
						$file_ext = explode('.',$file);
						$max_val = count($file_ext);
						$file_ext = $file_ext[($max_val-1)];
						$ermsg = "";
	                    $fileType = substr($file,0,2);
    
						if($file_ext == "601"){
                            if($orgName == $fileType){
							
							    $csv_file = $directory.$file;
							    if (($handle = fopen($csv_file, "r")) !== FALSE) {
								    
								    //fgetcsv($handle);//Adding this line will skip the reading of th first line from the csv file and the reading process will begin from the second line onwards
								    while (($data = fgetcsv($handle, 10000000, "|")) !== FALSE) {
									    $num = count($data);
									    //echo "<p> $num fields in line $row: <br /></p>\n";
									    $row++;
									    for ($c=0; $c < $num; $c++) {
										    //echo $data[$c] . "\n";
										    $col1 = trim($data[0]);
										    $col2 = trim($data[1]);
										    $col3 = trim($data[2]);
										    $col4 = trim($data[3]);
										    $col5 = trim($data[4]);
										    $col6 = trim($data[5]);
										    $col7 = trim($data[6]);
										    $col8 = trim($data[7]);
										    $col9 = trim($data[8]);
										    $col10 = trim($data[9]);
										    $col11 = trim($data[10]);
										    $col12 = trim($data[11]);
										    $col13 = trim($data[12]);
										    $col14 = trim($data[13]);
										    $col15 = trim($data[14]);
										    $col16 = trim($data[15]);
										    $col17 = trim($data[16]);
										    $col18 = trim($data[17]);
										    $col19 = trim($data[18]);
										    $col20 = trim($data[19]);
										    $col21 = trim($data[20]);
										    $col22 = trim($data[21]);
										    $col23 = trim($data[22]);
										    $col24 = trim($data[23]);
										    $col25 = trim($data[24]);
										    $col26 = trim($data[25]);
										    $col27 = trim($data[26]);
										    $col28 = trim($data[27]);
										    $col29 = trim($data[28]);
										    $col30 = trim($data[29]);
										    $col31 = trim($data[30]);
										    $col32 = trim($data[31]);
										    $col33 = trim($data[32]);
										    $col34 = trim($data[33]);
										    $col35 = trim($data[34]);
										    $col36 = trim($data[35]);
										    $col37 = trim($data[36]);
										    $col38 = trim($data[37]);
										    $col39 = trim($data[38]);
										    $col40 = trim($data[39]);
										    //$col7 = "'".trim(str_replace("'","''",date('m/d/Y',strtotime($data[6]))))."'";	
									    }
									    $olicDailyObj->olicData($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23,$col24,$col25,$col26,$col27,$col28,$col29,$col30,$col31,$col32,$col33,$col34,$col35,$col36,$col37,$col38,$col39,$col40);
								    }	
								    if($loaded = $olicDailyObj->displayLoadedRec()){
									    echo "
									    $().toastmessage('showToast', {
									    text     : '<b>Filename: </b> ".$file." data successfully imported to database! <br>"
									    .'<font color="#00FF00"><b>Uploaded Rec.:</b></font> '.$loaded['loaded']."',
									    sticky   : false,
									    position : 'middle-center',
									    type     : 'success',
									    close    : function () {console.log('toast is closed ...');}
									    });
									    ";
								    }
							    }
							    copy($directory.$file, $arch_directory.$file);
                            }
						}
						//echo "$().toastmessage('showSuccessToast','File data successfully imported to database!');";
					}
					
					//$perksObj->inserttblTxtfileTemp($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9);
					
					//echo "alert('File data successfully imported to database!!')";
					
					//mssql_close($connect);
				}
				closedir($handle);
				
				foreach(glob($directory.'*.601*') as $v){
					unlink($v);
				}
				foreach(glob($directory.'*.601*') as $v){
					unlink($v);
				}
				/*if ($checkEmpty == "Not empty"){
				
				}*/
			}
		
	exit();
	break;	
	
	case "mmsDataRemvSpace":
		$olicDailyObj->mmsDataRemvSpace();
	exit();
	break;	
	
	case "oracleData":
		$olicDailyObj->oracleData($_POST['orgId']);
	exit();
	
	case "remvLoadedInv":
		$olicDailyObj->remvLoadedInv();
	exit();
	break;	
	
	case "updFileNameDuplicate":
		$olicDailyObj->updFileNameDuplicate($_POST['orgId']);
	exit();
	break;	
    
    case "updFileNameCancelled":
        $olicDailyObj->updFileNameCancelled($_POST['orgId']);
    exit();
    break;    
    
    case "updFileNameLoad":
        $olicDailyObj->updFileNameLoad($_POST['orgId']);
    exit();
    break;    
	
	case "createTxtFile":
	
		$arrFileName = $olicDailyObj->viewFileName();
		
		foreach($arrFileName as $valFileName){
			
			$fileName = $valFileName['Col040'];
			
			$arrFileCont = $olicDailyObj->viewOlic($fileName);
			
			$fCont = "";
			
			foreach($arrFileCont as $valFileCont){
				
				$fCont .= trim($valFileCont['Col001'])."|";
				$fCont .= trim($valFileCont['Col002'])."|";
				$fCont .= trim($valFileCont['Col003'])."|";
				$fCont .= trim($valFileCont['Col004'])."|";
				$fCont .= trim($valFileCont['Col005'])."|";
				$fCont .= trim($valFileCont['Col006'])."|";
				$fCont .= trim(stripslashes($valFileCont['Col007']))."|";
				$fCont .= trim($valFileCont['Col008'])."|";
				$fCont .= trim($valFileCont['Col009'])."|";
				$fCont .= trim($valFileCont['Col010'])."|";
				$fCont .= trim($valFileCont['Col011'])."|";
				$fCont .= trim($valFileCont['Col012'])."|";
				$fCont .= trim($valFileCont['Col013'])."|";
				$fCont .= trim($valFileCont['Col014'])."|";
				$fCont .= trim($valFileCont['Col015'])."|";
				$fCont .= trim($valFileCont['Col016'])."|";
				$fCont .= trim($valFileCont['Col017'])."|";
				$fCont .= trim($valFileCont['Col018'])."|";
				$fCont .= trim($valFileCont['Col019'])."|";
				$fCont .= trim($valFileCont['Col020'])."|";
				$fCont .= trim($valFileCont['Col021'])."|";
				$fCont .= trim($valFileCont['Col022'])."|";
				$fCont .= trim($valFileCont['Col023'])."|";
				$fCont .= trim($valFileCont['Col024'])."|";
				$fCont .= trim($valFileCont['Col025'])."|";
				$fCont .= trim($valFileCont['Col026'])."|";
				$fCont .= trim($valFileCont['Col027'])."|";
				$fCont .= trim($valFileCont['Col028'])."|";
				$fCont .= trim($valFileCont['Col029'])."|";
				$fCont .= trim($valFileCont['Col030'])."|";
				$fCont .= trim($valFileCont['Col031'])."|";
				$fCont .= trim($valFileCont['Col032'])."|";
				$fCont .= trim($valFileCont['Col033'])."|";
				$fCont .= trim($valFileCont['Col034'])."|";
				$fCont .= trim($valFileCont['Col035'])."|";
				$fCont .= trim($valFileCont['Col036'])."|";
				$fCont .= trim($valFileCont['Col037'])."|";
				$fCont .= trim($valFileCont['Col038'])."|";
				$fCont .= trim($valFileCont['Col039'])."|";
				$fCont .= trim($valFileCont['Col040'])."|";
				$fCont .= trim($valFileCont['Col041']);
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
                var orgId = $("#cmbOrgId").val();

                if(orgId == '0'){
                    $().toastmessage('showToast', {
                        text: 'Please select company!',
                        sticky: true,
                        position: 'middle-center',
                        type: 'error',
                        closeText: '',
                        close: function () 
                        {
                        console.log("toast is closed ...");
                        }
                    });
                    return false;
                }
				
				$.ajax
				({
					url: 'con_file.php',
					type: 'POST',
					data: 'action=olicData&orgId='+orgId,
					beforeSend: function()
					{
						jQuery('#activity_pane').showLoading();
                        $('#process').html('Processing');
					},
					success: function(data1){
						if(data1 == 0){
							$().toastmessage('showToast', {
								text: 'Folder is empty!',
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
						}else{
							$().toastmessage('showToast', {
								text: 'Copying of source data success!',
								sticky: false,
								position: 'middle-center',
								type: 'success',
								closeText: '',
								close: function () 
								{
								console.log("toast is closed ...");
								}
							});
							process2(orgId);
						}
						eval(data1);
					}
				});	
			}
			
			function process2(orgId)
			{
				$.ajax
				({
					url: 'con_file.php',
					type: 'POST',
					data: 'action=mmsDataRemvSpace&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Remove spaces of invoices success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process3(orgId);
					}
				});	
			}
			
			function process3(orgId)
			{
				$.ajax
				({
					url: 'con_file.php',
					type: 'POST',
					data: 'action=oracleData&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Copying of oracle data success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process4(orgId);
					}
				});	
			}
			
			function process4(orgId)
			{
				$.ajax
				({
					url: 'con_file.php',
					type: 'POST',
					data: 'action=remvLoadedInv&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Removing loaded invoices success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process5(orgId);
					}
				});	
			}
			
			function process5(orgId)
			{
				$.ajax
				({
					url: 'con_file.php',
					type: 'POST',
					data: 'action=updFileNameDuplicate&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Updating filename success!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
						process6(orgId);
					}
				});	
			}
            
            function process6(orgId)
            {
                $.ajax
                ({
                    url: 'con_file.php',
                    type: 'POST',
                    data: 'action=updFileNameCancelled&orgId='+orgId,
                    success: function(data1){
                        $().toastmessage('showToast', {
                            text: 'Updating filename success!',
                            sticky: false,
                            position: 'middle-center',
                            type: 'success',
                            closeText: '',
                            close: function () 
                            {
                            console.log("toast is closed ...");
                            }
                        });
                        process7(orgId);
                    }
                });    
            }
            
            function process7(orgId)
            {
                $.ajax
                ({
                    url: 'con_file.php',
                    type: 'POST',
                    data: 'action=updFileNameLoad&orgId='+orgId,
                    success: function(data1){
                        $().toastmessage('showToast', {
                            text: 'Updating filename success!',
                            sticky: false,
                            position: 'middle-center',
                            type: 'success',
                            closeText: '',
                            close: function () 
                            {
                            console.log("toast is closed ...");
                            }
                        });
                        process8(orgId);
                    }
                });    
            }
			
			function process8(orgId)
			{
				$.ajax
				({
					url: 'con_file.php',
					type: 'POST',
					data: 'action=createTxtFile&orgId='+orgId,
					success: function(data1){
						$().toastmessage('showToast', {
							text: 'Textfile created!',
							sticky: true,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							document.location.reload();
							}
						});
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
                                <b>601 - CON (Textfiles)</b>
                            </font>   
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center">&nbsp;   
                        </td>
                    </tr>
                    <tr>
                        <td><font style="font-size:13px"><b> Company </b></font></td>
                        <td><font style="font-size:13px"><b> : </b></font></td>
                        <td><? $olicDailyObj->DropDownMenu($arrOrgId,'cmbOrgId','','class="selectBox"'); ?></td>
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