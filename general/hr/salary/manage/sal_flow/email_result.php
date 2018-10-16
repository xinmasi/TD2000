<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$BAOXIAN_XIANG_ARRAY = array(
    "ALL_BASE"=>_("保险基数"),
    "PENSION_BASE"=>_("养老保险"),
    "PENSION_U"=>_("单位养老"),
    "PENSION_P"=>_("个人养老"),
    "MEDICAL_BASE"=>_("医疗保险"),
    "MEDICAL_U"=>_("单位医疗"),
    "MEDICAL_P"=>_("个人医疗"),
    "FERTILITY_BASE"=>_("生育保险"),
    "FERTILITY_U"=>_("单位生育"),
    "UNEMPLOYMENT_BASE"=>_("失业保险"),
    "UNEMPLOYMENT_U"=>_("单位失业"),
    "UNEMPLOYMENT_P"=>_("个人失业"),
    "INJURIES_BASE"=>_("工伤保险"),
    "INJURIES_U"=>_("单位工伤"),
    "HOUSING_BASE"=>_("住房公积金"),
    "HOUSING_U"=>_("单位住房"),
    "HOUSING_P"=>_("个人住房"),
    "INSURANCE_DATE"=>_("投保日期")
);
if($fld_str=="")
{
    $query = "SELECT ITEM_ID from SAL_ITEM";
    $cursor= exequery(TD::conn(),$query);
    $FLOW_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $STYLE.="S".$ROW["ITEM_ID"].",";
    }
    $STYLE.="MEMO";
}
else
{
    $STYLE=substr($fld_str,0,-1);
}

$ITEM_ID_ARRAY = explode(",",$STYLE);
$TMP_STR="";
foreach($ITEM_ID_ARRAY as $tmp_value)
{
    if(substr($tmp_value,0,1)=="S")
        $TMP_STR.=substr($tmp_value,1).",";
    else
        $TMP_STR.=$tmp_value.",";
}

$query = "update SAL_FLOW set STYLE='$TMP_STR' where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);

$HTML_PAGE_TITLE = _("发送EMAIL工资条");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$SEND_TIME=time();
$NODATA="";
$COUNT=0;
$FLOW_ID = intval($FLOW_ID);
$query="select CONTENT from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT=$ROW["CONTENT"];
}
if($COPY_TO_ID!="")
{
    $COPY_TO_ID="'".str_replace(",","','",substr($COPY_TO_ID,0,-1))."'";
    $WHERE_STR.=" where USER_ID in ($COPY_TO_ID)";
}
$query1 = "SELECT USER_ID,USER_NAME from USER ".$WHERE_STR;
$cursor1= exequery(TD::conn(),$query1);
while($ROW=mysql_fetch_array($cursor1))
{
    $USERID=$ROW["USER_ID"];
    $USERNAME=$ROW["USER_NAME"];
    $FLOW_ID = intval($FLOW_ID);
    $query="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USERID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $STYLE_ARRAY=explode(",",$STYLE);
        $ARRAY_COUNT=sizeof($STYLE_ARRAY);
        $COUNT++;
        if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
        for($I=0;$I<$ARRAY_COUNT;$I++)
        {
            $STR=$STYLE_ARRAY[$I];
            if($STYLE_ARRAY[$I]!="MEMO")
                $$STR=format_money($ROW[$STR]);
            else
                $MEMO=$ROW["MEMO"];
            //echo $$STR;
        }
        $OPERATION=2;
        //echo $USERNAME._("工资条已发送<br>\n");
    }
    else
    {
        $NODATA.=$USERNAME.",";
        continue;
    }


    $STYLE_ARRAY=explode(",",$STYLE);
    $ARRAY_COUNT=sizeof($STYLE_ARRAY);

    if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;

    $ITEM_COUNT=0;
    $TR="<br><div align=center>\n<table border=0 cellspacing=1 class=TableBlock cellpadding=3>\n";
    $TR.="<tr class=TableContent><td nowrap align=center width=120><b>"._("工资项目")."</b></td><td nowrap align=center width=80><b>"._("金额")."</b></td></tr>\n";

    for($I=0; $I < $ARRAY_COUNT; $I++)
    {
        if($STYLE_ARRAY[$I]!="MEMO" && $STYLE_ARRAY[$I]!="PENSION_U" && $STYLE_ARRAY[$I]!="PENSION_P" && $STYLE_ARRAY[$I]!="MEDICAL_U" && $STYLE_ARRAY[$I]!="MEDICAL_P" && $STYLE_ARRAY[$I]!="FERTILITY_U" && $STYLE_ARRAY[$I]!="UNEMPLOYMENT_U" && $STYLE_ARRAY[$I]!="UNEMPLOYMENT_P" && $STYLE_ARRAY[$I]!="INJURIES_U" && $STYLE_ARRAY[$I]!="HOUSING_U" && $STYLE_ARRAY[$I]!="HOUSING_P")
        {
            $query="select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='".substr($STYLE_ARRAY[$I],1)."'";
            //echo $query."<br>\n";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $ITEM_COUNT++;
                $ITEM_ID=$ROW["ITEM_ID"];
                $ITEM_NAME=$ROW["ITEM_NAME"];
                $S_ID=$STYLE_ARRAY[$I];
                if($ITEM_COUNT==1)
                    $CONTENT="";

                if($ZERO=="on"&&$$S_ID==0) continue;

                $TR.="<tr class=TableData>";
                $TR.="<td nowrap align=center>".$ITEM_NAME."</td>";
                $TR.="<td nowrap align=center>".$$S_ID."</td>";
                $TR.="</tr>\n";
            }
        }//工资项目是备注字段
        else if($STYLE_ARRAY[$I]=="MEMO")
        {
            $TR.="<tr class=TableData>";
            $TR.="<td nowrap align=center>"._("备注")."</td>";
            $TR.="<td nowrap align=center>".$MEMO."</td>";
            $TR.="</tr>\n";
        }
        else
        {
            $TEM=$STYLE_ARRAY[$I];
            if(isset($BAOXIAN_XIANG_ARRAY[$TEM]))
            {
                if($ZERO=="on"&&$$BAOXIAN_XIANG_ARRAY[$TEM]==0) continue;
                $TR.="<tr class=TableData>";
                $TR.="<td nowrap align=center>".$BAOXIAN_XIANG_ARRAY[$TEM]."</td>";
                $TR.="<td nowrap align=center>".$$TEM."</td>";
                $TR.="</tr>\n";
            }
        }//end if
    }//end for

    if($ITEM_COUNT>0)
        $CONTENT.= $TR."</table></div>";
//ECHO $CONTENT;EXIT;
    $query="INSERT
         INTO EMAIL_BODY (FROM_ID,TO_ID2,COPY_TO_ID,SUBJECT,CONTENT,SEND_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,SEND_FLAG,IMPORTANT)
         SELECT '".$_SESSION["LOGIN_USER_ID"]."' as FROM_ID,'$USERID,' as TO_ID2,'' as COPY_TO_ID ,'$SUBJECT' as SUBJECT,'$CONTENT' as CONTENT,'$SEND_TIME' as SEND_TIME,'' as ATTACHMENT_ID,'' as ATTACHMENT_NAME,'1' as SEND_FLAG,'0' as IMPORTANT
         from USER where USER_ID='$USERID' and  NOT_LOGIN='0'";
    exequery(TD::conn(),$query);
    //echo "<xmp>$CONTENT</xmp>\n<br>";

    $BODY_ID=mysql_insert_id();
    $query="INSERT INTO EMAIL(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID) values('$USERID','0','0','0','$BODY_ID')";
    exequery(TD::conn(),$query);
    $ROW_ID=mysql_insert_id();
    $REMIND_URL="email/inbox/read_email/?BOX_ID=0&EMAIL_ID=".$ROW_ID;
    $SMS_CONTENT=sprintf(_("请查收邮件！%s主题："), "\n").csubstr($SUBJECT,0,100);
    send_sms("",$_SESSION["LOGIN_USER_ID"],$USERID,2,$SMS_CONTENT,$REMIND_URL,$ROW_ID);

}
/*
//标记工资流程发送状态为已发送
$query="update sal_flow set ISSEND=1 where FLOW_ID='$FLOW_ID'";
exequery(TD::conn(),$query);
*/
if($NODATA!=="")
    Message(_("提示"),_("以下人员无工资数据，请核实：").substr($NODATA,0,-1));
if($COUNT>0)
    Message(_("提示"),_("工资条已发送"));

Button_Back();
?>
</body>
</html>
