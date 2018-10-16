<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

include_once("inc/header.inc.php");
?>

<style>
.menulines{}
</style>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script Language="JavaScript">
var parent_window = getOpenner();
var to_form = parent_window.<?=$FORM_NAME?>;
var to_id =   to_form.<?=$TO_ID?>;

function click_dept(dept_id,td_id)
{
  TO_VAL=to_id.value;
  targetelement=$(td_id);

  if(TO_VAL.indexOf(","+dept_id+",")>0 || TO_VAL.indexOf(dept_id+",")==0)
  {
    if(TO_VAL.indexOf(dept_id+",")==0)
    {
       to_id.value=to_id.value.replace(dept_id+",","");
       borderize_off(targetelement);
    }
    if(TO_VAL.indexOf(","+dept_id+",")>0)
    {
       to_id.value=to_id.value.replace(","+dept_id+",",",");
       borderize_off(targetelement);
    }
  }
  else
  {
    to_id.value+=dept_id+",";
    borderize_on(targetelement);
  }
}

function borderize_on(targetelement)
{
 color="#003FBF";
 targetelement.style.borderColor="black";
 targetelement.style.backgroundColor=color;
 targetelement.style.color="white";
 targetelement.style.fontWeight="bold";
}

function borderize_off(targetelement)
{
  targetelement.style.backgroundColor="";
  targetelement.style.borderColor="";
  targetelement.style.color="";
  targetelement.style.fontWeight="";
}

function begin_set()
{
  TO_VAL=to_id.value;
  
  for (step_i=0; step_i<allElements.length; step_i++)
  {
    if(allElements[step_i].className=="menulines")
    {
       dept_id=allElements[step_i].title;
       if(TO_VAL.indexOf(","+dept_id+",")>0 || TO_VAL.indexOf(dept_id+",")==0)
          borderize_on(allElements[step_i]);
    }
  }
}

function add_all()
{
  TO_VAL=to_id.value;
  for (step_i=0; step_i<allElements.length; step_i++)
  {
    if(allElements[step_i].className=="menulines")
    {
       dept_id=allElements[step_i].title;

       if(TO_VAL.indexOf(","+dept_id+",")<=0 && TO_VAL.indexOf(dept_id+",")!=0)
       {
         to_id.value+=dept_id+",";
         borderize_on(allElements[step_i]);
       }
    }
  }
}

function del_all()
{
  for (step_i=0; step_i<allElements.length; step_i++)
  {
    TO_VAL=to_id.value;
    if(allElements[step_i].className=="menulines")
    {
       dept_id=allElements[step_i].title;
       
       if(TO_VAL.indexOf(dept_id+",")==0)
       {
          to_id.value=to_id.value.replace(dept_id+",","");
       }
       if(TO_VAL.indexOf(","+dept_id+",")>0)
       {
          to_id.value=to_id.value.replace(","+dept_id+",",",");
       }
       borderize_off(allElements[step_i]);
    }
  }
}

</script>


<body class="bodycolor" onload="begin_set()">
<?
 //-----------先组织SQL语句-----------
 $query = "SELECT * from USER_PRIV where USER_PRIV='".intval($_SESSION["LOGIN_USER_PRIV"])."'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $PRIV_NO=$ROW["PRIV_NO"];

 if($USER_ID!="")
    $WHERE_STR.=" and USER_ID like '%".$USER_ID."%'";

 if($USER_NAME!="")
    $WHERE_STR.=" and USER_NAME like '%".$USER_NAME."%'";

 if($BYNAME!="")
    $WHERE_STR.=" and BYNAME like '%".$BYNAME."%'";

 if($DEPT_ID!="")
    $WHERE_STR.=" and USER.DEPT_ID='$DEPT_ID'";

 if($DEPT_ID!="0")
    $WHERE_STR.=" and DEPARTMENT.DEPT_ID=USER.DEPT_ID";

 if($USER_PRIV!="")
    $WHERE_STR.=" and USER.USER_PRIV='$USER_PRIV'";

 $WHERE_STR.=" and USER.EMAIL!=''"; //仅查出在个人资料中设置了外部邮箱的OA用户 by dq 090606

 $USER_COUNT=0;

 $query = "SELECT * from USER,USER_PRIV";
 if($DEPT_ID!="0")
    $query .= ",DEPARTMENT";
 if($_SESSION["LOGIN_USER_PRIV"]!="1")
    $query .= " where USER.USER_PRIV=USER_PRIV.USER_PRIV and USER_PRIV.PRIV_NO>'$PRIV_NO' and USER_PRIV.USER_PRIV!=1 ".$WHERE_STR." order by ";
 else
    $query .= " where USER.USER_PRIV=USER_PRIV.USER_PRIV ".$WHERE_STR." order by ";
 if($LAST_VISIT_TIME!="")
    $query .= "LAST_VISIT_TIME ".$LAST_VISIT_TIME.",";
 if($DEPT_ID!="0")
    $query .= "DEPT_NO,";
 $query .= "PRIV_NO,USER_NO,USER_NAME";
 
 $query_count = str_replace("SELECT *", "SELECT COUNT(*)", $query);
 $cursor_count = exequery(TD::conn(), $query_count);
 if ( $ROW = mysql_fetch_array($cursor_count) )
   $USER_COUNT = $ROW[0];
 
 if ($USER_COUNT == 0) {
 	 Message("",_("无符合条件的记录"),"blank");
?> 	 
<div align="center">
  <input type="button" value="<?=_("返回")?>" class="SmallButton" onclick="location='query_user_cond.php?FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>&FORM_NAME=<?=$FORM_NAME?>'" title="<?=_("查询用户")?>" name="button">
</div>
<? 
 } else {
?>
   <table class="TableBlock" width="95%" align="center">
     <tr class="TableControl">
       <td onclick="javascript:add_all();" style="cursor:pointer" align="center" colspan="2"><?=_("全部添加")?></td>
     </tr>
     <tr class="TableControl">
       <td onclick="javascript:del_all();" style="cursor:pointer" align="center" colspan="2"><?=_("全部删除")?></td>
     </tr>
<?
   $cursor= exequery(TD::conn(), $query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
      $BYNAME=$ROW["BYNAME"];
      $PASSWORD=$ROW["PASSWORD"];
      $DEPT_ID=$ROW["DEPT_ID"];
      $USER_PRIV=$ROW["USER_PRIV"];
      $POST_PRIV=$ROW["POST_PRIV"];
      $LAST_VISIT_TIME=$ROW["LAST_VISIT_TIME"];
      $EMAIL=$ROW["EMAIL"];
      $MOBIL_NO=$ROW["MOBIL_NO"];
      $$FIELD=$ROW["$FIELD"];
  
      $IDLE_TIME_DESC="";
      if($LAST_VISIT_TIME=="0000-00-00 00:00:00")
      {
         $LAST_VISIT_TIME="";
      }
      else
      {
         $IDLE_TIME=time()-strtotime($LAST_VISIT_TIME)-MYOA_ONLINE_REF_SEC;
         if(floor($IDLE_TIME/86400)>0)
            $IDLE_TIME_DESC.=floor($IDLE_TIME/86400)._("天");
         if(floor(($IDLE_TIME%86400)/3600)>0)
            $IDLE_TIME_DESC.=floor(($IDLE_TIME%86400)/3600)._("小时");
         if(floor(($IDLE_TIME%3600)/60)>0)
            $IDLE_TIME_DESC.=floor(($IDLE_TIME%3600)/60)._("分");
            
         if($IDLE_TIME_DESC=="")
            $IDLE_TIME_DESC=_("0分");
      }
      //$LAST_VISIT_TIME=strtok($LAST_VISIT_TIME," ");
  
      if(!is_dept_priv($DEPT_ID)) {
         continue;
      
    }
  
      $query1 = "SELECT * from DEPARTMENT where DEPT_ID=" .$DEPT_ID;
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
         $DEPT_NAME=$ROW["DEPT_NAME"];
      else
         $DEPT_NAME=_("离职人员/外部人员");
  
      if($POST_PRIV=="0")
         $POST_PRIV=_("本部门");
      else if($POST_PRIV=="1")
         $POST_PRIV=_("全体");
      else if($POST_PRIV=="2")
         $POST_PRIV=_("指定部门");
  
      $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
           $USER_PRIV=$ROW["PRIV_NAME"];
  
      $DEPT_LONG_NAME=dept_long_name($DEPT_ID);
  
  ?>
    <tr class="TableData" style="cursor:pointer" align="center">
      <td class="menulines" id="<?=$USER_ID?>" onclick="javascript:click_dept('<?=$$FIELD?>','<?=$USER_ID?>')" title="<?=$$FIELD?>"><?=$USER_NAME?>(<?=$DEPT_LONG_NAME?>)(<?=$$FIELD?>)</a></td>
    </tr>
  <?
   }
  ?>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="button" value="<?=_("返回")?>" class="SmallButton" onclick="location='query_user_cond.php?FIELD=<?=$FIELD?>&TO_ID=<?=$TO_ID?>&FORM_NAME=<?=$FORM_NAME?>'" title="<?=_("查询用户")?>" name="button">
    </td>
   </tr>
  </table>
  <? 
 }
?>
</body>
</html>
