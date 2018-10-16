<?
include_once('inc/auth.inc.php');
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$SYS_INTERFACE = TD::get_cache("SYS_INTERFACE");
$THEME_SELECT  = $SYS_INTERFACE["THEME_SELECT"];
$THEME_MO      = $SYS_INTERFACE["THEME"];
$WEATHER_CITY  = $SYS_INTERFACE["WEATHER_CITY"];
$SHOW_RSS      = $SYS_INTERFACE["SHOW_RSS"];

$query = "SELECT * from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $CALL_SOUND        = $ROW["CALL_SOUND"];
    $SMS_ON            = $ROW["SMS_ON"];
    $MENU_TYPE         = $ROW["MENU_TYPE"];
    $THEME             = $ROW["THEME"];
    $PANEL             = $ROW["PANEL"];
    $MENU_IMAGE        = $ROW["MENU_IMAGE"];
    $WEATHER_CITY_USER = $ROW["WEATHER_CITY"];
    $SHOW_RSS_USER     = $ROW["SHOW_RSS"];
    $MENU_EXPAND       = $ROW["MENU_EXPAND"];
    $BKGROUND          = $ROW["BKGROUND"];
    
    $WEATHER_CITY_USER==""?$WEATHER_CITY_USER=$WEATHER_CITY:"";      
}

$PARA_ARRAY = get_sys_para("MYTABLE_BKGROUND");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
{
    $$PARA_NAME = $PARA_VALUE;
}
   
$URL_ARRAY = attach_url_old('swf', $_SESSION["LOGIN_UID"].".swf");
$SOUND_URL = $URL_ARRAY['view'];

$IS_FASHION_THEME = find_id(MYOA_FASHION_THEME, $THEME);

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>

<?
if(strpos($WEATHER_CITY, "_") !== FALSE)
{
?>
<script language="JavaScript" src="<?=MYOA_JS_SERVER?>/static/js/plugin.js<?=$GZIP_POSTFIX?>"></script>
<?
}
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/person_info/index.css" />
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/tabopt.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script Language="JavaScript">
var $ = function(id) {return document.getElementById(id);};
function CheckForm()
{
    if(document.form1.THEME_SELECT.value==1 && document.form1.THEME.value == "")
    {
        alert("<?=_("��ѡ���������")?>");
        return false;
    }
    
    if(document.form1.SHOW_WEATHER && document.form1.WEATHER_CITY && document.form1.SHOW_WEATHER.checked && document.form1.WEATHER_CITY.value.length!=6)
    {
        alert("<?=_("��ѡ�����")?>");
        return false;
    }

    var w_province = document.getElementById("w_province");
    var w_city = document.getElementById("w_city");
    var w_county = document.getElementById("w_county");
    var city_name = [w_province.options[w_province.selectedIndex].text, w_city.options[w_city.selectedIndex].text, w_county.options[w_county.selectedIndex].text].join("_");
    document.form1.WEATHER_CITY_NAME.value = city_name;
    
    if(document.form1.CALL_SOUND.value == "-1" && document.form1.CUSTOM_SOUND)
    {
        var filename = document.form1.CUSTOM_SOUND.value;
        var extname  = filename.substr(filename.length-4).toLowerCase();
        if(extname != ".swf")
        {
            alert("<?=_("��Ϣ������ֻ��ΪFlash�ļ�(swf��ʽ)")?>");
            return false;
        }
    }
    else
    {
        if(document.form1.CUSTOM_SOUND)
            document.form1.CUSTOM_SOUND.value = "";
    }
    
    return true;
}

function select_sound()
{
    var sound=document.form1.CALL_SOUND.value;
    if(sound!="0" && sound!="-1")
    {
        str="<object  classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='<?=MYOA_JS_SERVER?>/static/js/swflash.cab' width='0' height='0'><param name='movie' value='<?=MYOA_STATIC_SERVER?>/static/wav/"+sound+".swf'><param name=quality value=high><embed src='<?=MYOA_STATIC_SERVER?>/static/wav/"+sound+".swf' width='0' height='0' quality='autohigh' wmode='opaque' type='application/x-shockwave-flash' plugspace='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash'></embed></object>"
        $("sms_sound").innerHTML=str;
    }
    
    if(sound=="-1")
    {
        $('CUSTOM_CALL_SOUND').style.display = '';
    }
    else
    {
        $('CUSTOM_CALL_SOUND').style.display = 'none';
    }
}

function play_sound()
{
    str="<object  classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='<?=MYOA_JS_SERVER?>/static/js/swflash.cab' width='0' height='0'><param name='movie' value='<?=$SOUND_URL?>'><param name=quality value=high><embed src='<?=$SOUND_URL?>' width='0' height='0' quality='autohigh' wmode='opaque' type='application/x-shockwave-flash' plugspace='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash'></embed></object>"
    $("sms_sound").innerHTML=str;
}

function delete_sound()
{
    if(window.confirm("<?=_("ȷ��Ҫ�����Զ�����ʾ����")?>"))
        window.location="delete.php?ITEM=SOUND";
}
function bkchange(value)
{
    if(value == "")
    {
        $("bk_preview").style.display = "none";
        $("bk_image").style.display = "none";
    }
    else if(value == "0")
    {
        $("bk_preview").style.display = "none";
        $("bk_image").style.display = "";
    }
    else
    {
        $("bk_preview").href = "/attachment/background/" + value;
        $("bk_preview").style.display = "";
        $("bk_image").style.display = "none";
    }
}
function ChangeTheme(theme)
{
    if(theme == "15"){
        $("theme_msg").innerText="T-OS������IE9�����ϰ汾�����֧��";
    }
    else{
        $("theme_msg").innerText="�λ��鶯������IE7�����ϰ汾�����֧��";
    }
    var fashion_theme = "<?=MYOA_FASHION_THEME?>";
    if(fashion_theme.indexOf(theme + ',') == 0 || fashion_theme.indexOf(',' + theme + ',') > 0)
    {
        if($('tr_icon')) $('tr_icon').style.display = 'none';
        if($('tr_sms')) $('tr_sms').style.display = 'none';
        if($('tr_rss')) $('tr_rss').style.display = 'none';
        if($('intro_reshow')) $('intro_reshow').style.display = '';
    }
    else
    {
        if($('tr_icon')) $('tr_icon').style.display = '';
        if($('tr_sms')) $('tr_sms').style.display = '';
        if($('tr_rss')) $('tr_rss').style.display = '';
        if($('intro_reshow')) $('intro_reshow').style.display = 'none';
    }
}

jQuery.noConflict();
jQuery(document).ready(function(){
    if($("theme")){
        var index = $("theme").selectedIndex;
        var value = $("theme").options[index].value;
        if(value == "15"){
            $("theme_msg").innerText = "2015��������IE9�����ϰ汾�����֧��";
        }
    }
});
</script>

<body class="bodycolor content-body" onLoad="parent.window.document.getElementById('c_main').height=document.body.scrollHeight;<?if(strpos($WEATHER_CITY, "_") !== FALSE){?>InitProvince(ConvertWeatherCity('<?=$WEATHER_CITY_USER?>'));<?}?>">

<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
<input type="hidden" name="THEME_SELECT" value=<?=$THEME_SELECT?>>
<table class="table table-bordered">
    <colgroup>
        <col width="120">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("����������˵�")?></td>
        </tr>
    </thead>
    <tbody>
    <?
    if($THEME_SELECT=="1")
    {
    ?>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe63d;</i><?=_("�������⣺")?></td>
            <td>
                 <select id="theme" name="THEME" class="input-medium" onChange="ChangeTheme(this.value);" style="width:250px;">
                 <?=get_theme_list($THEME)?>
                 </select>
                
                <span id="theme_msg"><?=_("�λ��鶯������IE7�����ϰ汾�����֧��")?></span>
            </td>
        </tr>
     <?
    }
    if(MYOA_IS_DEMO)
    {
     ?>
         <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe647;</i><?=_("���汳��ͼƬ��")?></td>
            <td><?=_("��ʾ�治���Զ������汳��ͼƬ")?></td>
        </tr>
     <?
    }
    else if($MYTABLE_BKGROUND!="-1")
    {
     ?> 
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe647;</i><?=_("���汳��ͼƬ��")?></td>
            <td>
                <select name="BKGROUND" class="input-medium" onChange="bkchange(this.value)">
                  <option value=""<?if($BKGROUND=="") echo " selected";?>><?=_("ϵͳĬ��")?></option>
                  <?
                  if($handle=opendir('../../../attachment/background'))
                  {
                      while(false!== ($file_name = readdir($handle)))
                      {
                          $EXT_NAME = strtolower(substr($file_name, strrpos($file_name, ".")));
                          if(($EXT_NAME!=".jpg" && $EXT_NAME!=".jpeg" && $EXT_NAME!=".gif" && $EXT_NAME!=".png") || preg_match("/[\x80-\xff]./", $file_name) || is_dir("../../../attachment/background/".$file_name))
                          {
                              continue;
                          }
                          $IMG_SIZE = td_getimagesize("../../../attachment/background/".$file_name);
                      ?>
                  <option value="<?=$file_name?>"<?if($BKGROUND == $file_name) echo " selected";?>><?=$file_name?>(<?=$IMG_SIZE[0]?>x<?=$IMG_SIZE[1]?>)</option>    
                      <?
                      }
                  }
                  echo $BKGROUND;
                  ?>
                  <option value="0"<?if(substr($BKGROUND, 0, 6) == "users_") echo " selected";?>><?=_("�Զ���")?></option>
                </select>&nbsp;<a id="bk_preview" href="/attachment/background/<?=$BKGROUND?>" target="_blank" style="display:<?=(($BKGROUND == "" || substr($BKGROUND, 0, 6) == "users_") ? "none" : "")?>;"><?=_("Ԥ��")?></a>
            </td>
        </tr>
        <tr class="TableData" id="bk_image" style="display:<?=(substr($BKGROUND, 0, 6) != "users_" ? "none" : "")?>;">
            <td class="td-head"><i class="iconfont">&#xe647;</i><?=_("���汳��ͼƬ��")?></td>
            <td>
            <?
            
            if(substr($BKGROUND, 0, 6) == "users_")
            {
                
                $ATTACH_URL = attach_url_old('background', $BKGROUND);
            ?>
                <a href="<?=$ATTACH_URL['view']?>" target="_blank"><?=_("Ԥ��")?></a>&nbsp;&nbsp;
                <a href="javascript:window.location='delete.php?ITEM=BKGROUND&EXT_NAME=<?=substr($BKGROUND, strrpos($BKGROUND, ".")+1)?>';"><?=_("ɾ��")?></a>
            <?
            }else
            {
            ?>
                <input type="file" name="BKIMAGE" class="SmallInput" size="40">
            <?
            }
            ?>
            </td>
        </tr> 
      <?
    }
      ?>
          <tr class="TableData" id="tr_icon" style="display:<?if($IS_FASHION_THEME) echo "none";?>;">
            <td class="td-head"><i class="iconfont">&#xe648;</i><?=_("�˵�ͼ�꣺")?></td>
            <td>
                <font color=gray><?=_("��ʾ��ѡ����ʾͼ���һͼ��������������ʱ�ĵ�¼�ٶ�")?></font><br>
                <label for="MENU_IMAGE0" style="cursor:hand"><input type="radio" name="MENU_IMAGE" value="0" id="MENU_IMAGE0" <?if($MENU_IMAGE=="0") echo "checked";?>><?=_("ÿ���˵�ʹ�ò�ͬͼ��")?></label>
                <label for="MENU_IMAGE1" style="cursor:hand"><input type="radio" name="MENU_IMAGE" value="1" id="MENU_IMAGE1" <?if($MENU_IMAGE=="1") echo "checked";?>><?=_("����ʾ�˵�ͼ��")?></label>
                <?
                $IMG_ARRAY=scandir('../../../static/images/m');
                foreach($IMG_ARRAY as $IMG_NAME)
                {
                    $EXT_NAME=strtolower(substr($IMG_NAME,-4));
                    if($EXT_NAME!=".gif" && $EXT_NAME!=".png" && $EXT_NAME!=".jpg" && $EXT_NAME!=".bmp")
                    {
                        continue;
                    }
                ?>
                <input type="radio" name="MENU_IMAGE" value="<?=$IMG_NAME?>" <?if($MENU_IMAGE==$IMG_NAME) echo "checked";?>><img src="<?=MYOA_STATIC_SERVER?>/static/images/m/<?=$IMG_NAME?>" align="absmiddle">&nbsp;
                <?
                }
                $TOP_MENU_PRIV  = "";
                $FUNCTION_ARRAY = TD::get_cache('SYS_FUNCTION_ALL_'.bin2hex(MYOA_LANG_COOKIE));
                $FUNCTION_ARRAY = array_values($FUNCTION_ARRAY);
                $FUNC_COUNT     = count($FUNCTION_ARRAY);
                for($I=0; $I< $FUNC_COUNT; $I++)
                {
                    $FUNC_ID   = $FUNCTION_ARRAY[$I]["FUNC_ID"];
                    $MENU_ID   = $FUNCTION_ARRAY[$I]["MENU_ID"];
                    $FUNC_NAME = $FUNCTION_ARRAY[$I]["FUNC_NAME"];
                    $FUNC_CODE = $FUNCTION_ARRAY[$I]["FUNC_CODE"];
                    if($FUNC_ID=="")//����
                    {
                        continue;
                    }
                    if($FUNC_ID!="" && !find_id($USER_FUNC_ID_STR, $FUNC_ID))
                    {
                        continue;
                    }
                    $MENU_ID=substr($MENU_ID,0,2);
                    if(!find_id($TOP_MENU_PRIV,$MENU_ID))
                    {
                        $TOP_MENU_PRIV.= $MENU_ID.",";
                    }
                }
                ?>
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe61c;</i><?=_("Ĭ��չ���˵���")?></td>
            <td>
                <select class="input-medium" name="MENU_EXPAND">
                  <option></option>
                  <?
                  $query="select * from SYS_MENU order by MENU_ID";
                  $cursor= exequery(TD::conn(),$query);
                  while($ROW=mysql_fetch_array($cursor))
                  {
                      $MENU_ID   = $ROW["MENU_ID"];
                      $MENU_NAME = $ROW["MENU_NAME"];
                      $MENU_EXT  = unserialize($ROW["MENU_EXT"]);
                      if(is_array($MENU_EXT) && $MENU_EXT[MYOA_LANG_COOKIE] != "")
                      {
                          $MENU_NAME = $MENU_EXT[MYOA_LANG_COOKIE];
                      }
                      if(find_id($TOP_MENU_PRIV,$MENU_ID))
                      {
                      ?>
                  <option value="<?=$MENU_ID?>" <?if($MENU_ID==$MENU_EXPAND)echo "selected";?>><?=$MENU_NAME?></option>  
                      <?
                      }
                      
                  }
                  ?>
                </select>
                <span><?=_("����������֧��")?></span>
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <colgroup>
        <col width="120">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("��¼ѡ��")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe606;</i><?=_("��¼ģʽ��")?></td>
            <td>
                <select name="MENU_TYPE" class="input-xlarge">
                  <option value="1" <? if($MENU_TYPE=="1")echo "selected"; ?>><?=_("�ڱ����ڴ�OA")?></option>
                  <option value="2" <? if($MENU_TYPE=="2")echo "selected"; ?>><?=_("���´��ڴ�OA����ʾ������")?></option>
                  <option value="3" <? if($MENU_TYPE=="3")echo "selected"; ?>><?=_("���´��ڴ�OA���޹�����")?></option>
                </select>
                <span><?=_("���µ�¼����Ч")?></span>
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head" style="position:relative;"><i class="iconfont" style="position:absolute;">&#xe605;</i><span style="margin-left: 34px;display: inline-block;width: 68px;white-space: nowrap;"><?=_("��ʾ�������壺")?></span></td>
            <td>
                <select name="PANEL" class="input-medium">
                  <option value="1" <? if($PANEL==1)echo "selected"; ?>><?=_("����")?></option>
                  <option value="2" <? if($PANEL==2)echo "selected"; ?>><?=_("��֯")?></option>
                  <option value="3" <? if($PANEL==3)echo "selected"; ?>><?=_("΢Ѷ")?></option>
                  <option value="4" <? if($PANEL==4)echo "selected"; ?>><?=_("��ǩ")?></option>
                </select>
                
                <span><?=_("�Ǿ��������½�������OA�����¼")?></span>
            </td>
        </tr>
        <tr class="TableData" id="intro_reshow" style="display:<?if(!$IS_FASHION_THEME) echo "none";?>;">
            <td class="td-head"><i class="iconfont">&#xe63b;</i><?=_("��ʾ������ʾ�򵼣�")?></td>
            <td>
                <select name="INTRO_TYPE" class="input-medium">
                  <option value="0"><?=_("��")?></option>
                  <option value="1"><?=_("��")?></option>
                </select>
                
                <span><?=_("�鶯�����¿��������Ƿ���������ʾ��")?></span>
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <colgroup>
        <col width="120">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("��Ϣ��������")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="TableData" id="tr_sms" style="display:<?if($IS_FASHION_THEME) echo "none";?>;">
            <td class="td-head" style="position:relative"><i class="iconfont" style="position:absolute;">&#xe64a;</i><span style="margin-left: 34px;display: inline-block;width: 68px;white-space: nowrap;"><?=_("��Ϣ���Ѵ��ڵ�����ʽ��")?></span></td>
            <td>
                <select name="SMS_ON" class="input-xlarge">
                  <option value="1" <? if($SMS_ON==1)echo "selected"; ?>><?=_("�Զ�")?></option>
                  <option value="0" <? if($SMS_ON==0)echo "selected"; ?>><?=_("�ֶ�")?></option>
                </select>
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe643;</i><?=_("��Ϣ����������")?></td>
            <td>
                <select name="CALL_SOUND" class="input-xlarge" onChange="select_sound()">
                  <option value="-1" <? if($CALL_SOUND=="-1")echo "selected"; ?>><?=_("�Զ���")?></option>
                  <option value="1" <? if($CALL_SOUND=="1") echo "selected"; ?>><?=_("ͨ��OA������Ч")?></option>
                  <option value="9" <? if($CALL_SOUND=="9")echo "selected"; ?>><?=_("������")?></option>
                  <option value="8" <? if($CALL_SOUND=="8")echo "selected"; ?>><?=_("������")?></option>
                  <option value="2" <? if($CALL_SOUND=="2")echo "selected"; ?>><?=_("����")?></option>
                  <option value="3" <? if($CALL_SOUND=="3")echo "selected"; ?>><?=_("ˮ��")?></option>
                  <option value="4" <? if($CALL_SOUND=="4")echo "selected"; ?>><?=_("�ֻ�")?></option>
                  <option value="5" <? if($CALL_SOUND=="5")echo "selected"; ?>><?=_("�绰")?></option>
                  <option value="6" <? if($CALL_SOUND=="6")echo "selected"; ?>><?=_("����")?></option>
                  <option value="7" <? if($CALL_SOUND=="7")echo "selected"; ?>>OICQ</option>
                  <option value="0" <? if($CALL_SOUND=="0")echo "selected"; ?>><?=_("��")?></option>
                </select>
                <span id="CUSTOM_CALL_SOUND" style="display:<?=$CALL_SOUND=="-1" ? "" : "none"?>;">
                <?
                if($CALL_SOUND=="-1")
                {
                ?>
                    <a href="javascript:play_sound();"><?=_("����")?></a>
                    <a href="javascript:delete_sound();"><?=_("������ʾ��")?></a>
                <?    
                }else
                {
                ?>
                    <input type="file" name="CUSTOM_SOUND" class="BigInput" size="20">&nbsp;
                    <font color="#FF0000"><?=_("����Flash�ļ�(swf��ʽ)")?></font>
                <?
                }
                ?>
                </span>
                <div align="right" id="sms_sound"></div>
            </td>
        </tr>
    </tbody>
</table>
<?
if(strpos($WEATHER_CITY, "_") !== FALSE)
{
?>
<table class="table table-bordered">
    <colgroup>
        <col width="120">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("����Ԥ��")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe624;</i><?=_("�Ƿ���ʾ��")?></td>
            <td>
                <label for="SHOW_WEATHER">
                    <input type="checkbox" name="SHOW_WEATHER" id="SHOW_WEATHER" <?if(strpos($WEATHER_CITY_USER, "_") !== FALSE) echo "checked";?> onClick="if(this.checked) $('area_select').style.display='';else $('area_select').style.display='none';">
                    <?=_("��ʾ����Ԥ��")?>
                </label>
            </td>
        </tr>
        <tr class="TableData" id="area_select" style="display:<?if(strpos($WEATHER_CITY_USER, "_") === FALSE) echo "none";?>;">
            <td class="td-head"><i class="iconfont">&#xe61b;</i><?=_("Ĭ�ϳ��У�")?></td>
            <td>
                <select class="input-medium" id="w_province" onChange="InitCity(this.value);"></select>
                <select id="w_city" class="input-medium" onChange="InitCounty(this.value);"></select>
                <select id="w_county" class="input-medium" name="WEATHER_CITY"></select>
            </td>
        </tr>
    </tbody>
</table>
<?
}
if($SHOW_RSS=="1")
{
?>
<table class="table table-bordered" style="display:<?if($IS_FASHION_THEME) echo "none";?>;">
    <colgroup>
        <col width="120">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("������Ѷ")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe624;</i><?=_("�Ƿ���ʾ��")?></td>
            <td>
                <label for="SHOW_RSS">
                    <input type="checkbox" name="SHOW_RSS" id="SHOW_RSS" <?if($SHOW_RSS_USER=="1") echo "checked";?>>
                    <?=_("��ʾ������Ѷ")?>
                </label>
            </td>
        </tr>
    </tbody>
</table>
<?
}
?>
<center>
    <input type="hidden" name="WEATHER_CITY_NAME" value="<?=$WEATHER_CITY?>">
    <input type="submit" value="<?=_("�������ò�Ӧ��")?>" class="btn btn-primary">
</center>
</form>
</body>
</html>