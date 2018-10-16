<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("联系人查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script Language="JavaScript">
function query()
{
   document.form1.action='search_submit_oa.php';
   document.form1.target='_self';
   document.form1.submit();
}

(function($){
	jQuery(document).ready(function(){
		var dateLangConfigs = {
			monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
            dayNamesShort: ['日','一','二','三','四','五','六']
		};

		$.fn.datepicker.dates = {
			days: dateLangConfigs['dayNames'],
			daysShort: dateLangConfigs['dayNamesShort'],
			daysMin: dateLangConfigs['dayNamesShort'],
			months: dateLangConfigs['monthNames'],
			monthsShort:  dateLangConfigs['monthNamesShort']
		};
		$('.calendar-startdate, .calendar-enddate').datepicker({
		   format: "yyyy-m-d"
		}); 
		$('.calendar-starttime, .calendar-endtime').timepicker({ 
		   minuteStep: 5
		});
	});
})(jQuery);
</script>

<body class="bodycolor">
<div style="width:600px; margin:0 auto; height:50px; line-height:50px;">
   <span class="big3"> <?=_("联系人查询")?></span>&nbsp;
   <button type="button" onClick="javascript:window.location.href='search.php'" class="btn btn-info"><?=_("外部联系人")?></button>
</div>
<div style="width:600px;border: #EEE0E0 1px solid;margin:0 auto; background-color:#FFF; padding:20px 0 20px 0">
  <form action="search_submit.php"  method="post" name="form1" class="form-horizontal">
   <!--<div class="control-group">
        <label class="control-label"><?=_("姓名：")?></label>
        <div class="controls">
            <input name="USER_NAME" type="text">
        </div>
    </div>-->
    
    <div class="control-group">
        <label class="control-label" for="GROUP_ID"><?=_("部门：")?></label>
        <div class="controls">
            <select name="DEPT_ID">
          		<option value=""></option>
                <?
                echo my_dept_tree(0,$DEPT_ID,1);
				if($DEPT_ID==0)
				{
				?>
          		<option value="0"><?=_("离职人员/外部人员")?></option>
				<?
                }
				?>
            </select>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("角色：")?></label>
        <div class="controls">
            <select name="USER_PRIV" id="USER_PRIV">
              <option value=""></option>
			  <?
              $query = "SELECT * from USER_PRIV order by PRIV_NO desc";
			  $cursor= exequery(TD::conn(),$query);
			  while($ROW=mysql_fetch_array($cursor))
			  {
				  $USER_PRIV=$ROW["USER_PRIV"];
				  $PRIV_NAME=$ROW["PRIV_NAME"];
			 ?>
              <option value="<?=$USER_PRIV?>"><?=$PRIV_NAME?></option>
			  <?
              }
			 ?>
          </select>            
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("移动电话：")?></label>
        <div class="controls">
            <input name="MOBIL_NO" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("办公电话：")?></label>
        <div class="controls">
            <input name="TEL_NO_DEPT" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("住宅电话：")?></label>
        <div class="controls">
            <input name="TEL_NO_HOME" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("QQ：")?></label>
        <div class="controls">
            <input name="OICQ_NO" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("EMAIL：")?></label>
        <div class="controls">
            <input name="EMAIL" type="text">
        </div>
    </div>
    <input type="hidden" name="ORDER" value="dept">
    <div class="controls queryButtonGroup" style="text-align: center;margin-left:0px;">
        <button type="button" onClick="query();" class="btn btn-primary"><?=_("查询")?></button>
        <button type="button" onClick="self.close();" class="btn"><?=_("关闭")?></button>
    </div>
</form>
</div>
</body>
</html>