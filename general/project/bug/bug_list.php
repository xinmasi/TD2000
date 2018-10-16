<?
include_once("inc/auth.inc.php");

function level_desc($level)
{
    switch ($level)
    {
        case "1": return _("非常高");
        case "2": return _("高");
        case "3": return _("普通");
        case "4": return _("低");
    }
}

if ($HISTORY == 1)
    $TITLE = _("已解决问题");
else
    $TITLE = _("待解决项目问题");

$HTML_PAGE_TITLE = _("项目问题");
include_once("inc/header.inc.php");

//----zfc----2013-12-2
$field = isset($_GET['field']) ? $_GET['field'] : "LEVEL";
$order = isset($_GET['order']) ? $_GET['order'] : "DESC";



?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />


<link rel="stylesheet" type="text/css" href="<?= MYOA_STATIC_SERVER ?>/static/theme/<?= $_SESSION["LOGIN_THEME"] ?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?= MYOA_STATIC_SERVER ?>/static/theme/<?= $_SESSION["LOGIN_THEME"] ?>/calendar.css">
<script src="<?= MYOA_JS_SERVER ?>/static/js/dialog.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>


<script type="text/javascript">
    jQuery.noConflict();
    function showDetail(bug_id, flag)
    {
        if(flag == 1)
            jQuery("#stat" + flag).html("<font color=green><?= _("办理中") ?></font>");
        jQuery.get("detail.php?BUG_ID=" + bug_id, function(data){
            jQuery("#detail_body").html(data);
            ShowDialog('detail');
        });
    }
    //////------------------zfc------------
    var showProj2 = false;
    function showProj(PROJ_ID)
    {
        if(showProj2)
            showProj2.close();
        //myleft = (screen.availWidth - 800) / 2;
		var mywidth=screen.availWidth-25;
		var myheight=screen.availHeight-70;
        showProj2 = window.open("../portal/details/?PROJ_ID=" + PROJ_ID, "", "height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=0,left=0,resizable=yes");
    }
    function do_bug(bug_id)
    {
        document.form1.BUG_ID.value = bug_id;
        ShowDialog('do');
    }
    function check_form()
    {
        if(document.form1.RESULT.value == "")
        {
            return(false);
        }
        else
        {
            return(true);
        }
    }
    
    
    function order_by(field,order){
        if(order == "DESC")
            order = "ASC";
        else
            order = "DESC";
        
		var HISTORY = "&HISTORY=<?= isset($HISTORY)?$HISTORY:""?>";
		
        location.href="bug_list.php?field="+field+"&order="+order + HISTORY;    
        
    }
    

    
</script>

<style>
.table th, .table td {
    text-align: center;
}
</style>

<body style="padding:10px">

	
        <?
        $img = ($order == "DESC") ? "arrow_down" : "arrow_up";
        $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/$img.gif\" width=\"11\" height=\"10\">";
        $COUNT = 0;
        $query = "select a.*,b.USER_NAME FROM PROJ_BUG AS a LEFT JOIN USER AS b ON (a.BEGIN_USER=b.USER_ID) where DEAL_USER='" . $_SESSION["LOGIN_USER_ID"] . "'";
        if ($HISTORY == 1)
            $query .= " AND STATUS=3";
        else
            $query .= " AND STATUS IN (1,2)";
        $query .=" ORDER BY {$field} {$order}";
        $cursor = exequery(TD::conn(), $query);
        while ($ROW = mysql_Fetch_array($cursor))
        {
            $COUNT++;
            if ($COUNT == 1)
            {
			?>
		<table class="table table-bordered">
		<tr class="info" style="color:#2a70e9;">
			<td colspan="7"><strong><?= $TITLE?></strong></td>
		</tr>
		<tr class="info" style="color:#2a70e9;">
            <td STYLE="WIDTH:400PX;" onclick="order_by('PROJ_ID','<?=_($order);?>')">项目名称<?if($field == 'PROJ_ID') echo $ORDER_IMG;?></td>
            <td onclick="order_by('BUG_NAME','<?=_($order);?>')">问题名称<?if($field == 'BUG_NAME') echo $ORDER_IMG;?></td>
            <td onclick="order_by('BEGIN_USER','<?=_($order);?>')">提交人<?if($field == 'BEGIN_USER') echo $ORDER_IMG;?></td>
            <td width="120px" onclick="order_by('DEAD_LINE','<?=_($order);?>')">处理底线<?if($field == 'DEAD_LINE') echo $ORDER_IMG;?></td>
            <td width="120px">优先级</td>
            <td width="120px">状态</td>
            <td>操作</td>
		</tr>
<?
            }


            switch ($ROW["STATUS"])
            {
                case 1:
                    $STATUS_DESC = '<font color=red>' . _("未接收") . '</font>';
                    break;
                case 2:
                    $STATUS_DESC = '<font color=green>' . _("处理中") . '</font>';
                    break;
                case 3:
                    $STATUS_DESC = '<font color=blue>' . _("已反馈") . '</font>';
                    break;
                default:
                    break;
            }

            $PROJ_ID = $ROW["PROJ_ID"];
            $query1 = "select PROJ_NAME FROM PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
            $cursor1 = exequery(TD::conn(), $query1);
            if ($ROW1 = mysql_Fetch_array($cursor1))
            {
                $PROJ_NAME = $ROW1["PROJ_NAME"];
            }

            echo '<tr> 
                    <td align="center"><a href="#" onclick=showProj(' . $PROJ_ID . ')>' . $PROJ_NAME . '</a></td>
                    <td align="center"><a href="#" onclick="showDetail(' . $ROW["BUG_ID"] . ',' . $ROW["STATUS"] . ')">' . $ROW["BUG_NAME"] . '</a></td>
                    <td nowrap align="center">' . $ROW["USER_NAME"] . '</td>
                    <td nowrap align="center">' . $ROW["DEAD_LINE"] . '</td>
                  <td nowrap align="center"><span class="CalLevel' . $ROW["LEVEL"] . '" title="' . level_desc($ROW["LEVEL"]) . '">' . level_desc($ROW["LEVEL"]) . '</span></td>
                    <td nowrap align="center" id="stat_' . $ROW["BUG_ID"] . '">' . $STATUS_DESC . '</td>
                    <td nowrap align="center">
                     <a href="#" onclick="showDetail(' . $ROW["BUG_ID"] . ',' . $ROW["PROJ_ID"] . ')">详情</a>&nbsp;';
            if ($ROW["STATUS"] == 1 || $ROW["STATUS"] == 2)
            {
                echo '<a href="#" onclick="do_bug(' . $ROW["BUG_ID"] . ')">办理</a>&nbsp;';
            }
            echo '</td></tr>';
        }
        ?>			
	

    
    <?
    if ($COUNT == 0)
    {
        Message('', _("没有待处理项目问题！"));
    }else{
		?>
		</table>
		<?
	}
    ?>

	
	
	
    <div id="overlay"></div>
    <div id="detail" class="ModalDialog" style="width:550px;">
        <div class="header"><span id="title" class="title"><?= _("项目问题详情") ?></span><a class="operation" href="javascript:HideDialog('detail');"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/close.png"/></a></div>
        <div id="detail_body" class="body">
        </div>
        <div id="footer" class="footer">
            <input class="BigButton" onclick="HideDialog('detail')" type="button" value="<?= _("关闭") ?>"/>
        </div>
    </div>

    <div id="do" class="ModalDialog" style="width:500px;">
        <div class="header"><span id="title" class="title"><?= _("项目问题处理") ?></span><a class="operation" href="javascript:HideDialog('do');"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/close.png"/></a></div>
        <form action="do.php" method="post" name="form1" onsubmit="return check_form();">
            <div id="do_body" class="body">
                <table class="TableList" border=0 align="center">
                    <tr>
                        <td class="TableContent"><?= _("汇报时间：") ?></td>
                        <td class="TableData"><?= date("Y-m-d H:i:s", time()) ?></td>
                    </tr>
                    <tr>
                        <td class="TableContent"><?= _("处理结果汇报：") ?></td>
                        <td class="TableData"><textarea class="BigInput" rows=5 cols=50 name="RESULT"></textarea></td>
                    </tr>
                </table>
            </div>
            <div id="footer" class="footer">
                <input type="hidden" name="BUG_ID"/>
                <input class="BigButton" type="submit" value="<?= _("确定") ?>"/>
                <input class="BigButton" onclick="HideDialog('do')" type="button" value="<?= _("关闭") ?>"/>
            </div>
        </form>
    </div>

</body>
</html>