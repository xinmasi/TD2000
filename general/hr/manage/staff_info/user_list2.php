<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("��ְ��Ա");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor">
<?

if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("SMS", 15);
if(!isset($start))
   $start=0;
//ͳ���ܼ�¼����
if(!isset($TOTAL_ITEMS))
{
  $query = "SELECT count(USER.USER_ID) FROM USER left join HR_STAFF_INFO on USER.USER_ID=HR_STAFF_INFO.USER_ID where USER.DEPT_ID='0'";
  if($DEPT_ID!="")
  {
      $query.= " and HR_STAFF_INFO.DEPT_ID='".$DEPT_ID."'";
  }
  $cursor= exequery(TD::conn(),$query);
  $ROW=mysql_fetch_array($cursor);
  $TOTAL_ITEMS=$ROW[0];
}

//------������Ա-----
$query = "SELECT USER_PRIV.PRIV_NAME,USER.USER_ID,USER.USER_NAME,HR_STAFF_INFO.STAFF_SEX,STAFF_BIRTH,HR_STAFF_INFO.DEPT_ID FROM USER_PRIV,USER left join HR_STAFF_INFO on USER.USER_ID=HR_STAFF_INFO.USER_ID  "
       ." where USER.DEPT_ID='0' and USER.USER_PRIV=USER_PRIV.USER_PRIV";
  if($DEPT_ID!="")
  {
      $query.= " and HR_STAFF_INFO.DEPT_ID='".$DEPT_ID."'";
  }
    $query.= " order by PRIV_NO,USER_NO,USER_NAME limit $start,$PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);

if($TOTAL_ITEMS>0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" HEIGHT="20"><span class="big3"><?=_("δ��¼ԭ�������ŵ���ְ��Ա")?></span><br />
    </td>
    <td class="small1"><?=sprintf(_("��%s����Ϣ"), '<span class="big4">'.$TOTAL_ITEMS.'</span>')?> &nbsp;</td>
    <td align="center" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
  </tr>
</table>

<table width="100%" class="TableList">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("ԭ������")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��ɫ")?></td>
      <td nowrap align="center"><?=_("�Ա�")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("��ְԭ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>
<?
  while($ROW=mysql_fetch_array($cursor))
  {
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $PRIV_NAME=$ROW["PRIV_NAME"];

    $query1="select QUIT_REASON from HR_STAFF_LEAVE where LEAVE_PERSON='$USER_ID'";
    $cursor1=exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
    {
       $REASON=$ROW1["QUIT_REASON"];
    }

    if($ROW["STAFF_SEX"]==0)
      $SEX_DESC=_("��");
    if($ROW["STAFF_SEX"]==1)
      $SEX_DESC=_("Ů");
    $HR_BIRTHDAY=$ROW["STAFF_BIRTH"];
    if($HR_BIRTHDAY=="0000-00-00")
       $HR_BIRTHDAY="";

    $DEPT_NAME=dept_long_name($DEPT_ID);
    if($DEPT_NAME=="")
	     $DEPT_NAME=_("δ��¼");

?>
 <tr class="TableData">
      <td><?=$DEPT_NAME?></td>
      <td align="center"><?=$USER_NAME?></td>
      <td align="center"><?=$PRIV_NAME?></td>
      <td align="center"><?=$SEX_DESC?></td>
      <td align="center"><?=$HR_BIRTHDAY?></td>
      <td align="center"><?=$REASON?></td>
      <td align="center">
        <a href="staff_info.php?USER_ID=<?=$USER_ID?>&SOURCE=remind1"><?=_("�༭")?></a>
      </td>
    </tr>
<?
  }//end while
?>
</table>
<?
}
else //$TOTAL_ITEMS=0
{
?>
<div align="center">
<?
   Message("",_("����ְ��Ա!"));
?>
</div>
<?
}
?>
</body>
</html>
