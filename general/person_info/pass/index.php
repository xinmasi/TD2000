<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
$query = "SELECT USEING_KEY,LAST_PASS_TIME from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $USEING_KEY=$ROW["USEING_KEY"];
   $LAST_PASS_TIME=$ROW["LAST_PASS_TIME"];
   if($LAST_PASS_TIME=="0000-00-00 00:00:00")
      $LAST_PASS_TIME="";
}
$PARA_ARRAY=get_sys_para("SEC_PASS_FLAG,SEC_PASS_TIME,SEC_PASS_MIN,SEC_PASS_MAX,SEC_PASS_SAFE,RETRIEVE_PWD");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
   $$PARA_NAME = $PARA_VALUE;


$HTML_PAGE_TITLE = _("修改密码");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />


<body class="bodycolor" onload="document.form1.PASS0.focus();">

<form method="post" action="update.php" name="form1" >
<table class="table table-bordered" width="500">
<thead>
    <tr>
        <td colspan="2" class="Big"> <span class=""><?=_("修改密码")?></span><br>
        </td>
    </tr>
</thead>
<tr class="Big">
	<td class="TableData" width="150px"><b><i class="iconfont">&#xe630;</i><?=_("用户名：")?></b></td>
	<td class="TableData"><b><?=$_SESSION["LOGIN_BYNAME"]?></b></td>
</tr>
<tr>
	<td class="TableData" ><i class="iconfont">&#xe634;</i><?=_("原密码：")?></td>
	<td class="TableData" >
	  <input type="password" name="PASS0"  class="" size="20">
	</td>
</tr>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe62c;</i><?=_("新密码：")?></td>
	<td class="TableData" >
	  <input type="password" name="PASS1"  class="" size="20" maxlength="<?=$SEC_PASS_MAX?>" > 
    <span><?=$SEC_PASS_MIN?>-<?=$SEC_PASS_MAX?><?=_("位")?><?if($SEC_PASS_SAFE=="1") echo _("，必须同时包含字母和数字");?></span>
	</td>
</tr>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe61f;</i><?=_("确认新密码：")?></td>
	<td class="TableData" >
	  <input type="password" name="PASS2"  class="" size="20" maxlength="<?=$SEC_PASS_MAX?>" > 
    <span><?=$SEC_PASS_MIN?>-<?=$SEC_PASS_MAX?><?=_("位")?><?if($SEC_PASS_SAFE=="1") echo _("，必须同时包含字母和数字");?> </span>
	</td>
</tr>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe621;</i><?=_("上次修改时间：")?></td>
	<td class="TableData" >
	  <?=$LAST_PASS_TIME?>
	</td>
</tr>

<?
if($SEC_PASS_FLAG=="1")
	$REMARK=sprintf(_("您的密码将于 %s天后过期。"),"<span class=big4><b>".($SEC_PASS_TIME-floor((time()-strtotime($LAST_PASS_TIME))/24/3600))."</span> </b>");
   //$REMARK=_("您的密码将于 ")."<span class=big4><b>".($SEC_PASS_TIME-floor((time()-strtotime($LAST_PASS_TIME))/24/3600))."</span> </b>"._("天后过期。");
else
   $REMARK=_("密码永不过期");
?>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe619;</i><?=_("密码过期：")?></td>
	<td class="TableData" >
	  <?=$REMARK?>
	</td>
</tr>
<?
if($RETRIEVE_PWD == '1' && $_SESSION['LOGIN_USER_ID'] != 'admin' && $USEING_KEY != '1')
{
?>
<tr>
	<td class="TableData red" colspan="2">
	  <?=_("注：登录时如果忘记登录密码，可以通过在“电子邮件 >> Internet邮箱”中设置的“电子邮件外发默认邮箱”找回OA登录密码。")?>
	</td>
</tr>
<?
}
?>
<tr align="center" >
    <td class="TableData" colspan="2" style="text-align:center">
      <input type="submit" value="<?=_("保存修改")?>" class='btn btn-primary'>
    </td>
</tr>

</table>
</form>

<div style="width: 800px; margin-left:20px; height:25px; line-height:25px;">
   <span class="big3"> <?=_("最近10次修改密码日志")?></span>
</div>

<?
 $TYPE_DESC=get_code_name('14',"SYS_LOG");
 $query = "SELECT * from SYS_LOG where TYPE='14' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REMARK='' order by TIME desc";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if(mysql_num_rows($cursor)==0)
 {
 	 echo "<br>";
    Message("",_("无修改密码日志记录"));
    exit;
 }
?>
<table class="table table-bordered" width="70%">
  <thead style="background-color:#ebebeb;">
    <tr>
      <th style="text-align: center;"><?=_("用户")?></th>
      <th style="text-align: center;"><?=_("时间")?></th>
      <th style="text-align: center;">IP<?=_("地址")?></th>
      <th style="text-align: center;"><?=_("类型")?></th>
      <th style="text-align: center;"><?=_("备注")?></th>
    </tr>
  </thead>
<?
 $LOG_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $LOG_COUNT++;

    if($LOG_COUNT>10)
       break;
    $TIME=$ROW["TIME"];
    $IP=$ROW["IP"];
    $TYPE=$ROW["TYPE"];
    $REMARK=$ROW["REMARK"];
    if($LOG_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?> " style="background-color:#fff;">
      <td nowrap style="text-align: center;"><?=$_SESSION["LOGIN_USER_NAME"]?></td>
      <td nowrap style="text-align: center;"><?=$TIME?></td>
      <td nowrap style="text-align: center;"><?=$IP?></td>
      <td nowrap style="text-align: center;"><?=$TYPE_DESC?></td>
      <td><?=$REMARK?></td>
    </tr>
<?
 }
?>
</table>
</body>
</html>
