<?
include_once("inc/auth.inc.php");
include_once("inc/utility_var.php");

$HTML_PAGE_TITLE = _("��ʼ��USB�û�KEY");
include_once("inc/header.inc.php");
?>



<body class="bodycolor" onload="GET_USERKEY();">
<object id="tdPassSC" name="tdPassSC" CLASSID="clsid:C7672410-309E-4318-8B34-016EE77D6B58"	CODEBASE="<?=MYOA_JS_SERVER?>/static/js/tdPass_<?=(stristr($_SERVER['HTTP_USER_AGENT'], 'x64') ? 'x64' : 'x86')?>.cab#version=1,2,12,1023"
	BORDER="0" VSPACE="0" HSPACE="0" ALIGN="TOP" HEIGHT="0" WIDTH="0"></object>
<object id="tdPass" name="tdPass" CLASSID="clsid:0272DA76-96FB-449E-8298-178876E0EA89"	CODEBASE="<?=MYOA_JS_SERVER?>/static/js/tdPass_<?=(stristr($_SERVER['HTTP_USER_AGENT'], 'x64') ? 'x64' : 'x86')?>.cab#version=1,2,12,1023"
	BORDER="0" VSPACE="0" HSPACE="0" ALIGN="TOP" HEIGHT="0" WIDTH="0"></object>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<script>
function GetKey(va)
{
   var a = new VBArray(va);
   return a.toArray().toString();
}
var xmlHttpObj=getXMLHttpObj();
var KEY_USERINFO;
var KEY_SN;
var timeoutID;

function READ_KEYSN()
{
  var theDevice=$("tdPass");
  var bOpened = OpenDevice(theDevice);
  if(!bOpened)return false;
  //��ȡ�豸���к�
  try
  {
    KEY_SN=theDevice.GetStrProperty(7, 0, 0);
    if(KEY_SN.substr(0, 8).toUpperCase() != "<?=$KEY_TD_SIGN?>")
    {
	    theDevice.CloseDevice();
	    $("MSG_AREA").innerHTML="<?=_("��Ч��USB Key������ϵ�����̽����")?>";
	    alert("<?=_("��Ч��USB Key������ϵ�����̽����")?>");
	    $("BTN_RETRY").disabled=false;
	    return false;
    }
  }
  catch(ex)
  {
	  theDevice.CloseDevice();
	  $("MSG_AREA").innerHTML="<?=_("USB�û�KEY��ʼ��ʧ��!")?>";
	  alert("<?=_("USB�û�KEY��ʼ��ʧ��!")?>");
	  $("BTN_RETRY").disabled=false;
	  return false;
	}
	return true;
}
function GET_USERKEY()
{
	$("MSG_AREA").innerHTML="<?=_("���ڳ�ʼ��USB�û�KEY�����Ժ�")?>...";
	$("BTN_RETRY").disabled=true;
	if(!READ_KEYSN())
	   return false;
	   
  var theURL="get_userinfo.php?KEY_SN="+KEY_SN;
  xmlHttpObj.open("GET",theURL,true);
  var responseText="";
  xmlHttpObj.onreadystatechange=function()
  {
    if(xmlHttpObj.readyState==4)
    {
      KEY_USERINFO=xmlHttpObj.responseText;
      CREAT_KEY();
    }
  }
  xmlHttpObj.send(null);
  timeoutID=window.setTimeout(function(){alert("<?=_("��ȡ�û���Ϣ��ʱ�������³�ʼ��")?>");$("BTN_RETRY").disabled=false;}, 30000);
}
function OpenDevice(theDevice)
{
   try
   {
      theDevice.GetLibVersion();
   }
   catch(ex)
   {
	    $("MSG_AREA").innerHTML="<?=_("��û�����ز���ȷ��װUSB�û�KEY��������")?>";
	    alert("<?=_("��û�����ز���ȷ��װUSB�û�KEY��������")?>");
	    $("BTN_RETRY").disabled=false;
	    return false;
	 }
   try
   {
      theDevice.OpenDevice(1, "");
   }
   catch(ex)
   {
	    $("MSG_AREA").innerHTML="<?=_("��û�в��˺Ϸ���USB�û�KEY��")?>";
	    alert("<?=_("��û�в��˺Ϸ���USB�û�KEY")?>");
	    $("BTN_RETRY").disabled=false;
	    return false;
	 }
   return true;
}
function CREAT_KEY()
{
	 window.clearTimeout(timeoutID);
	 var KEY_USERINFO_ARRY=KEY_USERINFO.split(",");
	 var theDevice=$("tdPassSC");
   //���豸
   var bOpened = OpenDevice(theDevice);
   if(!bOpened)
      return false;
   try
   {
   	  //д�û���Ϣ
   	  var USER_INFO=KEY_USERINFO_ARRY[0];
   	  var USER_CERTINFO=KEY_USERINFO_ARRY[1];
   	  var sign=OPEN_FILE(3);
   	  if(sign==1)theDevice.DeleteFile(0,3);
      theDevice.CreateFile(0,3,strlen(USER_INFO),2,0,0,7,2);
      theDevice.write(0,0,0,USER_INFO,strlen(USER_INFO));
      theDevice.CloseFile();
      var key1;
      var key2;
      key1 = GetKey(VBGetKey(0,USER_CERTINFO,theDevice));
	    key2 = GetKey(VBGetKey(1,USER_CERTINFO,theDevice));

     //д����˽Կ
     sign=OPEN_FILE(5);
   	 if(sign==1)theDevice.DeleteFile(0,5);
     theDevice.CreateFile(0,5,16,4,7,0,0,0);
     theDevice.write(1,0,0,key1,16);//
     theDevice.CloseFile();
     sign=OPEN_FILE(6);
   	 if(sign==1)theDevice.DeleteFile(0,6);
     theDevice.CreateFile(0,6,16,4,7,0,0,0);
     theDevice.write(1,0,0,key2,16);//
     theDevice.CloseFile();
    }
   catch(ex)
   {
	    theDevice.CloseDevice();
	    $("MSG_AREA").innerHTML="<?=_("USB�û�KEY��ʼ��ʧ��!�����³�ʼ��KEY!!")?>";
	    alert("<?=_("USB�û�KEY��ʼ��ʧ��!�����³�ʼ��KEY!")?>\n"+ex.description);
	    $("BTN_RETRY").disabled=false;
	    return false;
	 }
	 theDevice.CloseDevice();
	 $("MSG_AREA").innerHTML="<?=_("USB�û�KEY��ʼ���ɹ�!")?>";
	 alert("<?=_("USB�û�KEY��ʼ���ɹ�!")?>");
	 $("BTN_RETRY").disabled=true;
	 //$("BTN_RETRY").style.display="none";
	 return false;
}

function OPEN_FILE(fileid)
{
	var theDevice=$("tdPass");
  var bOpened = OpenDevice(theDevice);
  if(!bOpened)return -1;
  //��ȡ�豸���к�
  try
  {
   theDevice.OpenFile (0,fileid);
   theDevice.CloseFile();
   return 1;
  }
  catch(ex)
  {
	  theDevice.CloseDevice();
	  return 0;
	}
}
</script>
<script language="VBScript">
<?
//��VB������ȡ��Key1��Key2
?>
function VBGetKey(WhichKey,CertKey,theDevice)
		On Error Resume Next
		dim key
		theDevice.Soft_MD5HMAC WhichKey,0,CertKey,key
		If Err Then
			MsgBox ("VBGetKey:No.1\nError#" & Hex(Err.number and &HFFFF) & " \nDescription:" & Err.description)
			ePass.CloseDevice
			Exit function
		End If

    VBGetKey = Array(key)
End function
</script>
<br><br>
<div align="center" class="big1" id="MSG_AREA"></div>
<br>
<div align="center">
 <input type="button" value="<?=_("���³�ʼ��")?>" class="BigButton" onClick="GET_USERKEY()" id="BTN_RETRY" disabled="1">&nbsp;&nbsp;
 <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
</div>

</body>
</html>