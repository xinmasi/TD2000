<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("��������Դ����");
include_once("inc/header.inc.php");

if($CYCID!="")
{
	$query = "select * from OA_CYCLESOURCE_USED where CYCID='$CYCID'";
	$cursor = exequery(TD::conn(),$query);
	if($ROWS=mysql_fetch_array($cursor))
	{
		$SOURCE_NO=$ROWS["SOURCEID"];
		$B_APPLY_TIME = $ROWS["B_APPLY_TIME"];
		$E_APPLY_TIME = $ROWS["E_APPLY_TIME"];
		$WEEKDAY_SET =$ROWS["WEEKDAY_SET"];
		$TIME_TITLE = $ROWS["TIME_TITLE"];
		$REMARK = $ROWS["REMARK"];
		$USER_ID=$ROWS["USER_ID"];
		$APPLY_TIME=$ROWS["APPLY_TIME"];
		$USER_NAME=td_trim(GetUserNameById($USER_ID));
	
	}
	$query1="select SOURCENAME from OA_SOURCE where SOURCEID='$SOURCE_NO'";
	$cursor1 = exequery(TD::conn(),$query1);
	if($ROWNAME=mysql_fetch_array($cursor1))
		$SOURCE_NAME=$ROWNAME["SOURCENAME"];
}

?>
<style>
.small td{
	border:1px solid #ccc;
	padding:5px;
}
</style>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/source.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��������Դ����")?></span></td>
  </tr>
</table>
<br>
<TABLE class=small width=100% border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
	<TR class=TableHeader>
		<TD colspan=2><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absmiddle"> <?=_("�鿴������Դ��������")?></TD>
	</TR>
	<TR class=TableData>
		<TD nowrap><b><?=_("��Դ����")?></b></TD>
		<TD>
			<?=$SOURCE_NAME?> 
		</TD>
	</TR>
	<TR class=TableData>
		<TD><b><?=_("��ʼ����")?></b></TD>
		<TD>
		<?=$B_APPLY_TIME ?>
   </TD>
	</TR>
	<TR class=TableData>
		<TD><b><?=_("��������")?></b></TD>
		<TD>
			<?=$E_APPLY_TIME ?>
   </TD>
	</TR>
	<TR class=TableData>
		<TD><b><?=_("�����趨")?></b></TD>
		<TD>
	  <? if(find_id($WEEKDAY_SET,"1")) { ?><input type='checkbox' name='WEEK1' id='WEEK1' checked ><label for='WEEK1'><?=_("����һ")?></label>&nbsp; <? }?>
	  <? if(find_id($WEEKDAY_SET,"2")) { ?><input type='checkbox' name='WEEK2' id='WEEK2' checked ><label for='WEEK2'><?=_("���ڶ�")?></label>&nbsp; <? }?>
	  <? if(find_id($WEEKDAY_SET,"3")) { ?><input type='checkbox' name='WEEK3' id='WEEK3'  checked ><label for='WEEK3'><?=_("������")?></label>&nbsp; <? }?>
	  <? if(find_id($WEEKDAY_SET,"4")) { ?><input type='checkbox' name='WEEK4' id='WEEK4'  checked><label for='WEEK4'><?=_("������")?></label>&nbsp; <? }?>
	  <? if(find_id($WEEKDAY_SET,"5")) { ?><input type='checkbox' name='WEEK1' id='WEEK5' checked ><label for='WEEK5'><?=_("������")?></label>&nbsp; <? }?>
	  <? if(find_id($WEEKDAY_SET,"6")) { ?><input type='checkbox' name='WEEK1' id='WEEK6'  checked ><label for='WEEK6'><?=_("������")?></label>&nbsp; <? }?>
	  <? if(find_id($WEEKDAY_SET,"0")) { ?><input type='checkbox' name='WEEK1' id='WEEK0'  checked ><label for='WEEK0'><?=_("������")?></label>&nbsp; <? }?>
	</TD>	
		<TR class=TableData>
		<TD><b><?=_("ʱ����趨")?></b></TD>
		<TD>
			<?=$TIME_TITLE?>
   </TD>
	</TR>
	<TR class=TableData>
		<TD><b><?=_("��ע")?></b></TD>
		<TD><?=$REMARK?></TD>
	</TR>
	<TR class=TableData>
		<TD><b><?=_("ʹ����")?></b></TD>
		<TD><?=$USER_NAME?></TD>
	</TR>
	<TR class=TableData>
		<TD><b><?=_("����ʱ��")?></b></TD>
		<TD><?=$APPLY_TIME?></TD>
	</TR>
	<TR class=TableControl>
		<TD colspan=2 align=center>
      <input type="button" class="BigButton" value="<?=_("�ر�")?>" onClick="window.close()";>
		</TD>
	</TR>
</TABLE>
</BODY>
</HTML>
