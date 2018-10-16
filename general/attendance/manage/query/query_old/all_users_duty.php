<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("上下班记录查询");
include_once("inc/header.inc.php");
?>


<script language="JavaScript">
function remark(USER_ID,REGISTER_TYPE,REGISTER_TIME)
{
  URL="../user_manage/remark.php?USER_ID="+USER_ID+"&REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>


<body class="bodycolor">
<?
  //----------- 合法性校验 ---------
  if($DATE1!="")
  {
    $TIME_OK=is_date($DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($DATE2!="")
  {
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if(compare_date($DATE1,$DATE2)==1)
  { Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
  }

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;
$MSG = sprintf(_("共%d天"), $DAY_TOTAL);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    <span class="big3"> <?=_("上下班查询结果")?> - [<?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>]</span><br>
    </td>
  </tr>
</table>

<br>

<?
 $query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $NO_DUTY_USER=$ROW["PARA_VALUE"];

 $WHERE_STR=" where 1=1";

 if($DEPARTMENT1!="ALL_DEPT")
 {
	  $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
    $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
 }

 if($DUTY_TYPE1!="ALL_TYPE")
    $WHERE_STR.=" and USER_EXT.DUTY_TYPE='$DUTY_TYPE1' ";

 $query4 = "SELECT USER_EXT.DUTY_TYPE,USER.USER_NAME,USER.USER_ID,DEPARTMENT.DEPT_NAME from USER,USER_PRIV,DEPARTMENT,USER_EXT ".$WHERE_STR." and USER.UID=USER_EXT.UID and (USER.NOT_LOGIN = 0 or USER.NOT_MOBILE_LOGIN = 0) and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";
 $cursor4= exequery(TD::conn(),$query4);
 $USER_COUNT=0;
 while($ROW4=mysql_fetch_array($cursor4))
 {
    $USER_COUNT++;
    $DUTY_TYPE=$ROW4["DUTY_TYPE"];
    $USER_NAME=$ROW4["USER_NAME"];
    $DEPT_NAME=$ROW4["DEPT_NAME"];
    $USER_ID=$ROW4["USER_ID"];

 //---- 取规定上下班时间 -----
 $query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
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

 if($USER_COUNT==1)
 {
?>

<table class="TableList"  width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("日期")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("部门")?></td>
<?
 $COUNT = 0;
 if($DUTY_TIME1!="")
 {
    $COUNT++;
    if($DUTY_TYPE1=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME1?>)</td>
<?
 }
 if($DUTY_TIME2!="")
 {
    $COUNT++;
    if($DUTY_TYPE2=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME2?>)</td>
<?
 }
 if($DUTY_TIME3!="")
 {
    $COUNT++;
    if($DUTY_TYPE3=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME3?>)</td>
<?
 }
 if($DUTY_TIME4!="")
 {
    $COUNT++;
    if($DUTY_TYPE4=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME4?>)</td>
<?
 }
 if($DUTY_TIME5!="")
 {
    $COUNT++;
    if($DUTY_TYPE5=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME5?>)</td>
<?
 }
 if($DUTY_TIME6!="")
 {
    $COUNT++;
    if($DUTY_TYPE6=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?>(<?=$DUTY_TIME6?>)</td>
<?
 }
?>
  </tr>
<?
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

   //-- 开始查询 --
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
?>
       <tr class="<?=$TableLine?>">
         <td nowrap align="center"><?=$J?>(<?=get_week($J)?>)</td>
         <td nowrap align="center"><?=$USER_NAME?></td>
         <td nowrap align="center"><?=$DEPT_NAME?></td>
<?
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
          {
    	       $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    	       $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");          
             $HOLIDAY1="<font color='#008000'>"._("请假-")."$LEAVE_TYPE2</font>";
          }
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
       $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TYPE='$I'";
       $cursor= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor))
       {
          $REGISTER_TIME2=$ROW["REGISTER_TIME"];
          $REGISTER_TIME=$ROW["REGISTER_TIME"];
          $REMARK=$ROW["REMARK"];
          $REMARK=str_replace("\n","<br>",$REMARK);
          $REGISTER_TIME=strtok($REGISTER_TIME," ");
          $REGISTER_TIME=strtok(" ");

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==1)
             {$REGISTER_TIME.=" <font color=red><b>"._("迟到")."</b></font>";}

          if($HOLIDAY1==""&&$DUTY_TYPE_I=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
             {$REGISTER_TIME.=" <font color=red><b>"._("早退")."</b></font>";}

          if($REMARK!="")
             $REMARK="<br>"._("说明：").$REMARK;
       }
       else
       {
          if($HOLIDAY1=="")
             $REGISTER_TIME="<font color=red>"._("未登记")."</font>";
          else
             $REGISTER_TIME=$HOLIDAY1;
       }

?>
         <td nowrap align="center"><?=$REGISTER_TIME?><?=$REMARK?>&nbsp;
<?
   if($REMARK!="")
      echo "<a href=\"javascript:remark('$USER_ID','$I','$REGISTER_TIME2');\" title=\""._("修改说明")."\">"._("修改")."</a>";
?>
         </td>
<?
    }
?>
       </tr>
<?
   }
   else
   {
?>
       <tr class="<?=$TableLine?>">
         <td nowrap align="center"><?=$J?>(<?=get_week($J)?>)</td>
         <td nowrap align="center"><?=$USER_NAME?></td>
         <td nowrap align="center"><?=$DEPT_NAME?></td>
<?
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
       $OUT="<font color=red>"._("未登记")."</font><font color='#008000'>"._("外出")."</font>)";

    $query="select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$J $DUTY_TIME_I' and LEAVE_DATE2 >= '$J $DUTY_TIME_I'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
    	 $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    	 $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
       $OUT="<font color='#008000'>"._("请假-")."$LEAVE_TYPE2</font>";
    }

	 if($OUT!="" && $HOLIDAY=="")
	    echo "<td nowrap align=center>$OUT</td>";
	 else if($HOLIDAY!="")
      echo "<td nowrap align=center>$HOLIDAY</td>";
   else
      echo "<td nowrap align=center><font color=red>"._("未登记")."</font></td>";
}
?>
       </tr>
<?
   }
}//for
}
?>
</table>
<?
Button_Back();
?>

</body>
</html>