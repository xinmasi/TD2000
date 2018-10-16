<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("招聘筛选查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>

<body class="bodycolor">
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"><?=_("招聘筛选查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="post" id="form1" name="form1">
<table class="TableBlock" width="500" align="center">
  <tr>
    <td nowrap class="TableData"><?=_("应聘者姓名：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="EMPLOYEE_NAME" class=BigInput size="15"  value="">
    </td>
    <td nowrap class="TableData"><?=_("计划名称：")?></td>
    <td class="TableData">
     <select name="PLAN_NAME" class="BigSelect">
     	  <option value=""></option>

<?
      $query = "SELECT PLAN_NAME,ADD_TIME,PLAN_NO from HR_RECRUIT_PLAN order by ADD_TIME desc";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $PLAN_NAME=$ROW["PLAN_NAME"];
         $PLAN_NO=$ROW["PLAN_NO"];

?>
          <option value="<?=$PLAN_NO?>"><?=$PLAN_NAME?></option>
<?
      }
?>
     </select>
    </td>  
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("应聘岗位：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="POSITION" class=BigInput size="15" value="">
    </td> 
    <td nowrap class="TableData"><?=_("所学专业：")?></td>
    <td class="TableData" >
      <INPUT type="text"name="EMPLOYEE_MAJOR" class=BigInput size="15" value="">
    </td>    
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("联系电话：")?></td>
    <td class="TableData">
      <INPUT type="text"name="EMPLOYEE_PHONE" class="BigInput" size="15" value="">
    </td>
    <td nowrap class="TableData"><?=_("发起人：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="TRANSACTOR_STEP_NAME" size="15" class="BigStatic" readonly value="">
      <INPUT type="hidden" name="TRANSACTOR_STEP" value="<?=$NEXT_TRANSA_STEP?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','TRANSACTOR_STEP', 'TRANSACTOR_STEP_NAME')"><?=_("选择")?></a>
    </td>     
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("初选办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP1_NAME" size="15" class="BigStatic" readonly value="">
      <INPUT type="hidden" name="NEXT_TRANSA_STEP1" value="<?=$NEXT_TRANSA_STEP?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP1', 'NEXT_TRANSA_STEP1_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData"><?=_("复选办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP2_NAME" size="15" class="BigStatic" readonly value="">
      <INPUT type="hidden" name="NEXT_TRANSA_STEP2" value="<?=$NEXT_TRANSA_STEP2?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP2', 'NEXT_TRANSA_STEP2_NAME')"><?=_("选择")?></a>
    </td>
  </tr>
  <tr>
  	<td nowrap class="TableData"><?=_("决选办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP3_NAME" size="15" class="BigStatic" readonly value="">
      <INPUT type="hidden" name="NEXT_TRANSA_STEP3" value="<?=$NEXT_TRANSA_STEP3?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP3', 'NEXT_TRANSA_STEP3_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData"><?=_("加试办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP4_NAME" size="15" class="BigStatic" readonly value="">
      <INPUT type="hidden" name="NEXT_TRANSA_STEP4" value="<?=$NEXT_TRANSA_STEP4?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP4', 'NEXT_TRANSA_STEP4_NAME')"><?=_("选择")?></a>
    </td>
  </tr>
    <tr>
  	<td nowrap class="TableData"><?=_("筛选状态：")?></td>
    <td class="TableData" colspan=3>
      <select name="STATUS" >
      	 <option value=""></option>
         <option value="1"><?=_("待筛选")?></option>
         <option value="2"><?=_("未通过")?></option>	
         <option value="3"><?=_("已通过")?></option>
      </select>
    </td>
  </tr>
	<tr align="center" class="TableControl">
	  <td colspan="6" nowrap>
	    <INPUT type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
	  </td>
 	</tr>          
</table>
</form>

</table>
</body>
</html>