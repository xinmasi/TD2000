<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_cache.php");
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
//$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");
$ROW=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV,POST_DEPT,DEPT_ID");
$POST_PRIV=$ROW["POST_PRIV"];
$DEPT_ID1=td_trim($ROW["POST_DEPT"]);
$DEPT_ID2=$ROW["DEPT_ID"];
//----------- 合法性校验 ---------
if($SEND_TIME_MIN!="")
{
    $TIME_OK=is_date($SEND_TIME_MIN);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("\"发布日期\"的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
}

if($SEND_TIME_MAX!="")
{
    $TIME_OK=is_date($SEND_TIME_MAX);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("\"发布日期\"的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
}

//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($SUBJECT!="")
    $CONDITION_STR.=" and SUBJECT like '%".$SUBJECT."%'";
if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
if($SEND_TIME_MIN!="")
    $CONDITION_STR.=" and SEND_TIME>='$SEND_TIME_MIN'";
if($SEND_TIME_MAX!="")
    $CONDITION_STR.=" and SEND_TIME<='$SEND_TIME_MAX'";
if($FORMAT!="")
    $CONDITION_STR.=" and FORMAT='$FORMAT'";
if($TYPE_ID!="")
    $CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
if($PUBLISH!="")
{
    if($PUBLISH==1)
        $CONDITION_STR.=" and PUBLISH='1'";
    else
        $CONDITION_STR.=" and PUBLISH<>'1'";
}
if($TOP!="")
    $CONDITION_STR.=" and TOP='$TOP'";
if($TO_ID!="")
    $CONDITION_STR.=" and find_in_set(FROM_ID,'$TO_ID')";

if($STAT!="")
{
    if($STAT==1)
        $CONDITION_STR.=" and BEGIN_DATE>'$CUR_DATE_U'";
    else if($STAT==2)
        $CONDITION_STR.=" and BEGIN_DATE<='$CUR_DATE_U' and (END_DATE='0' or END_DATE>='$CUR_DATE_U')";
    else if($STAT==3)
        $CONDITION_STR.=" and END_DATE!='0' and END_DATE<='$CUR_DATE_U'";
}

ob_end_clean();
require_once ('inc/ExcelWriter.php');

$FIELD_ARRAY = array(_("发布人"),_("类型"),_("标题"),_("发布时间"),_("生效日期"),_("终止日期"),_("状态"),_("附件名称"),_("内容"));
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("公告通知"));
$objExcel->addHead($FIELD_ARRAY);

$LIST_CLAUSE = " NOTIFY_ID,FROM_ID,SUBJECT,TYPE_ID,PUBLISH,ATTACHMENT_NAME,SEND_TIME,BEGIN_DATE,END_DATE,CONTENT ";
//$LIST_CLAUSE2= " a.NOTIFY_ID,a.FROM_ID,a.TO_ID,SUBJECT,a.FORMAT,TOP,a.TOP_DAYS,a.PRIV_ID,a.USER_ID,a.TYPE_ID,a.PUBLISH,a.AUDITER,a.SEND_TIME,a.BEGIN_DATE,a.END_DATE,a.SUBJECT_COLOR ";

if ($_SESSION["LOGIN_USER_PRIV"]=="1" || $POST_PRIV=="1")
    $query="SELECT ".$LIST_CLAUSE." FROM NOTIFY WHERE 1=1";
else if ($POST_PRIV=="0" || $POST_PRIV=="6")
    $query="SELECT ".$LIST_CLAUSE." FROM NOTIFY a left join USER b on a.FROM_ID=b.USER_ID where b.DEPT_ID='$DEPT_ID2'" ;
else if ($POST_PRIV=="2")
    $query="SELECT ".$LIST_CLAUSE." FROM NOTIFY where find_in_set(FROM_DEPT,'$DEPT_ID1') or FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
/*if ($_SESSION["LOGIN_USER_ID"]=="1" || $POST_PRIV=="1")
   $query="SELECT NOTIFY_ID,FROM_ID,SUBJECT,TYPE_ID,PUBLISH,ATTACHMENT_NAME,SEND_TIME,BEGIN_DATE,END_DATE,CONTENT FROM NOTIFY WHERE 1=1";
else if ($POST_PRIV=="0" || $POST_PRIV=="6")
   $query="SELECT NOTIFY_ID,FROM_ID,SUBJECT,TYPE_ID,PUBLISH,ATTACHMENT_NAME,SEND_TIME,BEGIN_DATE,END_DATE,CONTENT FROM NOTIFY LEFT JOIN USER ON NOTIFY.FROM_ID=USER.USER_ID WHERE USER.DEPT_ID='$DEPT_ID2'";
else if ($POST_PRIV=="2")
   $query="SELECT NOTIFY_ID,FROM_ID,SUBJECT,TYPE_ID,PUBLISH,ATTACHMENT_NAME,SEND_TIME,BEGIN_DATE,END_DATE,CONTENT FROM NOTIFY LEFT JOIN USER ON NOTIFY.FROM_ID=USER.USER_ID WHERE USER.DEPT_ID='$DEPT_ID1' OR NOTIFY.FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
/*if($_SESSION["LOGIN_USER_PRIV"]!="1"&&$POST_PRIV!="1")
   $query = "SELECT * from NOTIFY where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
else
   $query = "SELECT * from NOTIFY where 1=1";*/
$query.=$CONDITION_STR." order by TOP desc, SEND_TIME desc";

$ROW = 1;
$ROW_ARRAY=array();
$USER_ID_STR="";
$FROM_ID_STR="";
$TYPE_ID_STR="";
$cursor=exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
    $NOTIFY_ID1=$ROW1["NOTIFY_ID"];
    $FROM_ID1=$ROW1["FROM_ID"];
    $SUBJECT1=$ROW1["SUBJECT"];
    $TYPE_ID1=$ROW1["TYPE_ID"];
    $PUBLISH1=$ROW1["PUBLISH"];
    $ATTACHMENT_NAME1=$ROW1["ATTACHEMENT_NAME"];
    $SEND_TIME1=$ROW1["SEND_TIME"];
    $BEGIN_DATE1=$ROW1["BEGIN_DATE"];
    $END_DATE1=$ROW1["END_DATE"];
    $CONTENT1=$ROW1["CONTENT"];
    $FROM_ID_STR.=$FROM_ID1.",";
    if($TYPE_ID1!="")
        $TYPE_ID_STR.="'".$TYPE_ID1."',";
    $ROW_ARRAY[]=array("NOTIFY_ID"=> $NOTIFY_ID1,"FROM_ID"=> $FROM_ID1,"SUBJECT"=> $SUBJECT1,"PUBLISH"=> $PUBLISH1,"TYPE_ID"=> $TYPE_ID1,"ATTACHMENT_NAME"=> $ATTACHMENT_NAME1,"SEND_TIME"=> $SEND_TIME1,"BEGIN_DATE"=> $BEGIN_DATE1,"END_DATE"=> $END_DATE1,"CONTENT"=> $CONTENT1);

}

$USER_ID_STR=$FROM_ID_STR;
if(td_trim($USER_ID_STR)!="")
{
    $USER_UID=UserId2Uid($USER_ID_STR);
    if($USER_UID!="")
    {
        $USER_NAME_ARRAY=GetUserInfoByUID($USER_UID,"USER_NAME,DEPT_ID");
    }

}

//公告类型查询
$TYPE_ID_STR=td_trim($TYPE_ID_STR);
$TYPE_NAME_ARRAY=array();
$TYPE_NAME = "";
if($TYPE_ID_STR!="")
{
    $query1 = "select CODE_NAME,CODE_EXT,CODE_NO from SYS_CODE where PARENT_NO='NOTIFY' and CODE_NO in ($TYPE_ID_STR)";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW_CODE=mysql_fetch_array($cursor1))
    {
        $TYPE_ID_KEY=$ROW_CODE["CODE_NO"];
        $TYPE_NAME=$ROW_CODE["CODE_NAME"];
        $CODE_EXT=unserialize($ROW["CODE_EXT"]);
        if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
            $TYPE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
        $TYPE_NAME_ARRAY[$TYPE_ID_KEY]= $TYPE_NAME;
    }
}
foreach ($ROW_ARRAY as $key=> $val){
    $ROW=$val;
    $NOTIFY_ID=$ROW["NOTIFY_ID"];
    $FROM_ID=$ROW["FROM_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $PUBLISH=$ROW["PUBLISH"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    if($PUBLISH=="0")
        $PUBLISH_DESC="<font color=red>"._("未发布")."</font>";
    else
        $PUBLISH_DESC="";

    $SEND_TIME=$ROW["SEND_TIME"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $CONTENT=$ROW["CONTENT"];

    $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
    if ($END_DATE=="0")
        $END_DATE="";
    else
        $END_DATE=date("Y-m-d",$END_DATE);
    /*$BEGIN_DATE=strtok($BEGIN_DATE," ");
    $END_DATE=strtok($END_DATE," ");

    if($END_DATE=="0000-00-00")
       $END_DATE="";*/

    /*$query1="select USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
       $FROM_NAME=$ROW["USER_NAME"];
       $DEPT_ID=$ROW["DEPT_ID"];
    }*/

    $FROM_UID=UserId2Uid($FROM_ID);
    if($FROM_UID!="")
    {
        $FROM_NAME=$USER_NAME_ARRAY[$FROM_UID]["USER_NAME"];
        $DEPT_ID=$USER_NAME_ARRAY[$FROM_UID]["DEPT_ID"];
    }
    else //如果用缓存中取不到 数据库中查
    {
        $query1="select USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $FROM_NAME=$ROW["USER_NAME"];
            $DEPT_ID=$ROW["DEPT_ID"];
        }
        else
        {
            $FROM_NAME=$FROM_ID;
        }
    }

    $DEPT_NAME=dept_long_name($DEPT_ID);

    //$TYPE_NAME=get_code_name($TYPE_ID,"NOTIFY");
    $TYPE_NAME=$TYPE_NAME_ARRAY[$TYPE_ID];
    if($PUBLISH=="1") //发布的
    {
        if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
        {
            $NOTIFY_STATUS=1;
            $NOTIFY_STATUS_STR=_("待生效");
        }
        else
        {
            $NOTIFY_STATUS=2;
            $NOTIFY_STATUS_STR=_("生效");
        }
        if($END_DATE!="")
        {
            if(compare_date($CUR_DATE,$END_DATE)>0)
            {
                $NOTIFY_STATUS=3;
                $NOTIFY_STATUS_STR=_("终止");
            }
        }

    }
    if($PUBLISH=="2")//待审批
    {
        $NOTIFY_STATUS_STR=_("待审批");
    }
    if($PUBLISH=="3")//审批未通过
    {
        $NOTIFY_STATUS_STR=_("未通过");
    }


    /*if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
    {
       $NOTIFY_STATUS=1;
       $NOTIFY_STATUS_STR=_("待生效");
    }
    else
    {
       $NOTIFY_STATUS=2;
       $NOTIFY_STATUS_STR=_("生效");
    }


    if($END_DATE!="" || $PUBLISH=="0")
    {
      if(compare_date($CUR_DATE,$END_DATE)>0)
      {
         $NOTIFY_STATUS=3;
         $NOTIFY_STATUS_STR=_("终止");
      }
    }*/

    if($PUBLISH=="0")
        $NOTIFY_STATUS_STR=_("未发布");

    $objExcel->addRow(array($FROM_NAME, $TYPE_NAME, $SUBJECT, $SEND_TIME, $BEGIN_DATE, $END_DATE, $NOTIFY_STATUS_STR, $ATTACHMENT_NAME, $CONTENT));
}

$objExcel->Save();
?>