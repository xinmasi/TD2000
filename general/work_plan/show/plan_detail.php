<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("工作计划详情");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
function close_this_new()
{
  TJF_window_close();
}
</script>

<?
//============================ 显示已发布公告 =======================================
$CUR_DATE=date("Y-m-d",time());
$PLAN_ID=intval($PLAN_ID);
$query = "SELECT * from WORK_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PLAN_ID=$ROW["PLAN_ID"];
   $NAME=$ROW["NAME"];
   $CONTENT=$ROW["CONTENT"];
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $TYPE=$ROW["TYPE"];
   $TO_ID=$ROW["TO_ID"];
   $MANAGER=$ROW["MANAGER"];
   $PARTICIPATOR=$ROW["PARTICIPATOR"];
   $CREATOR=$ROW["CREATOR"];
   $CREATE_DATE=$ROW["CREATE_DATE"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ATTACHMENT_COMMENT=$ROW["ATTACHMENT_COMMENT"];
   $REMARK=$ROW["REMARK"];
   $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];
   $TO_PERSON_ID=$ROW["TO_PERSON_ID"];
   $PUBLISH=$ROW["PUBLISH"];   
   $OPINION_LEADER=$ROW["OPINION_LEADER"]; 
   
   //$CONTENT=str_replace("\n","<br>",$CONTENT);

   $query = "SELECT * from PLAN_TYPE where TYPE_ID='$TYPE'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW1=mysql_fetch_array($cursor1))
      $TYPE_DESC=$ROW1["TYPE_NAME"];
   else
      $TYPE_DESC="";

   if($TO_ID=="ALL_DEPT")
      $TO_NAME=_("全体部门");
   else
   {
     $TO_NAME="";
     $TOK=strtok($TO_ID,",");
     while($TOK!="")
     {
       if($TO_NAME!="")
          $TO_NAME.=_("，");
          
       $query1="select * from DEPARTMENT where DEPT_ID='$TOK'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $TO_NAME.=$ROW["DEPT_NAME"];

       $TOK=strtok(",");
     }
   }

   $TO_PERSON_NAME="";
   $TOK=strtok($TO_PERSON_ID,",");
   while($TOK!="")
   {
     if($TO_PERSON_NAME!="")
        $TO_PERSON_NAME.=_("，");
     $query1="select * from USER where USER_ID='$TOK'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW1=mysql_fetch_array($cursor1))
     {
        $DEPT_ID=$ROW1["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
        $TO_PERSON_NAME.= "<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW1["USER_NAME"]."</u>";
     }

     $TOK=strtok(",");
   }

   $PARTICIPATOR_NAME="";
   $TOK=strtok($PARTICIPATOR,",");
   while($TOK!="")
   {
     if($PARTICIPATOR_NAME!="")
        $PARTICIPATOR_NAME.=_("，");
     $query1="select * from USER where USER_ID='$TOK'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
        $PARTICIPATOR_NAME.= "<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW["USER_NAME"]."</u>";
     }

     $TOK=strtok(",");
   }
   $PARTICIPATOR_NAME=substr($PARTICIPATOR_NAME,0,-1);

   $MANAGE_NAME="";
   $TOK=strtok($MANAGER,",");
   while($TOK!="")
   {
     if($MANAGE_NAME!="")
        $MANAGE_NAME.=_("，");
     $query1="select * from USER where USER_ID='$TOK'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
        $MANAGE_NAME.= "<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW["USER_NAME"]."</u>";
     }

     $TOK=strtok(",");
   }
   $MANAGE_NAME=substr($MANAGE_NAME,0,-1);

   $OPINION_LEADER_NAME="";
   $TOK=strtok($OPINION_LEADER,",");
   while($TOK!="")
   {
     $query1="select USER_NAME from USER where USER_ID='$TOK'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW1=mysql_fetch_array($cursor1))
        $OPINION_LEADER_NAME.=$ROW1["USER_NAME"].",";
  
     $TOK=strtok(",");
   }

   $query1="select * from USER where USER_ID='$CREATOR'";
   $cursor= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor))
   {
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=dept_long_name($DEPT_ID);
      $CREATOR_NAME= "<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW["USER_NAME"]."</u>,";
   }
   $CREATOR_NAME=substr($CREATOR_NAME,0,-1);

   $MY_FLAG=0;
if($PUBLISH==1)  //发布
{
   if($SUSPEND_FLAG==1)
   {
      if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
         $STATUS_DESC=_("未开始");
      else
         $STATUS_DESC= "<font color='#00AA00'><b>"._("进行中")."</b></font>";
      
      if($END_DATE!="0000-00-00")
      {
         if(compare_date($CUR_DATE,$END_DATE) >= 0)
         {
            $STATUS_DESC= "<font color='#FF0000'><b>">_("已结束")."</b></font>";
            $MY_FLAG=1;
         }  
      }
      else
         $END_DATE="";
   }
   else
   {
      $STATUS_DESC= "<font color='#FF0000'><b>"._("暂停")."</b></font>";
      $MY_FLAG=1;
   }
}
else //未发布
{
	    $MY_FLAG=1;
      $STATUS_DESC= "<font color='#FF0000'><b>"._("未发布")."</b></font>";	
}
   if($WORK_PLAN_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("工作计划详情")?> - <?=$NAME?></span><br>
    </td>
    </tr>
</table>

<table class="TableBlock"  width="100%">
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("计划名称")?></td>
      <td align="left" class="TableData"><?=$NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("计划内容")?> </td>
      <td align="left" class="TableData Content" style="word-break:break-all "> <?=$CONTENT?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("开始时间")?></td>
      <td nowrap align="left" class="TableData"><?=$BEGIN_DATE?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("结束时间")?></td>
      <td nowrap align="left" class="TableData"><?=$END_DATE?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("计划类别")?></td>
      <td nowrap align="left" class="TableData"><?=$TYPE_DESC?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("开放部门")?></td>
      <td align="left" class="TableData"><?=$TO_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("开放人员")?></td>
      <td align="left" class="TableData"><?=$TO_PERSON_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("负责人")?></td>
      <td nowrap align="left" class="TableData"><?=$MANAGE_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("参与人")?></td>
      <td align="left" class="TableData"><?=$PARTICIPATOR_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("创建人")?> </td>
      <td nowrap align="left" class="TableData"><?=$CREATOR_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("批注领导")?> </td>
      <td nowrap align="left" class="TableData"><?=$OPINION_LEADER_NAME?></td>
  </tr>  
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("创建日期")?> </td>
      <td nowrap align="left" class="TableData"><?=$CREATE_DATE?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("进度详情")?> </td>
      <td nowrap align="left" class="TableData">
<?
if($MY_FLAG!=1)
{
?>        	
        <a href="javascript:;" onClick="window.open('add_diary.php?PLAN_ID=<?=$PLAN_ID?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');"><?=_("查看进度日志")?></a>      
<?
}
?>
        <a href="javascript:;" onClick="window.open('progress_map.php?PLAN_ID=<?=$PLAN_ID?>','','status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=0,top=0,resizable=yes');"><?=_("查看进度图")?></a>      
      </td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("领导批注")?> </td>
      <td nowrap align="left" class="TableData">
        <a href="javascript:;" onClick="window.open('add_opinion.php?PLAN_ID=<?=$PLAN_ID?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');"><?=_("查看领导批注")?></a>      
      </td>
  </tr> 
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("附件文件")?></td>
      <td nowrap align="left" class="TableData">
<?
if($ATTACHMENT_NAME=="")
   echo _("无");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,0,0,1,1,0);
?>
      </td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("附件说明")?></td>
      <td nowrap align="left" class="TableData"><?=$ATTACHMENT_COMMENT?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("备注")?></td>
      <td align="left" class="TableData"><?=$REMARK?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent" width="80"><?=_("状态")?></td>
      <td nowrap align="left" class="TableData"><?=$STATUS_DESC?></td>
  </tr>
  <tr class="TableControl">
      <td colspan="9" align="center">
           <input type="button" value="<?=_("添加收藏")?>" class="BigButton" onClick="AddFav('<?=_("工作计划")?> - <?=$NAME?>','/general/work_plan/show/plan_detail.php?PLAN_ID=<?=$PLAN_ID?>','1');" title="<?=_("添加收藏")?>">&nbsp;&nbsp;&nbsp;
           <input type="button" value="<?=_("打印")?>" class="BigButton" onClick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>">&nbsp;&nbsp;&nbsp;
           <?
              if($MY_FLAG!=1)
              {
              ?>        	
                 <input type="button" value="<?=_("撰写进度日志")?>" class="BigButton" onClick="window.open('add_diary.php?PLAN_ID=<?=$PLAN_ID?>','','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');" title="<?=_("撰写进度日志")?>">&nbsp;&nbsp;&nbsp;
              <?
              }
           ?>           
           <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="close_this_new();" title="<?=_("关闭窗口")?>">
      </td>
  </tr>
</table>
<?
}
else
      Message("",_("未找到相应记录！"));
?>



</body>
</html>