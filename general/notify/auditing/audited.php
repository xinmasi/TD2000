<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
if(!isset($TYPE))
   $TYPE="0";
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("NOTIFY", 10);
if(!isset($start) || $start=="")
   $start=0;
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";      

$HTML_PAGE_TITLE = _("已审批公告");
include_once("inc/header.inc.php");
?>


<script>
function open_notify(NOTIFY_ID,FORMAT)
{
 URL="read_notify.php?IS_MANAGE=1&NOTIFY_ID="+NOTIFY_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100
 mywidth=780;
 myheight=500;
 if(FORMAT=="1")
 {
    myleft=0;
    mytop=0
    mywidth=screen.availWidth-15;
    myheight=screen.availHeight-60;
 }
 window.open(URL,"read_news","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function show_reader(NOTIFY_ID)
{
  URL="show_reader.php?NOTIFY_ID="+NOTIFY_ID;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"read_notify","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function order_by(field,asc_desc)
{
 window.location="audited.php?start=<?=$start?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_type(type)
{
 window.location="audited.php?start=<?=$start?>&TYPE="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}
function delete_notify(notify_id)
{ 
	msg='<?=_("删除后将不可恢复，确认要删除该条公告吗？")?>';
  if(window.confirm(msg))
      window.location="../manage/delete.php?FROM=2&DELETE_STR="+ notify_id +"&start=<?=$start?>"; 
}

</script>


<body class="bodycolor">
<?
 $CUR_TIME=date("Y-m-d H:i:s",time());
$PARA_ARRAY=get_sys_para("NOTIFY_TOP_DAYS");
$TOP_MAX_DAYS=$PARA_ARRAY["NOTIFY_TOP_DAYS"]; //最大置顶日期

if($ASC_DESC	==	""){
   	$ASC_DESC	=	"1";
  }

$WHERE_CLAUSE = " WHERE AUDITER='".$_SESSION["LOGIN_USER_ID"]."' ";
if ($TYPE!="0")
  $WHERE_CLAUSE .= " AND TYPE_ID='$TYPE'";

$WHERE_CLAUSE .= " AND (PUBLISH='1' or PUBLISH='3') ";

$ORDER_CLAUSE = "ORDER BY ";
if ($FILED !="")
{
	$ORDER_CLAUSE .= $FIELD;
   if($ASC_DESC == "1"){
		$ORDER_CLAUSE .= " desc ";
	}else{
			$ORDER_CLAUSE .= " asc ";
		}
}
else 
  $ORDER_CLAUSE .= " TOP desc,SEND_TIME desc";

$LIMIT_CLAUSE = " limit $start,$PAGE_SIZE";

 if(!isset($TOTAL_ITEMS))
 {
   /* $query = "SELECT count(NOTIFY_ID) from NOTIFY where AUDITER='".$_SESSION["LOGIN_USER_ID"]."' and (PUBLISH='1' or PUBLISH='3')";
    if($TYPE!="0")
      $query .= " and TYPE_ID='$TYPE'";*/
 	$query = "SELECT count(NOTIFY_ID) FROM NOTIFY ".$WHERE_CLAUSE;
    $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
    $TOTAL_ITEMS=0;
    if($ROW=mysql_fetch_array($cursor))
       $TOTAL_ITEMS=$ROW[0];      
 }

 if($TOTAL_ITEMS==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("已审批公告通知")?></span>&nbsp;
       <select name="TYPE" class="BigSelect" onChange="change_type(this.value);">
          <option value="0"<?if($TYPE=="0") echo " selected";?>><?=_("所有类型")?></option>
          <?=code_list("NOTIFY",$TYPE)?>
          <option value=""<?if($TYPE=="") echo " selected";?>><?=_("无类型")?></option>
       </select>
    </td>
  </tr>
</table>

<?
   Message("",_("无符合条件的已审批公告"));
   exit;
 }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("已审批公告通知")?></span>&nbsp;
       <select name="TYPE" class="BigSelect" onChange="change_type(this.value);">
          <option value="0"<?if($TYPE=="0") echo " selected";?>><?=_("所有类型")?></option>
          <?=code_list("NOTIFY",$TYPE)?>
          <option value=""<?if($TYPE=="") echo " selected";?>><?=_("无类型")?></option>
       </select>
    </td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
  </tr>
</table>
<?
$LIST_CLAUSE = " NOTIFY_ID,FROM_ID,TO_ID,SUBJECT,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,PUBLISH,SEND_TIME,BEGIN_DATE,END_DATE,REASON,SUBJECT_COLOR ";
$query =" SELECT ".$LIST_CLAUSE." FROM NOTIFY ".$WHERE_CLAUSE.$ORDER_CLAUSE.$LIMIT_CLAUSE;
/*if($ASC_DESC=="")
   $ASC_DESC="1";8/
   $query = "SELECT NOTIFY_ID,FROM_ID,TO_ID,SUBJECT,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,PUBLISH,SEND_TIME,BEGIN_DATE,END_DATE,REASON,SUBJECT_COLOR from NOTIFY where AUDITER='".$_SESSION["LOGIN_USER_ID"]."' and (PUBLISH='1' or PUBLISH='3')";
/*if($TYPE!="0")
   $query .= " and TYPE_ID='$TYPE'";

if($FIELD=="")
   $query .= " order by TOP desc,SEND_TIME desc";
else
{
   $query .= " order by ".$FIELD;
   if($ASC_DESC=="1")
      $query .= " desc";
   else
      $query .= " asc";
}
$query .= " limit $start,$PAGE_SIZE";*/
if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("发布人")?></td>
      <td nowrap align="center"><?=_("类型")?></td>
      <td nowrap align="center"><?=_("发布范围")?></td>
      <td nowrap align="center" onClick="order_by('SUBJECT','<?if($FIELD=="SUBJECT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("标题")?></u><?if($FIELD=="SUBJECT") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('SEND_TIME','<?if($FIELD=="SEND_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("创建时间")?></u><?if($FIELD=="SEND_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('BEGIN_DATE','<?if($FIELD=="BEGIN_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("生效日期")?></u><?if($FIELD=="BEGIN_DATE") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('END_DATE','<?if($FIELD=="END_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("终止日期")?></u><?if($FIELD=="END_DATE") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("当前状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
$USER_ID_STR="";
$FROM_ID_STR="";
$TYPE_ID_STR="";
$ROW_ARRAY=array();
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW1=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW1["USER_ID"];
   $NOTIFY_ID=$ROW1["NOTIFY_ID"];
   $FROM_ID=$ROW1["FROM_ID"];
   $TO_ID=$ROW1["TO_ID"];
   $SUBJECT=$ROW1["SUBJECT"];
   $FORMAT=$ROW1["FORMAT"];
   $TOP=$ROW1["TOP"];
   $TOP_DAYS=$ROW1["TOP_DAYS"];
   $PRIV_ID=$ROW1["PRIV_ID"];
   $TYPE_ID=$ROW1["TYPE_ID"];
   $PUBLISH=$ROW1["PUBLISH"];
   $SUBJECT_COLOR=$ROW1["SUBJECT_COLOR"];
   $AUDITER=$ROW1["AUDITER"]; 
   $SEND_TIME=$ROW1["SEND_TIME"];
   $BEGIN_DATE=$ROW1["BEGIN_DATE"];
   $END_DATE=$ROW1["END_DATE"];
   $REASON=$ROW1["REASON"];
   $USER_ID_STR.=$USER_ID.",";
   $FROM_ID_STR.=$FROM_ID.",";
   if($TYPE_ID!="")
   	$TYPE_ID_STR.="'".$TYPE_ID."',";
   $ROW_ARRAY[]= array("FROM_ID"=> $FROM_ID,"USER_ID"=> $USER_ID,"TO_ID"=> $TO_ID,"NOTIFY_ID"=> $NOTIFY_ID,"SUBJECT"=> $SUBJECT,"FORMAT"=> $FORMAT,"TOP"=> $TOP,"TOP_DAYS"=> $TOP_DAYS,"PRIV_ID"=> $PRIV_ID,"SUBJECT_COLOR"=> $SUBJECT_COLOR,"TYPE_ID"=> $TYPE_ID,"PUBLISH" => $PUBLISH,"AUDITER"=> $AUDITER,"SEND_TIME"=> $SEND_TIME,"BEGIN_DATE"=> $BEGIN_DATE,"END_DATE"=> $END_DATE,"REASON"=> $REASON ); 
   
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

 //============================ 显示已审批公告 =======================================
 $POSTFIX = _("，");
 $CUR_DATE=date("Y-m-d",time());
 $NOTIFY_COUNT=0;
$USER_ID_STR=$USER_ID_STR.",".$FROM_ID_STR;   
if(td_trim($USER_ID_STR)!="")
{
   $USER_UID=UserId2Uid($USER_ID_STR);
   if($USER_UID!="")
   {
      $USER_NAME_ARRAY=GetUserInfoByUID($USER_UID,"USER_NAME,DEPT_ID");
   }
    
}
foreach ($ROW_ARRAY as $key=> $val){
    $ROW=$val;
    $NOTIFY_COUNT++;
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
    $REASON=$ROW["REASON"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
    
    $SUBJECT_TITLE="";
    if(strlen($SUBJECT) > 50)
    {
       $SUBJECT_TITLE=$SUBJECT;
       $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
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
    $TYPE_NAME=$TYPE_NAME_ARRAY[$TYPE_ID];
    //$TYPE_NAME=get_code_name($TYPE_ID,"NOTIFY");
      $TO_NAME="";
   if($TO_ID=="ALL_DEPT")
      $TO_NAME=_("全体部门");
   else
      $TO_NAME=GetDeptNameById($TO_ID);
   $PRIV_NAME=GetPrivNameById($PRIV_ID);
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
       $TO_NAME_STR.="<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
    }
    $NOTIFY_STATUS_STR="";
    if ($PUBLISH=="1")
    {
	   $RESULT="<font color=green>"._("批准")."</font>";
	   if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
	   {
	   	   $NOTIFY_STATUS=1;
           $NOTIFY_STATUS_STR=_("待生效");
	   }
	   else
	   {
	      $NOTIFY_STATUS=2;
	      $NOTIFY_STATUS_STR="<font color='#00AA00'><b>"._("生效")."</font>";
	   }
	   if($END_DATE!="")
       {
         if(compare_date($CUR_DATE,$END_DATE)>0)
         {
            $NOTIFY_STATUS=3;
            $NOTIFY_STATUS_STR="<font color='#FF0000'><b>"._("终止")."</font>";
         }
       }
	}
	if ($PUBLISH=="3")
	   $RESULT="<font color=red>"._("未批准")."</font>";

    if($TOP=="1")
       $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
    else
       $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
    if($NOTIFY_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u></td>
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
      <td><a href=javascript:open_notify("<?=$NOTIFY_ID?>","<?=$FORMAT?>"); title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
      </td>
      <td align="center"><?=$SEND_TIME?></a></td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=$END_DATE?></td>
      <td nowrap align="center" style="cursor:hand" <? if ($PUBLISH==3) echo "title='"._("审批意见").$REASON."'";?>><?=$RESULT?></td>
      <td nowrap align="center"><?=$NOTIFY_STATUS_STR?></td>
      <td nowrap align="center">
      <?if (($NOTIFY_STATUS==2|| $NOTIFY_STATUS==3) && $PUBLISH==1)
      {
      ?>
       <a href="javascript:show_reader('<?=$NOTIFY_ID?>');" title="<?=_("查阅情况")?>"> <?=_("查阅情况")?></a>&nbsp;
      <?
      }
      if($PUBLISH==1)
      {     
      	 if($NOTIFY_STATUS==1) //如果是待生效的可进行立即生效操作
      	 {
      ?>	
             <a href="../manage/manage.php?FROM=2&NOTIFY_ID=<?=$NOTIFY_ID?>&OPERATION=1&start=<?=$start?>"> <?=_("立即生效")?></a>&nbsp;
      <?
         }
         if($NOTIFY_STATUS==2)//如果是生效的可进行终止操作
         {
      ?>
            <a href="../manage/manage.php?FROM=2&NOTIFY_ID=<?=$NOTIFY_ID?>&OPERATION=2&start=<?=$start?>"> <?=_("终止")?></a>&nbsp;
       <?  	
         }
         if($NOTIFY_STATUS==3)//如果是终止的可进行生效操作
         {
         ?>
         <a href="../manage/manage.php?FROM=2&NOTIFY_ID=<?=$NOTIFY_ID?>&OPERATION=3&start=<?=$start?>"> <?=_("生效")?></a>&nbsp;      	
       <? 	
         }
        ?>
         <a href="javascript:delete_notify('<?=$NOTIFY_ID?>')"> <?=_("删除")?></a>   
       <?
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