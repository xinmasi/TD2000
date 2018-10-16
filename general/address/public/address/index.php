<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
 if(!$PAGE_SIZE)
   if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("PUBLIC_ADDRESS", 10);
 $start=intval($start);
//该组有没有维护权限
$PRIV_FLAG = 0;
if($_SESSION["LOGIN_USER_PRIV"]!=1)
  $query = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where GROUP_ID='$GROUP_ID' and  (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER))";
else
  $query = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PRIV_FLAG = 1;

if($_SESSION["LOGIN_USER_PRIV"]==1 && $GROUP_ID==0)
   $PRIV_FLAG = 1;


$HTML_PAGE_TITLE = _("通讯簿");
include_once("inc/header.inc.php");
?>


<script>
function add_detail(ADD_ID)
{
 URL="add_detail.php?ADD_ID="+ADD_ID;
 myleft=(screen.availWidth-750)/2;
 window.open(URL,"detail","height=650,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function delete_add(ADD_ID)
{
 msg='<?=_("确认要删除该联系人吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?ADD_ID=" + ADD_ID + "&start=<?=$start?>&GROUP_ID=<?=$GROUP_ID?>";
  window.location=URL;
 }
}

function order_by(field,asc_desc)
{
 window.location="index.php?start=<?=$start?>&GROUP_ID=<?=$GROUP_ID?>&PUBLIC_FLAG=<?=$PUBLIC_FLAG?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("address_select").length;i++)
 {
   if(allbox.checked)
      document.getElementsByName("address_select").item(i).checked=true;
   else
      document.getElementsByName("address_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(allbox.checked)
      document.getElementsByName("address_select").checked=true;
   else
      document.getElementsByName("address_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      allbox.checked=false;
}

function delete_address()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("address_select").length;i++)
  {

      el=document.getElementsByName("address_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("address_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要删除联系人，请至少选择其中一个。")?>");
     return;
  }

  msg='<?=_("确认要删除所选联系人吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?start=<?=$start?>&GROUP_ID=<?=$GROUP_ID?>&DELETE_STR="+ delete_str;
    location=url;
  }
}

function get_checked()
{
  checked_str="";
  for(i=0;i<document.getElementsByName("address_select").length;i++)
  {

      el=document.getElementsByName("address_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("address_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}

function change_group()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("要转移联系人分组，请至少选择其中一条数据。")?>");
     return;
  }

  group_id=document.getElementsByName("GROUP_ID").value;
  url="change_group.php?DELETE_STR="+ delete_str +"&GROUP_ID="+group_id+"&GROUP_ID_OLD=<?=$GROUP_ID?>&start=<?=$start?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
  location=url;
}
</script>

<body class="bodycolor">
<?
 $query = "SELECT * from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $GROUP_NAME=$ROW["GROUP_NAME"];
 if($GROUP_ID==0)
    $GROUP_NAME=_("默认");
    
 if(!isset($TOTAL_ITEMS))
 {
   $query = "SELECT count(*) from ADDRESS where USER_ID='' and GROUP_ID='$GROUP_ID'";
   $TOTAL_ITEMS=0;
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
 }
?>
<div class="PageHeader">
   <div class="title"><?=_("公共地址簿")?>(<?=$GROUP_NAME?>)</div>
<?
if($PRIV_FLAG==1)
{
?>
   <div class="header-left">
     <a class="ToolBtn" href="new.php?GROUP_ID=<?=$GROUP_ID?>"><span><?=_("新建联系人")?></span></a>
   </div>
<?
}
?>
   <div class="header-right"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></div>
</div>
<?
if($ASC_DESC=="")
   $ASC_DESC="0";
if($FIELD=="")
   $FIELD="PSN_NO";
   
$SYS_PARA_ARRAY = get_sys_para("ADDRESS_SHOW_FIELDS");
$HRMS_OPEN_FIELDS=$SYS_PARA_ARRAY["ADDRESS_SHOW_FIELDS"];
if($HRMS_OPEN_FIELDS=="")
   $HRMS_OPEN_FIELDS="PSN_NAME,SEX,DEPT_NAME,TEL_NO_DEPT,TEL_NO_HOME,MOBIL_NO,EMAIL,|"._("姓名,性别,单位名称,工作电话,家庭电话,手机,电子邮件,");

if($HRMS_OPEN_FIELDS=="" || $HRMS_OPEN_FIELDS=="|")
   $HRMS_OPEN_FIELDS="PSN_NAME,SEX,DEPT_NAME,TEL_NO_DEPT,TEL_NO_HOME,MOBIL_NO,EMAIL,|"._("姓名,性别,单位名称,工作电话,家庭电话,手机,电子邮件,");
$OPEN_ARRAY=explode("|",$HRMS_OPEN_FIELDS);
$FIELD_ARRAY=explode(",",$OPEN_ARRAY[0]);
$NAME_ARRAY=explode(",",$OPEN_ARRAY[1]);

$query = "SELECT * from ADDRESS where USER_ID='' and GROUP_ID='$GROUP_ID'";
$query .= " order by $FIELD";
if($ASC_DESC=="1")
   $query .= " desc";
else
   $query .= " asc";
$query .= " limit $start,$PAGE_SIZE";

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";


 //============================ 公共地址簿 =======================================
 $cursor= exequery(TD::conn(),$query);
 $ADD_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $ADD_COUNT++;

    $ADD_ID=$ROW["ADD_ID"];
    for($I=0;$I<count($FIELD_ARRAY);$I++)
    {
      if($FIELD_ARRAY[$I]=="")
         continue;
      $$FIELD_ARRAY[$I]=$ROW["$FIELD_ARRAY[$I]"];
     
      if($FIELD_ARRAY[$I]=="SEX")
      {
         if($$FIELD_ARRAY[$I]=="0")
           $$FIELD_ARRAY[$I]=_("男");
         else if($$FIELD_ARRAY[$I]=="1")
           $$FIELD_ARRAY[$I]=_("女");
         else
           $$FIELD_ARRAY[$I]="";  
      }
      
      if($FIELD_ARRAY[$I]=="MOBIL_NO")
      {
         $MOBIL_NO_STR.=$$FIELD_ARRAY[$I].",";   
      }
    }

    if($ADD_COUNT==1)
    {
?>
    <table class="TableList" width="100%">
<?
    }
?>
    <tr class="TableData">
<?
    if($PRIV_FLAG==1)
    {
?>      	
    	<td align="center"><input type="checkbox" name="address_select" value="<?=$ADD_ID?>" onClick="check_one(self);"></td>
<?
    }
      $col_count=0;
      for($I=0;$I<count($FIELD_ARRAY);$I++)
      {
         if($FIELD_ARRAY[$I]=="")
            continue;
         $col_count++;
         switch($FIELD_ARRAY[$I])
         {
           case "PSN_NAME":           
               echo "<td nowrap align='center'><a href='javascript:add_detail(".$ADD_ID.");'>".$$FIELD_ARRAY[$I]."</a></td>";
               break;
           case "MOBIL_NO":
               echo "<td nowrap align='center'><A href='/general/mobile_sms/?TO_ID1=".$$FIELD_ARRAY[$I].",'>".$$FIELD_ARRAY[$I]."</A></td>";
               break;
           case "EMAIL":
               echo "<td nowrap align='center'><a href='mailto:".$$FIELD_ARRAY[$I]."'>".$$FIELD_ARRAY[$I]."</a></td>";
               break;
           default:
               echo "<td nowrap align='center'>".$$FIELD_ARRAY[$I]."</td>";
               break;
         }
      }
      ?>
      <td nowrap align="center" width="100">
          <a href="javascript:add_detail('<?=$ADD_ID?>');"> <?=_("详情")?></a>
<?
if($PRIV_FLAG==1)
{
?>          
          <a href="edit.php?ADD_ID=<?=$ADD_ID?>&start=<?=$start?>"> <?=_("编辑")?></a>
          <a href="javascript:delete_add(<?=$ADD_ID?>);"> <?=_("删除")?></a>          
<?
}
?>
      </td>
    </tr>
<?
 }

 if($ADD_COUNT==0)
 {
   Message("",_("通讯簿尚无记录"));
   exit;
 }
 else
 {
?>
   <thead class="TableHeader">
<?
    if($PRIV_FLAG==1)
    {
?>   	
   	   <td nowrap align="center"><?=_("选择")?></td>
<?
   }
   for($I=0;$I<count($FIELD_ARRAY);$I++)
   {
     if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
         continue;
     
     switch($FIELD_ARRAY[$I])
     {
       case "PSN_NAME":           
       ?>
            <td nowrap align="center" onClick="order_by('PSN_NAME','<?if($FIELD=="PSN_NAME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("姓名")?></u><?if($FIELD=="PSN_NAME"||$FIELD=="") echo $ORDER_IMG;?></td>
       <?
           break;
       case "MOBIL_NO":
           echo "<td nowrap align='center'>"._("手机")." <a href='javascript:form1.submit();'>"._("群发")."</a></td>";
           break;
       case "SEX":
       ?>
            <td nowrap align="center" onClick="order_by('SEX','<?if($FIELD=="SEX") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("性别")?></u><?if($FIELD=="SEX") echo $ORDER_IMG;?></td>
       <?   
           break;
       case "DEPT_NAME":
       ?>
            <td nowrap align="center" onClick="order_by('DEPT_NAME','<?if($FIELD=="DEPT_NAME") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("单位名称")?></u><?if($FIELD=="DEPT_NAME") echo $ORDER_IMG;?></td>
       <?   
           break;
        case "PSN_NO":
       ?>
       <td nowrap align="center" onclick="order_by('PSN_NO','<?if($FIELD=="PSN_NO") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("排序号")?></u><?if($FIELD=="PSN_NO") echo $ORDER_IMG;?></td>
       <?  
           break;    
       default:
           echo "<td nowrap align='center'>".$NAME_ARRAY[$I]."</td>";
           break;
     }
   }
   ?>   
      <td nowrap align="center"><?=_("操作")?></td>
   </thead>
<?
 //============================ 今日生日 =======================================
 $CUR_MON=date("m",time());
 $CUR_DAY=date("d",time());
 $CUR_DAY=intval($CUR_DAY);
 $CUR_MON=intval($CUR_MON);
 $query = "SELECT * from ADDRESS where USER_ID='' and Month(BIRTHDAY)='$CUR_MON' and DAYOFMONTH(BIRTHDAY)='$CUR_DAY'";
 $cursor= exequery(TD::conn(),$query);
 $ADD_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $ADD_COUNT++;

    $ADD_ID=$ROW["ADD_ID"];
    $PSN_NAME=$ROW["PSN_NAME"];
    $SEX=$ROW["SEX"];

    switch($SEX)
    {
       case "0":
         $SEX=_("男");
         break;
       case "1":
         $SEX=_("女");
         break;
    }

    $PERSON_STR.="<A href='javascript:add_detail($ADD_ID);'>$PSN_NAME($SEX)</A>&nbsp&nbsp&nbsp&nbsp";
    
  }

if($ADD_COUNT>=1)
{
?>
   <tr class="TableData">
      <td nowrap align="center" style="color:#46A718" ><?=_("今天生日：")?></td>
      <td nowrap align="center" colspan="20">
      <marquee style="color:#FF6600;" behavior=scroll scrollamount=3 scrolldelay=120 onmouseover='this.stop()' onmouseout='this.start()' border=0><?=$PERSON_STR?></marquee>
      </td>
   </tr>
<?
}

if($PRIV_FLAG==1)
{
?> 
<tr class="TableControl">
<td colspan="<?=($col_count+3)?>">
	 &nbsp;<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;&nbsp;
    <input type="button"  value="<?=_("删除")?>" class="SmallButton" onClick="delete_address();">&nbsp;&nbsp;
    <?=_("转到")?>
   <select name="GROUP_ID" onChange="change_group();" class="SmallSelect">
<?
 if($_SESSION["LOGIN_USER_PRIV"]==1) 
 {
?>  	
     <option value=0 <?if($GROUP_ID==0) echo "selected";?>><?=_("默认")?></option>
<?
 }
 if($_SESSION["LOGIN_USER_PRIV"]!=1)
   $query = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where USER_ID='' and (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER)) order by ORDER_NO asc,GROUP_NAME asc";
 else
   $query = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";

 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID1=$ROW["GROUP_ID"];
    $GROUP_NAME1=$ROW["GROUP_NAME"];
?>
     <option value=<?=$GROUP_ID1?> <?if($GROUP_ID==$GROUP_ID1) echo "selected";?>><?=$GROUP_NAME1?></option>
<?
  }
?>
   </select>
</td>
</tr>
<?
} 
}
?>
</table>
<form name="form1" method="post" action="/general/mobile_sms/new/index.php">
<input type="hidden" name="TO_ID1" value="<?=$MOBIL_NO_STR?>">
</form>
</body>
</html>