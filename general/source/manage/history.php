<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��Դ������ʷ��¼");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/mouse_mon.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<body>
<?
//---- ��Դ���� ----
$query = "select * from OA_SOURCE where SOURCEID='$SOURCEID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SOURCENAME = $ROW["SOURCENAME"];
   $TIME_TITLE = $ROW["TIME_TITLE"];
   $MANAGE_USER = $ROW["MANAGE_USER"];
}
if($TIME_TITLE=="")
{
   Message(_("��ʾ"),_("δ�趨��Դ����ʱ���"));
   exit;
}
$TIME_ARRAY=explode(",",$TIME_TITLE);
$ARRAY_COUNT=sizeof($TIME_ARRAY);
if($TIME_ARRAY[$ARRAY_COUNT-1]=="")
   $ARRAY_COUNT--;
if(!find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"]))
{
	Message("",_("��Ȩ�鿴��ʷ��¼"));
	exit;
}
if(isset($_POST["save"]))
{
	$DIA_DATE=$_POST["DIA_DATE"];
	$DIA_DATE2=$_POST["DIA_DATE2"];
	if($DIA_DATE=="" && $DIA_DATE2!="")
	{
		Message(_("��ʾ"),_("��ѯ��ʼ���ڲ���Ϊ�գ�"));
	?>
	<center><input type="button" name="button" class="BigButton" value="<?=_("����")?>" onClick="window.location='history.php?SOURCEID=<?=$SOURCEID?>'"></center>
	<?	
   	exit;
	}
	if($DIA_DATE!="" && $DIA_DATE2=="")
	{
		Message(_("��ʾ"),_("��ѯ�������ڲ���Ϊ�գ�"));
	?>
		<center><input type="button" name="button" class="BigButton" value="<?=_("����")?>" onClick="window.location='history.php?SOURCEID=<?=$SOURCEID?>'"></center>
	<?
   	exit;
	}
	if($DIA_DATE>$DIA_DATE2 && $DIA_DATE!="" && DIA_DATE2!="")
	{
		Message(_("��ʾ"),_("��ѯ��ʼ����Ӧ�Ƚ���ʱ��С�����������룡"));
	?>
	<center><input type="button" name="button" class="BigButton" value="<?=_("����")?>" onClick="window.location='history.php?SOURCEID=<?=$SOURCEID?>'"></center>
	<?
   	exit;
 	}
}
?>
<form name=formdate action="" method=post>
	<table class=small>
		<tr>
	      <td ><b><?=_("��ѯ���ڣ�")?></b></td>
	      <td >
				<input type="text" name="DIA_DATE" size="10" maxlength="10" class="BigInput" value="<?=$DIA_DATE?>"  onClick="WdatePicker()">
				 <?=_("��")?>
				<input type="text" name="DIA_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$DIA_DATE2?>" onClick="WdatePicker()" >
				
				<input type="submit" name="save" value="<?=_("ȷ��")?>" >
			</td>
		</tr>
	</table>
</form>
<table class=small>
	<tr>
		<td><b><?=_("ͼ��˵����")?></b></td>
		<td width=20 bgColor="#00ff00"></td>
		<td width=40><?=_("����")?></td>
		<td width=20 bgColor="#ff33ff""></td>
		<td width=40><?=_("����")?></td>
		<td width=20 bgColor="#ff0000"></td>
		<td width=60><?=_("��������")?></td>
		<td width=20 bgColor="#0000ff"></td>
		<td width=60><?=_("��������")?></td>
		<td width=20 bgColor="#ff9966"></td>
		<td width=90><?=_("����Ա���ڰ���")?></td>
	</tr>
</table>
<h4>
<? if($DIA_DATE!="" && $DIA_DATE2!=""){?><?=$DIA_DATE?><?=_("��")?><?=$DIA_DATE2?><?}else{?><?=date("Y",time());}?><?=_("�����Դ������ʷ��¼��")?>
<?=$SOURCENAME?>
</h4>
<form name=form1 action="submit.php" method=post>
	<table class=small border="0" cellspacing="1" cellpadding="1" bgcolor="#000000">
		<tr class=TableHeader>
			<td><?=_("����")?></td>
			<?
			for($M=0;$M<$ARRAY_COUNT;$M++)
			{
			?>
			<td><?=$TIME_ARRAY[$M]?></td>
			<?
			}
			?>
		</tr>
<?
$CUR_DATE=date("Y-m-d",time());
$CUR_YEAR=date("Y-01-01",time());
$CUR_YEAR_END=date("Y-12-31",time());
$I=0;
if($DIA_DATE!="" && $DIA_DATE2!="")
{
	$query = "select * from OA_SOURCE_USED where SOURCEID='$SOURCEID' and APPLY_DATE>='$DIA_DATE' and APPLY_DATE<='$DIA_DATE2'";
	
}
else
{
	$query = "select * from OA_SOURCE_USED where SOURCEID='$SOURCEID' and APPLY_DATE>='$CUR_YEAR' ";
	
}
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$I++;
	if($I%2==1)
		$TableLine="TableData";
	else
		$TableLine="TableContent";
	$APPLY_DATE = $ROW["APPLY_DATE"];
	$USER_ID = $ROW["USER_ID"];
	$APPLY_DATE_DESC=substr($APPLY_DATE,5);
	$WEEK_DAY=get_week($APPLY_DATE);
	$USER_ARRAY=explode(",",$USER_ID);
	//����������Ը�����ں�
	$WEEK_DAY_CYCLE=date("w", strtotime($APPLY_DATE));    
?>
		<tr class="<?=$TableLine?>" height="35" <?if($_SESSION["LOGIN_THEME"]==9)echo "style=background:'white'";?>>
  			<td nowrap><b><?=$APPLY_DATE_DESC?><br><?=$WEEK_DAY?></b></td>
<?
	for($J=0;$J<$ARRAY_COUNT;$J++)
	{
		$TIME_TITLE_CUR=$TIME_ARRAY[$J];
		$query1 = "select USER_ID from OA_CYCLESOURCE_USED where SOURCEID='$SOURCEID' and E_APPLY_TIME>='$APPLY_DATE' and B_APPLY_TIME<='$APPLY_DATE' and find_in_set('$WEEK_DAY_CYCLE',WEEKDAY_SET) and find_in_set('$TIME_TITLE_CUR',TIME_TITLE) order by APPLY_TIME asc limit 0,1";
		$cursor1 = exequery(TD::conn(),$query1);
		if($ROW1=mysql_fetch_array($cursor1))
		{
			$USER_ID_CYCLE=$ROW1['USER_ID']; //�ظ�ȡֵ�Ļ�ȡ����ʱ��Ƚ����Ϊ׼
		}
		else
		{
			$USER_ID_CYCLE="";
		}
		if($USER_ID_CYCLE!="")
    	 {
    	 		$APPLY_VALUE=$USER_ID_CYCLE;
    	 		$COLOR="#ff9966";
    	 		$IS_CYCLE=1;
    	 }
    	else 
    	{ 
    		$IS_CYCLE=0;
			if(($USER_ARRAY[$J]==""||$USER_ARRAY[$J]=="0" ) )//û������
			{
				$APPLY_VALUE="0";
				$COLOR="";
			}
			else if(($USER_ARRAY[$J]==$_SESSION["LOGIN_USER_ID"]) )//�Լ�����
			{
				$APPLY_VALUE=$_SESSION["LOGIN_USER_ID"];
				$COLOR="#0000ff";
			}
			else //��������
			{
				$APPLY_VALUE=$USER_ARRAY[$J];
				$COLOR="#ff0000";
			}
		}
		if($APPLY_VALUE!="0")
		{
			$query = "select * from USER where USER_ID='$APPLY_VALUE'";
			$cursor1 = exequery(TD::conn(),$query);
			if($ROW=mysql_fetch_array($cursor1))
				$USER_NAME = $ROW["USER_NAME"];
			else
		  		$USER_NAME = $APPLY_VALUE;
		}
?>
			<td  width=36 bgcolor="<?=$COLOR?>">
				<?if($APPLY_VALUE=="0")echo $TIME_ARRAY[$J];else echo "<font color=#FFFFFF>".$USER_NAME."</font>";?>
			</td>
<?
    }
?>
		</tr>
<?
}
?>
		<tr class=TableControl>
			<td colspan=100 align=center>
				<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close()">&nbsp;&nbsp;
				<input type="button" value="<?=_("����")?>" class="BigButton" onClick="history.back()">
			</td>
		</tr>
   </table>
</form>
</body>
</HTML>
