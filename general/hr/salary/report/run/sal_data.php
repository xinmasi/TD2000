<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query="select * from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$SAL_YEAR=$ROW["SAL_YEAR"];
	$SAL_MONTH=$ROW["SAL_MONTH"];
}
?>
<script language="javascript">
 var FormulData=new Array();
 var TargetData=new Array();
 var flag=0;
function calculate(FORMULA,TARGET,SIGN)
{
	var s1=document.all(FORMULA).value;
   var re;
  //------所得税计算---------
  if(s1.indexOf("<")!=-1 && s1.indexOf(">")!=-1)
  {
   re=/\<|\>/gi;
   var r=s1.replace(re, "");
   re=/\[|\]/gi;
   r=r.replace(re, "");
   re=/\$/gi;
   r=r.replace(re, "S");

  for(var i=document.form1.ITEM_COUNT.value; i>0; i--)
  {
	 re="S"+i;
	 if (document.all(re).value=="" && r.indexOf(re)!=-1)
	 {
	 	alert(document.all(re+"_NAME").value+"<?=_("的值尚未填写或计算！")?>")
	 	r=r.replace(re,"0");
	 }
	else
	 r=r.replace(re,document.all(re).value);

  }
   cha=eval(r);
   if (cha<=0) {document.all(TARGET).value=0;}
   if (cha>0&&cha<=1500) {document.all(TARGET).value=(cha*0.03).toFixed(2);}
   if (cha>1500&&cha<=4500) {document.all(TARGET).value=(cha*0.1-105).toFixed(2);}
   if (cha>4500&&cha<=9000) {document.all(TARGET).value=(cha*0.2-555).toFixed(2);}
   if (cha>9000&&cha<=35000) {document.all(TARGET).value=(cha*0.25-1005).toFixed(2);}
   if (cha>35000&&cha<=55000) {document.all(TARGET).value=(cha*0.3-2755).toFixed(2);}
   if (cha>55000&&cha<=80000) {document.all(TARGET).value=(cha*0.35-5505).toFixed(2);}
   if (cha>80000) {document.all(TARGET).value=(cha*0.45-13505).toFixed(2);}
   return;
  }

	re=/\[|\]/gi;
	var r=s1.replace(re, "");
	re=/\$/gi;
	var r=r.replace(re, "S");
	var array =document.all('array').value;
	arraynum = array.split(',');
  for(var i=0;i<arraynum.length;i++)
 {
	 if (document.all(arraynum[i]).value=="" && r.indexOf(arraynum[i])!=-1)
	 {
	 	 if(SIGN==0)
	 	 alert(document.all(arraynum[i]+"_NAME").value+"<?=_("的值尚未填写或计算！")?>");
	 	r=r.replace(arraynum[i],"0");
	 }
	else
	 r=r.replace(arraynum[i],document.all(arraynum[i]).value);
 }

 document.all(TARGET).value=eval(r).toFixed(2);
}

function clear_all(TARGET)
{
	document.all(TARGET).value="0.00";
	for (var key=0;key<TargetData.length;key++)
  {
  	 if (TARGET!=TargetData[key] && (document.all(TargetData[key]).value>1))
     {
      calculate(FormulData[key],TargetData[key],1);
     }
  }
}

function funcal(FORMULA,TARGET)
{
  calculate(FORMULA,TARGET,0);
  for (var key=0;key<TargetData.length;key++)
  {
  	 if (TARGET!=TargetData[key] && (document.all(TargetData[key]).value>1))
     {
      calculate(FormulData[key],TargetData[key],1);
      }
   }

}

function view(FORMULANAME)
{
	alert(FORMULANAME);
}

function createstr(FORMULA,TARGET)
{
  FormulData[flag]=FORMULA;
  TargetData[flag]=TARGET;
  flag=flag+1;
	//formustr+=FORMULA+",";
	//targetstr+=TARGET+",";
}
</script>

<?
$HTML_PAGE_TITLE = _("工资数据录入");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//参考数据
if($SAL_YEAR=="")
  	$SAL_YEAR="2005";	
if($SAL_MONTH=="") 	
  	$SAL_MONTH="01";
$MONTH_BEGIN=$SAL_YEAR."-".$SAL_MONTH."-"."01";
$MONTH_BEGIN1=strtotime($MONTH_BEGIN." 00:00:00");
$MONTH_END=$SAL_YEAR."-".$SAL_MONTH."-".date("t",mktime(0,0,0,$SAL_MONTH,5,$SAL_YEAR));
$MONTH_END1=strtotime($MONTH_END." 23:59:59");
//----日志----
$query="select count(DIA_ID) from DIARY where DIA_TYPE!='2' and USER_ID='$USER_ID' and DIA_DATE >= '$MONTH_BEGIN 00:00:00' and DIA_DATE <= '$MONTH_END 23:59:59' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DIARY_NUM=$ROW["0"];
if($DIARY_NUM != 0)
   $DIARY_NUM=sprintf(_("日志%s篇"), $DIARY_NUM);
//日程
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM=$ROW["0"];
$query="select count(CAL_ID) from CALENDAR where CAL_TYPE!='2' and USER_ID='$USER_ID' and CAL_TIME >= '$MONTH_BEGIN1' and END_TIME <= '$MONTH_END1' and OVER_STATUS='1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CAL_NUM1=$ROW["0"];
if($CAL_NUM!=0)
   $CAL_NUM=sprintf(_("日程共%s条，完成%s条"), $CAL_NUM, $CAL_NUM1);

//奖惩
$query2="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='INCENTIVE_TYPE' order by CODE_ORDER";
$cursor2= exequery(TD::conn(),$query2);
while($ROW2=mysql_fetch_array($cursor2))
{
     $CODE_NO=$ROW2["CODE_NO"];
     $CODE_NAME=$ROW2["CODE_NAME"];
     
	$query="select INCENTIVE_DESCRIPTION from HR_STAFF_INCENTIVE  where INCENTIVE_TYPE='$CODE_NO' and STAFF_NAME='$USER_ID' and INCENTIVE_TIME>= '$MONTH_BEGIN' and INCENTIVE_TIME <= '$MONTH_END'";
	$cursor= exequery(TD::conn(),$query);
	$INCENTIVE_NUM=0;
	if($ROW=mysql_fetch_array($cursor))
	  	$INCENTIVE_NUM++;
	 if($INCENTIVE_NUM!='0')
	$INCENTIVE_NUM=$CODE_NAME.$INCENTIVE_NUM._("次");
}

//考勤
$DATE1=$SAL_YEAR."-".$SAL_MONTH."-"."01";
$DATE2=$SAL_YEAR."-".$SAL_MONTH."-".date("t",mktime(0,0,0,$SAL_MONTH,5,$SAL_YEAR));
$NOT_REG_COUNT = 0;
$LAST_COUNT = 0;
$EARLY_COUNT= 0;

$query = "SELECT * from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$LINE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
    $query_1="select DUTY_TYPE from USER_EXT where USER_ID='$USER_ID'";
    $cursor_1=exequery(TD::conn(),$query_1);
    if($row1=mysql_fetch_array($cursor_1))
    	$DUTY_TYPE=$ROW["DUTY_TYPE"];
   $DEPT_ID=$ROW["DEPT_ID"];
}

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DAY_TOTAL=$ROW[0]+1;

//---- 取规定上下班时间 -----
$query1 = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
{
   $DUTY_NAME=$ROW["DUTY_NAME"];
   $GENERAL=$ROW["GENERAL"];

   $DUTY_TIME1=$ROW["DUTY_TIME1"];
   $DUTY_TIME2=$ROW["DUTY_TIME2"];
   $DUTY_TIME3=$ROW["DUTY_TIME3"];
   $DUTY_TIME4=$ROW["DUTY_TIME4"];
   $DUTY_TIME5=$ROW["DUTY_TIME5"];
   $DUTY_TIME6=$ROW["DUTY_TIME6"];

   $DUTY_TYPE1=$ROW["DUTY_TYPE1"];
   $DUTY_TYPE2=$ROW["DUTY_TYPE2"];
   $DUTY_TYPE3=$ROW["DUTY_TYPE3"];
   $DUTY_TYPE4=$ROW["DUTY_TYPE4"];
   $DUTY_TYPE5=$ROW["DUTY_TYPE5"];
   $DUTY_TYPE6=$ROW["DUTY_TYPE6"];
}

$COUNT = 0;
if($DUTY_TIME1!="")
{
   $COUNT++;
   if($DUTY_TYPE1=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME2!="")
{
   $COUNT++;
   if($DUTY_TYPE2=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");

}
if($DUTY_TIME3!="")
{
   $COUNT++;
   if($DUTY_TYPE3=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME4!="")
{
   $COUNT++;
   if($DUTY_TYPE4=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME5!="")
{
   $COUNT++;
   if($DUTY_TYPE5=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}
if($DUTY_TIME6!="")
{
   $COUNT++;
   if($DUTY_TYPE6=="1")
      $DUTY_TYPE_DESC=_("上班");
   else
      $DUTY_TYPE_DESC=_("下班");
}

for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
{
   $WEEK=date("w",strtotime($J));
   $HOLIDAY="";
   $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$J' and END_DATE>='$J'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
        $HOLIDAY="<font color='#008000'>"._("节假日")."</font>";
   else
   {
        if(find_id($GENERAL,$WEEK))
           $HOLIDAY="<font color='#008000'>"._("公休日")."</font>";
   }

   if($HOLIDAY=="")
      $TableLine="TableData";
   else
      $TableLine="TableContent";

   //查询考勤记录
   $query1 = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') GROUP by to_days(REGISTER_TIME)";
   $cursor1= exequery(TD::conn(),$query1);
   $LINE_COUNT=0;
   if($ROW=mysql_fetch_array($cursor1))
   {
     $LINE_COUNT++;
     $REGISTER_TIME=$ROW["REGISTER_TIME"];

     $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
     $cursor= exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor))
        $HOLIDAY="<font color='#008000'>"._("出差")."</font>";

    //---- 第1组 -----
   for($I=1;$I<=6;$I++)
    {
       $DUTY_TIME_I="DUTY_TIME".$I;
       $DUTY_TIME_I=$$DUTY_TIME_I;
       $DUTY_TYPE_I="DUTY_TYPE".$I;
       $DUTY_TYPE_I=$$DUTY_TYPE_I;

       if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
          continue;

       $HOLIDAY1="";
       if($HOLIDAY=="")
       {
          $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$J $DUTY_TIME_I' and LEAVE_DATE2>='$J $DUTY_TIME_I'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $HOLIDAY1="<font color='#008000'>"._("请假")."</font>";
       }
       else
          $HOLIDAY1=$HOLIDAY;

       if($HOLIDAY==""&&$HOLIDAY1=="")
       {
           $DUTY_TIME_I = date("H:s:i",strtotime($DUTY_TIME_I));
          $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $HOLIDAY1="<font color='#008000'>"._("外出")."</font>";
       }

       $REGISTER_TIME="";
       $REMARK="";
       $ADD_IP_FLAG=0;
       $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TYPE='$I'";
       $cursor= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor))
       {
          $REGISTER_TIME2=$ROW["REGISTER_TIME"];
          $REGISTER_TIME=$ROW["REGISTER_TIME"];
          $REGISTER_IP=$ROW["REGISTER_IP"];
          $REMARK=$ROW["REMARK"];
          $REMARK=str_replace("\n","<br>",$REMARK);
          $REGISTER_TIME=strtok($REGISTER_TIME," ");
          $REGISTER_TIME=strtok(" ");

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==1)
             {$LAST_COUNT++;}

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
             {$EARLY_COUNT++;}
       }
       else
       {
          if($HOLIDAY1=="")
          {//echo $J; echo "<br>";
             $NOT_REG_COUNT++;
          }
       }
    }
   }
   else //未查到考勤记录
   {
      for($I=1;$I<=$COUNT;$I++)
      {
          $DUTY_TIME_I="DUTY_TIME".$I;
          $DUTY_TIME_I=$$DUTY_TIME_I;
          $DUTY_TYPE_I="DUTY_TYPE".$I;
          $DUTY_TYPE_I=$$DUTY_TYPE_I;
      
        	$OUT = "";
          $query="select USER_ID from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $OUT="<font color='#008000'>"._("出差")."</font>";
      
          $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
          {
             $OUT="<font color=red>"._("未登记")."</font>(<font color='#008000'>"._("外出").")</font>";
             //echo $J; echo "<br>";
             $NOT_REG_COUNT++;
          }
      
          $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$J $DUTY_TIME_I' and LEAVE_DATE2 >= '$J $DUTY_TIME_I'";
          $cursor= exequery(TD::conn(),$query);
          if($ROW=mysql_fetch_array($cursor))
             $OUT="<font color='#008000'>"._("请假")."</font>";
      
      	 if($OUT!="" && $HOLIDAY=="")
      	 {}
      	 else if($HOLIDAY!="")
         {}
         else
         {//echo $J; echo "<br>";
            $NOT_REG_COUNT++;
         }
      }
   }
}//for
if($NOT_REG_COUNT!=0)
$REGISTER = sprintf(_("上下班未登记%s次；"), $NOT_REG_COUNT);
if($LAST_COUNT!=0)
$REGISTER .= sprintf(_("迟到%s次；"), $LAST_COUNT);
if($EARLY_COUNT!=0)
$REGISTER .= sprintf(_("早退%s次；"), $EARLY_COUNT);

$query = "SELECT count(USER_ID) from ATTEND_OUT where USER_ID='$USER_ID' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') and ALLOW='1' order by SUBMIT_TIME";
$cursor= exequery(TD::conn(),$query);
$OUT_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $OUT_COUNT=$ROW[0];

$query = "SELECT count(USER_ID) from ATTEND_LEAVE where USER_ID='$USER_ID' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3')";
$cursor= exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $LEAVE_COUNT =$ROW[0];

$query = "SELECT count(USER_ID) from ATTEND_EVECTION where USER_ID='$USER_ID' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1'";
$cursor= exequery(TD::conn(),$query);
$EVECTION_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $EVECTION_COUNT = $ROW[0];
if($OUT_COUNT!=0)
$REGISTER .= sprintf(_("外出%s次；"), $OUT_COUNT);
if($LEAVE_COUNT!=0)
$REGISTER .= sprintf(_("请假%s次；"), $LEAVE_COUNT);
if($EVECTION_COUNT!=0)
$REGISTER .= sprintf(_("出差%s次；"), $EVECTION_COUNT);
//---------------------
$query = "SELECT * from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_NAME=$ROW["USER_NAME"];
}

$query="select * from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$SAL_YEAR=$ROW["SAL_YEAR"];
	$SAL_MONTH=$ROW["SAL_MONTH"];
}

//--------加班----------
$query = "SELECT count(USER_ID) from ATTENDANCE_OVERTIME where USER_ID='$USER_ID' and START_TIME>='$DATE1' and END_TIME<='$DATE2' and (ALLOW='1' or ALLOW='3')order by START_TIME";
$cursor= exequery(TD::conn(),$query);
$OVERTIME_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $OVERTIME_COUNT = $ROW[0];
if($OVERTIME_COUNT!=0)
$REGISTER.=sprintf(_("加班%s次"), $OVERTIME_COUNT);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=sprintf(_("%s绩效上报（%s年%s月）"), $USER_NAME, $SAL_YEAR, $SAL_MONTH)?></span>
    </td>
  </tr>
</table>

<div align="center">

<form name=form1 method="post" action="submit.php">

<?
 //-- 首先查询是否已录入过数据 --
 if($RECALL=="")
 {
	 $FLOW_ID = intval($FLOW_ID);
   $query="select FLOW_ID from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $OPERATION=2; //-- 将执行数据更新 --
   else 	
 	    $OPERATION=1; //-- 将执行数据插入 --
   
   $FLOW_ID = intval($FLOW_ID);
   $query="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID' and IS_DEPT_INPUT='1'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     for($I=1;$I<=50;$I++)
     {
       $STR="S".$I;
       $$STR=format_money($ROW["$STR"]);
     }
	   $MEMO=$ROW["MEMO"];
     
   }
   else
   {
     $query="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
     $cursor= exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor))
     {
        for($I=1;$I<=50;$I++)
        {
          $STR="S".$I;
          $$STR=format_money($ROW["$STR"]);
        }
     }
     
   }
 }

 //-- 生成录入项目 --
 $query="select * from SAL_ITEM where 1=1  order by ITEM_ID";
 //$query="select * from SAL_ITEM where ISREPORT='1' OR ITEM_NAME='计算项' order by ITEM_ID";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_COUNT++;

    $ITEM_ID=$ROW["ITEM_ID"];
    $ISREPORT=$ROW["ISREPORT"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $ISCOMPUTER=$ROW["ISCOMPUTER"];
    $FORMULA=$ROW["FORMULA"];
    $FORMULANAME=$ROW["FORMULANAME"];
    $FORMULAFLAG=$ITEM_ID."FORNULA";
    $FORMULAFLAGNAME=$ITEM_ID."FORNULANAME";
    $S_ID="S".$ITEM_ID;
    $array .= $S_ID.",";
    $S_NAME=$S_ID."_NAME";
    $CHECK_NAME="C".$S_ID;
    if($ITEM_COUNT==1)
    {
?>

    <table width="450" class="TableBlock">

<?
    }
?>
    <tr class="TableData" <? if($ISREPORT =='1' || $ITEM_NAME =='计算项'){echo "style='display:inline_block'"; }else{echo "style='display:none'";}?>>
      <td nowrap align="center" width="110"><?=$ITEM_NAME?></td>
      <td nowrap>

        <input type="hidden" name="<?=$S_NAME?>" value="<?=$ITEM_NAME?>">
<?
          if($ISCOMPUTER=="1")
          {

?>
        <input type="hidden" name="<?=$FORMULAFLAG?>" value="<?=$FORMULA?>">
        <input type="hidden" name="<?=$FORMULAFLAGNAME?>" value="<?=$FORMULANAME?>">
        <script language="javascript">createstr('<?=$FORMULAFLAG?>','<?=$S_ID?>')</script>
<?
          }
?>
         <input type="text" name="<?=$S_ID?>"  size="17" maxlength="14" <? if($ISCOMPUTER=="1"){echo "readonly"; echo "  class=BigStatic";} else {echo "  class=BigInputMoney";}?>  value="<?=$$S_ID?>">
<?
          if($ISCOMPUTER=="1")
          {
?>
          <input type="button" value="<?=_("计算")?>" class="SmallButton" onClick="funcal('<?=$FORMULAFLAG?>','<?=$S_ID?>');" title="<?=_("计算")?>" name="button">
          <input type="button" value="<?=_("清空")?>" class="SmallButton" onClick="clear_all('<?=$S_ID?>');" title="<?=_("清空")?>" name="button">
          <a href="javascript:view('<?=$FORMULANAME?>');"><?=_("计算公式")?></a>

<?
         }

?>
      </td>
    </tr>
    
<?
 }
 if($ITEM_COUNT>0)
 {
?>
    <tr class="TableData">
      <td nowrap align="center" width="110"><?=_("备注")?></td>
      <td nowrap align="center">
      <textarea name="MEMO" cols="45" rows="5"><? echo $MEMO; ?></textarea>
      </td>
    </tr>
    <tr class="TableData"> 
    	<td nowrap align="center" width="110"><?=_("员工表现")?></td>   
      <td><? if($DIARY_NUM!="0")echo $DIARY_NUM._("；"); ?><? if($CAL_NUM!="0") echo $CAL_NUM._("；"); ?><? if($INCENTIVE_NUM!="0") echo $INCENTIVE_NUM._("；"); ?><?=$REGISTER?></td>
    </tr>
    <tr class="TableData"> 
    	<td nowrap align="center" width="110"><?=_("相关操作")?></td> 	    
      <td colspan="2">
      <a href="javascript:;" onClick="window.open('staff_detail.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=270,resizable=yes');"><?=_("查看详情")?></a>
    </td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="2">
        <input type="hidden" value="<?=$OPERATION?>" name="OPERATION">
        <input type="hidden" value="<?=$USER_ID?>" name="USER_ID">
        <input type="hidden" value="<?=$USER_NAME?>" name="USER_NAME">
        <input type="hidden" value="<?=$UID?>" name="UID">
        <input type="hidden" value="<?=$DEPT_ID?>" name="DEPT_ID">
        <input type="hidden" value="<?=$FLOW_ID?>" name="FLOW_ID">
        <input type="hidden" value="<?=$ITEM_COUNT?>" name="ITEM_COUNT">
        <input type="hidden" value="<?=rtrim($array,',')?>" name="array">
        <input type="submit" value="<?=_("上报")?>" class="BigButton">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("取消")?>" class="BigButton" onClick="location='blank.php'">
      </td>
    </tfoot>

    <thead class="TableHeader">
      <td nowrap align="center"><?=_("工资项目")?></td>
      <td nowrap align="center"><?=_("金额")?></td>
    </thead>
    </table>
<?
 }
 else
    message("",_("尚未定义工资项目，请与财务主管联系！"));
?>

</form>
</div>

</body>
</html>