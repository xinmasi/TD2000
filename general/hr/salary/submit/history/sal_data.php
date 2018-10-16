<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("工资数据查阅");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
 if ($SIGN=="0")$USER_ID=$_SESSION["LOGIN_USER_ID"];
 $query = "SELECT * from USER where USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME=$ROW["USER_NAME"];
 }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=sprintf(_("工资数据查阅（%s）"), $USER_NAME)?></span>
    </td>
  </tr>
</table>

<div align="center">

<?
 //-- 首先查询是否已查阅过数据 --
 if($RECALL=="")
 {
   if ($SIGN=="0")$USER_ID=$_SESSION["LOGIN_USER_ID"];
   $FLOW_ID = intval($FLOW_ID);
   $query="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     for($I=1;$I<=50;$I++)
     {
       $STR="S".$I;
       $$STR=format_money($ROW["$STR"]);
     }
     $MEMO=$ROW["MEMO"];
     $PENSION_BASE = $ROW["PENSION_BASE"];
     $PENSION_U = $ROW["PENSION_U"];     
     $PENSION_P = $ROW["PENSION_P"];     
     $MEDICAL_BASE = $ROW["MEDICAL_BASE"];     
     $MEDICAL_U = $ROW["MEDICAL_U"];     
     $MEDICAL_P = $ROW["MEDICAL_P"];     
     $FERTILITY_BASE = $ROW["FERTILITY_BASE"];     
     $FERTILITY_U = $ROW["FERTILITY_U"];
     $UNEMPLOYMENT_BASE = $ROW["UNEMPLOYMENT_BASE"];     
     $UNEMPLOYMENT_U = $ROW["UNEMPLOYMENT_U"];     
     $UNEMPLOYMENT_P = $ROW["UNEMPLOYMENT_P"];     
     $INJURIES_BASE = $ROW["INJURIES_BASE"];     
     $INJURIES_U = $ROW["INJURIES_U"];     
     $HOUSING_BASE = $ROW["HOUSING_BASE"];     
     $HOUSING_U = $ROW["HOUSING_U"];
     $HOUSING_P = $ROW["HOUSING_P"];
     $INSURANCE_DATE  = $ROW["INSURANCE_DATE"];
   }
 }

 //-- 生成查阅项目 --
 $query="select * from SAL_ITEM";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_COUNT++;

    $ITEM_ID=$ROW["ITEM_ID"];
    $ITEM_NAME=$ROW["ITEM_NAME"];

    $S_ID="S".$ITEM_ID;
    $S_NAME=$S_ID."_NAME";
    
    if($ITEM_COUNT==1)
    {
?>

    <table width="500px" class="TableList">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center" width="110"><?=$ITEM_NAME?></td><td nowrap align="center"><?=$$S_ID?>
      </td>   
    </tr>
<?
 }
 if($ITEM_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("工资项目")?></td>
      <td nowrap align="center"><?=_("金额")?></td>
    </thead>
    </table>
<?
 }
 else
    message("",_("尚未定义工资项目，请与财务主管联系！"));

//保险
$query = "SELECT YES_OTHER from HR_INSURANCE_PARA";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $YES_OTHER=$ROW["YES_OTHER"];

if($YES_OTHER==1)
{
?>
<table width="500px" class="TableList">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("保险项")?></td>
    <td nowrap align="center"><?=_("金额")?></td>
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("养老保险")?></td>
    <td nowrap align="center"><?=$PENSION_BASE?></td>                     
  </tr>
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("单位养老")?></td>
    <td nowrap align="center"><?=$PENSION_U?></td>                     
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("个人养老")?></td>
    <td nowrap align="center"><?=$PENSION_P?></td>                     
  </tr>  
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("医疗保险")?></td>
    <td nowrap align="center"><?=$MEDICAL_BASE?></td>                     
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("单位医疗")?></td>
    <td nowrap align="center"><?=$MEDICAL_U?></td>                     
  </tr>
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("个人医疗")?></td>
    <td nowrap align="center"><?=$MEDICAL_P?></td>                     
  </tr>  
    
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("生育保险")?></td>
    <td nowrap align="center"><?=$FERTILITY_BASE?></td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("单位生育")?></td>
    <td nowrap align="center"><?=$FERTILITY_U?></td>                     
  </tr>    
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("失业保险")?></td>
    <td nowrap align="center"><?=$UNEMPLOYMENT_BASE?></td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("单位失业")?></td>
    <td nowrap align="center"><?=$UNEMPLOYMENT_U?></td>                     
  </tr>    
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("个人失业")?></td>
    <td nowrap align="center"><?=$UNEMPLOYMENT_P?></td>                     
  </tr>      
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("工伤保险")?></td>
    <td nowrap align="center"><?=$INJURIES_BASE?></td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("单位工伤")?></td>
    <td nowrap align="center"><?=$INJURIES_U?></td>                     
  </tr>      
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("住房公积金")?></td>
    <td nowrap align="center"><?=$HOUSING_BASE?></td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="110"><?=_("单位住房")?></td>
    <td nowrap align="center"><?=$HOUSING_U?></td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="110"><?=_("个人住房")?></td>
    <td nowrap align="center"><?=$HOUSING_P?></td>                     
  </tr>
  <tr class="TableData1">
    <td nowrap align="center" width="110"><?=_("投保日期")?></td>
    <td nowrap align="center"><?=$INSURANCE_DATE?></td>
  </tr>               
<?     
}
?>
    <tr class="TableData">
      <td nowrap align="center"><?=_("备注")?></td>
      <td nowrap align="left"><?=$MEMO?></td>
    </tr>
    </table>
</div>
<?
Button_Back();
?>
</body>
</html>