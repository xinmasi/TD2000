<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("������־����");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" align="absmiddle" height="20" width="22"><span class="big3"> <?=_("������־����")?></span>
    </td>
  </tr>
</table>
<br>
<br>
<?
$QUERY_MASTER=true;
$PARA_ARRAY=get_sys_para("LOCK_TIME");
$PARA_VALUE=$PARA_ARRAY["LOCK_TIME"];
$PARA_VALUE=explode(",",$PARA_VALUE);
$W_START=$PARA_VALUE[0];
$W_END=$PARA_VALUE[1];
$DAYS=$PARA_VALUE[2];

$PARA_ARRAY=get_sys_para("IS_COMMENTS,LOCK_SHARE,ALL_SHARE");
/*****��ѯ�Ƿ�ѡ��Ĭ�Ϲ�����־ģ��  songyang********/
$res_select_worklog_format=get_sys_para("DIARY_WORK_LOG_FORMAT");
/*****��ѯ�Ƿ�ѡ��Ĭ�Ϲ�����־ģ��  ����********/
/*****��ѯ���й�����־ģ��  songyang********/
$str_all_worklog_format = "SELECT MODEL_ID,MODEL_NO,MODEL_NAME,MODEL_TYPE FROM HTML_MODEL WHERE MODEL_TYPE=3;";
$res_all_worklog_format=exequery(TD::conn(),$str_all_worklog_format,$QUERY_MASTER);
/*****��ѯ���й�����־ģ��  ����********/
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
   $$PARA_NAME = $PARA_VALUE;
?>
<table class="TableBlock" width="500" align="center" >
  <form action="insert.php"  method="post" name="form1">
   <tr>
    <td class="Tableheader"colspan="2" align="center"><?=_("��������ʱ�䷶Χ")?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��������ʱ�䷶Χ����־��")?></td>
    <td class="TableData">
      <input type="text" id="start_time" name="W_START" size="10" maxlength="19" class="BigInput" value="<?=$W_START?>" onClick="WdatePicker()">
      <?=_("��")?>
      <input type="text" name="W_END" size="10" maxlength="19" class="BigInput" value="<?=$W_END?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"
>
   
      <br><br><?=_("˵������Ϊ�ձ�ʾ��������Ҳ����ֻ��д����һ��")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("����ָ��������ǰ����־��")?></td>
    <td class="TableData">
      <?=sprintf(_("����%s��ǰ����־"),"<input type='text' name='DAYS' size='3' maxlength='19' class='BigInput' value='".$DAYS."' style='text-align:center;'>")?>
      <br><br><?=_("˵����0��ձ�ʾ������")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�ɷ�������˵���־���������")?></td>
    <td class="TableData">
      <input type="checkbox" id="IS_COMMENTS1" name="IS_COMMENTS" value="1" <?if($IS_COMMENTS == 1) echo "checked"?> /><label for="IS_COMMENTS1"><?=_("����������˵���־�������")?></label>
      <br><?=_("˵����ѡ��Ϊ����������˵���")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("������־���Ƿ�������")?></td>
    <td class="TableData">
      <input type="checkbox" id="LOCK_SHARE1" name="LOCK_SHARE" value="1" <?if($LOCK_SHARE == 1) echo "checked"?> /><label for="LOCK_SHARE1"><?=_("������")?></label>
      <br><?=_("˵����ѡ��Ϊ������")?>
    </td>
   </tr>
   <tr style="display:none;">
    <td nowrap class="TableData"><?=_("�Ƿ���������Ĭ�϶������˹���")?></td>
    <td class="TableData">
      <input type="checkbox" id="ALL_SHARE1" name="ALL_SHARE" value="1" <?if($ALL_SHARE == 1) echo "checked"?> /><label for="ALL_SHARE1"><?=_("����������˹���")?></label>
      <br><?=_("˵����ѡ��Ϊ����������˹���")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("Ĭ�Ϲ�����־ģ�壺")?></td>
    <td class="TableData">
       <select name="DIARY_WORK_LOG_FORMAT" class="BigSelect" >
       		<?php
       		/*****��װҪ��ʾ������־ģ��ѡ��  songyang********/
       		$b_option_checked=FALSE;
					while($row=mysql_fetch_array($res_all_worklog_format))
					{
					?>
					<option value="<?php echo $row['MODEL_ID'];?>" <? if($res_select_worklog_format['DIARY_WORK_LOG_FORMAT'] == $row['MODEL_ID']) {echo "selected";$b_option_checked=TRUE;} ?>><? echo $row['MODEL_NAME']; ?></option>
					<?php 
					}
					if(!$b_option_checked)
					{
					?>
					<option value="" selected><?=_("��ʹ��ģ��")?></option>
					<?php
					}
					else
					{
					?>
					<option value="" ><?=_("��ʹ��ģ��")?></option>
					/*****��װҪ��ʾ������־ģ��ѡ��  ����********/
					<?
					}
					?>
       </select>
      <br><?=_("˵����ѡ��Ĭ�ϵĹ�����־ģ��")?>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("��������")?>" class="BigButton" name="button">&nbsp;&nbsp;
    </td>
  </tr>
  </form>
</table>

</body>
</html>