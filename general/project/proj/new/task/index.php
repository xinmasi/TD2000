<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("inc/utility_project.php");
include_once("inc/header.inc.php");

if(!project_update_priv($PROJ_ID)){
    Message(_("����"),_("��Ȩ����������Ŀ!"));
    Button_Back();
    exit;
}

?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>

<script>
function del_task(PROJ_ID,TASK_ID,CAL_ID)
{
	var msg='<?=_("ȷ��Ҫɾ����������")?>';
  if(window.confirm(msg))
  {
    url="delete.php?PROJ_ID="+PROJ_ID+"&TASK_ID="+TASK_ID+"&CAL_ID="+CAL_ID;
    location=url;
  }
}
</script>

<style>
.table th, .table td {
    text-align: center;
}
</style>

<body>

<div align="center" style="width:100%; height:50px; background:#fff; border-top:#3f9bca 3px solid; line-height:50px; position:fixed; top:100%; margin-top:-50px;">
   <input type="submit" value="<?=_("�½�����")?>" class="btn btn-success" title="<?=_("�½�����")?>" onclick="location.href='new.php?PROJ_ID=<?=$PROJ_ID?>'">
</div>

<div align="center" style="padding:10px;">
	<table align="center" class="table table-bordered table-striped table-hover" style="margin-bottom:70px;">
		<?php
			if($PROJ_ID){
				$count = 0;
				  $query = "select CAL_ID,TASK_ID,TASK_NAME,TASK_START_TIME,TASK_STATUS,TASK_END_TIME,TASK_ACT_END_TIME,TASK_TIME,USER_NAME from PROJ_TASK LEFT JOIN USER ON (USER.USER_ID=PROJ_TASK.TASK_USER) WHERE PROJ_ID='$PROJ_ID' ORDER BY TASK_NO,TASK_START_TIME";
				  $cursor = exequery(TD::conn(), $query);
				  while($ROW = mysql_fetch_array($cursor))
				  {
					$count++;
					
					if($count == 1){
					?>
					<tr class="info" style="color:#2a70e9;">
						<td colspan="6" ><strong>�����б�</strong></td>
					</tr>
					<tr class="info" style="color:#2a70e9;">
						<td ><?=_("��������")?></td>
						<td ><?=_("ִ����")?></td>
						<td ><?=_("��ʼ")?></td>
						<td ><?=_("����")?></td>
						<td ><?=_("����")?></td>
						<td ><?=_("����")?></td>  	
					</tr>					
					<?
					}$CAL_ID = $ROW["CAL_ID"];
					 $TASK_ID = $ROW["TASK_ID"];
					 $TASK_NAME = $ROW["TASK_NAME"];
					 $USER_NAME = $ROW["USER_NAME"];
					 $TASK_START_TIME = $ROW["TASK_START_TIME"];
					 $TASK_STATUS = $ROW["TASK_STATUS"];
						 $TASK_END_TIME = $ROW["TASK_END_TIME"];
					 $TASK_ACT_END_TIME = $ROW["TASK_ACT_END_TIME"];
					 if(strtotime($TASK_ACT_END_TIME)>0 && $TASK_STATUS=='1')
					 {
						   $days = round((strtotime($TASK_ACT_END_TIME)-strtotime($TASK_END_TIME))/(3600*24)) ;
						   $before = $days<0 ? _("��ǰ��").-$days._("��"): _("�Ƴ���").$days._("��");
					   $TASK_END_TIME = $TASK_ACT_END_TIME._("(�ƻ�").$TASK_END_TIME._("��").$before._("���)");
					 }
					 $TASK_TIME = $ROW["TASK_TIME"];
					 
					   	 $PRIV_NAME = get_code_name($PROJ_PRIV_ARRAY[$i],"PROJ_PRIV");
					 echo '
						  <tr class="'.$TableLine.'">
							<td >'.$TASK_NAME.'</td>
							<td>'.$USER_NAME.'</td>
							<td>'.$TASK_START_TIME.'</td>
							<td>'.$TASK_TIME.' ��</td>
							<td>'.$TASK_END_TIME.'</td>
							<td>
							<a href="new.php?PROJ_ID='.$PROJ_ID.'&TASK_ID='.$TASK_ID.'">�༭</a>
							<a href="#" onclick=del_task("'.$PROJ_ID.'","'.$TASK_ID.'","'.$CAL_ID.'")>ɾ��</a></td>  	  	
						  </tr>';
					}
					
					if($count == 0)
						Message("",_("��δ��������"));
					
			}			 
		?>
		
	</table>
</div>

</body>
</html>