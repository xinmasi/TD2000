<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("��ѯ");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("��ѯ���")?></span><br>
    </td>
  </tr>
</form>
</table>
<?
$CUR_DATE=date("Y-m-d",time());

 //----------- �Ϸ���У�� ---------
if($M_START_B!="")
{
   $TIME_OK=is_date($M_START_B);
   if(!$TIME_OK)
   {	$msg=sprintf(_("��ʼʱ��ĸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE);
   	  Message(_("����"),$msg);
      Button_Back();
      exit;
   }
}
if($M_END_B!="")
{
   $TIME_OK=is_date($M_END_B);
   if(!$TIME_OK)
   {
    	$msg=sprintf(_("��ʼʱ��ĸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE);
   	  Message(_("����"),$msg);
      Button_Back();
      exit;
   }
}

//------------------------ ���������ַ��� ------------------
if($M_START_B!="")
   $M_START_B=$M_START_B." 00:00:00";
else
   $M_START_B="";
if($M_END_B!="")
   $M_END_B=$M_END_B." 23:59:59";
else
   $M_START_B="";

$CONDITION_STR="";
if($M_NAME!="")
   $CONDITION_STR.=" and M_NAME like '%".$M_NAME."%'";
if($TO_ID!="")
   $CONDITION_STR.=" and M_PROPOSER='$TO_ID'";
if($M_START_B!="")
   $CONDITION_STR.=" and M_START>='$M_START_B'";
if($M_END_B!="")
   $CONDITION_STR.=" and M_START<='$M_END_B'";
if($M_ROOM!="")
   $CONDITION_STR.=" and M_ROOM='$M_ROOM'";
if($KEY_WORD1!="")
   $CONDITION_STR.=" and SUMMARY like '%".$KEY_WORD1."%'";
if($KEY_WORD2!="")
   $CONDITION_STR.=" and SUMMARY like '%".$KEY_WORD2."%'";
if($KEY_WORD3!="")
   $CONDITION_STR.=" and SUMMARY like '%".$KEY_WORD3."%'";

$COUNT=0;
$query = "SELECT * from MEETING where 1=1 $CONDITION_STR order by M_START desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
 $M_ID=$ROW["M_ID"];
 $M_PROPOSER=$ROW["M_PROPOSER"];
 $M_ATTENDEE=$ROW["M_ATTENDEE"];
 $M_NAME=$ROW["M_NAME"];
 $M_START=$ROW["M_START"];
 $M_END=$ROW["M_END"];
 $SUMMARY=$ROW["SUMMARY"];
 $READ_PEOPLE_ID=$ROW["READ_PEOPLE_ID"];

 $query1 = "SELECT * from USER where USER_ID='$M_PROPOSER'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW1=mysql_fetch_array($cursor1))
    $USER_NAME=$ROW1["USER_NAME"];

 $M_ATTENDEE=$M_ATTENDEE.$M_PROPOSER.",".$READ_PEOPLE_ID;

 if(!find_id($M_ATTENDEE,$_SESSION["LOGIN_USER_ID"]) && $_SESSION["LOGIN_USER_PRIV"]!=1)
    continue;

 elseif($SUMMARY!='')
 {
   	$COUNT++;
    if($COUNT==1)
    {
   ?>
      <table class="TableList" align="center" width="95%">
      <tr class="TableHeader">
        <td nowrap align="center"><?=_("��������")?></td>
        <td nowrap align="center"><?=_("������")?></td>
        <td nowrap align="center"><?=_("��ʼʱ��")?></td>
        <td nowrap align="center"><?=_("����ʱ��")?></td>
        <td align="center"><?=_("�鿴��Ҫ")?></td>
     </tr>
   <?
    }

   if($COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
   if($COUNT<=15)
   {
   ?>
     <tr class="<?=$TableLine?>">
       <td nowrap align="center"><?=$M_NAME?></td>
       <td nowrap align="center"><?=$USER_NAME?></td>
       <td nowrap align="center"><?=$M_START?></td>
       <td nowrap align="center"><?=$M_END?></td>
       <td align="center"><a href="javascript:;" onClick="window.open('../apply/review.php?M_ID=<?=$M_ID?>','','height=520,width=650,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=180,top=100,resizable=yes');"><?=_("�鿴��Ҫ")?></a>&nbsp;&nbsp;&nbsp;</td>
     </tr>
   <?
   }
 }
}//while
if($COUNT==0)
   message(_("��ʾ��"),_("�޷��������Ļ����Ҫ��"))
?>
</table>
<br><center><input type="button" class="BigButton" value="<?=_("����")?>" onclick="history.back();"></center>
</body>
</html>