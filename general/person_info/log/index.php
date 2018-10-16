<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/ip2add.php");

include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/person_info.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/person_info/index.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>


<body class="bodycolor">
<div class="pageheader" style="padding-top: 20px;padding-bottom: 0px;">
   <span class="big3"> <?=_("最近20条系统安全日志")?></span>
</div>
<?
 $query = "SELECT * from SYS_LOG where (TYPE='1' or TYPE='2' or TYPE='14') and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by TIME desc";
 $cursor= exequery(TD::conn(),$query);
 if(mysql_num_rows($cursor)==0)
 {
    Message("",_("无系统安全日志记录"));
    exit;
 }
?>
<table class="table table-bordered table-hover" style="width:90%;">
    <thead style="background-color:#EBEBEB;">
      <th style="text-align: center;"><?=_("用户")?></td>
      <th style="text-align: center;"><?=_("时间")?></td>
      <th style="text-align: center;">IP<?=_("地址")?></td>
      <th style="text-align: center;">IP<?=_("所在地")?></td>
      <th style="text-align: center;"><?=_("类型")?></td>
      <th style="text-align: center;"><?=_("备注")?></td>
    </thead>
<?
 $LOG_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $LOG_COUNT++;
    if($LOG_COUNT>20)
	{
		break;
	}
       
    $TIME      = $ROW["TIME"];
    $IP        = $ROW["IP"];
    $TYPE      = $ROW["TYPE"];
    $REMARK    = $ROW["REMARK"];
    $IP_ADD    = convertip($IP);
    $TYPE_DESC = get_code_name($TYPE,"SYS_LOG");

    if($LOG_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="TableData" style="background:#fff">
      <td nowrap style="text-align: center;"><?=$_SESSION["LOGIN_USER_NAME"]?></td>
      <td nowrap style="text-align: center;"><?=$TIME?></td>
      <td nowrap style="text-align: center;"><?=$IP?></td>
      <td nowrap style="text-align: center;"><?=$IP_ADD?></td>
      <td nowrap style="text-align: center;"><?=$TYPE_DESC?></td>
      <td><?=$REMARK?></td>
    </tr>
<?
 }
?>
</table>

</body>
</html>
