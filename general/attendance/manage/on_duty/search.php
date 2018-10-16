<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");


$HTML_PAGE_TITLE = _("值班查询");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function check_all()
{
 if(!document.all("email_select"))
    return;
 for (i=0;i<document.all("email_select").length;i++)
 {
   if(document.all("allbox").checked)
      document.all("email_select").item(i).checked=true;
   else
      document.all("email_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.all("allbox").checked)
      document.all("email_select").checked=true;
   else
      document.all("email_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.all("allbox").checked=false;
}
function delete_mail()
{
  delete_str="";
  for(i=0;i<document.all("email_select").length;i++)
  {

      el=document.all("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.all("email_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要删除排班记录，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选排班记录吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>&BOX_ID=<?=$BOX_ID?>";
    location=url;
  }
}

function show_reader(DIA_ID)
{
  URL="show_reader.php?DIA_ID="+DIA_ID;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"show_reader","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>


<body class="bodycolor">
<?
//----------- 合法性校验 ---------
if($ZBSJ_B!="")
{
  $TIME_OK=is_date($ZBSJ_B);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($ZBSJ_E!="")
{
  $TIME_OK=is_date($ZBSJ_E);

  if(!$TIME_OK)
  { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=_("值班查询结果")?></span>
    </td>
  </tr>
</table>

<?
$WHERE_STR="";
if($TO_ID!="" && $TO_ID!="ALL_DEPT")
   $WHERE_STR = " and find_in_set(ZHIBANREN_DEPT,'$TO_ID')";
if($PAIBAN_TYPE!="")
   $WHERE_STR .= " and PAIBAN_TYPE='$PAIBAN_TYPE'";
if($ZHIBAN_TYPE!="")
   $WHERE_STR .= " and ZHIBAN_TYPE='$ZHIBAN_TYPE'";  
if($ZBSJ_B!="")
{
   $ZBSJ_B.=" 00:00:01";
   $WHERE_STR .= " and ZBSJ_B >= '$ZBSJ_B'";
}
if($ZBSJ_E!="")
{
   $ZBSJ_E.=" 23:59:59";
   $WHERE_STR .= " and ZBSJ_E <= '$ZBSJ_E'";
}
if($ZBYQ!="")
   $WHERE_STR .= " and ZBYQ like '%$ZBYQ%'";
if($BEIZHU!="")
   $WHERE_STR .= " and BEIZHU like '%$BEIZHU%'";  
  

//============================ 显示排班记录 =======================================
$query = "SELECT * from ZBAP_PAIBAN where 1='1' ".$WHERE_STR;
$query .= " order by ZBSJ_B desc";
$cursor= exequery(TD::conn(),$query);
$PAIBAN_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $PAIBAN_COUNT++;

   $PAIBAN_ID=$ROW["PAIBAN_ID"];
   $ZHIBANREN=$ROW["ZHIBANREN"];
   $PAIBAN_TYPE=$ROW["PAIBAN_TYPE"];
   $ZHIBAN_TYPE=$ROW["ZHIBAN_TYPE"];
   $ZB_RZ=$ROW["ZB_RZ"];   
   $ZBSJ_B=$ROW["ZBSJ_B"];
   $ZBSJ_E=$ROW["ZBSJ_E"]; 
   $ZBYQ=$ROW["ZBYQ"];
   $BEIZHU=$ROW["BEIZHU"]; 
   $PAIBAN_APR=$ROW["PAIBAN_APR"]; 
   $ANPAI_TIME=$ROW["ANPAI_TIME"];  
   $PAIBAN_TYPE_NAME = get_code_name($PAIBAN_TYPE,"PAIBAN_TYPE");
   $ZHIBAN_TYPE_NAME = get_code_name($ZHIBAN_TYPE,"ZHIBAN_TYPE");
   
   if($PAIBAN_COUNT==1)
   {
?>
   <table class="TableList" width="95%" align="center">
<?
   }
   if($PAIBAN_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
   <tr class="<?=$TableLine?>">
     <td align="center"><?=substr(GetUserNameByID($ZHIBANREN),0,-1)?></td>   	
     <td align="center"><?=substr($ZBSJ_B,0,16)?></td>
     <td align="center"><?=substr($ZBSJ_E,0,16)?></td>
     <td nowrap><?=$PAIBAN_TYPE_NAME?></td>
     <td nowrap><?=$ZHIBAN_TYPE_NAME?></td>
     <td align="left"><?=str_replace("\n","<br>",$ZBYQ)?></td>
     <td align="left"><?=str_replace("\n","<br>",$BEIZHU)?></td>
     <td align="left"><?=str_replace("\n","<br>",$ZB_RZ)?></td>  
   </tr>
<?
}//while

if($PAIBAN_COUNT==0)
{
  Message("",_("无符合条件的值班记录"));
}
else
{
?>
  <thead class="TableHeader">
  	<td nowrap width="80"><?=_("值班人")?></td>
    <td nowrap width="120"><?=_("值班开始时间")?></td>
    <td nowrap width="120"><?=_("值班结束时间")?></td>
    <td nowrap><?=_("排班类型")?></td>
    <td nowrap><?=_("值班类型")?></td>
    <td nowrap><?=_("值班要求")?></td>
    <td nowrap><?=_("备注")?></td>
    <td nowrap><?=_("值班日志")?></td> 
  </thead>
</table>
<?
}
?>
<br>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';">
</div>

</body>
</html>
