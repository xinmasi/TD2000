<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
//2013-4-11 �������ѯ
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER=""; 

$HTML_PAGE_TITLE = _("��ʱ");
include_once("inc/header.inc.php");
?>



<script>
function delete_one(ROW_ID)
{
 msg='<?=_("ȷ��Ҫɾ���õ���ʱ��")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?ROW_ID="+ROW_ID;
  window.location=URL;
 }
}
</script>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�����ʱ��")?></span></td>
  </tr>
</table>
<div align="center">
  <input type="button" class="BigButton" value="<?=_("�½�����ʱ��")?>" onClick="location='new.php'">
</div>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("������ʱ��")?></span></td>
  </tr>
</table>
<?
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from COUNTDOWN where ISPRIVATE='1' and TO_USER='".$_SESSION["LOGIN_USER_ID"]."' order by ORDER_NO asc, ROW_ID desc";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if(mysql_num_rows($cursor) <= 0)
{
   Message("", _("���޵���ʱ��"));
   exit;
}
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center" width="13%"><?=_("�����")?></td>
      <!--  <td nowrap align="center"><?=_("��ʱ����")?></td>-->
      <!--<td nowrap align="center"><?=_("������Χ")?></td>-->
      <td nowrap align="center" width="35%"><?=_("����")?></td>
      <td nowrap align="center" width="14%"><?=_("��ʼ����")?></td>
      <td nowrap align="center" width="14%"><?=_("��ֹ����")?></td>
      <td nowrap align="center" width="14%"><?=_("��������")?></td>
      <td nowrap align="center" width="12%"><?=_("״̬")?></td>
      <td nowrap align="center" width="12%"><?=_("����")?></td>
    </tr>
<?
$POSTFIX = _("��");
while($ROW=mysql_fetch_array($cursor))
{
   $ROW_ID=$ROW["ROW_ID"];
   $ORDER_NO=$ROW["ORDER_NO"];
   $TO_DEPT=$ROW["TO_DEPT"];
   $TO_PRIV=$ROW["TO_PRIV"];
   $TO_USER=$ROW["TO_USER"];
   $CONTENT=$ROW["CONTENT"];
   $END_TIME=$ROW["END_TIME"];
   $COUNT_TYPE=$ROW["COUNT_TYPE"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];
   $ANNUAL=$ROW["ANNUAL"];

   $TO_NAME="";
   if($TO_DEPT=="ALL_DEPT")
      $TO_NAME=_("ȫ�岿��");
   else
      $TO_NAME=GetDeptNameById($TO_DEPT);
   $PRIV_NAME=GetPrivNameById($TO_PRIV);
   $USER_NAME=GetUserNameById($TO_USER);

   $TO_NAME_TITLE="";
   $TO_NAME_STR="";
/*
   if($TO_NAME!="")
   {
      if(substr($TO_NAME,-strlen($POSTFIX))==$POSTFIX)
         $TO_NAME=substr($TO_NAME,0,-strlen($POSTFIX));
      $TO_NAME_TITLE.=_("���ţ�").$TO_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("���ţ�")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
   }

   if($PRIV_NAME!="")
   {
      if(substr($PRIV_NAME,-strlen($POSTFIX))==$POSTFIX)
         $PRIV_NAME=substr($PRIV_NAME,0,-strlen($POSTFIX));
      if($TO_NAME_TITLE!="")
         $TO_NAME_TITLE.="\n\n";
      $TO_NAME_TITLE.=_("��ɫ��").$PRIV_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("��ɫ��")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
   }

   if($USER_NAME!="")
   {
      if(substr($USER_NAME,-strlen($POSTFIX))==$POSTFIX)
         $USER_NAME=substr($USER_NAME,0,-strlen($POSTFIX));
      if($TO_NAME_TITLE!="")
         $TO_NAME_TITLE.="\n\n";
      $TO_NAME_TITLE.=_("��Ա��").$USER_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("��Ա��")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
   }
*/
   /*if($ANNUAL==1)
   {
       if($CUR_DATE >=$END_TIME)
       {
           $END_TIME2 = date("Y-m-d",strtotime('+ 1 year',strtotime($END_TIME)));
            //$query1 = " update COUNTDOWN set END_TIME = '$END_TIME2' where ROW_ID='$ROW_ID'";
            //exequery(TD::conn(),$query1);
            $END_TIME = $END_TIME2;
       }
   }*/

   if($END_TIME >= $CUR_DATE || $ANNUAL=="1")
      $STATUS="<font color='#00AA00'><b>"._("δ����")."</font>";
   else
      $STATUS="<font color='red'><b>"._("�ѹ���")."</font>";

   if($END_TIME >= $CUR_DATE)
   {
   		$DAYS_UP = (strtotime($CUR_DATE) - strtotime($BEGIN_TIME)) / 86400;
      $DAYS_DOWN = (strtotime($END_TIME) - strtotime($CUR_DATE)) / 86400;
      $DAYS_SUM = (strtotime($END_TIME) - strtotime($BEGIN_TIME)) / 86400;
      $CONTENT = str_replace("{N}", $DAYS_DOWN, $CONTENT);
      $CONTENT = str_replace("{M}", $DAYS_UP, $CONTENT);
      $CONTENT = str_replace("{B}", $BEGIN_TIME, $CONTENT);
      $CONTENT = str_replace("{E}", $END_TIME, $CONTENT);
      $CONTENT = str_replace("{S}", $DAYS_SUM, $CONTENT);
   }
?>
    <tr class="TableData">
      <td align="center" width="13%"><?=$ORDER_NO?></a></td>
      <!--  <td align="center"><?=_("����ʱ")?></td>-->
      <!--<td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>-->
      <td align="center" width="35%"><?=$CONTENT?></a></td>
      <td align="center" width="14%"><?=$BEGIN_TIME?></a></td>
      <td align="center" width="14%"><? echo $ANNUAL=="1" ? "-" : $END_TIME; ?></a></td>
        <td align="center" width="14%"><? echo $ANNUAL=="1" ? $END_TIME : "-" ;?></a></td>
      <td nowrap align="center" width="12%"><?=$STATUS?></td>
      <td nowrap align="center" width="12%">
      <a href="new.php?ROW_ID=<?=$ROW_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("�޸�")?></a>&nbsp;
      <a href="javascript:delete_one('<?=$ROW_ID?>');"> <?=_("ɾ��")?></a>
      </td>
    </tr>
<?
}
?>
</table>
</body>
</html>