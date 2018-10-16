<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");


$CUR_TIME=date("Y-m-d H:i:s",time());
$CHANGE=0;//鉴别是否有筛选办理  如果有办理为1
if($FILTER_ID!="")
{
	 $query = "SELECT * from HR_RECRUIT_FILTER where FILTER_ID='$FILTER_ID'";
	 $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $FILTER_COUNT++;    
    	$FILTER_ID=$ROW["FILTER_ID"];
      $NEW_TIME=$ROW["NEW_TIME"];
      $EXPERT_ID=$ROW["EXPERT_ID"];
      $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
      $PLAN_NO=$ROW["PLAN_NO"];    
      $POSITION=$ROW["POSITION"];
      $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
      $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
      $TRANSACTOR_STEP=$ROW["TRANSACTOR_STEP"];
      $STEP_FLAG=$ROW["STEP_FLAG"];
      $END_FLAG=$ROW["END_FLAG"];
      $NEXT_DATE_TIME=$ROW["NEXT_DATE_TIME"]=="0000-00-00 00:00:00"?"":$ROW["NEXT_DATE_TIME"];
      $NEXT_TRANSA_STEP=$ROW["NEXT_TRANSA_STEP"];
      
      $FILTER_METHOD1=$ROW["FILTER_METHOD1"];
      $FILTER_DATE_TIME1=$ROW["FILTER_DATE_TIME1"]=="0000-00-00 00:00:00"?"":$ROW["FILTER_DATE_TIME1"];
      $FIRST_CONTENT1=$ROW["FIRST_CONTENT1"];
      $FIRST_VIEW1=$ROW["FIRST_VIEW1"];
      $TRANSACTOR_STEP1=$ROW["TRANSACTOR_STEP1"];
      $PASS_OR_NOT1=$ROW["PASS_OR_NOT1"];
      $NEXT_TRANSA_STEP1=$ROW["NEXT_TRANSA_STEP1"];
      $NEXT_DATE_TIME1=$ROW["NEXT_DATE_TIME1"]=="0000-00-00 00:00:00"?"":$ROW["NEXT_DATE_TIME1"];      
      
      $FILTER_METHOD2=$ROW["FILTER_METHOD2"];
      $FILTER_DATE_TIME2=$ROW["FILTER_DATE_TIME2"]=="0000-00-00 00:00:00"?"":$ROW["FILTER_DATE_TIME2"];
      $FIRST_CONTENT2=$ROW["FIRST_CONTENT2"];
      $FIRST_VIEW2=$ROW["FIRST_VIEW2"];
      $TRANSACTOR_STEP2=$ROW["TRANSACTOR_STEP2"];
      $PASS_OR_NOT2=$ROW["PASS_OR_NOT2"];
      $NEXT_TRANSA_STEP2=$ROW["NEXT_TRANSA_STEP2"];
      $NEXT_DATE_TIME2=$ROW["NEXT_DATE_TIME2"]=="0000-00-00 00:00:00"?"":$ROW["NEXT_DATE_TIME2"];
      
      $FILTER_METHOD3=$ROW["FILTER_METHOD3"];
      $FILTER_DATE_TIME3=$ROW["FILTER_DATE_TIME3"]=="0000-00-00 00:00:00"?"":$ROW["FILTER_DATE_TIME3"];
      $FIRST_CONTENT3=$ROW["FIRST_CONTENT3"];
      $FIRST_VIEW3=$ROW["FIRST_VIEW3"];
      $TRANSACTOR_STEP3=$ROW["TRANSACTOR_STEP3"];
      $PASS_OR_NOT3=$ROW["PASS_OR_NOT3"];
      $NEXT_TRANSA_STEP3=$ROW["NEXT_TRANSA_STEP3"];
      $NEXT_DATE_TIME3=$ROW["NEXT_DATE_TIME3"]=="0000-00-00 00:00:00"?"":$ROW["NEXT_DATE_TIME3"];
      
      $FILTER_METHOD4=$ROW["FILTER_METHOD4"];
      $FILTER_DATE_TIME4=$ROW["FILTER_DATE_TIME4"]=="0000-00-00 00:00:00"?"":$ROW["FILTER_DATE_TIME4"];
      $FIRST_CONTENT4=$ROW["FIRST_CONTENT4"];
      $FIRST_VIEW4=$ROW["FIRST_VIEW4"];
      $TRANSACTOR_STEP4=$ROW["TRANSACTOR_STEP4"];
      $PASS_OR_NOT4=$ROW["PASS_OR_NOT4"];
      
     
      $query1 = "SELECT PLAN_NAME from HR_RECRUIT_PLAN where PLAN_NO='$PLAN_NO'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $PLAN_NAME=$ROW1["PLAN_NAME"]; 
         
      $NEXT_TRANSA_STEP_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP),0,-1);
      $TRANSACTOR_STEP_NAME=substr(GetUserNameById($TRANSACTOR_STEP),0,-1);  
      
      
      $NEXT_TRANSA_STEP1_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP1),0,-1);
      $TRANSACTOR_STEP1_NAME=substr(GetUserNameById($TRANSACTOR_STEP1),0,-1); 
      $FILTER_METHOD1=get_hrms_code_name($FILTER_METHOD1,"HR_RECRUIT_FILTER"); 
           
      $NEXT_TRANSA_STEP2_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP2),0,-1);
      $TRANSACTOR_STEP2_NAME=substr(GetUserNameById($TRANSACTOR_STEP2),0,-1); 
      $FILTER_METHOD2=get_hrms_code_name($FILTER_METHOD2,"HR_RECRUIT_FILTER"); 
      
      $NEXT_TRANSA_STEP3_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP3),0,-1);
      $TRANSACTOR_STEP3_NAME=substr(GetUserNameById($TRANSACTOR_STEP3),0,-1); 
      $FILTER_METHOD3=get_hrms_code_name($FILTER_METHOD3,"HR_RECRUIT_FILTER"); 
      
      $TRANSACTOR_STEP4_NAME=substr(GetUserNameById($TRANSACTOR_STEP4),0,-1); 
      $FILTER_METHOD4=get_hrms_code_name($FILTER_METHOD4,"HR_RECRUIT_FILTER");
   }
}
//if($END_FLAG==1||$STEP_FLAG==5)
	// header("location: filter_detail.php?FILTER_ID=$FILTER_ID");

$HTML_PAGE_TITLE = _("筛选办理");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/modul.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utilit.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calenda.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function read_info(recruiter)
{
   _get("load_recruiter.php","EXPERT_ID="+recruiter, show_info);
}
function show_info(req)
{
   if(req.status==200)
   {
      if(req.responseText!=";;")
      {
      	 var sliceOfArray = req.responseText.split(";")
         document.form1.POSITION.value=sliceOfArray[0];
         document.form1.EMPLOYEE_MAJOR.value=sliceOfArray[1];
         document.form1.EMPLOYEE_PHONE.value=sliceOfArray[2];
      }
      else
      	 alert("<?=_("没有这个人的信息")?>");
   }
}

function LoadWindow()
{
  URL="employee_name_select/?EXPERT_ID=<?=$EXPERT_ID?>";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
  	window.open(URL,"parent","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}

function LoadWindow2()
{
  URL="plan_no_info/?PLAN_NO=<?=$PLAN_NO?>";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
     window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
  	 window.open(URL,"parent","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}

function sendform(flag, step)
{
	if (flag==1)
	{
		document.form1.IS_FINISH.value='1';
	}
	else if (!checkform(step))
		return;
	document.form1.submit();
}

function checkform(STEP_FLAG)
{
		if(STEP_FLAG==1)
		{
			if(document.form1.PASS_OR_NOT1.value==1)
			{
			   	if(document.form1.NEXT_TRANSA_STEP1.value=="") { alert("<?=_("下一步筛选人不能为空")?>"); return false;}
			   	if(document.form1.NEXT_DATE_TIME1.value=="") { alert("<?=_("下一步筛选时间不能为空")?>"); return false;}	
			}
		}
		if(STEP_FLAG==2)
		{
			if(document.form1.PASS_OR_NOT2.value==1)
			{
			   if(document.form1.NEXT_TRANSA_STEP2.value=="") { alert("<?=_("下一步筛选人不能为空")?>"); return false;}		
			   if(document.form1.NEXT_DATE_TIME2.value=="") { alert("<?=_("下一步筛选时间不能为空")?>"); return false;}	 
			}  
			
		}
		if(STEP_FLAG==3)
		{
			if(document.form1.PASS_OR_NOT3.value==1)
			{
			   if(document.form1.NEXT_TRANSA_STEP3.value=="") { alert("<?=_("下一步筛选人不能为空")?>"); return false;}
			   if(document.form1.NEXT_DATE_TIME3.value=="") { alert("<?=_("下一步筛选时间不能为空")?>"); return false;}
			}  
		}
		if(STEP_FLAG==4)
		{
			if(document.form1.PASS_OR_NOT4.value=="") { alert("<?=_("请选择是否通过")?>"); return false;}
		}
		return true;
}

function next_play(STEP_FLAG)
{
	  if(STEP_FLAG==1)
		{
			if(document.form1.PASS_OR_NOT1.value==0)
			{
			   document.getElementById("NEXT1").style.display='none';
			   document.getElementById("BTN_NEXT").style.display='none';
			}
			else
			{
				document.getElementById("NEXT1").style.display='';
				document.getElementById("BTN_NEXT").style.display='';
			}
		}
		if(STEP_FLAG==2)
		{
			if(document.form1.PASS_OR_NOT2.value==0)
			{
			   document.getElementById("NEXT2").style.display='none';
			   document.getElementById("BTN_NEXT").style.display='none';
			}
			else
			{
				document.getElementById("NEXT2").style.display='';
				document.getElementById("BTN_NEXT").style.display='';
			}
			
		}
		if(STEP_FLAG==3)
		{
			if(document.form1.PASS_OR_NOT3.value==0)
			{
			   document.getElementById("NEXT3").style.display='none';
			   document.getElementById("BTN_NEXT").style.display='none';
			}
			else
			{
				document.getElementById("NEXT3").style.display='';
				document.getElementById("BTN_NEXT").style.display='';
			}
		}
	
}
function resetTime1()
{
   document.form1.FILTER_DATE_TIME1.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime2()
{
   document.form1.NEXT_DATE_TIME1.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime3()
{
   document.form1.FILTER_DATE_TIME2.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime4()
{
   document.form1.NEXT_DATE_TIME2.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime5()
{
   document.form1.FILTER_DATE_TIME3.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime6()
{
   document.form1.NEXT_DATE_TIME3.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime7()
{
   document.form1.FILTER_DATE_TIME4.value="<?=date("Y-m-d H:i:s",time())?>";
}
</script>


<body class="bodycolor" >
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("筛选办理")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="update_deal.php"  method="post" name="form1" onSubmit="return checkform('');">
<table class="TableBlock" width="80%" align="center" >
  <tr>
    <td nowrap class="TableData" colspan="4" align="center"><?=_("基本信息")?><?if($END_FLAG==2) {echo "<div style='color:red'>("._("已通过筛选").")</div>";} else if($END_FLAG==1) {echo "<div style='color:red'>("._("未通过筛选").")</div>";} else echo "<div style='color:red'>("._("待筛选").")</div>"; ?></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("应聘者姓名：")?></td>
    <td class="TableData" ><a href="#" onClick="javascript: window.open('../hr_pool/pool_detail.php?EXPERT_ID=<?=$EXPERT_ID?>','<?=_("详细信息")?>','toolbar=no,location=no,height=600px,width=800px,resizable=yes,scrollbars=yes')"><?=$EMPLOYEE_NAME?></a></td>
    <input type="hidden" name="EMPLOYEE_NAME" value="<?=$EMPLOYEE_NAME?>">
   <td nowrap class="TableData"><?=_("计划名称：")?></td>
    <td class="TableData"><?=$PLAN_NAME?></td>  
    <input type="hidden" name="PLAN_NAME" value="<?=$PLAN_NAME?>">
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("应聘岗位：")?></td>
    <td class="TableData" ><?=$POSITION?></td> 
    <input type="hidden" name="POSITION" value="<?=$POSITION?>">
    <td nowrap class="TableData"><?=_("所学专业：")?></td>
    <td class="TableData" ><?=$EMPLOYEE_MAJOR?></td>
    <input type="hidden" name="EMPLOYEE_MAJOR" value="<?=$EMPLOYEE_MAJOR?>">    
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("联系电话：")?></td>
    <td class="TableData"><?=$EMPLOYEE_PHONE?></td>
    <input type="hidden" name="EMPLOYEE_PHONE" value="<?=$EMPLOYEE_PHONE?>">
    <td nowrap class="TableData"><?=_("发起人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP_NAME?></td> 
    <input type="hidden" name="LOGIN_USER_ID" value="<?=$_SESSION["LOGIN_USER_ID"]?>">    
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("下一次筛选办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP_NAME?>    </td>
    <input type="hidden" name="$NEXT_TRANSA_STEP" value="<?=$$NEXT_TRANSA_STEP?>">
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME?>    </td> 
    <input type="hidden" name="NEXT_DATE_TIME" value="<?=$ONE_DATE_TIME?>">   
  </tr>
</table>
<?
if($STEP_FLAG==1 && $NEXT_TRANSA_STEP==$_SESSION["LOGIN_USER_ID"])
{
	$CHANGE=1;
?>
<br>
<table class="TableBlock" width="80%" align="center" id="table1">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤一")?></td>
  </tr>
 <tr>
    <td nowrap class="TableData"><?=_("初选时间：")?></td>
    <td class="TableData">
      <input type="text" name="FILTER_DATE_TIME1" size="20" maxlength="20" class="BigInput" value="<?=$FILTER_DATE_TIME1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime1();"><?=_("置为当前时间")?></a>
    </td>
    <td nowrap class="TableData"><?=_("初选方式：")?></td>
    <td class="TableData" >
      <select name="FILTER_METHOD1" title="<?=_("筛选方式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
        <?=hrms_code_list("HR_RECRUIT_FILTER","$FILTER_METHOD1")?>
      </select>
    </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("初选内容：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_CONTENT1" cols="77" rows="4" class="BigInput" value=""><?=$FIRST_CONTENT1?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("初选意见：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_VIEW1" cols="77" rows="4" class="BigInput" value=""><?=$FIRST_VIEW1?></textarea>
    </td>
  </tr>  
  <tr>
  	<td nowrap class="TableData"><?=_("初选办理人：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="TRANSACTOR_STEP1_NAME" size="15" class="BigStatic" readonly value="<?=$_SESSION["LOGIN_USER_NAME"]?>">
      <INPUT type="hidden" name="TRANSACTOR_STEP1" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
    </td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData">
      <select name="PASS_OR_NOT1" style="background: white;" title="" onChange="next_play(<?=$STEP_FLAG?>);">
        <option value="0" ><?=_("未通过")?></option>
        <option value="1" ><?=_("通过")?></option>
      </select>
    </td>
  </tr>
  <tr id="NEXT1" style="display:none">
    <td nowrap class="TableData"><?=_("下一步骤办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP1_NAME" size="15" class="BigStatic" readonly value="<?=$NEXT_TRANSA_STEP1_NAME?>">
      <INPUT type="hidden" name="NEXT_TRANSA_STEP1" value="<?=$NEXT_TRANSA_STEP1?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP1', 'NEXT_TRANSA_STEP1_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData">
     <input type="text" name="NEXT_DATE_TIME1" size="20" maxlength="20" class="BigInput" value="<?=$NEXT_DATE_TIME1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime2();"><?=_("置为当前时间")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("提醒：")?></td>
    <td class="TableData" colspan="3"><?=sms_remind(65);?></td>
  </tr>
</table>
<?
}else if($TRANSACTOR_STEP1_NAME!=""){
?>	
<br>
<table class="TableBlock" width="80%" align="center" id="table1">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤一")?></td>
  </tr>
 <tr>
    <td nowrap class="TableData"><?=_("初选时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME1?></td>
    <td nowrap class="TableData"><?=_("初选方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD1?> </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("初选内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT1?> </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("初选意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW1?></td>
  </tr>  
  <tr>
  	<td nowrap class="TableData"><?=_("初选办理人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP1_NAME?></td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT1==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("下一步骤办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP1_NAME?></td>
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME1?> </td>
  </tr>
</table>
<?
}
if($STEP_FLAG==2 && $NEXT_TRANSA_STEP1==$_SESSION["LOGIN_USER_ID"])
{
	$CHANGE=1;
?>
<br>
<table class="TableBlock" width="80%" align="center" id="table2">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤二：")?></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("复选日期：")?></td>
    <td class="TableData">
      <input type="text" name="FILTER_DATE_TIME2" size="20" maxlength="20" class="BigInput" value="<?=$FILTER_DATE_TIME2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime3();"><?=_("置为当前时间")?></a>
    </td>
    <td nowrap class="TableData"><?=_("复选方式：")?></td>
    <td class="TableData" >
      <select name="FILTER_METHOD2" style="background: white;" title="<?=_("筛选方式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
        <?=hrms_code_list("HR_RECRUIT_FILTER","$FILTER_METHOD2")?>
      </select>
    </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("复选内容：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_CONTENT2" cols="67" rows="4" class="BigInput" value=""><?=$FIRST_CONTENT2?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("复选意见：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_VIEW2" cols="67" rows="4" class="BigInput" value=""><?=$FIRST_VIEW2?></textarea>
    </td>
  </tr>  
  <tr>
  	<td nowrap class="TableData"><?=_("当前办理人：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="TRANSACTOR_STEP2_NAME" size="15" class="BigStatic" readonly value="<?=$_SESSION["LOGIN_USER_NAME"]?>">&nbsp;
      <INPUT type="hidden" name="TRANSACTOR_STEP2" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
      
    </td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData">
      <select name="PASS_OR_NOT2" style="background: white;" title="" onChange="next_play(<?=$STEP_FLAG?>);">
        <option value="0" ><?=_("未通过")?></option>
        <option value="1" ><?=_("通过")?></option>
      </select>
    </td>
  </tr>
  <tr id="NEXT2" style="display:none">
    <td nowrap class="TableData"><?=_("下一步骤办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP2_NAME" size="15" class="BigStatic" readonly value="<?=$NEXT_TRANSA_STEP2_NAME?>">&nbsp;
      <INPUT type="hidden" name="NEXT_TRANSA_STEP2" value="<?=$NEXT_TRANSA_STEP2?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP2', 'NEXT_TRANSA_STEP2_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData">
      <input type="text" name="NEXT_DATE_TIME2" size="20" maxlength="20" class="BigInput" value="<?=$NEXT_DATE_TIME2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime4();"><?=_("置为当前时间")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("提醒：")?></td>
    <td class="TableData" colspan=3><?=sms_remind(65);?></td>
  </tr>
</table>
<?
}else if($TRANSACTOR_STEP2_NAME!=""){
?>	
	<br>
<table class="TableBlock" width="80%" align="center" id="table1">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤二")?></td>
  </tr>
 <tr>
    <td nowrap class="TableData"><?=_("复选时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME2?></td>
    <td nowrap class="TableData"><?=_("复选方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD2?> </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("复选内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT2?> </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("复选意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW2?></td>
  </tr>  
  <tr>
  	<td nowrap class="TableData"><?=_("复选办理人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP2_NAME?></td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT2==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("下一步骤办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP2_NAME?></td>
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME2?> </td>
  </tr>
</table>
<?	
}
if($STEP_FLAG==3  && $NEXT_TRANSA_STEP2==$_SESSION["LOGIN_USER_ID"])
{
	$CHANGE=1;
?>
<br>
<table class="TableBlock" width="80%" align="center" id="table3">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤三：")?></td>
  </tr>
 </tr>
 <tr>
    <td nowrap class="TableData"><?=_("决选日期：")?></td>
    <td class="TableData">
      <input type="text" name="FILTER_DATE_TIME3" size="20" maxlength="20" class="BigInput" value="<?=$FILTER_DATE_TIME3?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime5();"><?=_("置为当前时间")?></a>
    </td>
    <td nowrap class="TableData"><?=_("决选方式：")?></td>
    <td class="TableData" >
      <select name="FILTER_METHOD3" style="background: white;" title="<?=_("筛选方式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
        <?=hrms_code_list("HR_RECRUIT_FILTER","$FILTER_METHOD3")?>
      </select>
    </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("决选内容：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_CONTENT3" cols="67" rows="4" class="BigInput" value=""><?=$FIRST_CONTENT3?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("决选意见：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_VIEW3" cols="67" rows="4" class="BigInput" value=""><?=$FIRST_VIEW3?></textarea>
    </td>
  </tr>  
  <tr>
  	<td nowrap class="TableData"><?=_("当前办理人：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="TRANSACTOR_STEP3_NAME" size="15" class="BigStatic" readonly value="<?=$_SESSION["LOGIN_USER_NAME"]?>">&nbsp;
      <INPUT type="hidden" name="TRANSACTOR_STEP3" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
    </td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData">
      <select name="PASS_OR_NOT3" style="background: white;" title="" onChange="next_play(<?=$STEP_FLAG?>);">
        <option value="0" ><?=_("未通过")?></option>
        <option value="1" ><?=_("通过")?></option>
      </select>
    </td>
  </tr>
  <tr id="NEXT3" style="display:none">
    <td nowrap class="TableData"><?=_("下一步骤办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP3_NAME" size="15" class="BigStatic" readonly value="<?=$NEXT_TRANSA_STEP3_NAME?>">&nbsp;
      <INPUT type="hidden" name="NEXT_TRANSA_STEP3" value="<?=$NEXT_TRANSA_STEP3?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP3', 'NEXT_TRANSA_STEP3_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData">
      <input type="text" name="NEXT_DATE_TIME3" size="20" maxlength="20" class="BigInput" value="<?=$NEXT_DATE_TIME3?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime6();"><?=_("置为当前时间")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("提醒：")?></td>
    <td class="TableData" colspan=3><?=sms_remind(65);?></td>
  </tr>
</table>
<?
}else if($TRANSACTOR_STEP3_NAME!=""){
?>
<br>
<table class="TableBlock" width="80%" align="center" id="table1">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤三")?></td>
  </tr>
 <tr>
    <td nowrap class="TableData"><?=_("决选时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME3?></td>
    <td nowrap class="TableData"><?=_("决选方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD3?> </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("决选内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT3?> </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("决选意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW3?></td>
  </tr>  
  <tr>
  	<td nowrap class="TableData"><?=_("决选办理人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP3_NAME?></td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT3==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("下一步骤办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP3_NAME?></td>
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME3?> </td>
  </tr>
</table>
<?
} 
if($STEP_FLAG==4  && $NEXT_TRANSA_STEP3==$_SESSION["LOGIN_USER_ID"])
{	
	$CHANGE=1;
?>
<br>

<table class="TableBlock" width="80%" align="center" id="table4">
 <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤四：")?></td>
  </tr>
 </tr>
    <tr>
    <td nowrap class="TableData"><?=_("加试日期：")?></td>
    <td class="TableData">
      <input type="text" name="FILTER_DATE_TIME4" size="20" maxlength="20" class="BigInput" value="<?=$FILTER_DATE_TIME4?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime7();"><?=_("置为当前时间")?></a>
    </td>
    <td nowrap class="TableData"><?=_("加试方式：")?></td>
    <td class="TableData" >
      <select name="FILTER_METHOD4" style="background: white;" title="<?=_("筛选方式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
        <?=hrms_code_list("HR_RECRUIT_FILTER","$FILTER_METHOD4")?>
      </select>
    </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("加试内容：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_CONTENT4" cols="67" rows="4" class="BigInput" value=""><?=$FIRST_CONTENT4?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("加试意见：")?></td>
    <td class="TableData" colspan=3>
      <textarea name="FIRST_VIEW4" cols="67" rows="4" class="BigInput" value=""><?=$FIRST_VIEW4?></textarea>
    </td>
  </tr>  
  	<tr>
  	<td nowrap class="TableData"><?=_("当前办理人：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="TRANSACTOR_STEP4_NAME" size="15" class="BigStatic" readonly value="<?=$_SESSION["LOGIN_USER_NAME"]?>">&nbsp;
      <INPUT type="hidden" name="TRANSACTOR_STEP4" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
    </td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData">
      <select name="PASS_OR_NOT4" style="background: white;" title="">
        <option value="0" <? if($PASS_OR_NOT4==0) echo "selected";?>><?=_("未通过")?></option>
        <option value="1" <? if($PASS_OR_NOT4==1) echo "selected";?>><?=_("通过")?></option>
      </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("提醒：")?></td>
    <td class="TableData" colspan=3><?=sms_remind(65);?></td>
  </tr>
</table>
<?
}else if($TRANSACTOR_STEP4_NAME!=""){	
?>
<br>
<table class="TableBlock" width="80%" align="center" id="table1">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center" ><?=_("筛选步骤四")?></td>
  </tr>
 <tr>
    <td nowrap class="TableData"><?=_("加试时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME4?></td>
    <td nowrap class="TableData"><?=_("加试方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD4?> </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("加试内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT4?> </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("加试意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW4?></td>
  </tr>  
  <tr>
  	<td nowrap class="TableData"><?=_("加试办理人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP4_NAME?></td>
    <td nowrap class="TableData"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT4==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
</table>
<?
}
if($CHANGE==1)
{
?>
<table class="TableBlock" width="80%" align="center">
  <tr align="center" class="TableControl">
    <td colspan=4 nowrap>
    	<input type="hidden" name="STEP_FLAG" value="<?=$STEP_FLAG?>">
    	<input type="hidden" name="FILTER_ID" id="FILTER_ID" value="<?=$FILTER_ID?>">
      <input type="button" id="BTN_NEXT" value="<?=_("下一步骤")?>" class="BigButton" style="display:none" onClick="sendform('0', '<?=$STEP_FLAG?>');">&nbsp;
	    <input type="button" id="BTN_FINISH" value="<?=_("结束筛选")?>" class="BigButton"  onclick="sendform('1', '<?=$STEP_FLAG?>');">&nbsp;
	    <input type="hidden" id="IS_FINISH" name="IS_FINISH" value="0">
    </td>
  </tr>
</table>
<?
}else
{
?>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';">
</div>
<?
}
?>
</form>
</body>
</html>