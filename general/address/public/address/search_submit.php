<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("联系人查询");
include_once("inc/header.inc.php");
?>

<script>
function add_detail(ADD_ID)
{
 URL="add_detail.php?ADD_ID="+ADD_ID;
 myleft=(screen.availWidth-750)/2;
 window.open(URL,"detail","height=620,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function delete_add(ADD_ID)
{
 msg='<?=_("确认要删除该联系人吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?ADD_ID=" + ADD_ID;
  window.location=URL;
 }
}
</script>


<body class="bodycolor">
<div class="PageHeader">
   <div class="title"><?=_("查询结果")?></div>
   <div class="header-left">
     <a class="ToolBtn" href="search.php"><span><?=_("返回")?></span></a>
   </div>
</div>
<?
 //============================ 显示 =======================================
 $query = "select * from ADDRESS_GROUP where USER_ID='' order by GROUP_NAME asc";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
 	 $PRIV_DEPT=$ROW["PRIV_DEPT"];
 	 $PRIV_ROLE=$ROW["PRIV_ROLE"];
 	 $PRIV_USER=$ROW["PRIV_USER"];
	 if($PRIV_DEPT!="ALL_DEPT")
	 {
	    if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) and !check_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)!="" and !check_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)!="")
	    {
	       continue;
	    }
   }

   $GROUP_ID1=$ROW["GROUP_ID"];

   $GROUP_ID_STR.=$GROUP_ID1.",";
 }//while

 $GROUP_ID_STR=$GROUP_ID_STR."0,";

 $query = "SELECT * from ADDRESS where USER_ID=''";

 if($GROUP_ID!="ALL_DEPT")
    $query .= " and GROUP_ID='$GROUP_ID'";

 if($PSN_NAME!="")
    $query .= " and PSN_NAME like '%$PSN_NAME%'";
    
 if($B_BIRTHDAY!="")
    $query .= " and BIRTHDAY >= '$B_BIRTHDAY'";

 if($E_BIRTHDAY!="")
    $query .= " and BIRTHDAY <= '$E_BIRTHDAY'";

 if($SEX!="ALL")
    $query .= " and SEX='$SEX'";

 if($NICK_NAME!="")
    $query .= " and NICK_NAME like '%$NICK_NAME%'";

 if($DEPT_NAME!="")
    $query .= " and DEPT_NAME like '%$DEPT_NAME%'";

 if($ADD_DEPT!="")
    $query .= " and ADD_DEPT like '%$ADD_DEPT%'";

 if($ADD_HOME!="")
    $query .= " and ADD_HOME like '%$ADD_HOME%'";

 if($TEL_NO_DEPT!="")
    $query .= " and TEL_NO_DEPT like '%$TEL_NO_DEPT%'";

 if($TEL_NO_HOME!="")
    $query .= " and TEL_NO_HOME like '%$TEL_NO_HOME%'";

 if($MOBIL_NO!="")
    $query .= " and MOBIL_NO like '%$MOBIL_NO%'";

 if($NOTES!="")
    $query .= " and NOTES like '%$NOTES%'";

 if($ALL_NO!="")
    $query .= " and (TEL_NO_DEPT like '%$ALL_NO%' or TEL_NO_HOME like '%$ALL_NO%' or MOBIL_NO like '%$ALL_NO%')";

 $query.=field_where_str("ADDRESS",$_POST,"ADDRESS.ADD_ID");
 $query.=" order by GROUP_ID,PSN_NAME asc";
 $cursor= exequery(TD::conn(),$query);
 $ADD_COUNT=0;

while($ROW=mysql_fetch_array($cursor))
 {
		$GROUP_ID=$ROW["GROUP_ID"];
		if(!find_id($GROUP_ID_STR,$GROUP_ID))
		   continue;

    $ADD_COUNT++;

    $ADD_ID=$ROW["ADD_ID"];
    $PSN_NAME=$ROW["PSN_NAME"];
    $SEX=$ROW["SEX"];
    $DEPT_NAME=$ROW["DEPT_NAME"];
    $TEL_NO_DEPT=$ROW["TEL_NO_DEPT"];
    $MOBIL_NO=$ROW["MOBIL_NO"];
    $EMAIL=$ROW["EMAIL"];

    $query1 = "select * from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
       $GROUP_NAME=$ROW1["GROUP_NAME"];
    if($GROUP_ID==0)
       $GROUP_NAME=_("默认");

    //该组有没有维护权限
    $PRIV_FLAG = 0;
    if($_SESSION["LOGIN_USER_PRIV"]!=1)
      $query1 = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where GROUP_ID='$GROUP_ID' and  (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER))";
    else
      $query1 = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
       $PRIV_FLAG = 1;
    
    if($_SESSION["LOGIN_USER_PRIV"]==1 && $GROUP_ID==0)
       $PRIV_FLAG = 1;


    if($MOBIL_NO!="")
       $MOBIL_NO_STR.=$MOBIL_NO.",";

    if($SEX=="0")
       $SEX=_("男");
    else if($SEX=="1")
       $SEX=_("女");
    else
       $SEX="";


    if($ADD_COUNT==1)
    {
?>

    <table class="TableList" width="95%">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$GROUP_NAME?></a></td>
      <td nowrap align="center"><a href="javascript:add_detail('<?=$ADD_ID?>');"><?=$PSN_NAME?></a></td>
      <td nowrap align="center"><?=$SEX?></td>
      <td nowrap align="center"><?=$DEPT_NAME?></td>
      <td nowrap align="center"><?=$TEL_NO_DEPT?></td>
      <td nowrap align="center"><A href="/general/mobile_sms/?TO_ID1=<?=$MOBIL_NO?>,"><?=$MOBIL_NO?></A></td>
      <td nowrap align="center"><a href="mailto:<?=$EMAIL?>"><?=$EMAIL?></a></td>
      <td nowrap align="center" width="100">
          <a href="javascript:add_detail('<?=$ADD_ID?>');"> <?=_("详情")?></a>
<?
if($PRIV_FLAG==1)
{
?>          
          <a href="edit.php?ADD_ID=<?=$ADD_ID?>"> <?=_("编辑")?></a>
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
   Message("",_("无符合条件的联系人"));
 }
 else
 {
?>
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("组名")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("性别")?></td>
      <td nowrap align="center"><?=_("单位名称")?></td>
      <td nowrap align="center"><?=_("工作电话")?></td>
      <td nowrap align="center"><?=_("手机")?> <a href="javascript:form1.submit();"><?=_("群发")?></a></td>
      <td nowrap align="center"><?=_("电子邮件")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
   </thead>
   </table>
<?
 }
?>
<form name="form1" method="post" action="/general/mobile_sms/new/index.php">
   <input type="hidden" name="TO_ID1" value="<?=$MOBIL_NO_STR?>">
</form>
<br>

<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='search.php';">
</div>

</body>
</html>
