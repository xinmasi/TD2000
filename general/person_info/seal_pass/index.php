<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("选择印章");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script Language="JavaScript">
var obj;
function MyGetOpenner()
{
   if(is_moz)
      return parent.opener;
   else
      return parent.dialogArguments;
}
function modifyPass(seal_id,seal_name)
{
  obj = $("DMakeSealV61");
	if(!obj){
		alert("<?=_("尚未安装控件")?>");
		return false;
	}
	
	clearSeal();
	show_info(seal_id);
	ShowDialog("seal_pass");
}
function show_info(ID)
{
	_get("get_seal.php?ID=" +ID ,'',function(req){	
	    if(req.responseText!="")
	    {
	    	 var obj = document.getElementById("DMakeSealV61");
	       if(!obj){
	       	  alert("<?=_("控件加载失败！")?>");
		        return false;
	       }
	       if(0 == obj.LoadData(req.responseText)){
	       	
        		var vID = 0; 
        		vID = obj.GetNextSeal(0);
        		if(!vID){
        			return true;
        		}
        		if(obj.SelectSeal(vID)) return false;
        		var vSealID = obj.strSealID;
        		var vSealName = obj.strSealName;

            $("seal_id").innerHTML=vSealID;
            $("seal_name").innerHTML=vSealName; 
            $("SID").value=ID;
	       }
	       else
	         alert("<?=_("读取印章数据失败")?>");
	    }
	    else
	      alert("<?=_("无印章信息！")?>");
	 });
}
function mysubmit()
{
	if($("PASS1").value != $("PASS2").value)
	{
		alert("<?=_("两次密码不一致，请重新输入！")?>");
		return;
	}
	obj.strOpenPwd = $("PASS1").value;

	$("SEAL_DATA").value = obj.SaveData();
	_post("set_seal.php","ID="+$("SID").value+"&SEAL_DATA="+$("SEAL_DATA").value.replace(/\+/g, '%2B')+"&PASS="+$("PASS1").value,function(req){
		if(req.responseText!="err")
		{
			alert("<?=_("密码修改成功！")?>");
			HideDialog('seal_pass');
			return;
		}
		});
}
function clearSeal()
{
		var vID = 0;
		do{
  	  vID = obj.GetNextSeal(vID);
  	  if(!vID)
  		  break;
      obj.DelSeal(vID);
    }while(vID);
    
    $("seal_id").innerHTML="";
    $("seal_name").innerHTML=""; 
    $("SID").value="";
    $("PASS1").value="";
    $("PASS2").value="";
}
</script>


<body class="bodycolor">
<?
$query = "select * from SEAL WHERE find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_STR) ORDER BY CREATE_TIME DESC ";
$cursor= exequery(TD::conn(),$query);
$SEAL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $SEAL_COUNT++;
   $ID=$ROW["ID"];
   $SEAL_ID=$ROW["SEAL_ID"];
   $SEAL_NAME=$ROW["SEAL_NAME"];
   $SEAL_DEPT=$ROW["SEAL_DEPT"];
   $USER_STR=$ROW["USER_STR"];
   $CREATE_TIME=$ROW["CREATE_TIME"];
   
   $USER_NAME_STR="";
   $query1 = "SELECT USER_NAME FROM USER WHERE FIND_IN_SET(USER_ID,'$USER_STR')";
   $cursor1 = exequery(TD::conn(),$query1);
   while($ROW = mysql_fetch_array($cursor1))
     $USER_NAME_STR.=$ROW[0].",";
        
   if($SEAL_COUNT==1)
   {
?>
<table class="table table-bordered" width="100%">
<thead>
  <tr class="" style="background-color:#ebebeb;">
    <th align="center"><b><?=_("印章ID")?></b></th>
    <th align="center"><b><?=_("印章名称")?></b></th>
    <th align="center"><b><?=_("授权范围")?></b></th>
    <th align="center"><b><?=_("创建时间")?></b></th>
    <th align="center"><?=_("操作")?></th>
  </tr>
</thead>

<?
   }
    if($SEAL_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>

<tr class="<?=$TableLine?>"  style="background-color:#fff;">
  <td class="menulines" align="center"><?=$SEAL_ID?></td>	
  <td align="left"><?=$SEAL_NAME?></td>
  <td align="left"><?=$USER_NAME_STR?></td>
  <td nowrap align="center"><?=$CREATE_TIME?></td>
  <td nowrap align="center"><a href="javascript:modifyPass('<?=$ID?>','<?=$SEAL_NAME?>')"><?=_("修改密码")?></a></td>
</tr>
<?
}

if($SEAL_COUNT==0)
   Message("",_("无任何印章权限！"));
else
	 echo "</table>";
?>

<div id="overlay"></div>
<div id="seal_pass" class="ModalDialog" style="width:400px;">
    <div class="header"><span id="title" class="title"> <?=_("印章密码修改")?></span><a class="operation" href="javascript:HideDialog('seal_pass');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
    <div id="seal_pass_body" class="body bodycolor">
        <table class="TableBlock" width="90%" align="center">
    		<tr>
    			<td class="TableData" colspan=2 align="center">
          <? include_once("module/seal_maker/ver.php");?>
          </td>
        </tr>
        <tr>
       	  <td class="TableContent" width=80><?=_("印章ID")?></td>
       	  <td class="TableData"><span id="seal_id"></span></td>
        </tr>
         <tr>
         	<td class="TableContent"><?=_("印章名称")?></td>
         	<td class="TableData"><span id="seal_name"></span></td>
        </tr>         
        <tr>
        	<td class="TableContent" ><?=_("新密码：")?></td>
        	<td class="TableData" >
        	  <input type="password" id="PASS1"  class="BigInput" size="20">
        	</td>
        </tr>
        
        <tr>
        	<td class="TableContent" ><?=_("确认新密码：")?></td>
        	<td class="TableData" >
        	  <input type="password" id="PASS2"  class="BigInput" size="20">
        	</td>
        </tr>   
        <tr class="TableControl">
        	<td class="TableData" colspan=2 align=center>
        	<input type="hidden" id="SID" value="">
        	<input type="hidden" id="SEAL_DATA" value="">
          <input type="button" onclick="mysubmit()" class=BigButton value="<?=_("确认")?>">
        	</td>
        </tr> 
      </table>		  	
      </div>
    </div>
</div>
</body>
</html>
