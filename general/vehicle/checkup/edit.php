<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("车辆使用申请");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function IsNumber(str)
{
   return str.match(/^[0-9]*$/)!=null;
}

function CheckForm()
{
   if(document.form1.V_ID.value=="")
   { alert("<?=_("请指车辆！")?>");
     return (false);
   }

   if(document.form1.VU_START.value=="")
   { alert("<?=_("起始时间不能为空！")?>");
     return (false);
   }

   if (document.form1.VU_MILEAGE.value!=""&&!IsNumber(document.form1.VU_MILEAGE.value))
   { alert("<?=_("申请里程应为数字！")?>");
     return (false);
   }

   if(document.form1.VU_OPERATOR.value=="")
   { alert("<?=_("请指定调度人员！")?>");
     return (false);
   }

   form1.submit();
}

function showDetail()
{
   var tem1 =form1.V_ID.value.indexOf("*");
   var tem2 =form1.V_ID.value.substr(0,tem1);
   var tem3 =form1.V_ID.value.substr(tem1+1); 

   form1.VU_DRIVER.value=tem3;
   document.getElementById("vehicle_detail").src="../show_detail.php?V_ID="+tem2;
}

function LoadWindow(field_id,id,desc)
{
  URL="/ikernel/select/?FIELD_ID="+field_id+"&ID="+id+"&DESC="+desc;
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:300px;dialogHeight:250px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
    window.open(URL,"loadwin","height=250,width=300,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}
function info()
{
	var tem1 =form1.V_ID.value.indexOf("*");
	var tem2 =form1.V_ID.value.substr(0,tem1);
	var tem3 =form1.V_ID.value.substr(tem1+1); 
	form1.VU_DRIVER.value=tem3;
	document.getElementById("vehicle_detail").src="../show_detail.php?V_ID="+tem2;
}
</script>


<body class="bodycolor" onLoad="info();">

<?
$VU_ID=intval($VU_ID);
$CUR_TIME=date("Y-m-d H:i:s",time());
if($VU_ID!="")
{
   $query = "SELECT * from VEHICLE_USAGE where VU_ID='$VU_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $V_ID=$ROW["V_ID"];
      $VU_PROPOSER=$ROW["VU_PROPOSER"];
      $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
      $VU_USER=$ROW["VU_USER"];
      $VU_REASON=$ROW["VU_REASON"];
      $VU_START =$ROW["VU_START"];
      $VU_END=$ROW["VU_END"];
      $VU_MILEAGE=$ROW["VU_MILEAGE"];
      $VU_DEPT=$ROW["VU_DEPT"];
      $VU_STATUS=$ROW["VU_STATUS"];
      $VU_REMARK=$ROW["VU_REMARK"];
      $VU_DESTINATION=$ROW["VU_DESTINATION"];
      $VU_OPERATOR=$ROW["VU_OPERATOR"];
      $VU_OPERATOR1=$ROW["VU_OPERATOR1"];  //备选调度员
      $VU_DRIVER=$ROW["VU_DRIVER"];
     $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
   	 $cursor_name= exequery(TD::conn(),$query_name);
   		if($ROW_NAME=mysql_fetch_array($cursor_name)){
      		//$VU_USER_ID = $ROW_NAME["USER_NAME"] != ""?$VU_USER:"";
      		$VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
   		}
      if($VU_START=="0000-00-00 00:00:00")
         $VU_START="";
      if($VU_END=="0000-00-00 00:00:00")
         $VU_END="";
   }
}
if($VU_REQUEST_DATE=="0000-00-00 00:00:00" || $VU_REQUEST_DATE=="")
   $VU_REQUEST_DATE=$CUR_TIME;
if($VU_PROPOSER=="")
   $VU_PROPOSER=$_SESSION["LOGIN_USER_ID"];

$query = "SELECT * from USER  where USER_ID='$VU_PROPOSER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $VU_PROPOSER_NAME=$ROW["USER_NAME"];
   
   $VU_DEPT=intval($VU_DEPT);

if($VU_DEPT!="")
{
   $query = "SELECT * from DEPARTMENT  where DEPT_ID='$VU_DEPT'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $VU_DEPT_FIELD_DESC=$ROW["DEPT_NAME"];
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=_("车辆使用申请")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" align="center" width="620">
<form enctype="multipart/form-data" action="update.php" method="post" name="form1">
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("车牌号：")?></td>
      <td class="TableData" width="230">
        <select name="V_ID" class="BigSelect" onChange="showDetail();">
<?
$query = "SELECT * from VEHICLE order by V_NUM";
$cursor1= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor1))
{
   $V_ID1=$ROW1["V_ID"];
   $V_NUM=$ROW1["V_NUM"];
   $V_DRIVER=$ROW1["V_DRIVER"];    
?>
          <option value="<?=$V_ID1?>*<?=$V_DRIVER?>" <? if($V_ID==$V_ID1) echo "selected";?>><?=$V_NUM?></option>
<?
}
?>
        </select>
      </td>
      <td nowrap class="TableContent" width="80"> <?=_("司机：")?></td>
      <td class="TableData" width="230">
        <input type="text" name="VU_DRIVER" size="11" class="BigInput" value="<?=td_trim($VU_DRIVER) ?>" > 
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("用车人：")?></td>
      <td class="TableData">
        <input type="text" name="VU_USER" size="20" maxlength="100" class="BigStatic" value="<?=$VU_USER?>" readonly>
      </td>
      <td nowrap class="TableContent" width="80"> <?=_("用车部门：")?></td>
      <td class="TableData">
        <input type="hidden" name="VU_DEPT" value="<?=$VU_DEPT?>">
        <input type="text" name="VU_DEPT_FIELD_DESC" value="<?=$VU_DEPT_FIELD_DESC?>" class=BigStatic size=20 maxlength=100 readonly>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("起始时间：")?></td>
      <td class="TableData">
        <input type="text" name="VU_START" size="20" maxlength="19" class="BigInput" value="<?=$VU_START?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
     
      </td>
      <td nowrap class="TableContent" width="80"> <?=_("结束时间：")?></td>
      <td class="TableData">
        <input type="text" name="VU_END" size="20" maxlength="19" class="BigInput" value="<?=$VU_END?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("目的地：")?></td>
      <td class="TableData">
        <input type="text" name="VU_DESTINATION" size="20" maxlength="100" class="BigInput" value="<?=$VU_DESTINATION?>">
      </td>
      <td nowrap class="TableContent" width="80"> <?=_("申请里程：")?></td>
      <td class="TableData">
        <input type="text" name="VU_MILEAGE" size="20" maxlength="14" class="BigInput" value="<?=$VU_MILEAGE?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("申请人：")?></td>
      <td class="TableData">
        <input type="text" name="VU_PROPOSER_NAME" size="10" maxlength="100" class="BigStatic" value="<?=$VU_PROPOSER_NAME?>" readonly>
        <input type="hidden" name="VU_PROPOSER" value="<?=$VU_PROPOSER?>">
      </td>
      <td nowrap class="TableContent" width="80"> <?=_("调度员：")?></td>
      <td class="TableData">
        <select name="VU_OPERATOR" class="BigStatic">
<?
$query = "SELECT * from USER where USER_ID='$VU_OPERATOR'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
?>
          <option value="<?=$USER_ID?>" selected><?=$USER_NAME?></option>
<?
}
?>
        </select> (<?=_("注：负责审批")?>)
      </td>
    </tr>
    
    <tr>
        <?if($VU_OPERATOR1 != ""){?>
        <td nowrap class="TableContent" width="80"> <?=_("备选调度员：")?></td>
        <td class="TableData">
        <select name="VU_OPERATOR1" class="BigStatic">
<?
$query = "SELECT * from USER where USER_ID='$VU_OPERATOR1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
?>
          <option value="<?=$USER_ID?>" selected><?=$USER_NAME?></option>
<?
}
?>
        </select>
      </td>
      
    <td nowrap class="TableContent" width="80"></td>
      <td class="TableData">
       
      </td>
    </tr>
    <?}?>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("事由：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VU_REASON" class="BigInput" cols="74" rows="2"><?=$VU_REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("备注：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VU_REMARK" class="BigInput" cols="74" rows="2"><?=$VU_REMARK?></textarea>
      </td>
    </tr>
    <tr class="TableControl">
      <td nowrap colspan="4" align="center">
        <input type="hidden" value="<?=$VU_ID?>" name="VU_ID">
        <input type="hidden" value="<?=$VU_STATUS?>" name="VU_STATUS">
        <input type="hidden" value="<?=$VU_REQUEST_DATE?>" name="VU_REQUEST_DATE">
        <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="CheckForm();">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
<?
if($VU_ID!="")
{
?>
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php?VU_STATUS=<?=$VU_STATUS?>'">
<?
}
?>
      </td>
    </tr>
    </table>
</form>
<div align="center">
<iframe id="vehicle_detail" width="626" height="200" src="../show_detail.php" frameBorder="0" frameSpacing="0" scrolling="yes" align="center"></iframe>
</div>
<script>
   var tem1 =form1.V_ID.value.indexOf("*");
   var tem2 =form1.V_ID.value.substr(0,tem1);
   
   document.getElementById("vehicle_detail").src="../show_detail.php?V_ID="+tem2;
</script>
</body>
</html>