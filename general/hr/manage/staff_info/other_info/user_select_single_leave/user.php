<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

if($TO_ID=="" || $TO_ID=="undefined")
{
    $TO_ID="TO_ID";
   $TO_NAME="TO_NAME";
}
if($FORM_NAME=="" || $FORM_NAME=="undefined")
   $FORM_NAME="form1";

include_once("inc/header.inc.php");
?>



<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/user_select.js"></script>
<script Language="JavaScript">
var parent_window = getOpenner();
var to_form = parent_window.<?=$FORM_NAME?>;
var to_id =   to_form.<?=$TO_ID?>;
var to_name = to_form.<?=$TO_NAME?>;

function add_user(user_id,user_name,dept_id,dept_name)
{
  TO_VAL=to_id.value;
  if(TO_VAL.indexOf(","+user_id+",")<0 && TO_VAL.indexOf(user_id+",")!=0)
  {
    to_id.value=user_id;
    to_name.value=user_name;
    parent_window.form1.LEAVE_DEPT.value=dept_id;
    parent_window.form1.LEAVE_DEPT_NAME.value=dept_name;
  }
  parent.close();
}
</script>

<body class="bodycolor" onload="begin_set()">

<?
include_once("inc/my_priv.php");

if($MODULE_ID=="2")
   $EXCLUDE_UID_STR=my_exclude_uid();
   
 if($DEPT_ID=="")
 {
    if(is_dept_priv($_SESSION["LOGIN_DEPT_ID"], $DEPT_PRIV, $DEPT_ID_STR, 1))
    {
       $DEPT_ID=$_SESSION["LOGIN_DEPT_ID"];
    }
    else
    {
       Message("",_("请选择部门"));
       exit;
    }
 }

 //============================ 显示人员信息 =======================================
 if($USER_PRIV=="")
 {
    $query = "SELECT UID,USER_ID,DEPT_ID,USER_NAME,PRIV_NO from USER,USER_PRIV where (DEPT_ID='$DEPT_ID' or find_in_set('$DEPT_ID',DEPT_ID_OTHER)) and USER.USER_PRIV=USER_PRIV.USER_PRIV";
    if(!$MANAGE_FLAG)
        $query .= " and NOT_LOGIN='0'";
    if($DEPT_PRIV=="3"||$DEPT_PRIV=="4")
      $query .= " and find_in_set(USER.USER_ID,'$USER_ID_STR')";
    $query .= " order by PRIV_NO,USER_NO,USER_NAME";
 }
 else
 {
    $query = "SELECT UID,USER_ID,DEPT_ID,USER_NAME,DEPT_ID from USER where USER_PRIV='$USER_PRIV' and DEPT_ID!=0";
    if(!$MANAGE_FLAG)
       $query .= " and NOT_LOGIN='0'";
    if($DEPT_PRIV=="3"||$DEPT_PRIV=="4")
      $query .= " and find_in_set(USER.USER_ID,'$USER_ID_STR')";
    $query .= " order by USER_NO,USER_NAME";
 }

 $ONLINE_USER_ARRAY = TD::get_cache('SYS_ONLINE_USER');
 $cursor= exequery(TD::conn(),$query);
 $USER_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $UID=$ROW["UID"];
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_ID_I=$ROW["DEPT_ID"];
    $PRIV_NO_I=$ROW["PRIV_NO"];
    $DEPT_ID=$ROW["DEPT_ID"];    
    $DEPT_NAME=substr(GetDeptNameById($DEPT_ID),0,-1); 
    
    if($USER_PRIV=="" && ($ROLE_PRIV=="0" && $PRIV_NO_I<=$MY_PRIV_NO || $ROLE_PRIV=="1" && $PRIV_NO_I< $MY_PRIV_NO || $ROLE_PRIV=="3" && !find_id($PRIV_ID_STR, $PRIV_NO_I)))
       continue;
    else if($USER_PRIV!="" && !is_dept_priv($DEPT_ID_I, $DEPT_PRIV, $DEPT_ID_STR, true))
       continue;

    $USER_COUNT++;
    if($USER_COUNT==1)
    {
?>
<table class="TableBlock" width="100%">
<tr class="TableHeader">
  <td align="center"><b><?=_("选择人员")?></b></td>
</tr>

<?
    }
?>

<tr class="TableData" style="cursor:pointer">
  <td class="menulines" align="center" onclick="javascript:add_user('<?=$USER_ID?>','<?=$USER_NAME?>','<?=$DEPT_ID?>','<?=$DEPT_NAME?>')"><?=$USER_NAME?><span id="attend_status_<?=$UID?>" title="<?=$USER_ID?>" style="color:#FF0000;"><?if(array_key_exists($UID,$ONLINE_USER_ARRAY)) echo _("(在线)");?></span></td>
</tr>
<?
 }

if($USER_COUNT==0)
   Message("",_("没有定义用户"),"blank");
else
	 echo "</table>";
?>

</body>
</html>
