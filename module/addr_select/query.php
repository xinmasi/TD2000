<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

if($TO_ID=="" || $TO_ID=="undefined")
{
   $TO_ID="TO_ID";
}
if($FORM_NAME=="" || $FORM_NAME=="undefined")
   $FORM_NAME="form1";

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
	 if(allElements[step_i].id)
	 {
	   dept_id=allElements[step_i].className;
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
    if(allElements[step_i].id)
    {
       dept_id=allElements[step_i].className;
       
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
 $GROUP_ID_STR = $GROUP_ID_STR . "0";
 $query = "SELECT * from ADDRESS where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or USER_ID='') and GROUP_ID in ($GROUP_ID_STR) and $FIELD!=''
           and (PSN_NAME like '%$KWORD%' or EMAIL like '%$KWORD%' or TEL_NO_DEPT like '%$KWORD%' or TEL_NO_HOME like '%$KWORD%'
            or DEPT_NAME like '%$KWORD%' or OICQ_NO like '%$KWORD%' or ICQ_NO like '%$KWORD%') order by PSN_NAME";
 $cursor = exequery(TD::conn(),$query);//echo $query;
 if (mysql_num_rows($cursor) == 0) 
 {
    Message("",_("无符合条件的记录"),"blank");
    exit;
 }
?>
 <table class="TableBlock" width="95%" align="center">
   <tr class="TableControl">
     <td onclick="javascript:add_all();" style="cursor:pointer" align="center" colspan="2"><?=_("全部添加")?></td>
   </tr>
   <tr class="TableControl">
     <td onclick="javascript:del_all();" style="cursor:pointer" align="center" colspan="2"><?=_("全部删除")?></td>
   </tr>
<?
/**
一次查询数据库，存放数组
 */
$query_group = "SELECT GROUP_ID,GROUP_NAME from ADDRESS_GROUP";
$cursor_group = exequery(TD::conn(),$query_group);
$RESULT = array();
while($ROW=mysql_fetch_array($cursor_group))
{
	$RESULT[$ROW['GROUP_ID']] = $ROW['GROUP_NAME'];
}
 while($ROW=mysql_fetch_array($cursor))
 {
 	
    $ADD_ID=$ROW["ADD_ID"];
    $PSN_NAME=$ROW["PSN_NAME"];
    $GROUP_NAME=td_htmlspecialchars($RESULT[$ROW["GROUP_ID"]]);
    if($GROUP_NAME == '')$GROUP_NAME = '默认';
    
    $FIELD_VALUE=$ROW[$FIELD];
?>
  <tr class="TableData" style="cursor:pointer" align="center">
    <td class="<?=$FIELD_VALUE?>"id="<?=$ADD_ID?>" onclick="javascript:click_dept('<?=$FIELD_VALUE?>','<?=$ADD_ID?>')" title="<?=$GROUP_NAME.'  '.$FIELD_VALUE?>"><?=$PSN_NAME?>(<?=$FIELD_VALUE?>)</a></td>
  </tr>
<?
 }
?>
</body>
</html>
