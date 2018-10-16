<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("外出人员");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'hr';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$CUR_TIME=date("Y-m-d H:i:s",time());

$COUNT=0;
$query = "SELECT USER_NAME,EVECTION_DEST,EVECTION_DATE1,EVECTION_DATE2,REASON from USER,USER_PRIV,ATTEND_EVECTION where USER.USER_ID=ATTEND_EVECTION.USER_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV and STATUS='1' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$CUR_TIME') and to_days(EVECTION_DATE2)>=to_days('$CUR_TIME') order by USER_PRIV.PRIV_NO,USER.USER_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $COUNT++;
   $USER_NAME=$ROW1["USER_NAME"];
   $EVECTION_DEST=$ROW1["EVECTION_DEST"];
   $REASON=$ROW1["REASON"];   
   $EVECTION_DATE1=substr($ROW1["EVECTION_DATE1"],0,10);
   $EVECTION_DATE2=substr($ROW1["EVECTION_DATE2"],0,10);

   if($COUNT==1)
      $MODULE_BODY.='<fieldset class="small"><legend align=left><b>'._("出差人员").'</b></legend>';

   $MODULE_BODY.='<u title="'.sprintf(_("%s 至 %s"),$EVECTION_DATE1, $EVECTION_DATE2).' '._("地点：").$EVECTION_DEST._("事由：").$REASON.'" style="cursor:hand">'.$USER_NAME.'</u>&nbsp;';
}

if($COUNT>0)
   $MODULE_BODY.= "</fieldset>";

$COUNT1=0;
$query = "SELECT USER_NAME,LEAVE_DATE1,LEAVE_DATE2,LEAVE_TYPE from USER,USER_PRIV,ATTEND_LEAVE where USER.USER_ID=ATTEND_LEAVE.USER_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV and STATUS='1' and ALLOW='1' and LEAVE_DATE1<='$CUR_TIME' and LEAVE_DATE2>='$CUR_TIME' order by USER.DEPT_ID,USER_PRIV.PRIV_NO,USER.USER_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $COUNT1++;
   $USER_NAME=$ROW1["USER_NAME"];
   $LEAVE_TYPE=$ROW1["LEAVE_TYPE"];
   $LEAVE_DATE1=$ROW1["LEAVE_DATE1"];
   $LEAVE_DATE2=$ROW1["LEAVE_DATE2"];
   if($COUNT1==1)
      $MODULE_BODY.='<fieldset class="small"><legend align=left><b>'._("请假人员").'</b></legend>';

   $MODULE_BODY.='<u title="'.sprintf(_("%s 至 %s"),$LEAVE_DATE1,$LEAVE_DATE2)._("原因：").$LEAVE_TYPE.'" style="cursor:hand">'.$USER_NAME.'</u>&nbsp;';
}

if($COUNT1>0)
   $MODULE_BODY.= "</fieldset>";

$COUNT2=0;
$query = "SELECT OUT_TYPE,USER_NAME,OUT_TIME1,OUT_TIME2 from USER,USER_PRIV,ATTEND_OUT where USER.USER_ID=ATTEND_OUT.USER_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV and STATUS='0' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$CUR_TIME') and OUT_TIME1<='".date("H:i",time())."' and OUT_TIME2>='".date("H:i",time())."' order by USER_PRIV.PRIV_NO,USER.USER_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $COUNT2++;
   $USER_NAME=$ROW1["USER_NAME"];
   $OUT_TYPE=$ROW1["OUT_TYPE"];    
   $OUT_TIME1=$ROW1["OUT_TIME1"];
   $OUT_TIME2=$ROW1["OUT_TIME2"];
   $SUBMIT_TIME=substr($ROW1["SUBMIT_TIME"],0,-3);
   if($COUNT2==1)
      $MODULE_BODY.='<fieldset class="small"><legend align=left><b>'._("外出人员").'</b></legend>';
   $MODULE_BODY.='<u title="'.sprintf(_("%s 至 %s"),$OUT_TIME1,$OUT_TIME2).' '._("原因：").$OUT_TYPE.'" style="cursor:hand">'.$USER_NAME.'</u>&nbsp;';
}

if($COUNT2>0)
   $MODULE_BODY.= "</fieldset>";

if($COUNT==0&&$COUNT1==0&&$COUNT2==0)
   $MODULE_BODY.= "<li>"._("无外出人员")."</li>";
}
?>
