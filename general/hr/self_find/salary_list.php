<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d");

$query = "SELECT ITEM_ID from SAL_ITEM";
$cursor= exequery(TD::conn(),$query);
$FLOW_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
        $STYLE=$STYLE.$ROW["ITEM_ID"].",";
}

$PAGE_SIZE = 20;
if(!isset($start) || $start=="")
   $start=0;

$query3 = "select * from SAL_DATA,sal_flow where sal_data.FLOW_ID=sal_flow.FLOW_ID and sal_flow.ISSEND='1' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor3= exequery(TD::conn(),$query3);
$STAFF_COUNT = mysql_num_rows($cursor3);


//判断密码
$PWD=$_POST["super_pass"];
$VIEW=$_POST["view"]==""?$_GET["view"]:$_POST["view"];
//============================ 显示已定义用户 =======================================

$HTML_PAGE_TITLE = _("人员工资查询");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<body class="bodycolor" leftmargin="0">

<?

if($VIEW!="1")
{
?>
<form method="post" action="salary_list.php">
  <table class="TableBlock" width="50%" align="center">  
    <tr class="TableContent">
        <td colspan=2>
         <b><?=_("说明：")?></b><?=_("OA登陆密码")?>
        </td>
    </tr>
      <tr>
        <td class="TableContent">
            <?=_("请输入密码：")?>
        </td>
        <td class="TableData">
            <input type="password" id="super_pass" name="super_pass"  class="BigInput" size="30" >
            <input type="hidden" name="view" value="1">
            <br>
        </td>
      </tr>
      <tr>
        <td nowrap class="TableControl" align="center" colspan="2">
          <input class="BigButton"  type="submit" value="<?=_("确定")?>"/>
        </td>
      </tr>
    </table>
</form>

<?
exit();
}
else
{
    $PARA_ARRAY=get_sys_para("LOGIN_USE_DOMAIN,DOMAIN_SYNC_CONFIG");
    while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
        $$PARA_NAME = $PARA_VALUE;
    
    $USER_GUID = "";
    $USER_ID   = $_SESSION["LOGIN_USER_ID"];
    
    if($LOGIN_USE_DOMAIN == 1)
    {
        $query = "select * from USER_MAP where USER_ID='$USER_ID'";
        $cursor1= exequery(TD::conn(),$query);
        if($ROW1=mysql_fetch_array($cursor1))
            $USER_GUID = $ROW1["USER_GUID"];
    }
    if(!isset($stype)&& $stype!=1)
    {
        if($USER_GUID == "")
        {
            $query = "SELECT PASSWORD from USER where USER_ID='$USER_ID'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $PASSWORD=$ROW["PASSWORD"];
            }
            if(crypt($PWD,$PASSWORD)!= $PASSWORD)
            {
                Message("",_("密码错误"));
            ?>
                <div align="center">
                 <input type="button" value="返回" class="BigButtonA" onClick="history.back();">
                </div>
            <?
                exit();
             }
            
        }else
        {
            if($PWD=="")
            {
                Message("",_("绑定的域用户密码不能为空"));
                Button_Back();
                exit;
            }
            include_once("inc/utility_user.php");
            include_once("inc/ldap/adLDAP.php");
            $result = false;
            try
            {
                $SYNC_CONFIG = unserialize($DOMAIN_SYNC_CONFIG);
                $option = get_ldap_option($SYNC_CONFIG);
                $adldap = new adLDAP($option);
                if(!$adldap)
                {
                    Message("",_("初始化域验证失败"));
                    Button_Back();
                    exit;
                }
                if(!$adldap->authenticate($SYNC_CONFIG['AD_USER'],$SYNC_CONFIG['AD_PWD']))
                {
                    Message("",sprintf(_("域相关参数设置有误(%s)"), $adldap->get_last_error()));
                    Button_Back();
                    exit;    
                }
                //根据guid查询登录名
                $user_info = $adldap->user_info($USER_GUID, array("samaccountname"), true);
                if($user_info === FALSE)
                {
                    Message("",sprintf(_("获取用户[%s]的域用户名出错(%s)"), $USER_ID, $adldap->get_last_error()));
                    Button_Back();
                    exit;
                }

                $user_info = $user_info[0];
                if(!is_array($user_info) || !is_array($user_info['samaccountname']) || $user_info['samaccountname'][0] == "")
                {
                    Message("",sprintf(_("查询不到用户[%s]对应的域用户"), $USER_ID));
                    Button_Back();
                    exit;
                }    
                //$adldap->close();
                $DOMAIN_USER = $user_info['samaccountname'][0];
                
                $adldap = new adLDAP($option);
                $result = $adldap->authenticate($DOMAIN_USER, iconv(MYOA_CHARSET, "utf-8", $PWD));
                if(!$result)
                {
                    Message("",sprintf(_("用户[%s]域验证失败(%s)"), $USER_ID, $adldap->get_last_error()));
                    Button_Back();
                    exit;
                }
            }
            catch (adLDAPException $e)
            {
                return var_export($e, true);
            }
        }
    } 
}
$query = "SELECT YES_OTHER from HR_INSURANCE_PARA";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $YES_OTHER=$ROW["YES_OTHER"];
}

$THE_FOUR_VAR = "view=$VIEW&stype=1&"."start";

?>
<table border="0" cellspacing="0" cellpadding="3" id="table_title" class="small" style="width: 960px;">
  <tr>
      <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("薪酬信息")?></span><br></td>
        <?
        if($STAFF_COUNT>0)
        {
        ?>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE,$THE_FOUR_VAR )?></td>
    <?
        }
    ?>
    </tr>
</table>
<?
if($STAFF_COUNT>0)
{
?>
<table class="TableBlock" align="center" id="table_content" cellspacing="0" cellpadding="0">
    <tr class="TableHeader" align="center">
      <td nowrap ><b><?=_("月份")?></b></td>
      <td nowrap ><b><?=_("工资流程备注")?></b></td>
      <td nowrap ><b><?=_("个人工资条备注")?></b></td>
<?
 $STYLE_ARRAY=explode(",",$STYLE);
 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
 $COUNT=0;
 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
 for($I=0;$I<$ARRAY_COUNT;$I++)
 {
     $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $ITEM_NAME=$ROW["ITEM_NAME"];
        $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
     }
     $COUNT++;
?>
      <td nowrap align="center" onClick="clickTitle('<?=$ITEM_ID[$COUNT-1]?>')" style="cursor:hand"><b><?=$ITEM_NAME?></b></td>
<?
 }
 if($YES_OTHER==1)
 {
?>
      <td nowrap align="center" style="cursor:hand"><b><?=_("保险基数")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("养老保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位养老")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人养老")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("医疗保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位医疗")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人医疗")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("生育保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位生育")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("失业保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位失业")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人失业")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("工伤保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位工伤")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("住房公积金")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位住房")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人住房")?></b></td>
    </tr>

<?
 }
//============================ 显示已定义用户 =======================================
$query2 = "select FLOW_ID from SAL_DATA where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by FLOW_ID desc limit $start,$PAGE_SIZE";
$cursor2= exequery(TD::conn(),$query2);
while($ROW2=mysql_fetch_array($cursor2))
{

  $FLOW_ID=$ROW2["FLOW_ID"];

  $query = "SELECT * from SAL_FLOW where FLOW_ID='$FLOW_ID' order by SAL_YEAR desc,SAL_MONTH desc";
  $cursor= exequery(TD::conn(),$query);

  $USER_COUNT=0;
  while($ROW=mysql_fetch_array($cursor))
  {
     $USER_COUNT++;
     $SAL_YEAR=$ROW["SAL_YEAR"];
     $SAL_MONTH=$ROW["SAL_MONTH"];
     $CONTENT=$ROW["CONTENT"];
     $END_DATE=$ROW["END_DATE"];
     $END_DATE=strtok($END_DATE," ");
     $ISSEND=$ROW["ISSEND"];
     $SAL_MONTH=$SAL_YEAR._("年").$SAL_MONTH._("月");
   if(($END_DATE!="0000-00-00" && compare_date($CUR_DATE,$END_DATE)>0) || $ISSEND==1)
   {
?>
    <tr class="TableLine1" align="center">
      <td nowrap><?=$SAL_MONTH?></td>
      <td nowrap><?=$CONTENT?></td>
<?
   $query1="select * from SAL_DATA where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and FLOW_ID='$FLOW_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
     for($I=1;$I<=50;$I++)
     {
       $STR="S".$I;
       $$STR=format_money($ROW["$STR"]);
     }
     $ALL_BASE = $ROW["ALL_BASE"];
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
     $MEMO = $ROW["MEMO"];
   }

?>
      <td nowrap align="center" ><?=$MEMO?></td>
<?
  $STYLE_ARRAY=explode(",",$STYLE);
  $ARRAY_COUNT=sizeof($STYLE_ARRAY);
  $COUNT=0;
  if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
  for($I=0;$I<$ARRAY_COUNT;$I++)
   {
     $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
        {
            $ITEM_NAME=$ROW["ITEM_NAME"];
          $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
          $S_ID="S".$ITEM_ID[$COUNT];
        }
     $COUNT++;
?>
      <td nowrap align="center" ><?=$$S_ID?></td>
<?
   }
   if($YES_OTHER==1)
   {
?>
     <td nowrap align="center"><?=$ALL_BASE?> </td>
     <td nowrap align="center"><?=$PENSION_BASE?> </td>
     <td nowrap align="center"><?=$PENSION_U?></td>
     <td nowrap align="center"><?=$PENSION_P?></td>
     <td nowrap align="center"><?=$MEDICAL_BASE?></td>
     <td nowrap align="center"><?=$MEDICAL_U?></td>
     <td nowrap align="center"><?=$MEDICAL_P?></td>
     <td nowrap align="center"><?=$FERTILITY_BASE?></td>
     <td nowrap align="center"><?=$FERTILITY_U?></td>
     <td nowrap align="center"><?=$UNEMPLOYMENT_BASE?></td>
     <td nowrap align="center"><?=$UNEMPLOYMENT_U?></td>
     <td nowrap align="center"><?=$UNEMPLOYMENT_P?></td>
     <td nowrap align="center"><?=$INJURIES_BASE?></td>
     <td nowrap align="center"><?=$INJURIES_U?></td>
     <td nowrap align="center"><?=$HOUSING_BASE?></td>
     <td nowrap align="center"><?=$HOUSING_U?></td>
     <td nowrap align="center"><?=$HOUSING_P?></td>
    </tr>
<?
   }
  }
?>
    <input type="hidden" name="<?=$USER_ID?>_OPERATION" class="SmallInput" value="<?=$OPERATION?>" size="10" >
<?
 }
}
 if($USER_COUNT==0)
    $DEPT_COUNT--;
?>
</table>
<br>
<div align="center">
<input type="hidden" value="<?=$STYLE?>"  name="STYLE">
<input type="hidden" value="<?=$FLOW_ID?>"  name="FLOW_ID">
<input type="hidden" value="<?=$DEPT_ID?>"  name="DEPT_ID">
<input type="hidden" value="<?=$STYLE_USER?>"  name="STYLE_USER">
</div>
<?
}
else
{
    Message("",_("无薪酬信息"));
}
?>
</body>
</html>
<script language="JavaScript">
function clickTitle(ID)
{
  var str1=document.all("STYLE_USER").value;
  var id_value_array=str1.split(",");
  var temp=id_value_array.length-2;
  for(i=0;i<=temp;i++){
      control=id_value_array[i]+"_"+ID;
      if(i==0)setvalue=document.all(control).value;
      document.all(control).value=setvalue;
  }
}

//$(document).ready(function(){
//    var table_content=$("#table_content").width();
//    $("#table_title").width(table_content);
//})
</script>