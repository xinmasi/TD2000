<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");

$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");
ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/msword");
Header("Content-Disposition: attachment; ".get_attachment_filename(_("工作日志").".doc"));
?>

<body topmargin="5">
  <table border="1" cellspacing="1" width="95%" class="small" cellpadding="3"> 
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;">
        <td nowrap align="center"><?=_("作者")?></td>
        <td nowrap align="center"><?=_("日期")?></td>
        <td nowrap align="center"><?=_("写日志时间")?></td>        
        <td nowrap align="center"><?=_("日志类型")?></td>
        <td nowrap align="center"><?=_("日志标题")?></td>
        <td nowrap align="center"><?=_("日志内容")?></td>
        <td nowrap align="center"><?=_("点评")?></td>
        <td nowrap align="center"><?=_("附件名称")?></td>
      </tr>
<?
//$WHERE_STR="";

if($diarydb!="")
{
    $DIARY_TABLE_NAME=TD::$_arr_db_master['db_archive'].".DIARY". $diarydb;
    $DIARY_COMMENT_TABLE_NAME=TD::$_arr_db_master['db_archive'].".DIARY_COMMENT". $diarydb;  
}
else
{
    $DIARY_TABLE_NAME="DIARY";
    $DIARY_COMMENT_TABLE_NAME="DIARY_COMMENT";   
}
if($startdate!="")
{
    $URL_BEGIN_DATE = $startdate;
    $startdate.=" 00:00:00";
    $WHERE_STR .= " and DIA_DATE >= '$startdate'";
}
if($enddate!="")
{
    $URL_END_DATE = $enddate;  
    $enddate.=" 23:59:59";
    $WHERE_STR .= " and DIA_DATE <= '$enddate'";
}
/*if($SUBJECT!="")
{
    $URL_SUBJECT = $SUBJECT;
    $WHERE_STR .= " and SUBJECT like '%$SUBJECT%'";
}*/
/*if($TO_ID1!="")
{
    $WHERE_STR .= " and find_in_set(".$DIARY_TABLE_NAME.".USER_ID,'$TO_ID1')";
}*/
if($deptname!="" && $deptname!="ALL_DEPT")
{
    $deptname .= "," . GetChildDeptId($deptname);
    if(substr($deptname,-1,1)==",")
    {
        $deptname=substr($deptname,0,-1);
    }
    $deptname = str_replace(",,", ",", $deptname);
    
    $WHERE_STR .= " and b.DEPT_ID in ($deptname)";
}

if($rolename!="")
{
    if(substr($rolename,-1,1)==",")
    {
        $rolename=substr($rolename,0,-1); 
    }  
    $WHERE_STR .= " and b.USER_PRIV in ($rolename)";
}

if($username!="")
{
    if(substr($username,-1,1)==",")
    {
        $username=substr($username,0,-1); 
    } 
    $WHERE_STR .= " and find_in_set(".$DIARY_TABLE_NAME.".USER_ID ,'$username')";
}
//file_put_contents("str.txt",$WHERE_STRS."er");
$WHERE_STRS = substr($WHERE_STRS, 4);
if($diarytype == "all")
{
    if($WHERE_STRS == "")
    {
        $WHERE_STR .= " and (DIA_TYPE!=2 or ".$DIARY_TABLE_NAME.".USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) || TO_ALL= '1'))";
    }
    else
    {
        $WHERE_STR .= " and ((".$WHERE_STRS." and DIA_TYPE!=2) or ".$DIARY_TABLE_NAME.".USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) || TO_ALL= '1'))";
    }
    //$WHERE_STR .= " and 1=1";
}
else if($diarytype == "mine")
{
    $WHERE_STR .= " and ".$DIARY_TABLE_NAME.".USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
}
else if($diarytype == "shared")
{
    $WHERE_STR .= " and ".$DIARY_TABLE_NAME.".USER_ID!='".$_SESSION["LOGIN_USER_ID"]."' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) or TO_ALL=='1')";
}
else if($diarytype == "permission")
{
    if($WHERE_STRS == "")
    {
        $WHERE_STR .= " and ".$DIARY_TABLE_NAME.".USER_ID!='".$_SESSION["LOGIN_USER_ID"]."' and DIA_TYPE!=2";
    }
    else
    {
        $WHERE_STR .= " and ".$DIARY_TABLE_NAME.".USER_ID!='".$_SESSION["LOGIN_USER_ID"]."' and DIA_TYPE!=2 and ".$WHERE_STRS;
    }
    //$WHERE_STR .= " and 1=1";
}

$query = "SELECT ".$DIARY_TABLE_NAME.".DIA_ID,".$DIARY_TABLE_NAME.".DIA_DATE,".$DIARY_TABLE_NAME.".DIA_TYPE,".$DIARY_TABLE_NAME.".SUBJECT,".$DIARY_TABLE_NAME.".COMPRESS_CONTENT,".$DIARY_TABLE_NAME.".CONTENT,".$DIARY_TABLE_NAME.".ATTACHMENT_ID,".$DIARY_TABLE_NAME.".ATTACHMENT_NAME,".$DIARY_TABLE_NAME.".LAST_COMMENT_TIME,".$DIARY_TABLE_NAME.".USER_ID,".$DIARY_TABLE_NAME.".DIA_TIME,USER_NAME from ".$DIARY_TABLE_NAME." left join USER b on b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1 ".$WHERE_STR;
$query .= " order by DIA_DATE desc,DIA_ID desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $DIA_ID=$ROW["DIA_ID"];
    $DIA_DATE=$ROW["DIA_DATE"];
    $DIA_TIME=$ROW["DIA_TIME"]; 
    $DIA_DATE=strtok($DIA_DATE," ");
    $DIA_TYPE=$ROW["DIA_TYPE"];
    $SUBJECT=$ROW["SUBJECT"];
    $NOTAGS_CONTENT=$ROW["CONTENT"];
    $AUTHOR = $ROW["USER_NAME"];    
    $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]); 
    if($CONTENT=="")   
        $CONTENT=$NOTAGS_CONTENT;    
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    
    $CONTENT_COMENT="";
    $query = "SELECT USER.USER_NAME,".$DIARY_COMMENT_TABLE_NAME.".SEND_TIME,".$DIARY_COMMENT_TABLE_NAME.".CONTENT from ".$DIARY_COMMENT_TABLE_NAME.",USER where ".$DIARY_COMMENT_TABLE_NAME.".DIA_ID='$DIA_ID' and ".$DIARY_COMMENT_TABLE_NAME.".USER_ID = USER.USER_ID order by ".$DIARY_COMMENT_TABLE_NAME.".SEND_TIME desc";
    $cursor1= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor1))
    {
        $USER_NAME=$ROW["USER_NAME"];     
        $SEND_TIME=$ROW["SEND_TIME"];
        $CONTENT1=$ROW["CONTENT"];
        $CONTENT1=str_replace("\"","'",$CONTENT1);
        
        $CONTENT_COMENT.="<font color=\"#0000FF\">".$USER_NAME."&nbsp;&nbsp;".$SEND_TIME."</font><br>".

$CONTENT1."<br><br>";
    }

   $DIA_TYPE_DESC=get_code_name($DIA_TYPE,"DIARY_TYPE");
?>
   <tr style="BACKGROUND: #FFFFFF;">
     <td nowrap align="center" width="50"><?=$AUTHOR?></td>
     <td nowrap align="center" width="100"><?=$DIA_DATE?></td>
     <td nowrap align="center" width="100"><?=$DIA_TIME?></td>     
     <td nowrap align="center" width="100"><?=$DIA_TYPE_DESC?></td>
     <td><?=$SUBJECT?></td>
     <td><?=$CONTENT?></td>
     <td><?=$CONTENT_COMENT?></td>
     <td>
<?
  $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
  $ARRAY_COUNT=sizeof($ATTACHMENT_NAME_ARRAY);
  for($I=0;$I<$ARRAY_COUNT;$I++)
  {
     if($ATTACHMENT_NAME_ARRAY[$I]=="")
        continue;
     echo $ATTACHMENT_NAME_ARRAY[$I]."<br>";
  }
?>
     </td>
   </tr>
<?
}
?>
  </table>

</body>
</html>


