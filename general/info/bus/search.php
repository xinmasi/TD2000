<?
include_once("inc/auth.inc.php");
$PAGE_SIZE=5;

setcookie("BUS_CITY", $CITY,time() + 60*60*24*3000);

$HTML_PAGE_TITLE = _("������ѯ");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function set_page()
{
  PAGE_START=(PAGE_NUM.value-1)*<?=$PAGE_SIZE?>+1;
  location="search.php?CITY=<?=$CITY?>&START=<?=$START?>&END=<?=$END?>&LINEID=<?=$LINEID?>&PAGE_START="+PAGE_START;
}

</script>


<body class="bodycolor" >
<?
//-----------����֯SQL���-----------
mysql_select_db("BUS", TD::conn());

$TABLE=$CITY."_LINE";

$START=trim($START);
$END=trim($END);

if($LINEID!="")
   $query="SELECT COUNT(*) from $TABLE where LINEID like '%$LINEID%'";
else
   $query="SELECT COUNT(*) from $TABLE where PASSBY like '%$START%' and PASSBY like '%$END%'";

$BUS_COUNT=0;
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $BUS_COUNT=$ROW[0];

 $PAGE_TOTAL=$BUS_COUNT/$PAGE_SIZE;
 $PAGE_TOTAL=ceil($PAGE_TOTAL);

 //--- ����,ĩҳ ---
 if($BUS_COUNT<=$PAGE_SIZE)
    $LAST_PAGE_START=1;
 else if($BUS_COUNT%$PAGE_SIZE==0)
    $LAST_PAGE_START=$BUS_COUNT-$PAGE_SIZE+1;
 else
    $LAST_PAGE_START=$BUS_COUNT-$BUS_COUNT%$PAGE_SIZE+1;

 //--- ���ܷ�ҳ ---
 //-- ҳ�� --
 if($PAGE_START=="")
    $PAGE_START=1;

 if($PAGE_START>$BUS_COUNT)
    $PAGE_START=$LAST_PAGE_START;

 if($PAGE_START<1)
    $PAGE_START=1;

 //-- ҳβ --
 $PAGE_END=$PAGE_START+$PAGE_SIZE-1;
 if($PAGE_END>$BUS_COUNT)
    $PAGE_END=$BUS_COUNT;

 //--- ���㵱ǰҳ ---
 $PAGE_NUM=($PAGE_START-1)/$PAGE_SIZE+1;

 $query1=str_replace("COUNT(*)","*",$query);
 $cursor1=exequery(TD::conn(), $query1);

 if($BUS_COUNT==0)
 {
   Message(_("��ʾ"),_("û�з��������Ĺ�����·"));
?>
<br>
<div align="center">
 <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='index.php';">
</div>

<?
     exit;
 }

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("������·��ѯ���")?> </span><br><br>
    </td>
    <td valign="bottom">
    <?
    	$msg1=sprintf(_("��ǰΪ��%s��%s��"),"<b>".$PAGE_START."</b>","<b>".$PAGE_END."</b>");
    	$msg2=sprintf(_("��%dҳ����%dҳ��ÿҳ���%d��"),$PAGE_NUM,$PAGE_TOTAL,$PAGE_SIZE);
    ?>
    <span class="small1"><?=$msg1 ?>(<?=$msg2 ?>)</small>
    </td>
    </tr>
</table>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("��·")?> </td>
      <td nowrap align="center"><?=_("�װ೵ʱ��")?> </td>
      <td nowrap align="center"><?=_("ĩ�೵ʱ��")?> </td>
      <td nowrap align="center"><?=_(";��վ��")?> </td>
      <td nowrap align="center"><?=_("����")?> </td>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
      <td nowrap align="center"><?=_("����")?> </td>
<?
}
?>
  </tr>

<?
  $BUS_COUNT = 0;
  while($ROW=mysql_fetch_array($cursor1))
  {
    $BUS_COUNT++;

    if($BUS_COUNT<$PAGE_START)
       continue;
    else if($BUS_COUNT>$PAGE_END)
       break;

    $ID=$ROW["id"];
    $LINEID1=$ROW["lineid"];
    $PASSBY=$ROW["PassBy"];
    $STARTTIME=$ROW["startTime"];
    $ENDTIME=$ROW["endTime"];
    $BUSTYPE=$ROW["busType"];

    if($BUS_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$LINEID1?></td>
      <td nowrap align="center"><?=$STARTTIME?></td>
      <td nowrap align="center"><?=$ENDTIME?></td>
      <td align="center" width="550">
<?
if(substr($PASSBY,-1,1)==",")
   $PASSBY=substr($PASSBY,0,-1);
$PASSBY = str_replace(","," - ", $PASSBY);
$PASSBY=str_replace($START,"<font color='FF0000'>".$START."</font>", $PASSBY);
$PASSBY=str_replace($END,"<font color='FF0000'>".$END."</font>", $PASSBY);
echo $PASSBY;
?>
      </td>
      <td nowrap align="center"><?=$BUSTYPE?></td>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
      <td nowrap align="center"><a href="new.php?ID=<?=$ID?>&CITY_ID=<?=$CITY?>"><?=_("�༭")?></a></td>
<?
}
?>
    </tr>
<?
  } //while($ROW=mysql_fetch_array($cursor1))

?>
  <tr class="TableControl">
  <td colspan="6" align="right">
     <input type="button"  value="<?=_("��ҳ")?>" class="SmallButton"  <?if($PAGE_START==1)echo "disabled";?> onclick="location='search.php?CITY=<?=$CITY?>&START=<?=$START?>&END=<?=$END?>&LINEID=<?=$LINEID?>'"> &nbsp;&nbsp;
     <input type="button"  value="<?=_("��һҳ")?>" class="SmallButton" <?if($PAGE_START==1)echo "disabled";?> onclick="location='search.php?CITY=<?=$CITY?>&START=<?=$START?>&END=<?=$END?>&LINEID=<?=$LINEID?>&PAGE_START=<?=($PAGE_START-$PAGE_SIZE)?>'"> &nbsp;&nbsp;
     <input type="button"  value="<?=_("��һҳ")?>" class="SmallButton" <?if($PAGE_END>=$BUS_COUNT)echo "disabled";?> onclick="location='search.php?CITY=<?=$CITY?>&START=<?=$START?>&END=<?=$END?>&LINEID=<?=$LINEID?>&PAGE_START=<?=($PAGE_END+1)?>'"> &nbsp;&nbsp;
     <input type="button"  value="<?=_("ĩҳ")?>" class="SmallButton"  <?if($PAGE_END>=$BUS_COUNT)echo "disabled";?> onclick="location='search.php?CITY=<?=$CITY?>&START=<?=$START?>&END=<?=$END?>&LINEID=<?=$LINEID?>&PAGE_START=<?=$LAST_PAGE_START?>'"> &nbsp;&nbsp;
     <?=_("ҳ��")?>
     <input type="text" name="PAGE_NUM" value="<?=$PAGE_NUM?>" class="SmallInput" size="2"> <input type="button"  value="<?=_("ת��")?>" class="SmallButton" onclick="set_page();" title="<?=_("ת��ָ����ҳ��")?>">&nbsp;&nbsp;
  </td>
  </tr>
</table>

<br>
<div align="center">
 <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='index.php';">
</div>

</body>
</html>