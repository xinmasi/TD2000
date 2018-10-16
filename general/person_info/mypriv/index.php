<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$PARA_ARRAY = get_sys_para("EDIT_BYNAME");
$EDIT_BYNAME = $PARA_ARRAY["EDIT_BYNAME"];

$HTML_PAGE_TITLE = _("我的帐户");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/person_info/index.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>

<script Language="JavaScript">
function CheckForm()
{
  form1.submit();
}

</script>

<body class="bodycolor">

<? 
 //.................................
 $query = "SELECT a.*,EMAIL_CAPACITY,FOLDER_CAPACITY from USER a ,USER_EXT b  where a.UID='".$_SESSION["LOGIN_UID"]."' and a.UID=b.UID";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME        = $ROW["USER_NAME"];
    $SEX              = $ROW["SEX"];
    $DEPT_ID          = $ROW["DEPT_ID"];
    $USER_PRIV        = $ROW["USER_PRIV"];
    $POST_PRIV        = $ROW["POST_PRIV"];
    $POST_DEPT        = $ROW["POST_DEPT"];
    $USER_PRIV_OTHER  = $ROW["USER_PRIV_OTHER"];
    $USER_NO          = $ROW["USER_NO"];
    $NOT_VIEW_USER    = $ROW["NOT_VIEW_USER"];
    $NOT_VIEW_TABLE   = $ROW["NOT_VIEW_TABLE"];
    $NOT_MOBILE_LOGIN = $ROW["NOT_MOBILE_LOGIN"];//获取禁止登陆手机客户端设置wrj 20140326
    $NOT_SEARCH       = $ROW["NOT_SEARCH"];
    $BYNAME           = $ROW["BYNAME"];
    $BIRTHDAY         = $ROW["BIRTHDAY"];
    $THEME            = $ROW["THEME"];
    $MOBIL_NO         = $ROW["MOBIL_NO"];
    $MOBIL_NO_HIDDEN  = $ROW["MOBIL_NO_HIDDEN"];
    $BIND_IP          = $ROW["BIND_IP"];
    $USEING_KEY       = $ROW["USEING_KEY"];
    $FOLDER_CAPACITY  = $ROW["FOLDER_CAPACITY"];
    $EMAIL_CAPACITY   = $ROW["EMAIL_CAPACITY"];  
    if($EMAIL_CAPACITY=="" || $EMAIL_CAPACITY==0)
    	$EMAIL_CAPACITY=_("不限制");
    else
      $EMAIL_CAPACITY=$EMAIL_CAPACITY." MB";
    if($FOLDER_CAPACITY=="" || $FOLDER_CAPACITY==0)
    	$FOLDER_CAPACITY=_("不限制");
    else
      $FOLDER_CAPACITY=$FOLDER_CAPACITY." MB";
    $BIRTHDAY=strtok($BIRTHDAY," ");
    if($BIRTHDAY=="0000-00-00")
        $BIRTHDAY="";
    
 }

 $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW=mysql_fetch_array($cursor1))
    $PRIV_NAME=$ROW["PRIV_NAME"];

 $TOK=strtok($USER_PRIV_OTHER,",");
 while($TOK!="")
 {
   $query1 = "SELECT * from USER_PRIV where USER_PRIV='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $OTHER_PRIV_NAME.=$ROW["PRIV_NAME"].",";
   $TOK=strtok(",");
 }

 if(substr($OTHER_PRIV_NAME,-1)==",")
   $OTHER_PRIV_NAME=substr($OTHER_PRIV_NAME,0,-1);

 if($POST_PRIV=="1")
    $POST_PRIV=_("全体");
 elseif($POST_PRIV=="2")
    $POST_PRIV=_("指定部门");
 elseif($POST_PRIV=="0")
    $POST_PRIV=_("本部门");
 elseif($POST_PRIV=="6")
    $POST_PRIV=_("本机构"); 
	
 if($POST_PRIV==_("指定部门"))
 {
    $TOK=strtok($POST_DEPT,",");
    while($TOK!="")
    {
       $query1 = "SELECT * from DEPARTMENT where DEPT_ID='$TOK'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $POST_DEPT_NAME.=$ROW["DEPT_NAME"].",";
       $TOK=strtok(",");
    }

    if(substr($POST_DEPT_NAME,-1)==",")
       $POST_DEPT_NAME=substr($POST_DEPT_NAME,0,-1);
 }

?>

<form action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="table table-bordered" width="90%">
    <thead>
        <tr>
            <td colspan="2"><?=_("账户信息设置")?>(<?=$USER_NAME?>)</td>
        </tr>
    </thead>
    <tr class="TableData">
        <td nowrap width="160px"><i class="iconfont">&#xe630;</i> <?=_("用户名：")?></td>
        <td>
            <input type="hidden" name="EDIT_BYNAME" class="" value="<?=$EDIT_BYNAME?>" maxlength="20">
<?
if($EDIT_BYNAME)
{
?>
            <input type="text" name="BYNAME" class="" value="<?=$BYNAME?>" maxlength="20">
<?
}
else
{
    echo $BYNAME;
}
?>
        </td>
    </tr>
<?
if($EDIT_BYNAME)
{
?>
    <tr>
        <td class="TableData" colspan="2" align="center" style="text-align:center">
            <input type="submit" class="btn btn-primary" value="<?=_("保存修改")?>">
        </td>
    </tr>
<?
}
?>
</table>
</form>
<table class="table table-bordered" width="90%">
  <thead>
    <tr>
      <td colspan="2"><?=_("用户角色与管理范围")?></td>
    </tr>
  </thead>
    <tr class="TableData" height="25">
      <td nowrap width="160px"><i class="iconfont">&#xe637;</i> <?=_("主角色：")?></td>
      <td><?=$PRIV_NAME?></td>
    </tr>
    <tr class="TableData" height="25">
      <td nowrap> <i class="iconfont">&#xe609;</i><?=_("辅助角色：")?></td>
      <td><?=$OTHER_PRIV_NAME?></td>
    </tr>
    <tr class="TableData" height="25">
      <td nowrap><i class="iconfont">&#xe612;</i> <?=_("管理范围：")?></td>
      <td><?=$POST_PRIV?> <?if($POST_DEPT_NAME!="")echo "<br><br>".$POST_DEPT_NAME;?></td>
    </tr>
</table>
<table class="table table-bordered" width="90%">
    <thead>
        <tr>
            <td colspan="2"><?=_("系统使用权限")?></td>
        </tr>
    </thead>
   <tr class="TableData">
    <td nowrap width="160px"><i class="iconfont">&#xe608;</i><?=_("访问控制：")?></td>
    <td nowrap  >
    	  <input type="checkbox" disabled name="NOT_VIEW_USER" style="margin:0;" <?if($NOT_VIEW_USER) echo "checked";?>><?=_("禁止查看用户列表")?>&nbsp;
    	  <input type="checkbox" disabled name="NOT_VIEW_TABLE" style="margin:0;" <?if($NOT_VIEW_TABLE) echo "checked";?>><?=_("禁止显示桌面")?>&nbsp;
          <input type="checkbox" disabled name="USEING_KEY" style="margin:0;" <?if($USEING_KEY) echo "checked";?>><?=_("使用用户KEY登录")?>&nbsp;
          <input type="checkbox" disabled name="NOT_MOBILE_LOGIN" style="margin:0;" <?if($NOT_MOBILE_LOGIN) echo "checked";?>><?=_("禁止登陆手机客户端")?>  
    
    </td>
   </tr>
   <tr class="TableData">
    <td nowrap ><i class="iconfont">&#xe61d;</i><?=_("内部邮箱容量：")?></td>
    <td nowrap ><?=$EMAIL_CAPACITY?></td>
   </tr>
   <tr class="TableData">
    <td nowrap ><i class="iconfont">&#xe60c;</i><?=_("个人文件柜容量：")?></td>
    <td nowrap ><?=$FOLDER_CAPACITY?></td>
   </tr>
</table>

</body>
</html>