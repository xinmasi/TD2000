<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("�ֻ�����");
$HTML_PAGE_META_FOR_MAP = true;
$HTML_PAGE_BASE_STYLE = false;
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
require_once "inc/utility_all.php";
if(!$mid || !is_numeric($mid))
    exit;
$sql = "select UID from user where USER_ID = '$user_id'";
$cursor= exequery(TD::conn(),$sql);
if($row=mysql_fetch_array($cursor))
{
    $u_id = $row['UID'];
}
if($m_isfoot == 0)
{
    //�ϰ�ʱ���ѯ
    /*$query1="select DUTY_TYPE from USER_EXT where UID='$u_id'";
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
    }*/

    //��ȡ���β鿴����Ϣ
    $sql = "SELECT * FROM ATTEND_MOBILE M, ATTEND_DUTY D WHERE M.M_ID = D.ATTEND_MOBILE_ID AND M.M_ID = $mid AND M.M_UID = '$u_id'";
    //var_dump($sql);die();
    $cursor= exequery(TD::conn(),$sql, true);
    if($ROW=mysql_fetch_array($cursor))
    {
        $REGISTER_TYPE      = $ROW["REGISTER_TYPE"];
        $REGISTER_TIME      = $ROW["REGISTER_TIME"];
        $REMARK             = $ROW["REMARK"];
        $M_LOCATION         = $ROW["M_LOCATION"];
        $ATTACHMENT_ID      = $ROW['ATTACHMENT_ID'];
        $ATTACHMENT_NAME    = $ROW['ATTACHMENT_NAME'];
        $ATTACHMENT_NAME    = $ROW['ATTACHMENT_NAME'];

        $DUTY_TYPE_USER     = $ROW['DUTY_TYPE'];
        $DUTY_TIME_USER     = $ROW['DUTY_TIME'];
        $TIME_LATE          = $ROW['TIME_LATE'];
        $TIME_EARLY         = $ROW['TIME_EARLY'];
        $M_REMARK           = $ROW['M_REMARK'];

        if(strstr($M_LOCATION,'|'))
        {
            $arr = explode('|',$M_LOCATION);
            $LOCATION_ONE = $arr[0];
            $LOCATION_TWO = $arr[1];
        }
        $M_LNG = $ROW["M_LNG"];
        $M_LAT = $ROW["M_LAT"];
    }

    $sql1 = "SELECT * FROM attend_config WHERE DUTY_TYPE = '$DUTY_TYPE_USER' ";
    $cursor1= exequery(TD::conn(),$sql1);
    if($row1=mysql_fetch_array($cursor1))
    {
        $DUTY_NAME    = $row1["DUTY_NAME"];
        $GENERAL      = $row1["GENERAL"];
        $DUTY_TYPE_ARR = array();
        for($I=1;$I<=6;$I++)
        {
            $cn = $I%2==0?"2":"1";
            if($row1["DUTY_TIME".$I]!="")
                $DUTY_TYPE_ARR[$I]=array( "DUTY_TIME" => $row1["DUTY_TIME".$I] ,"DUTY_TYPE" => $cn);
        }
    }

    if($DUTY_TIME_USER!="")
    {
        $DUTY_TIME = $DUTY_TIME_USER;
    }
    else
    {
        $DUTY_TIME = $DUTY_TYPE_ARR[$REGISTER_TYPE]['DUTY_TIME'];
    }

    $DUTY_TYPE = $REGISTER_TYPE%2==0?"2":"1";

    if($DUTY_TYPE=="1")
        $DUTY_TYPE_NAME=_("ǩ��");
    else
        $DUTY_TYPE_NAME=_("ǩ��");

    $TYPE = 'success';
    $REGISTER_TIME_DESC = _("����");

    if($REGISTER_TIME!="")
    {
        $REGISTER_TIME = strtok($REGISTER_TIME," ");
        $REGISTER_TIME = strtok(" ");

        if($DUTY_TIME_USER!="")
        {
            //����
            $str1 = this_compare_time($REGISTER_TIME,$DUTY_TIME,$TIME_EARLY,1);
            //�ٵ�
            $str2 = this_compare_time($REGISTER_TIME,$DUTY_TIME,$TIME_LATE,0);
        }else
        {
            if($DUTY_TYPE=="1" && compare_time($REGISTER_TIME,$DUTY_TIME)==1)
                $str2 = 1;
            if($DUTY_TYPE=="2" && compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
                $str1 = -1;
        }

        if($DUTY_TYPE=="1" && $str2==1)
        {
            $TYPE = 'important';$REGISTER_TIME_DESC = _("�ٵ�");
        }

        if($DUTY_TYPE=="2" && $str1==-1)
        {
            $TYPE = 'warning';$REGISTER_TIME_DESC = _("����");
        }
    }

    if($REGISTER_TIME[0] == "0")
        $REGISTER_TIME = substr($REGISTER_TIME, 1);

    if(($M_LNG == "" || $M_LAT == "") && $M_REMARK!="wifi")
    {
        echo _('������Ϣȱʧ');
        exit;
    }
}
else if($m_isfoot == 2)
{
    $sql2 = "select * from attend_mobile where M_ID = $mid and M_UID = '$u_id'";
    $cursor2 = exequery(TD::conn(),$sql2, true);
    if($row2=mysql_fetch_array($cursor2))
    {
        $M_TIME         = $row2['M_TIME'];
        $m_location     = $row2['M_LOCATION'];
        $attach_id      = $row2['ATTACHMENT_ID'];
        $attach_name    = $row2['ATTACHMENT_NAME'];
        $m_remark       = $row2['M_REMARK'];
        $lng            = $row2['M_LNG'];
        $lat            = $row2["M_LAT"];

        if(strstr($m_location,'|'))
        {
            $arr = explode('|',$m_location);
            $location_one = $arr[0];
            $location_two = $arr[1];
        }
    }
}
?>
<body>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=($_SESSION["LOGIN_THEME"] == "" ? "1" : td_htmlspecialchars($_SESSION["LOGIN_THEME"]))?>/style.css" />
<style type="text/css">
    #map{height: 300px;}
    .table td{color: #666;}
</style>
<?php
if($m_isfoot == 0)
{
    ?>
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
                <?
                if(strstr($M_LOCATION,'|'))
                {
                    ?>
                    <td><span style="font-weight:bold"><?=$LOCATION_ONE?></span><br/><span style="font-size:12px;color:#B0B0B0"><?=$LOCATION_TWO?></span></td>
                    <?
                }
                else
                {
                    ?>
                    <td><?=$M_LOCATION?></td>
                    <?
                }
                ?>
            </tr>
            <?
            if($ATTACHMENT_NAME!="")
            {
                ?>
                <tr>
                    <th><?=_("ͼƬ")?></th>
                    <td colspan="3"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,0,0,0)?></td>
                </tr>
                <?
            }
            ?>
            <? if($REMARK!=""){ ?>
                <tr>
                    <th><?=_("����˵��")?></th>
                    <td colspan="3"><?=$REMARK?></td>
                </tr>
            <? } ?>
            </tbody>
        </table>
    </div>
    <?
}
else if($m_isfoot == 2)
{
    ?>
    <div id="info">
        <table class="table">
            <colgroup>
                <col width="100" />
                <col />
            </colgroup>
            <tbody>
            <tr>
                <th><?=_("ǩ��ʱ��")?></th>
                <td><?=date('H:i:s',$M_TIME)?></td>
            </tr>
            <tr>

                <th><?=_("�򿨵ص�")?></th>
                <?
                if(strstr($m_location,'|'))
                {
                    ?>
                    <td><span style="font-weight:bold"><?=$location_one?></span><br/><span style="font-size:12px;color:#B0B0B0"><?=$location_two?></span></td>
                    <?
                }
                else
                {
                    ?>
                    <td><?=$m_location?></td>
                    <?
                }
                ?>
            </tr>
            <?
            if($attach_name!="")
            {
                ?>
                <tr>
                    <th><?=_("ͼƬ")?></th>
                    <td colspan="3"><?=attach_link($attach_id,$attach_name,1,1,1,0,0,0)?></td>
                </tr>
                <?
            }
            ?>
            <? if($m_remark!=""){ ?>
                <tr>
                    <th><?=_("����˵��")?></th>
                    <td colspan="3"><?=$m_remark?></td>
                </tr>
            <? } ?>
            </tbody>
        </table>
    </div>
    <?
}
?>
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
        var point = new BMap.Point(<?=(($m_isfoot==0) ? $M_LNG : $lng)?>, <?=(($m_isfoot==0) ? $M_LAT : $lat)?>); // ����������
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