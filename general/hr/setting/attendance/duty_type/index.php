<?
include_once("inc/auth.inc.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("���ڷ�ʽ����");
include_once("inc/header.inc.php");
?>
<!DOCTYPE html>
<!--[if IE 6 ]> <html class="ie6 lte_ie6 lte_ie7 lte_ie8 lte_ie9"> <![endif]--> 
<!--[if lte IE 6 ]> <html class="lte_ie6 lte_ie7 lte_ie8 lte_ie9"> <![endif]--> 
<!--[if lte IE 7 ]> <html class="lte_ie7 lte_ie8 lte_ie9"> <![endif]--> 
<!--[if lte IE 8 ]> <html class="lte_ie8 lte_ie9"> <![endif]--> 
<!--[if lte IE 9 ]> <html class="lte_ie9"> <![endif]--> 
<!--[if (gte IE 10)|!(IE)]><!--><html><!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <meta name="renderer" content="ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
    <link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
    <link rel="stylesheet" type="text/css" href="/static/modules/attendance/setting/duty_type.css">
    <style type="text/css">
        .modal.fade.in {
            top: 1%;
        }
    </style>
</head>
<body class="">
    <div class="wrapper">
        <h5 class=""><?=_("���·�ʽ����һ��������Ա������ɿ��ڡ�")?></h5>
        <div id="locations" class="content-wrapper">
            <div class="locations-title content-title">
                <label class="locations-label"><?=_("1.���ݰ칫�ص㿼�ڣ�����Ӷ�����ڵص㣩")?></label>
            </div>
            <div class="locations-content">
                <table class="table table-bordered">
                    
                        <tr class="table-header">
                            <th class=" text-left"><?=_("���ڵ�ַ")?></th>
                            <th class=" text-left"><?=_("��Ч��Χ(��)")?></th>
                            <th class=" text-left"><?=_("����")?></th>
                        </tr>
                    
                </table>
            </div>
            <div class="locations-footer">
                <a  href="#myLocations" role="button"  data-toggle="modal"><?=_("��ӿ��ڵص�")?></a>
            </div>
            <div id="myLocations" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-body">
                    <div id="r-result"><?=_("������:")?><input type="text" id="suggestId" size="20" value="" style="width:150px;" />
                    </div>
                    <div id="searchResultPanel">
                    </div> 
                    <div id="myMap" style="width: 550px;height: 550px;">
                    </div>
                </div>
                <div class="modal-footer">   
                    <label class="text-left"><?=_("��ϸ��ַ:")?><span class="locationStr"></span></label>
                    <label class="text-left"><?=_("ѡ����Ч��Χ:")?>
                    <span class="">
                        <select id="range">
                            <option value="100"><?=_("100��")?></option>
                            <option value="200"><?=_("200��")?></option>
                            <option selected="" value="300"><?=_("300��")?></option>
                            <option value="400"><?=_("400��")?></option>
                            <option value="500"><?=_("500��")?></option>
                            <option value="600"><?=_("600��")?></option>
                            <option value="700"><?=_("700��")?></option>
                            <option value="800"><?=_("800��")?></option>
                            <option value="900"><?=_("900��")?></option>
                            <option value="1000"><?=_("1000��")?></option>
                            <option value="2000"><?=_("2000��")?></option>
                            <option value="3000"><?=_("3000��")?></option>
                        </select>
                    </span>
                    </label>
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("�ر�")?></button>
                    <button id="subLocat" class="btn btn-primary"><?=_("�ύ")?></button>
                </div>
            </div>
        </div>
        <div id="wifis" class="content-wrapper">
            <div class="wifis-title  content-title">
                <label class=""><?=_("2.����WiFi���ڣ���ȷ��λ���칫���ڣ�����Ӷ���칫WiFi��")?></label>
            </div>
            
            <div class="wifis-content">
                <table class="table table-bordered">
                    
                        <tr class="table-header">
                            <th class=" text-left"><?=_("����")?></th>
                            <th class=" text-left"><?=_("MAC��ַ")?></th>
                            <th class=" text-left"><?=_("����")?></span></th>
                        </tr>
                    
                </table>
            </div>
            <div class="wifis-footer">
                <a  href="#myWifi" role="button"  data-toggle="modal"><?=_("��Ӱ칫WiFi")?></a>
            </div>
            <div id="myWifi" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
                    <h3 id="myModalLabel"><?=_("��Ӱ칫WiFi")?></h3>
                </div>
                <div class="modal-body">
                    <label><?=_(" �� ")?>&nbsp;&nbsp;<?=_("�ƣ�")?>&nbsp;&nbsp;<input class="input-xlarge" autocomplete="off" type="text" name="wifiName" placeholder="<?=_("��಻����15����")?>" size="15"></label>
                    <label><?=_("·����MAC��ַ��")?><input class="input-xlarge" type="text" autocomplete="off" name="wifiAddress" placeholder="<?=_("���ӣ�02:10:18:02:40:7b")?>"></label>
                    <label><?=_("���ƾ��������뿼��WiFi����һ�£�����Ա���������")?></label>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("ȡ��")?></button>
                    <button class="btn btn-primary" id="subMac"><?=_("ȷ��")?></button>
                </div>
            </div>
        </div>
    </div>
	<br>
	<div align="center">
		<input type="button"  value="<?=_("����")?>" class="btn" onClick="location='../#dutyOrno';">
	</div>
	<br>
<script type="text/javascript" src="/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="/static/js/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=dLiSInX3qupL2HaAAtcMNBrdec2RIeXg&s=1"></script>
<script type="text/javascript">
  
$(function() {
    var map = new BMap.Map('myMap', {
        enableMapClick : false
    });
    var mycity = new BMap.LocalCity();
    var geoc = new BMap.Geocoder();
    var locationStr;
    var locationPx;
    var locationPy;
    var htmlAdd='';
    mycity.get(function(result){
        var cityname = result.name;
        map.centerAndZoom(cityname,15);
    });

    map.enableScrollWheelZoom();
    map.addControl(new BMap.NavigationControl({
        anchor: BMAP_ANCHOR_TOP_LEFT, 
        offset: new BMap.Size(10, 10)
    }));

    map.addControl(new BMap.CityListControl({
        anchor: BMAP_ANCHOR_TOP_LEFT,
        offset: new BMap.Size(10, 20)
    }));
    map.addEventListener('click', function(e){
        var p = e;
        var pt = e.point;
        
        geoc.getLocation(pt, function (result) {
            address = result.address;
            locationStr=address;
            htmlAdd=locationStr;
            $('.locationStr').empty();
            $('.locationStr').append(htmlAdd);
        });
        locationPx=pt.lng.toString();
        locationPy=pt.lat.toString();
        
    });

    function G(id) {
        return document.getElementById(id);
    }

    var ac = new BMap.Autocomplete(    //����һ���Զ���ɵĶ���
        {"input" : "suggestId"
        ,"location" : map
    });

    ac.addEventListener("onhighlight", function(e) {  //�����������б��ϵ��¼�
    var str = "";
        var _value = e.fromitem.value;
        var value = "";
        if (e.fromitem.index > -1) {
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }    
        str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
        
        value = "";
        if (e.toitem.index > -1) {
            _value = e.toitem.value;
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }    
        str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
        G("searchResultPanel").innerHTML = str;
    });

    var myValue;
    ac.addEventListener("onconfirm", function(e) {    //����������б����¼�
    var _value = e.item.value;
        myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
        
        locationStr=myValue;
        htmlAdd=locationStr;
        $('.locationStr').empty();
        $('.locationStr').append(htmlAdd);
        setPlace();
    });
    function setPlace(){
        map.clearOverlays();    //�����ͼ�����и�����
        function myFun(){
            var pp = local.getResults().getPoi(0).point;    //��ȡ��һ�����������Ľ��
            map.centerAndZoom(pp, 18);
            map.addOverlay(new BMap.Marker(pp));    //��ӱ�ע
        }
        var local = new BMap.LocalSearch(map, { //��������
          onSearchComplete: myFun
        });
        local.search(myValue);
    }
    function bd_decrypt(bd_lon,bd_lat) {
                var X_PI = Math.PI * 3000.0 / 180.0;
                var x = bd_lon - 0.0065;
                var y = bd_lat - 0.006;
                var z = Math.sqrt(x * x + y * y) - 0.00002 * Math.sin(y * X_PI);
                var theta = Math.atan2(y, x) - 0.000003 * Math.cos(x * X_PI);
                gg_lon = z * Math.cos(theta);
                gg_lat = z * Math.sin(theta);
        }
    $('#subLocat').on('click',function(){
        var offset= $('#range').val();
        if(locationStr==''|| locationStr==undefined){
            alert("<?=_("δ��ȡ����ϸ��ַ��ѡ���ڵص�ʧ�ܣ�")?>");
        }else if(locationPy==''||locationPy==undefined){
            alert("<?=_("��γ�Ȳ����������ڵ�ͼ�ϵ��ѡ��")?>");
        }else if(locationPx==''||locationPx==undefined){
            alert("<?=_("��γ�Ȳ����������ڵ�ͼ�ϵ��ѡ��")?>");
        }else{
            bd_decrypt(locationPx,locationPy);
            $.ajax({
                type:"POST",
                url:"/general/hr/setting/attendance/data.php",
                data:{
                    action:"save_position",
                    state:0,
                    address:locationStr,
                    latitude:gg_lat,
                    longitude:gg_lon,
                    offset:offset,
                },
                success:function(data) {
                    var data =JSON.parse(data);
                    if(data.status=="1"){
                        html = "<tr ><td>"+locationStr+"</td><td>"+offset+"</td><td><a  data-id='"+data.id+"'><?=_("ɾ��")?></a></td></tr>"; 
                        alert("<?=_("��ӿ��ڵص�ɹ���")?>");
                        $('.locations-content table').append(html);
                        $('#myLocations').modal('hide');
                        $('.locationStr').empty();
                        locationStr='';
                    }else{
                        alert("<?=_("��ӿ��ڵص�ʧ�ܣ�")?>");
                        locationStr='';
                    }
                }
            })
        }
        
    });

    $('#subMac').on('click',function() {
        var macName = $("input[name='wifiName']").val();
        var macAddress = $("input[name='wifiAddress']").val();
        $("input[name='wifiName']").val('');
        $("input[name='wifiAddress']").val('');
        if(macName==''){
            alert("<?=_("WiFi���ֲ���Ϊ�գ�����")?>");
        }else{
            if(macAddress==''){
                 alert("<?=_("·����MAC��ַ����Ϊ�գ�����")?>");
             }else{
                var re =/^([0-9a-fA-F]{2})(([/\s:][0-9a-fA-F]{2}){5})$/;

                var result = re.test(macAddress);

                 
                if(result){
                    $.ajax({
                    type:"POST",
                    url:"/general/hr/setting/attendance/data.php",
                    data:{
                        action:"save_position",
                        state:1,
                        name:macName,
                        macAddress:macAddress
                    },
                    success:function(data){
                        var data =JSON.parse(data);
                        if(data.status=="1"){
                            html = "<tr ><td>"+macName+"</td><td>"+macAddress+"</td><td><a data-id='"+data.id+"'><?=_("ɾ��")?></a></td></tr>"; 
                            alert("<?=_("��Ӱ칫WiFi�ɹ���")?>");
                            $('.wifis-content table').append(html);
                            $('#myWifi').modal('hide');
                        }else if(data.status==0){
                            alert(data.msg);
                        }else{
                            alert("<?=_("��Ӱ칫WiFiʧ�ܣ�")?>");
                        }
                    }
                    })
                }else{

                 alert("<?=_("��������ȷ��·����MAC��ַ���磺02:10:18:02:40:7b")?>")
             }
                }
        }
       
    });

    function getLocations() {
        $.ajax({
            type:"GET",
            url:"/general/hr/setting/attendance/data.php",
            data:{
                action:"get_locations"
            },
            success:function(data){
                var data =JSON.parse(data);
                if(data.status==1){
                    var html = "";
                    for(var i= 0;i<data.locations.length;i++){
                        var datas = data.locations[i];
                        html += "<tr><td>"+data.locations[i].address+"</td><td>"+data.locations[i].offset+"</td><td><a  data-id="+data.locations[i].id+"><?=_("ɾ��")?></a></td></tr>"
                    }
                    $('.locations-content table').append(html);
                }else{
                    alert("<?=_("���ҿ��ڵص�ʧ�ܣ�")?>");
                    
                }
            }
        })
    };

    function getWifis() {
        $.ajax({
            type:"GET",
            url:"/general/hr/setting/attendance/data.php",
            data:{
                action:"get_wifis"
            },
            success:function(data){
                var data =JSON.parse(data);
                if(data.status==1){
                    var html = "";
                    for(var i= 0;i<data.wifis.length;i++){
                        var datas = data.wifis[i];
                        html += "<tr><td>"+data.wifis[i].name+"</td><td>"+data.wifis[i].macAddress+"</td><td ><a data-id="+data.wifis[i].id+"><?=_("ɾ��")?></a></td></tr>"
                    }
                    $('.wifis-content table').append(html);
                }else{
                    alert("<?=_("���ҿ���WiFiʧ�ܣ�")?>");
                    
                }
            }
        })
    };

    getLocations();

    getWifis();

    $('.wrapper').on('click','td a',function(e) {
        var self = e.target ;
        var id = $(self).data('id');
        if(confirm("<?=_("ȷ��ɾ�������ݣ�")?>")){
            $.ajax({
                type:"GET",
                url:"/general/hr/setting/attendance/data.php",
                data:{
                    action:"del_attend",
                    id:id
                },
                success:function(data){
                    var data =JSON.parse(data);
                    if(data.status==1){
                        $(self).parents('tr').remove();
                    }else{
                        alert("<?=_("ɾ������ʧ�ܣ�")?>");   
                    }
                }
            });
        }
    })
    $('.wrapper').on('mouseover','td a',function(e) {
        this.style.cursor = 'pointer';
    })

    $('#myWifi').on('hidden', function () {
        $("input[name='wifiName']").val('');
        $("input[name='wifiAddress']").val('');
    })

    $('#myLocations').on('hidden', function () {
        $('.locationStr').html('');
        $("input[id='suggestId']").val('');
    })
});
</script>
</body>
</html>