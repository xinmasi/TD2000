<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��Դ����");
include_once("inc/header.inc.php");
?>

<SCRIPT>
function add_onclick()
{
	if(document.addform.SOURCENAME.value == "")
		alert("<?=_("��Դ���ֲ�����Ϊ��")?>")
	else
		document.addform.submit()
}
</SCRIPT>
<style>
.TableData{
	height:35px;
}
.small td{
	border:1px solid #ccc;
	padding:5px;
}
</style>
<BODY class=bodycolor <?if(!$SAVE){?>onload="document.addform.SOURCENO.focus();"<?}?>>
<?
if($SAVE)
{
	for($I=0;$I<7;$I++)
	{
		$VAR="WEEK".$I;
		if($$VAR=="on")
			$WEEKDAY_SET.=$I.",";
	}
	$WEEKDAY_SET=substr($WEEKDAY_SET,0,-1);
	if($SOURCEID!="")
	{
	   $query = "update OA_SOURCE set SOURCENO='$SOURCENO',SOURCENAME='$SOURCENAME',DAY_LIMIT='$DAY_LIMIT',WEEKDAY_SET='$WEEKDAY_SET',TIME_TITLE='$TIME_TITLE',REMARK='$REMARK' where SOURCEID='$SOURCEID'";
	   exequery(TD::conn(),$query);
	   Message("",_("�༭��Դ�ɹ�"));
	}
	else
	{
		$query = "insert into OA_SOURCE(SOURCENO,SOURCENAME,DAY_LIMIT,WEEKDAY_SET,TIME_TITLE,REMARK) values ('$SOURCENO','$SOURCENAME','$DAY_LIMIT','$WEEKDAY_SET','$TIME_TITLE','$REMARK')";
		exequery(TD::conn(),$query);
		Message("",_("�����Դ�ɹ�"));
 	}
?>
<BR>
<div align=center>
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close()">
</div>
<script>opener.location.reload();
</script>
<?
	exit;
}
//--------------------------------------------------------------------
if($SOURCEID!="")
{
	$query = "select * from OA_SOURCE where SOURCEID='$SOURCEID'";
	$cursor = exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$SOURCENO = $ROW["SOURCENO"];
		$SOURCENAME = $ROW["SOURCENAME"];
		$DAY_LIMIT = $ROW["DAY_LIMIT"];
		$WEEKDAY_SET = $ROW["WEEKDAY_SET"];
		$TIME_TITLE = $ROW["TIME_TITLE"];
		$REMARK = $ROW["REMARK"];
	}
}
else
{
   $DAY_LIMIT = "7";
   $WEEKDAY_SET = "1,2,3,4,5";
   $TIME_TITLE="09:00-09:30,09:30-10:00,10:00-10:30,10:30-11:00,11:00-11:30,11:30-12:00,12:00-12:30,12:30-13:00,13:00-13:30,13:30-14:00,14:00-14:30,14:30-15:00,15:00-15:30,15:30-16:00,16:00-16:30,16:30-17:00,17:00-17:30,17:30-18:00,18:00-21:00";
}
?>
<FORM id=addform name=addform action=add.php?SAVE=1 method=post>
	<table class="small" width=100% border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
		<TR class=TableHeader>
			<TD colspan=2><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absmiddle"> <?if($SOURCEID!="")echo _("�༭");else echo _("���");?><?=_("��Դ")?></TD>
		</TR>
		<TR class=TableData>
			<TD><b><?=_("��Դ���")?></b></TD>
			<TD><INPUT name=SOURCENO size="5" value="<?=$SOURCENO?>"></TD>
		</TR>
		<TR class=TableData>
			<TD><b><?=_("��Դ����")?></b></TD>
			<TD><INPUT name=SOURCENAME size="20" value="<?=$SOURCENAME?>"></TD>
		</TR>
		<TR class=TableData>
			<TD><b><?=_("ԤԼ����")?></b></TD>
			<TD><INPUT name=DAY_LIMIT size="20" value="<?=$DAY_LIMIT?>"></TD>
		</TR>
		<TR class=TableData>
			<TD><b><?=_("�����趨")?></b></TD>
			<TD>
	          <input type="checkbox" name="WEEK1" id="WEEK1"<?if(find_id($WEEKDAY_SET,"1")) echo " checked";?>><label for="WEEK1"><?=_("����һ")?></label>&nbsp;
	          <input type="checkbox" name="WEEK2" id="WEEK2"<?if(find_id($WEEKDAY_SET,"2")) echo " checked";?>><label for="WEEK2"><?=_("���ڶ�")?></label>&nbsp;
	          <input type="checkbox" name="WEEK3" id="WEEK3"<?if(find_id($WEEKDAY_SET,"3")) echo " checked";?>><label for="WEEK3"><?=_("������")?></label>&nbsp;
	          <input type="checkbox" name="WEEK4" id="WEEK4"<?if(find_id($WEEKDAY_SET,"4")) echo " checked";?>><label for="WEEK4"><?=_("������")?></label>&nbsp;
	          <input type="checkbox" name="WEEK5" id="WEEK5"<?if(find_id($WEEKDAY_SET,"5")) echo " checked";?>><label for="WEEK5"><?=_("������")?></label>&nbsp;
	          <input type="checkbox" name="WEEK6" id="WEEK6"<?if(find_id($WEEKDAY_SET,"6")) echo " checked";?>><label for="WEEK6"><?=_("������")?></label>&nbsp;
	          <input type="checkbox" name="WEEK0" id="WEEK0"<?if(find_id($WEEKDAY_SET,"0")) echo " checked";?>><label for="WEEK0"><?=_("������")?></label>
			</TD>
		</TR>
		<TR class=TableData>
			<TD><b><?=_("ʱ���")?></b></TD>
			<TD><textarea name=TIME_TITLE rows=5 cols=65><?=$TIME_TITLE?></textarea><br><?=_("����Ӣ�Ķ��ŷָ�")?></TD>
		</TR>
		<TR class=TableData>
			<TD><b><?=_("��ע")?></b></TD>
			<TD><textarea name=REMARK rows=5 cols=65><?=$REMARK?></textarea></TD>
		</TR>
		<TR class=TableControl>
			<TD colspan=2 align=center width="500">
				 <INPUT TYPE="hidden" NAME="SOURCEID" value="<?=$SOURCEID?>">
				 <INPUT type="button" class="BigButton" value="<?=_("����")?>" id=post name=post LANGUAGE=javascript onClick="return add_onclick()">&nbsp;&nbsp;
	       <input type="button" class="BigButton" value="<?=_("�ر�")?>" onClick="window.close()">
			</TD>
	</TABLE>
</FORM>
</BODY>
</HTML>
