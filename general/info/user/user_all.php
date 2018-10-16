<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("人员列表");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/sort_table.js"></script>


<body class="bodycolor" leftmargin="0" onload="SortTable('beSortTable');">
<table class="TableList" id="beSortTable" width="100%" align="center">
    <tr class="TableHeader" align="center">
      <td nowrap><b><?=_("部门")?></b></td>
      <td nowrap><b><?=_("角色")?></b></td>
      <td nowrap><b><?=_("姓名")?></b></td>
      <td nowrap><b><?=_("在线时长")?></b></td>
      <td nowrap><b><?=_("性别")?></b></td>
      <td nowrap><b><?=_("工作电话")?></b></td>
      <td nowrap><b><?=_("手机")?></b></td>
      <td nowrap><b><?=_("电子邮件")?></b></td>
    </tr>
<?
//============================ 显示已定义用户 =======================================
$query = "SELECT * from DEPARTMENT order by DEPT_NO";
$cursor_10= exequery(TD::conn(),$query);

$DEPT_COUNT=0;
while($ROW=mysql_fetch_array($cursor_10))
{
 $DEPT_COUNT++;
 $DEPT_ID=$ROW["DEPT_ID"];
 $DEPT_NAME=$ROW["DEPT_NAME"];
 $DEPT_LONG_NAME=dept_long_name($DEPT_ID);

 $query = "SELECT * from USER,USER_PRIV where DEPT_ID='$DEPT_ID' and USER.USER_PRIV=USER_PRIV.USER_PRIV  order by PRIV_NO,USER_NO,USER_NAME";
 $cursor= exequery(TD::conn(),$query);

 $USER_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $USER_COUNT++;
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $USER_PRIV=$ROW["USER_PRIV"];
    $ONLINE=$ROW["ONLINE"];
    $SEX=$ROW["SEX"];
    $TEL_NO_DEPT=$ROW["TEL_NO_DEPT"];
    $MOBIL_NO=$ROW["MOBIL_NO"];
    $EMAIL=$ROW["EMAIL"];
    $MOBIL_NO_HIDDEN=$ROW["MOBIL_NO_HIDDEN"];
    $HOUR=round($ONLINE/3600,1);

    $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
         $USER_PRIV=$ROW["PRIV_NAME"];

    if($SEX==0)
       $SEX=_("男");
    else
       $SEX=_("女");

    if($DEPT_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableContent";
?>
    <tr class="<?=$TableLine?>" align="center">
      <td nowrap title="<?=$DEPT_LONG_NAME?>"><?=$DEPT_NAME?></td>
      <td nowrap><?=$USER_PRIV?></td>
      <td nowrap><a href="/general/ipanel/user/user_info.php?USER_ID=<?=$USER_ID?>" target="user_info"><?=$USER_NAME?></a></td>
      <td nowrap align=right><?=$HOUR?></td>
      <td nowrap><?=$SEX?></td>
      <td nowrap><?=$TEL_NO_DEPT?></td>
      <td nowrap>
<?
    if($MOBIL_NO_HIDDEN!="1")
    {
?>
        <A href="/general/mobile_sms/?TO_ID=<?=$USER_ID?>,&TO_NAME=<?=$USER_NAME?>,"><?=$MOBIL_NO?></A>
<?
    }
    else
    {
?>
        <span title="<?=_("不公开")?>" style="cursor:hand">***********</span>
<?
    }
?>
      </td>
      <td nowrap><a href="mailto:<?=$EMAIL?>"><?=$EMAIL?></a></td>
    </tr>
<?
 }
 if($USER_COUNT==0)
    $DEPT_COUNT--;
}
?>

</table>

</body>
</html>