<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("��ϵ�˲�ѯ");
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
   document.form1.action='search_submit.php';
   document.form1.target='_self';
   document.form1.submit();
}

function export_excel()
{
   document.form1.action='export_excel.php';
   document.form1.target='_blank';
   document.form1.submit();
}

(function($){
	jQuery(document).ready(function(){
		var dateLangConfigs = {
			monthNames: ['һ��','����','����','����','����','����','����','����','����','ʮ��','ʮһ��','ʮ����'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            dayNames: ['������','����һ','���ڶ�','������','������','������','������'],
            dayNamesShort: ['��','һ','��','��','��','��','��']
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
   <span class="big3"> <?=_("��ϵ�˲�ѯ")?></span>&nbsp;
   <button type="button" onClick="javascript:window.location.href='search_oa.php'" class="btn btn-info"><?=_("�ڲ���ϵ��")?></button>
</div>
<div style="width:600px;border: #EEE0E0 1px solid;margin:0 auto; background-color:#FFF; padding:20px 0 20px 0">
  <form action="search_submit.php"  method="post" name="form1" class="form-horizontal">
    <div class="control-group">
        <label class="control-label"><?=_("������")?></label>
        <div class="controls">
            <input name="PSN_NAME" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="GROUP_ID"><?=_("���飺")?></label>
        <div class="controls">
            <select name="GROUP_ID">
                <option value="ALL_DEPT" selected><?=_("����")?></option>
                <option value="0"><?=_("Ĭ��")?></option>
            
<?
            $query1 = "select * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by GROUP_ID asc";
            $cursor1= exequery(TD::conn(),$query1);
            while($ROW1=mysql_fetch_array($cursor1))
            {
                $GROUP_ID1      = $ROW1["GROUP_ID"];
                $GROUP_NAME1    = $ROW1["GROUP_NAME"];
?>
                <option value="<?=$GROUP_ID1?>"><?=$GROUP_NAME1?></option>
<?
            }
            $query = "SELECT * from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";
            $cursor= exequery(TD::conn(),$query);
            while($ROW=mysql_fetch_array($cursor))
            {
                $GROUP_ID=$ROW["GROUP_ID"];
                $GROUP_NAME=$ROW["GROUP_NAME"]._("(����)");
                $PRIV_DEPT=$ROW["PRIV_DEPT"];
                $PRIV_ROLE=$ROW["PRIV_ROLE"];
                $PRIV_USER=$ROW["PRIV_USER"];
                if($PRIV_DEPT!="ALL_DEPT")
                {
                    if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) && !check_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)!="" && !check_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)!="")
                    {
                        continue;
                    }
                }
?>
                <option value="<?=$GROUP_ID?>"><?=$GROUP_NAME?></option>
<?
            }
?>
            </select>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("�ƶ��绰��")?></label>
        <div class="controls">
            <input name="MOBIL_NO" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("��λ��")?></label>
        <div class="controls">
            <input name="DEPT_NAME" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("�칫�绰��")?></label>
        <div class="controls">
            <input name="TEL_NO_DEPT" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("סլ�绰��")?></label>
        <div class="controls">
            <input name="TEL_NO_HOME" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("�칫��ַ��")?></label>
        <div class="controls">
            <input name="ADD_DEPT" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("סլ��ַ��")?></label>
        <div class="controls">
            <input name="ADD_HOME" type="text">
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?=_("��ע��")?></label>
        <div class="controls">
            <input name="NOTES" type="text">
        </div>
    </div>
    
    <div class="controls queryButtonGroup" style="text-align: center;margin-left:0px;">
        <button type="button" onClick="query();" class="btn btn-primary"><?=_("��ѯ")?></button>
        <!--<button type="button" onClick="export_excel();" class="btn"><?=_("����EXCEL")?></button>-->
        <button type="button" onClick="self.close();" class="btn"><?=_("�ر�")?></button>
    </div>
</form>
</div>
</body>
</html>