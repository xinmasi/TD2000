<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

ob_end_clean();

if($TASK_ID)
{
	$query = "select * from PROJ_TASK WHERE TASK_ID='$TASK_ID'";
	$cursor = exequery(TD::conn(), $query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$TASK_NAME = $ROW["TASK_NAME"];
		$TASK_NO = $ROW["TASK_NO"];
		$PROJ_ID = $ROW["PROJ_ID"];
		$TASK_DESCRIPTION = $ROW["TASK_DESCRIPTION"];
		$TASK_USER  = $ROW["TASK_USER"];
		$TASK_TIME  = $ROW["TASK_TIME"];
		$PRE_TASK = $ROW["PRE_TASK"];
		$PARENT_TASK = $ROW["PARENT_TASK"];
		$TASK_START_TIME  = $ROW["TASK_START_TIME"];
		$TASK_END_TIME = $ROW["TASK_END_TIME"];
		$TASK_LEVEL  = $ROW["TASK_LEVEL "];
		$TASK_PERCENT_COMPLETE = $ROW["TASK_PERCENT_COMPLETE"];
		$TASK_MILESTONE  = $ROW["TASK_MILESTONE "];
	}
	
	if($PRE_TASK)
	{
    $query = "select TASK_PERCENT_COMPLETE,TASK_STATUS,TASK_ID,TASK_NAME,TASK_USER from PROJ_TASK WHERE TASK_ID='$PRE_TASK'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
       $PRE_TASK_NAME = $ROW["TASK_NAME"];
       $PRE_TASK_USER = $ROW["TASK_USER"];
       $PRE_TASK_ID = $ROW["TASK_ID"];
       $PRE_TASK_STATUS = $ROW["TASK_STATUS"];
       $PRE_TASK_PERCENT_COMPLETE = $ROW["TASK_PERCENT_COMPLETE"];
	}
	
	if($PARENT_TASK)
	{
    $query = "select PARENT_TASK,TASK_NAME from PROJ_TASK WHERE TASK_ID='$PARENT_TASK'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
       $PARENT_TASK_NAME = $ROW["TASK_NAME"];
	}
?>
<style type="text/css">
  table.TableList td, table.TableList th {
    vertical-align: top;
}
table.TableList td .attach_link {
  display: block;
}
</style>
<table class="TableList" border="0" width="95%" align="center">
   <tr>
  		<td nowrap class="TableContent"><?=_("������ţ�")?></td>
  	  <td class="TableData"><?=$TASK_NO?></td>  	  	
  	</tr>
   <tr>
  		<td nowrap class="TableContent"><?=_("�������ƣ�")?></td>
  	  <td class="TableData"><?=$TASK_NAME?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("�ϼ�����")?></td>
  	  <td class="TableData"><?=$PARENT_TASK_NAME==""? _("��"):$PARENT_TASK_NAME?>
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("ǰ������")?></td>
  	  <td class="TableData">
      <!---zfc--->
         <?php
         if($PRE_TASK_NAME == ""){
                echo "��";
            }else{
                echo rtrim(GetUserNameById($PRE_TASK_USER),',') . "������ [" . $PRE_TASK_NAME."]";
                if($PRE_TASK_STATUS != "1"){
                echo "  �����".$PRE_TASK_PERCENT_COMPLETE."%";
                ?>
                &nbsp;&nbsp;
                <a href="../../../project/task/task_urge.php?TASK_USER=<?=_($PRE_TASK_USER)?>&TASK_ID=<?=_($PRE_TASK_ID)?>" title="��������߰�">�߰�</a>
                <?php
                }else{
                    echo "  �����";
                }
            }
         ?>         
      
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("����ƻ����ڣ�")?></td>
  	  <td class="TableData"><?=$TASK_START_TIME?> <?=_("��")?> <?=$TASK_END_TIME?></td>  	  	
  	</tr>
    <tr>
  		<td nowrap class="TableContent"><?=_("����ʱ��")?></td>
  	  <td class="TableData">
  	  <?=$TASK_TIME._("��������");?>
      </td>
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("����������")?></td>
  	  <td class="TableData"> <?=$TASK_DESCRIPTION?>
      </td>
    </tr>
</table>
<?
}
?>