<?
    include_once("inc/auth.inc.php");
    $HTML_PAGE_TITLE = _("�ֻ�����");
    $HTML_PAGE_META_FOR_MAP = true;
    $HTML_PAGE_BASE_STYLE = false;
    include_once("inc/header.inc.php");
    require_once "inc/utility_all.php";

    if(!$mid || !is_numeric($mid))
        exit;

	$THE_UID = 0;
	$query = "SELECT UID from USER where USER_ID='$USER_ID'";
	$cursor = exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor)){
		$THE_UID = $ROW['UID'];
	}
    //�ϰ�ʱ���ѯ
    $query1="select DUTY_TYPE from USER_EXT where UID='".$THE_UID."'";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
        $DUTY_TYPE = $ROW["DUTY_TYPE"];
        if($DUTY_TYPE!="99")
        {
            //---- ȡ�涨���°�ʱ�� -----
            $DUTY_TYPE=intval($DUTY_TYPE);
            $query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $DUTY_NAME=$ROW["DUTY_NAME"];
                $GENERAL=$ROW["GENERAL"];

                $DUTY_TIME1=$ROW["DUTY_TIME1"];
                $DUTY_TIME2=$ROW["DUTY_TIME2"];
                $DUTY_TIME3=$ROW["DUTY_TIME3"];
                $DUTY_TIME4=$ROW["DUTY_TIME4"];
                $DUTY_TIME5=$ROW["DUTY_TIME5"];
                $DUTY_TIME6=$ROW["DUTY_TIME6"];

                $DUTY_TYPE1=$ROW["DUTY_TYPE1"];
                $DUTY_TYPE2=$ROW["DUTY_TYPE2"];
                $DUTY_TYPE3=$ROW["DUTY_TYPE3"];
                $DUTY_TYPE4=$ROW["DUTY_TYPE4"];
                $DUTY_TYPE5=$ROW["DUTY_TYPE5"];
                $DUTY_TYPE6=$ROW["DUTY_TYPE6"];
            }
        }
    }

    //��ȡ���β鿴����Ϣ
    $sql = "SELECT * FROM ATTEND_MOBILE M, ATTEND_DUTY D WHERE M.M_ID = D.ATTEND_MOBILE_ID AND M.M_ID = $mid AND M.M_UID = '".$THE_UID."'";
    $cursor= exequery(TD::conn(),$sql, true);
    if($ROW=mysql_fetch_array($cursor))
    {
        $REGISTER_TYPE = $ROW["REGISTER_TYPE"];
        $REGISTER_TIME = $ROW["REGISTER_TIME"];
        $REMARK = $ROW["REMARK"];
        $M_LOCATION = $ROW["M_LOCATION"];

        $M_LNG = $ROW["M_LNG"];
        $M_LAT = $ROW["M_LAT"];
    }

    $STR="DUTY_TIME".$REGISTER_TYPE;
    $DUTY_TIME=$$STR;

    $STR="DUTY_TYPE".$REGISTER_TYPE;
    $DUTY_TYPE=$$STR;

    if($DUTY_TYPE=="1")
        $DUTY_TYPE_NAME=_("�ϰ�");
    else
        $DUTY_TYPE_NAME=_("�°�");

    $TYPE = 'success';
    $REGISTER_TIME_DESC = _("����");

    if($REGISTER_TIME!="")
    {
        $REGISTER_TIME = strtok($REGISTER_TIME," ");
        $REGISTER_TIME = strtok(" ");

        if($DUTY_TYPE=="1" && compare_time($REGISTER_TIME,$DUTY_TIME)==1)
        {$TYPE = 'important';$REGISTER_TIME_DESC = _("�ٵ�");}

        if($DUTY_TYPE=="2" && compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
        {$TYPE = 'warning';$REGISTER_TIME_DESC = _("����");}
    }

    if($REGISTER_TIME[0] == "0")
        $REGISTER_TIME = substr($REGISTER_TIME, 1);

    if($M_LNG == "" || $M_LAT == "")
    {
        echo _('������Ϣȱʧ');
        exit;
    }

?>
<body>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=($_SESSION["LOGIN_THEME"] == "" ? "1" : td_htmlspecialchars($_SESSION["LOGIN_THEME"]))?>/style.css" />
<style type="text/css">
#map{height: 300px;}
.table td{color: #666;}
</style>
<div id="info">
    <table class="table">
        <colgroup>
            <col width="100" />
            <col />
        </colgroup>
        <tbody>
            <tr>
                <th><?=sprintf(_("�涨%sʱ��"), $DUTY_TYPE_NAME)?></th>
                <td><?=$DUTY_TIME?></td>
                <th><?=sprintf(_("ʵ��%sʱ��"), $DUTY_TYPE_NAME)?></th>
                <td><?=$REGISTER_TIME?></td>
            </tr>
            <tr>
                <th><?=_("�򿨽��")?></th>
                <td><span class="label label-<?=$TYPE?>"><?=$REGISTER_TIME_DESC?></span></td>
                <th><?=_("�򿨵ص�")?></th>
                <td><?=$M_LOCATION?></td>
            </tr>
            <? if($REMARK!=""){ ?>
            <tr>
                <th><?=_("����˵��")?></th>
                <td colspan="3"><?=$REMARK?></td>
            </tr>
            <? } ?>
        </tbody>
    </table>
</div>
<div id="map"><?=_("��ͼ��Ϣ������...")?></div>
<script type="text/javascript">
//�ٶȵ�ͼAPI����
function loadJScript() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://api.map.baidu.com/api?v=2.0&ak=K7tnwI1zYBTaC9XcgI725m7K&callback=init";
    document.body.appendChild(script);
}
function init() {
    var map = new BMap.Map("map");          // ����Mapʵ��
    var point = new BMap.Point(<?=$M_LNG?>, <?=$M_LAT?>); // ����������
    map.centerAndZoom(point,18);                 
    //map.enableScrollWheelZoom();                 //���ù��ַŴ���С
    var marker = new BMap.Marker(point);  // ������ע
    map.addOverlay(marker);               // ����ע��ӵ���ͼ��
    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //�����Ķ���
}  
window.onload = loadJScript;  //�첽���ص�ͼ
</script>
</body>
</html>