<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("������ַ����");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/today.js"></script>
<script Language="JavaScript">
var $ = function(id) {return document.getElementById(id);};
function CheckForm()
{
   if(document.form1.URL_TYPE.value=="1" && document.form1.SUB_TYPE.value=="")
   { alert("<?=_("��ѡ��RSS�������")?>");
     return (false);
   }
   if(document.form1.URL_DESC.value=="")
   { alert("<?=_("˵������Ϊ�գ�")?>");
     return (false);
   }
   if(document.form1.URL.value=="")
   { alert("<?=_("��ַ����Ϊ�գ�")?>");
     return (false);
   }
}

function delete_url(URL_ID)
{
 msg='<?=_("ȷ��Ҫɾ��������ַ��")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?URL_ID=" + URL_ID;
  window.location=URL;
 }
}

function delete_all()
{
 msg='<?=_("ȷ��Ҫɾ��������ַ��")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
 }
}
function change_type()
{
   if(document.form1.URL_TYPE.value=='1')
   {
   	$('EWP_TYPE_TR').style.display='none';
      $('RSS_TYPE_TR').style.display='';
      $('btnGetTitle').style.display='inline';
   }else if(document.form1.URL_TYPE.value=='3'){
   	$('EWP_TYPE_TR').style.display='';
   	$('RSS_TYPE_TR').style.display='none';
      $('btnGetTitle').style.display='none';
   }else
   {
   	$('EWP_TYPE_TR').style.display='none';
      $('RSS_TYPE_TR').style.display='none';
      $('btnGetTitle').style.display='none';
   }
}
function get_title()
{
   var theURL=document.form1.URL.value
   if(theURL=="")
   { alert("<?=_("����д��ַ��")?>");
     return;
   }
   $("loading").style.display='';
   var req=getXMLHttpObj();
	req.open("GET","/inc/RSS/title.php?RSSURL="+encodeURIComponent(theURL),true);
	req.onreadystatechange=function()
	{
	   if(req.readyState==4)
	   {
         if(req.responseText.indexOf("<?=_("���Ӳ�����ַ��")?>")>=0)
            $("loading").innerHTML=req.responseText;
         else
         {
            document.form1.URL_DESC.value=req.responseText;
            $("loading").style.display='none';
         }
      }
   };
   req.send(null);
}
function get_icon()
{
   var theURL=document.form1.EWP_ICONURL.value
   if(theURL=="")
   { 
   	alert("<?=_("����дͼ���ַ��")?>");
   	return;
   }
   if(CheckImgExists(theURL)){
   	$("ckIconMsg").innerHTML = '<span style="color:green"><?=_("�ļ�����")?></span>';
   	return;
  	}else{
  		$("ckIconMsg").innerHTML = '<span style="color:red"><?=_("�ļ������ڣ����飡")?></span>';
  		$("EWP_ICONURL").focus();
  		return;
  	}
}

//lp ���ͼƬ�Ƿ����
function CheckImgExists(imgurl) {
    try 
    {
        var xmlhttp;
        var baseurl = "<?=MYOA_STATIC_SERVER?>/static/images/app_icons/";
        var url = baseurl + encodeURIComponent(imgurl);
        if (window.XMLHttpRequest) 
        { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("get", url, false);
        xmlhttp.send();
        if (xmlhttp.status == "404") 
        {
            return false;
        }
        else
        {
            return true;
        }
    } catch (e) 
    {
        return false;
    }
}
/*function CheckImgExists(imgurl)
{
	var baseurl = "<?=MYOA_STATIC_SERVER?>/static/images/app_icons/";
  var ImgObj = null;
  ImgObj = new Image();
  ImgObj.src = baseurl + encodeURIComponent(imgurl);
  if(!0)
  {
    if (ImgObj.width > 0 && ImgObj.height > 0)
    {
      return true;
    }
  }else{
    if(ImgObj.fileUpdatedDate!=""){
      return true;
    }
  }
  return false;
}*/

//lp ����ʱ���״̬
function checkSelect()
{
	 if(document.form1.URL_TYPE.value=='3')
	 {
   	$('EWP_TYPE_TR').style.display='';	
   }			
}
</script>


<body class="bodycolor" onLoad="document.form1.URL_NO.focus();checkSelect();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("��ӹ�����ַ")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="90%" align="center">
  <form action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="120"><?=_("���ͣ�")?></td>
    <td nowrap class="TableData">
      <select name="URL_TYPE" class="BigSelect" onChange="change_type();">
        <option value=""><?=_("��ͨ��ַ")?></option>
        <option value="1"><?=_("RSS����")?></option>
        <option value="3"><?=_("������Ӧ���Զ���")?></option>
      </select>
    </td>
   </tr>
   <tr id="RSS_TYPE_TR" style="display:none;">
    <td nowrap class="TableData">RSS<?=_("���")?></td>
    <td nowrap class="TableData">
      <select name="SUB_TYPE" class="BigSelect">
        <?=code_list("RSS_TYPE",$SUB_TYPE);?>
      </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��ţ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_NO" class="BigInput" size="10" maxlength="25">
    </td>
   <tr>
    <td nowrap class="TableData"><?=_("˵����")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_DESC" class="BigInput" size="25" maxlength="200">
        <input type="button" id="btnGetTitle" class="SmallButton" value="<?=_("����RSS��ַ��ȡ")?>" onClick="get_title();" style="display:<?if($URL_TYPE=="")echo "none";?>;">
        <br><span id="loading" style="display:none;" class="TextColor2"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("���ڻ�ȡ����")?></span>
    </td>
   </tr>
	<tr id="EWP_TYPE_TR" style="display:none;">
    <td nowrap class="TableData"><?=_("Ӧ��ͼ���ļ�����")?></td>
    <td nowrap class="TableData">
      <input type="text" name="EWP_ICONURL" id="EWP_ICONURL" class="BigInput" size="25" maxlength="200">
      <input type="button" id="btnGetIcon" class="SmallButton" value="<?=_("����ļ��Ƿ����")?>" onClick="get_icon();"> <span id="ckIconMsg" style="font-weight:bold;"></span>
      <br /><?=_("�뽫��Ӧ��ͼ���ļ�����ڷ����� OA����Ŀ¼\webroot\static\images\app_icons\ Ŀ¼�£��粻��д����ʾĬ��ͼƬ��ͼ��ߴ�64*64Ч�����")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��ַ��")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL" class="BigInput" size="50" maxlength="200" value="http://">
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" name="USER" value="">
        <input type="submit" value="<?=_("���")?>" class="BigButton" title="<?=_("�����ַ")?>" name="button">
    </td>
  </form>
</table>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("��������ַ")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 $query = "SELECT * from URL where USER='' order by URL_TYPE,URL_NO";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

 $URL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $URL_COUNT++;
    $URL_ID=$ROW["URL_ID"];
    $URL_NO=$ROW["URL_NO"];
    $URL_DESC=$ROW["URL_DESC"];
    $URL=$ROW["URL"];
    $URL_TYPE=$ROW["URL_TYPE"];
    $SUB_TYPE=$ROW["SUB_TYPE"];
    $SUB_TYPE=$ROW["SUB_TYPE"];
    $SUB_TYPE_DESC=$URL_TYPE=="1" ? get_code_name($SUB_TYPE,"RSS_TYPE") : "";
    if($URL_TYPE =="1")
       $URL_TYPE_DESC = _("RSS����");
    else if($URL_TYPE =="2")
       $URL_TYPE_DESC = _("�ղؼ�");
    else if($URL_TYPE =="3")
		 $URL_TYPE_DESC = _("�Զ���Ӧ��");
	 else		
       $URL_TYPE_DESC = _("��ͨ��ַ");

    if($URL_COUNT==1)
    {
?>
<table class="TableList" width="90%">
<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$URL_NO?></td>
      <td nowrap align="center"><?=$URL_DESC?></td>
      <td><A href="<?=$URL?>" target="_blank"><?=$URL?></A></td>
      <td nowrap align="center"><?=$URL_TYPE_DESC?></td>
      <td nowrap align="center"><?=$SUB_TYPE_DESC?></td>
      <td nowrap align="center" width="80">
      <a href="edit.php?URL_ID=<?=$URL_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("�༭")?></a>
      <a href="javascript:delete_url('<?=$URL_ID?>');"> <?=_("ɾ��")?></a>
      </td>
    </tr>
<?
 }

 if($URL_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("���")?></td>
      <td nowrap align="center"><?=_("˵��")?></td>
      <td nowrap align="center"><?=_("��ַ")?></td>
      <td nowrap align="center"><?=_("���")?></td>
      <td nowrap align="center"><?=_("�����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    <thead class="TableControl">
      <td nowrap align="center" colspan="6">
      <input type="button" class="BigButton" OnClick="javascript:delete_all();" value="<?=_("ȫ��ɾ��")?>">
      </td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("��δ�����ַ"));
?>

</div>

</body>
</html>