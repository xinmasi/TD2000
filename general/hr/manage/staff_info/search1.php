<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");

$PAGE_SIZE = 10;
$CUR_DATE=date("Y-m-d",time());
if(!isset($start) || $start=="")
{
    $start=0;
}

$HTML_PAGE_TITLE = _("未建档人员查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION['LOGIN_THEME']?>/bbs.css">

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("未建档人员")?></span>&nbsp;
        </td>
    </tr>
<?
$count=0;
$td="";
$query="select * from USER b where DEPT_ID!='0' ".$WHERE_STR." order by UID";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW['USER_ID'];
    $USER_NAME=$ROW['USER_NAME'];
    $USER_PRIV = $ROW['USER_PRIV'];
    $DEPT_ID=$ROW['DEPT_ID'];
    $DEPT_NAME = dept_long_name($DEPT_ID);
  
    $query2 = "SELECT PRIV_NAME FROM USER_PRIV WHERE USER_PRIV ='$USER_PRIV'";
    $cursor2 = exequery(TD::conn(),$query2);
    if($ROW2 = mysql_fetch_array($cursor2))
    {
        $PRIV_NAME = $ROW2['PRIV_NAME'];
    }
    
    $query2="SELECT * FROM hr_staff_info WHERE USER_ID='$USER_ID'";
    $cursor2 = exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        continue;
    }
    else
    {
        $count++;
        if($count > $start && $count <=($PAGE_SIZE+$start))
        {
            $td.= "<tr class='TableData'>
            <td nowrap align='center'>".$USER_NAME."</td><td nowrap align='center'>".$DEPT_NAME."</td><td nowrap align='center'>".$PRIV_NAME."</td><td nowrap align='center'><a href='staff_info.php?USER_ID=".$USER_ID."&DEPT_ID=".$DEPT_ID."'>"._("建立档案")."</a></td></tr>";
        }
        else
        {
            continue;
        }
    }
}
?>
</table>
<?
if($count>0)
{
?>
<table class="TableList" width="60%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center" width="20%"><?=_("姓名")?></td>
        <td nowrap align="center" width="30%"><?=_("所属部门")?></td>
        <td nowrap align="center" width="30%"><?=_("主角色")?></td>
        <td nowrap align="center" width="20%"><?=_("操作")?></td>
    </tr>
<?=$td?>
</table>
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$count,$PAGE_SIZE)?></td>
    </tr>
</table>
<?
    $MESSAGE=sprintf(_("共%s条未建档人员"),$count);
    //message("",$MESSAGE);
}
else
{
    message("",_("无未建档人员"));
}
?>
<br>
</body>
</html>