<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("离职人员");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor">
<?

if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("SMS", 15);
if(!isset($start))
   $start=0;
//统计总记录条数
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

//------退休人员-----
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" HEIGHT="20"><span class="big3"><?=_("未记录原所属部门的离职人员")?></span><br />
    </td>
    <td class="small1"><?=sprintf(_("共%s条信息"), '<span class="big4">'.$TOTAL_ITEMS.'</span>')?> &nbsp;</td>
    <td align="center" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
  </tr>
</table>

<table width="100%" class="TableList">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("原属部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("角色")?></td>
      <td nowrap align="center"><?=_("性别")?></td>
      <td nowrap align="center"><?=_("出生日期")?></td>
      <td nowrap align="center"><?=_("离职原因")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
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
      $SEX_DESC=_("男");
    if($ROW["STAFF_SEX"]==1)
      $SEX_DESC=_("女");
    $HR_BIRTHDAY=$ROW["STAFF_BIRTH"];
    if($HR_BIRTHDAY=="0000-00-00")
       $HR_BIRTHDAY="";

    $DEPT_NAME=dept_long_name($DEPT_ID);
    if($DEPT_NAME=="")
	     $DEPT_NAME=_("未记录");

?>
 <tr class="TableData">
      <td><?=$DEPT_NAME?></td>
      <td align="center"><?=$USER_NAME?></td>
      <td align="center"><?=$PRIV_NAME?></td>
      <td align="center"><?=$SEX_DESC?></td>
      <td align="center"><?=$HR_BIRTHDAY?></td>
      <td align="center"><?=$REASON?></td>
      <td align="center">
        <a href="staff_info.php?USER_ID=<?=$USER_ID?>&SOURCE=remind1"><?=_("编辑")?></a>
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
   Message("",_("无离职人员!"));
?>
</div>
<?
}
?>
</body>
</html>
