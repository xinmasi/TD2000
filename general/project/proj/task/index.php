<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��Ŀ�����б�");
include_once("inc/header.inc.php");

include_once("../proj_priv.php");
include_once("inc/td_core.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>

<script>
jQuery.noConflict();
function showDetail(TASK_ID)
{
	jQuery.get("detail.php?TASK_ID="+TASK_ID,function(data){jQuery("#detail_body").html(data);ShowDialog('detail');});
}
//-------------zfc----------
var show_l = false;
function show_log(TASK_ID)
{
    if(show_l)
        show_l.close();
	myleft=(screen.availWidth-600)/2;
    show_l = window.open("task_detail.php?PROJ_ID=<?=$PROJ_ID?>&TASK_ID="+TASK_ID,"","status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=500,left="+myleft+",top=50");
}
</script>


<?

$QUERY = "select PROJ_TASK.*,USER.USER_NAME from PROJ_TASK LEFT JOIN USER ON(PROJ_TASK.TASK_USER=USER.USER_ID) WHERE PROJ_ID='$PROJ_ID' ORDER BY TASK_SORT,TASK_START_TIME";
$CUR = exequery(TD::conn(),$QUERY);
$datas = array();
while($ROW = mysql_fetch_array($CUR)){
	$datas[$ROW['TASK_ID']]['TASK_ID'] = $ROW['TASK_ID'];
	$datas[$ROW['TASK_ID']]['PARENT_TASK'] = $ROW['PARENT_TASK'];
	$datas[$ROW['TASK_ID']]['TASK_NAME'] = $ROW['TASK_NAME'];
	$datas[$ROW['TASK_ID']]['PROJ_ID'] = $ROW['PROJ_ID'];
	$datas[$ROW['TASK_ID']]['TASK_START_TIME'] = $ROW['TASK_START_TIME'];
	$datas[$ROW['TASK_ID']]['TASK_TIME'] = $ROW['TASK_TIME'];
	$datas[$ROW['TASK_ID']]['TASK_PERCENT_COMPLETE'] = $ROW['TASK_PERCENT_COMPLETE'];
	$datas[$ROW['TASK_ID']]['TASK_USER'] = $ROW['TASK_USER'];
	$datas[$ROW['TASK_ID']]['TASK_NO'] = $ROW['TASK_NO'];
	$datas[$ROW['TASK_ID']]['USER_NAME'] = $ROW['USER_NAME'];
	$datas[$ROW['TASK_ID']]['TASK_MILESTONE'] = $ROW['TASK_MILESTONE'];
	$datas[$ROW['TASK_ID']]['TASK_END_TIME'] = $ROW['TASK_END_TIME'];
    $TASK_ACT_END_TIME = $ROW["TASK_ACT_END_TIME"];
	 if(strtotime($TASK_ACT_END_TIME)>0)
	$datas[$ROW['TASK_ID']]['TASK_END_TIME'] = $TASK_ACT_END_TIME;
	$datas[$ROW['TASK_ID']]['TASK_LEVEL'] = $ROW['TASK_LEVEL'];	
}

function tree($items) { 
	foreach ($items as $item) 
		$items[$item['PARENT_TASK']]['SON'][$item['TASK_ID']] = &$items[$item['TASK_ID']]; 
	return isset($items[0]['SON']) ? $items[0]['SON'] : array(); 
}
$datas = tree($datas);
?>



<body>

<?
if(empty($datas))
{
	 Message("",_("��δ������Ŀ����"));
	 exit;
}
?>

<table class="table table-bordered table-hover" style="">
	<tr class="info">
		<td colspan="8"><strong>��Ŀ�����б�</strong><input type="button" class="btn btn-mini" style="float:right;" onclick="window.location='../gantt/?PROJ_ID=<?=$PROJ_ID?>'" value="<?=_("����ͼ")?>"></td>
	</tr>
	
	<tr class="info">
		<td><strong><?=_("��ʶ")?></strong></td>
		<td><strong><?=_("��������")?></strong></td>
		<td><strong><?=_("������")?></strong></td>
		<td><strong><?=_("��ʼ����")?></strong></td>
		<td><strong><?=_("����")?></strong></td>
		<td><strong><?=_("��������")?></strong></td>
		<td><strong><?=_("��ɶ�")?></strong></td>
	</tr>
	
	<?php
	
	
		function temp($data){
			if(isset($data['SON'])){
				foreach($data['SON'] as $datan){
					?>
						<tr>
							<td nowrap align="center">					
					<?
						if($datan["TASK_MILESTONE"] == 1) 
							echo '<img src="'.MYOA_STATIC_SERVER.'/static/images/project/milestone.gif" title="'._("��̱�").'" />';
						else
							echo '-';
						
						$tempArray = explode(".", $datan["TASK_NO"]);
						$num = count($tempArray) * 20;
						$StyleTd = "text-align: left; padding-left:".$num."px;";
					?>
							</td>
							<td nowrap align="left" style="<?=$StyleTd?>"><a href="#" onclick="showDetail('<?=$datan["TASK_ID"]?>')"><?=$datan["TASK_NO"]?>&nbsp;<?=$datan["TASK_NAME"]?></td>
							<td nowrap align="center"><?=$datan["USER_NAME"]?></td>
							<td nowrap align="center"><?=$datan["TASK_START_TIME"]?></td>
							<td nowrap align="center"><?=$datan["TASK_TIME"]?> <?=_("��")?></td>
							<td nowrap align="center"><?=$datan["TASK_END_TIME"]?></td>
							<td nowrap align="center"><?=$datan["TASK_PERCENT_COMPLETE"]?>% &nbsp;<a href="#" onclick="show_log('<?=$datan["TASK_ID"]?>')"><?=_("����")?></a></td>
						</tr>							
					<?
						if(isset($datan['SON'])){
							temp($datan);
						}			
				}
			}
		}
	
	
		foreach($datas as $data){
		
			?>
				<tr>
					<td nowrap align="center">
			<?
			if($data["TASK_MILESTONE"] == 1) 
				echo '<img src="'.MYOA_STATIC_SERVER.'/static/images/project/milestone.gif" title="'._("��̱�").'" />';
			else
				echo '-';
			
				$tempArray = explode(".", $data["TASK_NO"]);
				$num = count($tempArray) * 20;
				$StyleTd = "text-align: left; padding-left:".$num."px;";
			?>
					</td>
					<td nowrap align="left" style="<?=$StyleTd?>"><a href="#" onclick="showDetail('<?=$data["TASK_ID"]?>')"><?=$data["TASK_NO"]?>&nbsp;<?=$data["TASK_NAME"]?></td>
					<td nowrap align="center"><?=$data["USER_NAME"]?></td>
					<td nowrap align="center"><?=$data["TASK_START_TIME"]?></td>
					<td nowrap align="center"><?=$data["TASK_TIME"]?> <?=_("��")?></td>
					<td nowrap align="center"><?=$data["TASK_END_TIME"]?></td>
					<td nowrap align="center"><?=$data["TASK_PERCENT_COMPLETE"]?>% &nbsp;<a href="#" onclick="show_log('<?=$data["TASK_ID"]?>')"><?=_("����")?></a></td>
				</tr>
			<?
			temp($data);
			?>
				<tr >
					<td style="background:#efefef;" colspan='8'></td>
				</tr>			
			<?
		}
	
	?>
	
</table>
<div id="overlay"></div>
<div id="detail" class="ModalDialog" style="width:550px;">
  <div class="header"><span id="title" class="title"><?=_("��������")?></span><a class="operation" href="javascript:HideDialog('detail');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <div id="detail_body" class="body">
  </div>
  <div id="footer" class="footer">
    <input class="btn btn-mini" onclick="HideDialog('detail')" type="button" value="<?=_("�ر�")?>"/>
  </div>
</div>
</body>
</html>
