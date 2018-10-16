<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("工作计划");
include_once("inc/header.inc.php");
?>



<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function plan_detail(PLAN_ID)
{
  var URL="../plan_detail.php?PLAN_ID="+PLAN_ID;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"read_work_plan","height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=60,left=110,resizable=yes");
}
</script>


<body class="bodycolor">
<?
$MANAGER=$SECRET_TO_ID;
$PARTICIPATOR=$COPY_TO_ID;

//----------- 合法性校验 ---------
if($BEGIN_DATE!="")
{
   $TIME_OK=is_date($BEGIN_DATE);
 
   if(!$TIME_OK)
   {
     Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
     Button_Back();
     exit;
   }
}

if($END_DATE!="")
{
   $TIME_OK=is_date($END_DATE);
 
   if(!$TIME_OK)
   {
     Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
     Button_Back();
     exit;
   }
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($NAME!="")
   $CONDITION_STR.="NAME like '%".$NAME."%'";
if($CONTENT!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="CONTENT like '%".$CONTENT."%'";
}

if($BEGIN_DATE!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="BEGIN_DATE>='".$BEGIN_DATE."'";
}

if($END_DATE!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="END_DATE<='".$END_DATE."'";
}

if($TYPE!="ALL_TYPE")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="TYPE='$TYPE'";
}
if($TO_ID!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="TO_ID like '%".$TO_ID."%'";
}
if($TO_ID3!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="TO_PERSON_ID like '%".$TO_ID3."%'";
}
if($MANAGER!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="MANAGER like '%".$MANAGER."%'";
}
if($PARTICIPATOR!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="PARTICIPATOR like '%".$PARTICIPATOR."%'";
}
if($REMARK!="")
{
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";
   $CONDITION_STR.="REMARK like '%".$REMARK."%'";
}
if($NEWER_DEPT_ID!="")
{
   $USER_ID1="";
   $query = "SELECT USER_ID from USER where DEPT_ID='$NEWER_DEPT_ID'";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor)) 
      $USER_ID1.=$ROW["USER_ID"].",";
   if($CONDITION_STR!="")
      $CONDITION_STR.=" and ";    
   $CONDITION_STR.="find_in_set(CREATOR,'$USER_ID1')";
}

if($CONDITION_STR!="")
   $CONDITION_STR.=" and "; 
$CONDITION_STR.=" PUBLISH='1'"; 
//------------------------------------------------------------------------------
if($_SESSION["LOGIN_USER_PRIV"]=="1")
{
   if($CONDITION_STR!="")
      $CONDITION_STR1=" where ".$CONDITION_STR;
}
else
{
   $CONDITION_STR1=" where (TO_ID='ALL_DEPT' or InStr(TO_ID,',".$_SESSION["LOGIN_DEPT_ID"].",')>0 or InStr(TO_ID,'".$_SESSION["LOGIN_DEPT_ID"].",')=1
                      or InStr(TO_PERSON_ID,',".$_SESSION["LOGIN_USER_ID"].",')>0 or InStr(TO_PERSON_ID,'".$_SESSION["LOGIN_USER_ID"].",')=1
                      or InStr(MANAGER,',".$_SESSION["LOGIN_USER_ID"].",')>0 or InStr(MANAGER,'".$_SESSION["LOGIN_USER_ID"].",')=1
                      or InStr(PARTICIPATOR,',".$_SESSION["LOGIN_USER_ID"].",')>0 or InStr(PARTICIPATOR,'".$_SESSION["LOGIN_USER_ID"].",')=1)";
   if($CONDITION_STR!="")
      $CONDITION_STR1.=" and $CONDITION_STR";
}

$query = "SELECT count(*) from WORK_PLAN".$CONDITION_STR1;
$cursor= exequery(TD::conn(),$query);
$WORK_PLAN_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $WORK_PLAN_COUNT=$ROW[0];

if($WORK_PLAN_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="20" align="absmiddle"><span class="big3"> <?=_("查询结果")?></span><br>
    </td>
  </tr>
</table>
<br>

<?
  Message("",_("无符合条件的工作计划"));
?>
   <P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'"></P>
<?
  exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="20" align="absmiddle"><span class="big3"> <?=_("查询结果")?></span><br>
    </td>

    <td valign="bottom" class="small1"><?=sprintf(_("共%s条符合条件的工作计划"),"<span class='big4'>&nbsp;".$WORK_PLAN_COUNT."</span>&nbsp;");?>
    </td>
    </tr>
</table>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("序号")?></td>  	
    <td nowrap align="center"><?=_("计划名称")?></td>
    <td nowrap align="center"><?=_("进度")?></td>
    <td nowrap align="center"><?=_("开始时间")?></td>
    <td nowrap align="center"><?=_("结束时间")?></td>
    <td nowrap align="center"><?=_("计划类别")?></td>
    <td nowrap align="center"><?=_("负责人")?></td>
    <td nowrap align="center"><?=_("参与人")?></td>
    <td nowrap align="center"><?=_("附件")?></td>
    <td nowrap align="center"><?=_("状态")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
//============================ 显示查询结果 =======================================
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from WORK_PLAN".$CONDITION_STR1." order by PLAN_ID desc";
$cursor= exequery(TD::conn(),$query);
$POSTFIX = _("，");
$WORK_PLAN_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $WORK_PLAN_COUNT++;

   $PLAN_ID=$ROW["PLAN_ID"];
   $NAME=$ROW["NAME"];
   
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $TYPE=$ROW["TYPE"];
   $MANAGER=$ROW["MANAGER"];
   $PARTICIPATOR1=$ROW["PARTICIPATOR"];   
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $TO_PERSON_ID=$ROW["TO_PERSON_ID"];
   $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];
   
   $DIARY_WRITER=$MANAGER.$PARTICIPATOR1;
   
   if(find_id($DIARY_WRITER,$_SESSION["LOGIN_USER_ID"]))
      $HINT_FLAG=0;
   else
      $HINT_FLAG=1;   

   $query = "SELECT * from PLAN_TYPE where TYPE_ID='$TYPE'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW1=mysql_fetch_array($cursor1))
      $TYPE_DESC=$ROW1["TYPE_NAME"];
   else
      $TYPE_DESC="";
      
   $MANAGE_NAME="";
   $TOK=strtok($MANAGER,",");
   while($TOK!="")
   {
     $query1="select * from USER where USER_ID='$TOK'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW1=mysql_fetch_array($cursor1))
     {
        $DEPT_ID=$ROW1["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
        $MANAGE_NAME.="<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\" onClick = \" window.open('../my_view.php?USER_ID=".$TOK."&PLAN_ID=".$PLAN_ID."','','status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=0,top=0,resizable=yes')\">".$ROW1["USER_NAME"]."</u>".$POSTFIX;
     }

     $TOK=strtok(",");
   }
   $MANAGE_NAME=substr($MANAGE_NAME,0,-strlen($POSTFIX));
   
    $PARTICIPATOR_NAME="";
    $TOK1=strtok($PARTICIPATOR1,",");
	$TOTAL_PERCENT=0;
	$PERSON=0;
	$SUM_PERCENT=0;
    while($TOK1!="")
    {
      $query1="select * from USER where USER_ID='$TOK1'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
      {
         $DEPT_ID=$ROW["DEPT_ID"];
         $DEPT_NAME=dept_long_name($DEPT_ID);
         
         $PARTICIPATOR_NAME.="<u style=\"cursor:hand\" onClick = \" window.open('../my_view.php?USER_ID=".$TOK1."&PLAN_ID=".$PLAN_ID."','','status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=0,top=0,resizable=yes')\">" .$ROW["USER_NAME"]. "</u>".$POSTFIX;;
		 //总进度
		 $query2 = "SELECT MAX(PERCENT) as PERCENT from WORK_DETAIL where PLAN_ID='$PLAN_ID' and WRITER='$TOK1'";
		 $cursor2=exequery(TD::conn(),$query2);
		   if($ROW2=mysql_fetch_array($cursor2))
		 {
			$PERCENT=$ROW2["PERCENT"];
			$SUM_PERCENT=$SUM_PERCENT + $PERCENT;
			$PERSON++;
		 }		 
      }
	  
      $TOK1=strtok(",");
    }
	if($PERSON!=0)
	$TOTAL_PERCENT=round($SUM_PERCENT/($PERSON*100)*100);
    $PARTICIPATOR_NAME=substr($PARTICIPATOR_NAME,0,-strlen($POSTFIX));
   
   $MY_FLAG=0;
   if($SUSPEND_FLAG==1)
   {
   	  if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
      {
         $STATUS=1;
         $STATUS_DESC=_("未开始");
      }
      else
      {
         $STATUS=2;
         $STATUS_DESC="<font color='#00AA00'><b>"._("进行中")."</b></font>";
      }
   
      if($END_DATE!="0000-00-00")
      {
        if(compare_date($CUR_DATE,$END_DATE)>=0)
        {
           $STATUS=3;
           $STATUS_DESC="<font color='#FF0000'><b>"._("已结束")."</b></font>";
           $MY_FLAG=1;
        }
      }
   }
   else
   {
      $STATUS=2;
      $STATUS_DESC="<font color='#FF0000'><b>"._("暂停")."</b></font>";
      $MY_FLAG=1;
   } 

   if($WORK_PLAN_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";

   if($END_DATE=="0000-00-00")
      $END_DATE="";
?>
    <tr class="<?=$TableLine?>">
      <td align="center"><?=$PLAN_ID?></td>   
      <td align="left"><a href="javascript:plan_detail('<?=$PLAN_ID?>');"><?=$NAME?></a>
      <input type="button" value="<?=_("进度图")?>" class="SmallButton" onClick="window.open('../progress_map.php?PLAN_ID=<?=$PLAN_ID?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');" title="<?=_("查看进度图")?>">
      </td>
      <td nowrap align="center"><?=$TOTAL_PERCENT?>%</td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=$END_DATE?></a></td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td align="center"><?=$MANAGE_NAME?></td>
      <td align="center"><?=$PARTICIPATOR_NAME?></td>
      <td align="left">
<?
     if($ATTACHMENT_NAME=="")
        echo _("无");
     else
        echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1,0,0,1,0,0); 
?>
     </td>
     <td nowrap align="center"><?=$STATUS_DESC?></td>
     <td nowrap align="center"> 	
<?
if($MY_FLAG!=1  && $HINT_FLAG!=1)
{
?>     	
       <a href="javascript:;" onClick="window.open('../add_diary.php?PLAN_ID=<?=$PLAN_ID?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');"><?=_("进度日志")?></a>
<?
}
?>
     </td>
   </tr>
<?
}
?>

<tr class="TableControl">
<td colspan="11" align="center">
    <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
</td>
</tr>

</table>
</body>

</html>
