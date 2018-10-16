<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("未签劳动合同");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function CheckForm()
{
	if(document.form1.TRAN_DEPT_AFTER_NAME.value=="")
	{
		alert("<?=_("请选择要查询的部门")?>");
		return (false);
	}
	document.form1.submit();
}

</script>
<body class="bodycolor">
<form method="post" name="form1" action="#">
<table border="0" width="100%" cellpadding="3" cellspacing="1" align="center" >
  <tr>
    <td  class="TableHeader"><span> <?=_("未签劳动合同查询")?></span>&nbsp;
    <?=_("部门")?>
    <input type="hidden" name="TRAN_DEPT_AFTER">
        <input type="text" name="TRAN_DEPT_AFTER_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','TRAN_DEPT_AFTER','TRAN_DEPT_AFTER_NAME')"><?=_("选择")?></a>  
        <input type="hidden" name="inform" value="1"/>
    <input type="button" value="<?=_("查询")?>" class="SmallButton" onClick="CheckForm();">&nbsp;&nbsp;
    </td>
  </tr>
</table>
</form>
<br>
<?
$WHERE_STR="";
if($inform=="1")
{
    $WHERE_STR.="and DEPT_ID='$TRAN_DEPT_AFTER'";
}
$query = "SELECT USER_ID,USER_NAME,DEPT_ID,BIRTHDAY,USER_PRIV,SEX from USER where 1=1 ".$WHERE_STR;
$cursor = exequery(TD::conn(),$query);
$count = 0;
while($ROW = mysql_fetch_array($cursor))
{
   $USER_ID = $ROW['USER_ID'];
   $USER_NAME = $ROW['USER_NAME'];
   $DEPT_ID = $ROW['DEPT_ID'];
   $DEPT_NAME = dept_long_name($DEPT_ID);
   $BIRTHDAY = $ROW['BIRTHDAY'];
   $USER_PRIV = $ROW['USER_PRIV'];
   $SEX = $ROW['SEX']==0 ?_("男"):_("女");   
   $query2 = "SELECT PRIV_NAME FROM USER_PRIV WHERE USER_PRIV ='$USER_PRIV'";
   $cursor2 = exequery(TD::conn(),$query2);
   if($ROW2 = mysql_fetch_array($cursor2))
      $PRIV_NAME = $ROW2['PRIV_NAME'];
   
   $query1 = "SELECT * FROM hr_staff_contract WHERE STAFF_NAME='$USER_ID' and CONTRACT_TYPE !='2' and CONTRACT_TYPE !='3'";
   $cursor1 = exequery(TD::conn(),$query1);
   if(mysql_num_rows($cursor1)!=0)
   {
      continue;
   }
   else
   {
        $count++;
        $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$USER_ID'";
        $cursor2= exequery(TD::conn(),$query2);
        if(mysql_fetch_array($cursor2))
        {
            $query3 = "SELECT DEPT_ID from user where USER_ID='$USER_ID'";
            $cursor3= exequery(TD::conn(),$query3);
            $ROW3=mysql_fetch_array($cursor3);
            if($ROW3["DEPT_ID"]==0)
            {
                $USER_NAME=$USER_NAME."(<font color='red'>"._("用户已离职")."</font>)";
            }                     
        }    
        
          
      $td.= "<tr class='TableData'>
         <td nowrap align='center'>".$USER_NAME."</td><td nowrap align='center'>".$SEX."</td><td nowrap align='left'>".$DEPT_NAME."</td><td nowrap align='center'>".$PRIV_NAME."</td><td nowrap align='center'><a href='new.php?STAFF_NAME=".$USER_ID."&STAFF_NAME1=".$ROW['USER_NAME']."'>"._("新建合同")."</a></td></tr>";
   }   
}
if($count>0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td  class="big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("未签劳动合同")?></span>&nbsp;
    </td>
    </tr>
</table>
<table class="TableList" width="100%">  
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("单位员工")?></td>
      <td nowrap align="center"><?=_("性别")?></td>
      <td nowrap align="center"><?=_("所属部门")?></td>
      <td nowrap align="center"><?=_("主角色")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?=$td?>
</table>
<?
}
else
{
	message("",_("无未签订劳务合同人员"));
}
?>
</body>

</html>
