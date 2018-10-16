<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("NOTIFY", 10);
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("公告通知查询");
include_once("inc/header.inc.php");
?>


<script>
function open_notify(NOTIFY_ID,WHERE_STR)
{
 URL="../show/read_notify.php?NOTIFY_ID="+NOTIFY_ID+"&IS_SEARCH=1&"+WHERE_STR;

 myleft=(screen.availWidth-780)/2;
 window.open(URL,"read_notify"+NOTIFY_ID,"height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

</script>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$SEND_TIME_MAX1=$SEND_TIME_MAX;
$SEND_TIME_MIN1=$SEND_TIME_MIN;

//----------- 合法性校验 ---------
if($SEND_TIME_MIN!="")
   $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
if($SEND_TIME_MAX!="")
   $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
   
//------------------------ 生成条件字符串 ------------------
$WHERE_CLAUSE="";
 // where条件
$WHERE_CLAUSE 	= " WHERE PUBLISH='1' ";
if($TYPE_ID != "")
   $WHERE_CLAUSE .= " AND TYPE_ID='$TYPE_ID'";
if($SUBJECT!="")
   $WHERE_CLAUSE.=" AND SUBJECT like '%".$SUBJECT."%'";
if($CONTENT!="")
   $WHERE_CLAUSE.=" AND CONTENT like '%".$CONTENT."%'";
if($FORMAT!="")
   $WHERE_CLAUSE.=" AND FORMAT='$FORMAT'";
if($SEND_TIME_MIN!="")
   $WHERE_CLAUSE.=" AND SEND_TIME>='$SEND_TIME_MIN'";
if($SEND_TIME_MAX!="")
   $WHERE_CLAUSE.=" AND SEND_TIME<='$SEND_TIME_MAX'";  
if($TO_ID!="")
   $WHERE_CLAUSE.=" AND find_in_set(FROM_ID,'$TO_ID')";
if($SEND_TIME!="")
{
    $WHERE_CLAUSE .= " AND SUBSTRING(SEND_TIME,1,10)='$SEND_TIME'";  
    
}
$WHERE_CLAUSE  .= " AND BEGIN_DATE<='$CUR_DATE_U' and (END_DATE>='$CUR_DATE_U' or END_DATE='0') ";
$WHERE_CLAUSE  .= " AND (TO_ID='ALL_DEPT' 
													 OR find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) 
													 OR find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)
													 OR find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) "
									. priv_other_sql("PRIV_ID").dept_other_sql("TO_ID")." )";									
$querys = "SELECT count(NOTIFY_ID) from NOTIFY".$WHERE_CLAUSE;
$cursors=exequery(TD::conn(),$querys);
if($ROWS=mysql_fetch_array($cursors))
   $TOTAL_ITEMS=$ROWS[0];
$THE_FOUR_VAR = "SEND_TIME_MIN=$SEND_TIME_MIN1&SEND_TIME_MAX=$SEND_TIME_MAX1&FORMAT=$FORMAT&TYPE_ID=$TYPE_ID&CONTENT=".urlencode($CONTENT)."&TO_ID=$TO_ID&SUBJECT=".urlencode($SUBJECT)."&"."start";  
?>
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("公告通知查询结果")?></span><br>
    </td>
    <?
    	 if($TOTAL_ITEMS>0)
    	 {
    ?>
       <td align=right><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE,$THE_FOUR_VAR)?></td>
    <?
 		}
    ?>   
  </tr>
</table>
<?
$POSTFIX = _("，");
$LIST_CLAUSE = " NOTIFY_ID,FROM_ID,TO_ID,SUBJECT,READERS,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,BEGIN_DATE,SUBJECT_COLOR,END_DATE ";
$query = "SELECT ".$LIST_CLAUSE." from NOTIFY ".$WHERE_CLAUSE;
$query.=" order by TOP desc, BEGIN_DATE desc, SEND_TIME desc";
$query .= " limit $start,$PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
//把查询结果存放到数组里，取一次数据缓存
$ROW_ARRAY=array();
$USER_ID_STR="";
$FROM_ID_STR="";
$TYPE_ID_STR="";
while($ROW1=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW1["USER_ID"];
   $NOTIFY_ID=$ROW1["NOTIFY_ID"];
   $FROM_ID=$ROW1["FROM_ID"];
   $TO_ID=$ROW1["TO_ID"];
   $SUBJECT=$ROW1["SUBJECT"];
   $FORMAT=$ROW1["FORMAT"];
   $READERS=$ROW1["READERS"];
   $TOP=$ROW1["TOP"];
   $TOP_DAYS=$ROW1["TOP_DAYS"];
   $PRIV_ID=$ROW1["PRIV_ID"];
   $TYPE_ID=$ROW1["TYPE_ID"];
   $SUBJECT_COLOR=$ROW1["SUBJECT_COLOR"]; 
   $BEGIN_DATE=$ROW1["BEGIN_DATE"];
   $END_DATE=$ROW1["END_DATE"];
   $USER_ID_STR.=$USER_ID.",";
   $FROM_ID_STR.=$FROM_ID.",";
   if($TYPE_ID!="")
   	$TYPE_ID_STR.="'".$TYPE_ID."',";
   $ROW_ARRAY[]= array("FROM_ID"=> $FROM_ID,"USER_ID"=> $USER_ID,"TO_ID"=> $TO_ID,"NOTIFY_ID"=> $NOTIFY_ID,"SUBJECT"=> $SUBJECT,"FORMAT"=> $FORMAT,"TOP"=> $TOP,"PRIV_ID"=> $PRIV_ID,"SUBJECT_COLOR"=> $SUBJECT_COLOR,"TYPE_ID"=> $TYPE_ID,"BEGIN_DATE"=> $BEGIN_DATE,"TOP_DAYS"=> $TOP_DAYS,"READERS"=> $READERS,"END_DATE"=> $END_DATE );
}
$USER_ID_STR=$USER_ID_STR.",".$FROM_ID_STR;   
if(td_trim($USER_ID_STR)!="")
{
   $USER_UID=UserId2Uid($USER_ID_STR);
   if($USER_UID!="")
   {
      $USER_NAME_ARRAY=GetUserInfoByUID($USER_UID,"USER_NAME,DEPT_ID,AVATAR");
   } 
}
//公告类型查询
$TYPE_ID_STR=td_trim($TYPE_ID_STR);
$TYPE_NAME_ARRAY=array();
$TYPE_NAME		=	"";
if($TYPE_ID_STR!="")
{
	$query1=	"select CODE_NAME,CODE_EXT,CODE_NO from SYS_CODE where PARENT_NO='NOTIFY' and CODE_NO in ($TYPE_ID_STR)";
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
//..............................................			
$NOTIFY_COUNT=0;
foreach ($ROW_ARRAY as $key=> $val){
    $ROW=$val;
    $NOTIFY_COUNT++;
    $NOTIFY_ID=$ROW["NOTIFY_ID"];
    $FROM_ID=$ROW["FROM_ID"];
    $TO_ID=$ROW["TO_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $READERS=$ROW["READERS"];
    $FORMAT=$ROW["FORMAT"];
    $TOP=$ROW["TOP"];
    $PRIV_ID=$ROW["PRIV_ID"];
    $USER_ID=$ROW["USER_ID"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
    $SUBJECT_TITLE="";
    if(strlen($SUBJECT) > 50)
    {
       $SUBJECT_TITLE=$SUBJECT;
       $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
    }
    $SUBJECT=td_htmlspecialchars($SUBJECT);
    $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

    if($SUBJECT_COLOR!="")    
      $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
      
    $SEND_TIME=$ROW["SEND_TIME"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
    if($END_DATE=="0")
       $END_DATE="";
    else
		$END_DATE=date("Y-m-d",$END_DATE);
    // 发布用户数据从缓存中获取
    $FROM_UID=UserId2Uid($FROM_ID);
    if($FROM_UID!="")
    {
		$FROM_NAME=$USER_NAME_ARRAY[$FROM_UID]["USER_NAME"];
      $DEPT_ID=$USER_NAME_ARRAY[$FROM_UID]["DEPT_ID"];
      $AVATAR=$USER_NAME_ARRAY[$FROM_UID]["AVATAR"];
      $DEPT_NAME	=	dept_long_name($DEPT_ID);
    }
	 else //如果用缓存中取不到 数据库中查
	 {
		$query1="select USER_NAME,DEPT_ID,AVATAR from USER where USER_ID='$FROM_ID'";
		$cursor1= exequery(TD::conn(),$query1);
		if($ROW=mysql_fetch_array($cursor1))
		{
			$FROM_NAME=$ROW["USER_NAME"];
			$DEPT_ID=$ROW["DEPT_ID"];
			$AVATAR		=	$ROW["AVATAR"];
			$DEPT_NAME	=	dept_long_name($DEPT_ID);
		}
		else
		{
			$FROM_NAME=$FROM_ID;   
			$AVATAR		=	"";
			$DEPT_NAME	=	_("用户已删除");
		} 
	}
	 $TYPE_NAME=$TYPE_NAME_ARRAY[$TYPE_ID];
    if($TO_ID=="ALL_DEPT")
       $TO_NAME=_("全体部门");
    else
    {
		  $TO_NAME=GetDeptNameById($TO_ID);
    }
    $TO_NAME_TITLE="";
    $TO_NAME_STR="";
    if($TO_NAME!="")
    {
       if(substr($TO_NAME,-1)==",")
          $TO_NAME=substr($TO_NAME,0,-1);
       $TO_NAME_TITLE.=_("部门：").$TO_NAME;
       $TO_NAME_STR.="<font color=#0000FF><b>"._("部门：")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
    }
     $PRIV_NAME=GetPrivNameById($PRIV_ID); 
    if($PRIV_NAME!="")
    {
       if(substr($PRIV_NAME,-1)==",")
          $PRIV_NAME=substr($PRIV_NAME,0,-1);
       
       if($TO_NAME_TITLE!="")
          $TO_NAME_TITLE.="\n\n";
       $TO_NAME_TITLE.=_("角色：").$PRIV_NAME;
                     
       $TO_NAME_STR.="<font color=#0000FF><b>"._("角色：")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
    }   
    $USER_NAME="";
		if($USER_ID!="")
		{   
			$USER_UID=UserId2Uid($USER_ID);
			$UID_ARRAY=explode(',',$USER_UID);
			$USER_ID_ARRAY=explode(',',$USER_ID);
			for($I=0;$I<count($USER_ID_ARRAY);$I++)
			{
			   if($USER_ID_ARRAY[$I]=="")
			   continue;
			   if($UID_ARRAY[$I]!="" )
			   {
			      $USER_NAME1=$USER_NAME_ARRAY[$UID_ARRAY[$I]]["USER_NAME"];
			   }
			   else  //如果缓存中取不到uid的值那根据user_id从数据库中获取
			   {
			      $query1="select USER_NAME from USER where USER_ID='$USER_ID_ARRAY[$I]'";
			      $cursor1= exequery(TD::conn(),$query1);
			      if($ROW=mysql_fetch_array($cursor1))
			         $USER_NAME1=$ROW["USER_NAME"];
			      else
			         $USER_NAME1=$USER_ID_ARRAY[$I];   
			   }
			   $USER_NAME.=$USER_NAME1.",";
			}
		}       
    if($USER_NAME!="")
    {
       if(substr($USER_NAME,-1)==",")
          $USER_NAME=substr($USER_NAME,0,-1);
       if($TO_NAME_TITLE!="")
          $TO_NAME_TITLE.="\n\n";
       $TO_NAME_TITLE.=_("人员：").$USER_NAME;
       $TO_NAME_STR.="<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
    }
   if($NOTIFY_COUNT==1)
   {
?>
<table class="TableList" width="95%" align="center">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("发布人")?></td>
      <td nowrap align="center"><?=_("类型")?></td>
      <td nowrap align="center"><?=_("标题")?></td>
      <td nowrap align="center"><?=_("发布范围")?></td>
      <td nowrap align="center"><?=_("生效日期")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
   </thead>
<?
   }
   if($NOTIFY_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap width="120"><img src="<?=avatar_path($AVATAR)?>" width="16" height="16"> <u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u></td>
      <td align="center"><?=$TYPE_NAME?></td>
      <td><a href=javascript:open_notify('<?=$NOTIFY_ID?>','<?=$THE_FOUR_VAR?>'); title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
       <?
        if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
         echo "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' height=11 width=28>";
?>
      </td>
      <td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
    </tr>
<?
}
if($NOTIFY_COUNT==0)
{
   Message("",_("无符合条件的公告通知"));
   Button_Back();
   exit;
}
else
{
?>
</table>
<?
//Button_Back();

}
?>
 <br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='query.php'"></center> 

</body>

</html>
