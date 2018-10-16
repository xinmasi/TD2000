<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("公告通知审批");
include_once("inc/header.inc.php");
?>


<script language="javascript">
function checkform()
{
   if(document.form1.REASON.value=="")
   { 
     alert("<?=_("审批意见不能为空！")?>");
     return (false);
   }
   document.form1.OP.value="0";
   document.form1.submit();
   document.getElementById("buz").disabled = "disabled";
   document.form1.action = "";
}
</script>

<body class="bodycolor">
<?
$POSTFIX = _("，");
$query="select * from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $NOTIFY_ID=$ROW["NOTIFY_ID"];
    $FROM_ID=$ROW["FROM_ID"];
    $TO_ID=$ROW["TO_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $FORMAT=$ROW["FORMAT"];
    $TOP=$ROW["TOP"];
    $PRIV_ID=$ROW["PRIV_ID"];
    $USER_ID=$ROW["USER_ID"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $PUBLISH=$ROW["PUBLISH"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
  
    $SUBJECT_TITLE="";
    if(strlen($SUBJECT) > 60)
    {
       $SUBJECT_TITLE=$SUBJECT;
       $SUBJECT=csubstr($SUBJECT, 0, 60)."...";
    }
    $SUBJECT=td_htmlspecialchars($SUBJECT);
    $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);
    
    if($TOP=="1")
       $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
    else
       $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
    $SEND_TIME=$ROW["SEND_TIME"];
    $BEGIN_DATE=date("Y-m-d",$ROW["BEGIN_DATE"]);
    $END_DATE=$ROW["END_DATE"];

    //$BEGIN_DATE=strtok($BEGIN_DATE," ");
    //$END_DATE=strtok($END_DATE," ");

    if($END_DATE==0)
       $END_DATE="";
    else 
       $END_DATE=date("Y-m-d",$END_DATE);
    $FROM_UID=UserId2Uid($FROM_ID);
   if($FROM_UID!="")
   {
      $ROW=GetUserInfoByUID($FROM_UID,"USER_NAME,DEPT_ID");
      $FROM_NAME=$ROW["USER_NAME"];
      $DEPT_ID=$ROW["DEPT_ID"];
   }
    $DEPT_NAME=dept_long_name($DEPT_ID);
    $TYPE_NAME=get_code_name($TYPE_ID,"NOTIFY");

    $TO_NAME="";
    if($TO_ID=="ALL_DEPT")
       $TO_NAME=_("全体部门");
    else
       $TO_NAME=GetDeptNameById($TO_ID);   

    $PRIV_NAME=GetPrivNameById($PRIV_ID);

    $USER_NAME="";
    if($USER_ID!="")
      $USER_UID=UserId2Uid($USER_ID);
    if($USER_UID!="")
    {
   	  $USER_NAME=GetUserInfoByUID($USER_UID,"USER_NAME");
    }
    $TO_NAME_TITLE="";
    $TO_NAME_STR="";

    if($TO_NAME!="")
    {
       if(substr($TO_NAME,-strlen($POSTFIX))==$POSTFIX)
          $TO_NAME=substr($TO_NAME,0,-strlen($POSTFIX));
       $TO_NAME_TITLE.=_("部门：").$TO_NAME;
       $TO_NAME_STR.="<font color=#0000FF><b>"._("部门：")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
    }

    if($PRIV_NAME!="")
    {
       if(substr($PRIV_NAME,-strlen($POSTFIX))==$POSTFIX)
          $PRIV_NAME=substr($PRIV_NAME,0,-strlen($POSTFIX));
       if($TO_NAME_TITLE!="")
          $TO_NAME_TITLE.="\n\n";
       $TO_NAME_TITLE.=_("角色：").$PRIV_NAME;
       $TO_NAME_STR.="<font color=#0000FF><b>"._("角色：")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
    }

    if($USER_NAME!="")
    {
       if(substr($USER_NAME,-1)==",")
          $USER_NAME=substr($USER_NAME,0,-1);
       if($TO_NAME_TITLE!="")
          $TO_NAME_TITLE.="\n\n";
       $TO_NAME_TITLE.=_("人员：").$USER_NAME;
       $TO_NAME_STR.="<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,30).(strlen($USER_NAME)>30?"...":"")."<br>";
    }

    if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
    {
       $NOTIFY_STATUS=1;
       $NOTIFY_STATUS_STR=_("待生效");
    }
    else
    {
       $NOTIFY_STATUS=2;
	   if ($PUBLISH!="2")
          $NOTIFY_STATUS_STR="<font color='#00AA00'><b>"._("生效")."</font>";
	   else
	      $NOTIFY_STATUS_STR="<font color='#00AA00'><b>"._("待审批")."</font>";
    }


    if($END_DATE!="" || $PUBLISH=="0")
    {
      if(compare_date($CUR_DATE,$END_DATE)>0)
      {
         $NOTIFY_STATUS=3;
         $NOTIFY_STATUS_STR="<font color='#FF0000'><b>"._("终止")."</font>";
      }
    }

    if($PUBLISH=="0")
       $NOTIFY_STATUS_STR="";
    
}

?>
<form enctype="multipart/form-data" action="operation.php"  method="post" name="form1">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("公告通知审批")?></span>
    </td>
  </tr>
</table>
<table class="TableBlock" width="550" align="center">
<tr>
<td nowrap class="TableData"><?=_("标题：")?></td>
<td class="TableData" title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("发布人：")?></td>
<td class="TableData"><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u></td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("发布范围：")?></td>
<td style="cursor:hand" title="<?=$TO_NAME_TITLE?>" class="TableData"><?=$TO_NAME_STR?></td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("创建时间：")?></td>
<td class="TableData"><?=$SEND_TIME?></td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("生效日期：")?></td>
<td class="TableData"><?=$BEGIN_DATE?></td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("终止日期：")?></td>
<td class="TableData"><?=$END_DATE?></td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("审批意见：")?></td>
<td class="TableData"><textarea cols="50" name="REASON" rows="2" class="BigInput" wrap="no"></textarea></td>
</tr>
<tr align="center" class="TableControl">
<td colspan="2" nowrap>

<input type="button" value="<?=_("不批准")?>" id="buz" class="BigButton" onClick="checkform()">&nbsp;
<?
if($FROM==1)
{
?>
<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
<?
}
else
{
?>
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="javascript:window.history.go(-1);">
<?
}
?>
</td>
</tr>
</table>
<input type="hidden" name="OP" value="">
<input type="hidden" name="FROM" value=<?=$FROM?>>
<input type="hidden" name="NOTIFY_ID" value="<?=$NOTIFY_ID?>">
</form>
</body>
</html>