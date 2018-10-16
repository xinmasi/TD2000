<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$a_para_array = get_sys_para("SMS_REMIND");
$s_sms_remind_str = $a_para_array["SMS_REMIND"];
$remind_array = explode("|", $s_sms_remind_str);
$sms_remind = $remind_array[0];
$sms2_remind = $remind_array[1];
$sms3_remind = $remind_array[2];
$sms4_remind = $remind_array[3];
$sms5_remind = $remind_array[4];

$HTML_PAGE_TITLE = _("公告通知审批");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   var now_date=document.form1.CUR_DATE.value.split("-");
   var end_date = document.form1.END_DATE.value.split("-"); 
   var begin_date = document.form1.BEGIN_DATE.value.split("-"); 
   var v_now = new Date(now_date[0],now_date[1],now_date[2]); 
   var v_begin = new Date(begin_date[0],begin_date[1],begin_date[2]); 
   var v_end=new Date(end_date[0],end_date[1],end_date[2]); 
   /*if(v_begin!="" && v_now >v_begin)
   { 
      alert("生效日期应该大于等于当前日期！"); 
      document.form1.BEGIN_DATE.focus();
      return (false);    
   } */
   if(v_end!="" && v_end <=v_begin)
   { 
      alert("<?=_("终止日期不能早于生效日期！")?>"); 
      document.form1.END_DATE.focus();
      return (false);    
   }
   if(document.getElementById("TOP").checked!=false)
   {
	   if(document.form1.TOP_DAYS.value!=""&&(document.form1.TOP_DAYS.value <0 || document.form1.TOP_DAYS.value!=parseInt(document.form1.TOP_DAYS.value)))
	   {
	       alert("<?=_("最大置顶时间应为正整数！")?>");
		   return (false);
	   }
	   if(document.form1.TOP_FLAG.value!=0 && document.form1.TOP_DAYS.value!="" && parseInt(document.form1.TOP_DAYS.value) <=0)
	   {
	       alert("<?=_("最大置顶时间应为大于0的正整数！")?>");
		   return (false);
	   }
	   if(document.form1.TOP_FLAG.value!=0 && document.form1.TOP_DAYS.value!="" && parseInt(document.form1.TOP_DAYS.value) > parseInt(document.form1.TOP_FLAG.value))
	   {
	       alert("<?=_("最大置顶时间不能大于系统设置中的最大置顶时间！")?>");
		   return (false);
	   }
   }
   document.form1.submit();
   document.getElementById("pinz").disabled = "disabled";
   document.form1.action = "";
}
function resetTime()
{
   document.form1.SEND_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}

function resetDate()
{
   document.form1.BEGIN_DATE.value="<?=date("Y-m-d",time())?>";
}
</script>

<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());
$PARA_ARRAY=get_sys_para("NOTIFY_TOP_DAYS");
$TOP_DAYS=$PARA_ARRAY["NOTIFY_TOP_DAYS"]; //最大置顶日期
if($TOP_DAYS=="")//最大指定日期没有限制的时候
{
    $TOP_FLAG=0;
    $TOP_DAYS_REMIND=_("0表示一直置顶");
} 
else
{
   	$TOP_FLAG=$TOP_DAYS;
    $TOP_DAYS_REMIND=_("最大置顶时间为").$TOP_DAYS._("天");
}
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
    $TOP_DAYS=$ROW["TOP_DAYS"];
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

    $SEND_TIME=$ROW["SEND_TIME"];
    $BEGIN_DATE=date("Y-m-d",$ROW["BEGIN_DATE"]);
    $END_DATE=$ROW["END_DATE"];

    //$BEGIN_DATE=strtok($BEGIN_DATE," ");
    //$END_DATE=strtok($END_DATE," ");

    if($END_DATE==0)
       $END_DATE="";
    else 
       $END_DATE=date("Y-m-d",$END_DATE);
    $query1="select USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
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
    $TOK=strtok($USER_ID,",");
    while($TOK!="")
    {
       $query1 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW1=mysql_fetch_array($cursor1))
          $USER_NAME.=$ROW1["USER_NAME"].",";
       $TOK=strtok(",");
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

    if($TOP=="1")
       $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
    else
       $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
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
<td nowrap class="TableData" width=20%><?=_("标题：")?></td>
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
<td nowrap class="TableData"><?=_("发布时间：")?></td>
<td class="TableData">
   <input type="text" name="SEND_TIME" size="20" maxlength="20" class="BigInput" value="<?=$SEND_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
   
   &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("设置为当前时间")?></a>
</td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("生效日期：")?></td>
<td class="TableData">
   <input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
  
   &nbsp;&nbsp;<a href="javascript:resetDate();"><?=_("设置为当前日期")?></a>
</td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("终止日期：")?></td>
<td class="TableData">
   <input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()">
  
   <?=_("为空为手动终止")?>
   <input type="hidden" name="CUR_DATE" value="<?=$CUR_DATE?>">
</td>
</tr>
<tr>
<td nowrap class="TableData" valign="top"><?=_("置顶：")?></td>
<td class="TableData"><input type="checkbox" name="TOP" id="TOP"<?if($TOP=="1") echo " checked";?>><label for="TOP"><?=_("使公告通知置顶，显示为重要")?></label><br>
<input type="text" name="TOP_DAYS" size="3" maxlength="4" class="BigInput" value="<?=$TOP_DAYS?>">&nbsp;<?=$TOP_DAYS_REMIND?>
<input type="hidden" name="TOP_FLAG" value="<?=$TOP_FLAG?>">
</td>
</tr>
<tr>
<td nowrap class="TableData"><?=_("事务提醒：")?></td>
<td class="TableData">
<?
sms_remind(1);

if(find_id($sms4_remind, '1'))
{
    echo "<label class='sms-remind-label'><input type=\"checkbox\" name=\"SNS_REMIND\" id=\"SNS_REMIND\"";
    if(find_id($sms5_remind, '1'))
        echo " checked";
    echo ">"._("分享到企业社区")."</label>";
}
?>
</td>
</tr>
<tr align="center" class="TableControl">
<td colspan="2" nowrap>
<input type="hidden" name="OP" value="1">
<input type="hidden" name="FROM" value=<?=$FROM?>>
<input type="hidden" name="NOTIFY_ID" value="<?=$NOTIFY_ID?>">
<input type="button" value="<?=_("批准")?>" id="pinz" class="BigButton" onClick="CheckForm();">&nbsp;
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
</form>
</body>
</html>