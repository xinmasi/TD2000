<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 ����������ѯ
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


$HTML_PAGE_TITLE = _("�޸�����");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />


<body class="bodycolor" onload="document.form1.PASS0.focus();">

<form method="post" action="update.php" name="form1" >
<table class="table table-bordered" width="500">
<thead>
    <tr>
        <td colspan="2" class="Big"> <span class=""><?=_("�޸�����")?></span><br>
        </td>
    </tr>
</thead>
<tr class="Big">
	<td class="TableData" width="150px"><b><i class="iconfont">&#xe630;</i><?=_("�û�����")?></b></td>
	<td class="TableData"><b><?=$_SESSION["LOGIN_BYNAME"]?></b></td>
</tr>
<tr>
	<td class="TableData" ><i class="iconfont">&#xe634;</i><?=_("ԭ���룺")?></td>
	<td class="TableData" >
	  <input type="password" name="PASS0"  class="" size="20">
	</td>
</tr>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe62c;</i><?=_("�����룺")?></td>
	<td class="TableData" >
	  <input type="password" name="PASS1"  class="" size="20" maxlength="<?=$SEC_PASS_MAX?>" > 
    <span><?=$SEC_PASS_MIN?>-<?=$SEC_PASS_MAX?><?=_("λ")?><?if($SEC_PASS_SAFE=="1") echo _("������ͬʱ������ĸ������");?></span>
	</td>
</tr>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe61f;</i><?=_("ȷ�������룺")?></td>
	<td class="TableData" >
	  <input type="password" name="PASS2"  class="" size="20" maxlength="<?=$SEC_PASS_MAX?>" > 
    <span><?=$SEC_PASS_MIN?>-<?=$SEC_PASS_MAX?><?=_("λ")?><?if($SEC_PASS_SAFE=="1") echo _("������ͬʱ������ĸ������");?> </span>
	</td>
</tr>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe621;</i><?=_("�ϴ��޸�ʱ�䣺")?></td>
	<td class="TableData" >
	  <?=$LAST_PASS_TIME?>
	</td>
</tr>

<?
if($SEC_PASS_FLAG=="1")
	$REMARK=sprintf(_("�������뽫�� %s�����ڡ�"),"<span class=big4><b>".($SEC_PASS_TIME-floor((time()-strtotime($LAST_PASS_TIME))/24/3600))."</span> </b>");
   //$REMARK=_("�������뽫�� ")."<span class=big4><b>".($SEC_PASS_TIME-floor((time()-strtotime($LAST_PASS_TIME))/24/3600))."</span> </b>"._("�����ڡ�");
else
   $REMARK=_("������������");
?>

<tr>
	<td class="TableData" ><i class="iconfont">&#xe619;</i><?=_("������ڣ�")?></td>
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
	  <?=_("ע����¼ʱ������ǵ�¼���룬����ͨ���ڡ������ʼ� >> Internet���䡱�����õġ������ʼ��ⷢĬ�����䡱�һ�OA��¼���롣")?>
	</td>
</tr>
<?
}
?>
<tr align="center" >
    <td class="TableData" colspan="2" style="text-align:center">
      <input type="submit" value="<?=_("�����޸�")?>" class='btn btn-primary'>
    </td>
</tr>

</table>
</form>

<div style="width: 800px; margin-left:20px; height:25px; line-height:25px;">
   <span class="big3"> <?=_("���10���޸�������־")?></span>
</div>

<?
 $TYPE_DESC=get_code_name('14',"SYS_LOG");
 $query = "SELECT * from SYS_LOG where TYPE='14' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REMARK='' order by TIME desc";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if(mysql_num_rows($cursor)==0)
 {
 	 echo "<br>";
    Message("",_("���޸�������־��¼"));
    exit;
 }
?>
<table class="table table-bordered" width="70%">
  <thead style="background-color:#ebebeb;">
    <tr>
      <th style="text-align: center;"><?=_("�û�")?></th>
      <th style="text-align: center;"><?=_("ʱ��")?></th>
      <th style="text-align: center;">IP<?=_("��ַ")?></th>
      <th style="text-align: center;"><?=_("����")?></th>
      <th style="text-align: center;"><?=_("��ע")?></th>
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
