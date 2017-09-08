<?php

include ("../../../../inc/includes.php");
include ("../../../../config/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{
    $data_ini =  $_POST['date1'];
    $data_fin = $_POST['date2'];
}

else {
    $data_ini = date("Y-m-01");
    $data_fin = date("Y-m-d");
    }

if(!isset($_POST["sel_ent"])) {
    $id_ent = $_GET["tec"];
}

else {
    $id_ent = $_POST["sel_ent"];
}
?>

<html>
<head>
<title> GLPI - <?php echo _n('Task','Tasks',2) .'  '. __('by Entity', 'dashboard') ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
<script language="javascript" src="../js/jquery.min.js"></script>
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<script src="../js/media/js/jquery.dataTables.min.js"></script>
<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
<script src="../js/media/js/dataTables.bootstrap.js"></script> 

<script src="../js/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="../js/media/pdfmake.min.js"></script>
<script src="../js/media/vfs_fonts.js"></script>
<script src="../js/media/jszip.min.js"></script>

<script src="../js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="../js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />


<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">

<div id="charts" class="fluid chart" >
<div id="pad-wrapper" >
<div id="head-rel" class="fluid">

<style type="text/css">
a:link, a:visited, a:active {
    text-decoration: none
    }
a:hover {
    color: #000099;
    }
</style>

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

    <div id="titulo_rel"> <?php echo _n('Task','Tasks',2) .'  '. __('by Entity','dashboard') ?>  </div>

    <div id="datas-tec" class="span12 fluid" >
    <form id="form1" name="form1" class="form_rel" method="post" action="rel_task_ent.php?con=1">
    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef">
	   <tr>
			<td style="width: 310px;">
			<?php
			
			echo '
				<table>
					<tr>
						<td>
						   <div class="input-group date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
						    	<input class="col-md-9 form-control" size="13" type="text" name="date1" value="'.$data_ini.'" >		    	
						    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
					    	</div>
						</td>
						<td>&nbsp;</td>
						<td>
					   	<div class="input-group date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
						    	<input class="col-md-9 form-control" size="13" type="text" name="date2" value="'.$data_fin.'" >		    	
						    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
					    	</div>
						</td>
						<td>&nbsp;</td>
					</tr>
				</table> ';
			?>
			
			<script language="Javascript">
				$('#dp1').datepicker('update');
				$('#dp2').datepicker('update');
			</script>
			
			</td>
			
			<td style="margin-top:2px;">
			<?php
			
				//seleciona entidade
				$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
				$result_e = $DB->query($sql_e);
				$sel_ent = $DB->result($result_e,0,'value');
			
				if($sel_ent == '' || $sel_ent == -1) {
					$entities = $_SESSION['glpiactiveentities'];
					$ents = implode(",",$entities);
				}
				else {
					$ents = $sel_ent;
				}
			
			
				$sql_ent = "
				SELECT id, name, completename AS cname
				FROM `glpi_entities`
				WHERE id IN (".$ents.")
				ORDER BY `name` ASC ";
			
				$result_ent = $DB->query($sql_ent);
			
				$arr_ent = array();
				$arr_ent[0] = "-- ". __('Select a entity', 'dashboard') . " --" ;
				
				while ($row_result = $DB->fetch_assoc($result_ent)) {
					$v_row_result = $row_result['id'];
					$arr_ent[$v_row_result] = $row_result['cname'] ;
				}
			
				$name = 'sel_ent';
				$options = $arr_ent;
				$selected = $id_ent;
			
				echo dropdown( $name, $options, $selected );
			
			?>
		</td>
	</tr>
	<tr><td height="15px"></td></tr>
	<tr>
		<td colspan="2" align="center">
			<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
			<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
		</td>
	</tr>

   </table>
<?php Html::closeForm(); ?>
<!-- </form> -->

        </div>
    </div>
</div>

<script type="text/javascript" >
	$(document).ready(function() { $("#sel1").select2({dropdownAutoWidth : true}); });
</script>

<?php

if(isset($_GET['con'])) {

$con = $_GET['con'];

if($con == "1") {

if(!isset($_POST['date1']))
{
    $data_ini2 = $_GET['date1'];
    $data_fin2 = $_GET['date2'];
}

else {
    $data_ini2 = $_POST['date1'];
    $data_fin2 = $_POST['date2'];
}

if(!isset($_POST["sel_ent"])) {
    $id_ent = $_GET["ent"];
}

else {
    $id_ent = $_POST["sel_ent"];
}

if($id_ent == "") {
	echo '<script language="javascript"> alert(" ' . __('Select a entity', 'dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="rel_task_ent.php"; </script>';
}

if($data_ini2 === $data_fin2) {
    $datas2 = "LIKE '".$data_ini2."%'";
}

else {
    $datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
}

// Chamados
$sql_cham =
"SELECT glpi_tickets.id AS id, glpi_tickettasks.taskcategories_id AS tipo, glpi_tickettasks.date AS date, glpi_tickettasks.content,
glpi_tickettasks.users_id AS uid, glpi_tickettasks.actiontime, glpi_tickettasks.begin AS begin, glpi_tickettasks.end
FROM glpi_tickettasks, glpi_tickets
WHERE glpi_tickets.entities_id = ".$id_ent."
AND glpi_tickets.id = glpi_tickettasks.tickets_id
AND glpi_tickets.is_deleted = 0
AND glpi_tickettasks.date ". $datas2 ."
ORDER BY id DESC, begin DESC ";

$result_cham = $DB->query($sql_cham);


$consulta1 =
"SELECT glpi_tickets.id AS id, glpi_tickettasks.taskcategories_id AS tipo, glpi_tickettasks.date AS date, glpi_tickettasks.content,
glpi_tickettasks.users_id, glpi_tickettasks.actiontime, glpi_tickettasks.begin AS begin, glpi_tickettasks.end
FROM glpi_tickettasks, glpi_tickets
WHERE glpi_tickets.entities_id = ".$id_ent."
AND glpi_tickets.id = glpi_tickettasks.tickets_id
AND glpi_tickets.is_deleted = 0
AND glpi_tickettasks.date ". $datas2 ."
ORDER BY id DESC, begin DESC ";

$result_cons1 = $DB->query($consulta1);
$conta_cons = $DB->numrows($result_cons1);
$consulta = $conta_cons;


if($consulta > 0) {

//nome e total
$sql_nome = "
SELECT name, completename
FROM `glpi_entities`
WHERE `id` = ".$id_ent." ";

$result_nome = $DB->query($sql_nome) ;
$tech = $DB->result($result_nome,0,'name');

//total time of tasks
while($row = $DB->fetch_assoc($result_cons1)){
    $tempo_total += $row['actiontime'];
}
	
	echo "
	<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>
	
	<table class='fluid'  style='width:100%; font-size: 18px; font-weight:bold; margin-bottom: 30px;' cellpadding = 1px>
		<tr>
			<td style='vertical-align:middle; width:40%;'> <span style='color: #000;'>".__('Entity').": </span>  ". $tech. "</td>
			<td colspan='3' style='font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td style='vertical-align:middle;'> <span style='color: #000;'>"._n('Task', 'Tasks',2).": </span>". $conta_cons ."</td>
			<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Time').": </span>". time_ext($tempo_total) ."</td>
		</tr>
	</table>
	
	<table id='tarefa' class='display' style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
		<thead>
			<tr>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Ticket') ."  </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Date') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Technician') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Requester') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Description') ."</th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Duration') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Begin') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('End') ."  </th>
			</tr>
		</thead>
	<tbody>";

//listar chamados

$DB->data_seek($result_cham, 0);
while($row = $DB->fetch_assoc($result_cham)){
	
	//nome e total
	$sql_nome = "
	SELECT glpi_users.id, glpi_users.firstname , glpi_users.realname, glpi_users.name
	FROM glpi_users, glpi_tickettasks
	WHERE glpi_tickettasks.users_id = glpi_users.id
	AND glpi_users.id = ".$row['uid']." ";
	
	$result_nome = $DB->query($sql_nome) ;
	$row_nome = $DB->fetch_assoc($result_nome);
	
	//Requester
	$sql_req = "SELECT gu.firstname AS name, gu.realname AS sname
					FROM glpi_users gu, glpi_tickets_users gtu
					WHERE gtu.tickets_id = ".$row['id']."
					AND gtu.users_id = gu.id
					AND gtu.type = 1 ";
	
	$result_req = $DB->query($sql_req) ;
	$req = $DB->fetch_assoc($result_req);
	
	echo "
	<tr style='font-weight:normal; font-size:11px;'>
		<td style='text-align:center; vertical-align:middle; font-weight:bold;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
		<td style='text-align:center; vertical-align:middle;'> ". conv_data_hora($row['date']) ." </td>
		<td style='vertical-align:middle;'> ". $row_nome['firstname'] ." ".$row_nome['realname']." </td>
		<td style='vertical-align:middle;'> ". $req['name']." ".$req['sname']." </td>
		<td style='max-width:400px; vertical-align:middle;'> ". $row['content'] ." </td>
		<td style='text-align:center; vertical-align:middle;''> ". time_ext($row['actiontime']) ."</td>
		<td style='text-align:center; vertical-align:middle;''> ". conv_data_hora($row['begin']) ."</td>
		<td style='text-align:center; vertical-align:middle;''> ". conv_data_hora($row['end']) ."</td>
	</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#tarefa')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
    $('#tarefa').DataTable( {    	

		  select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc']],
		  displayLength: 25,
        lengthMenu: [[25, 50, 75, 100], [25, 50, 75, 100]],        
        buttons: [
        	    {
                 extend: "copyHtml5",
                 text: "<?php echo __('Copy'); ?>"
             },
             {
             	  extend: "collection",
                 text: "<?php echo __('Print','dashboard'); ?>",
						  buttons:[ 
						  	{               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('All','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Entity'); ?> : </span><?php echo $tech; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  _n('Task','Tasks',2); ?> : </span><?php echo $conta_cons ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'><?php echo __('Time'); ?></span> : <?php echo time_ext($tempo_total); ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Entity'); ?> : </span><?php echo $tech; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  _n('Task','Tasks',2); ?> : </span><?php echo $conta_cons ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'><?php echo __('Time'); ?></span> : <?php echo time_ext($tempo_total); ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
		                 exportOptions: {
		                    modifier: {
		                        selected: true
		                    }
		                }
		                }
	                ]
             },
             {
                 extend: "collection",
                 text: "<?php echo _x('button', 'Export'); ?>",
                 buttons: [ "csvHtml5", "excelHtml5",
                  {
                 		extend: "pdfHtml5",
                 		orientation: "landscape",
                 		message: "<?php echo __('Entity'); ?> : <?php echo $tech.'\n'; ?> <?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2).'\n'; ?> <?php echo __('Time'); ?> : <?php echo time_ext($tempo_total); ?>",
                  } 
                  ]
             }
        ]
        
    } );
} );

</script>  
<?php
echo '</div><br>';
}

else {

	echo "
	<div id='nada_rel' class='well info_box fluid col-md-12'>
		<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
			<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
			<tr></tr>
		</table>
	</div>";

}
}
}
?>

</div>
</div>
</div>
</body>
</html>

