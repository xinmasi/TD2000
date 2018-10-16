function createxmlhttp()
{
	xmlhttpobj = false;
	try
	{
		xmlhttpobj = new XMLHttpRequest;//IE以外的浏览器
	}
	catch(e)
	{
		try
		{
			xmlhttpobj=new ActiveXObject("MSXML2.XMLHTTP");
		}
		catch(e2)
		{
			try
			{
				xmlhttpobj=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e3)
			{
				xmlhttpobj = false;
			}
		}
	}
	return xmlhttpobj; 
}
 
function getAjax(CHAT_ID)
{
	var CHAT_ID=CHAT_ID;
	var xmlhttpobj = createxmlhttp();
	if(xmlhttpobj)
	{
		xmlhttpobj.open('get',"view_get.php?CHAT_ID="+CHAT_ID,true);
		xmlhttpobj.setRequestHeader("Cache-Control","no-cache"); 
		xmlhttpobj.setRequestHeader("If-Modified-Since","0"); 
		
		xmlhttpobj.onreadystatechange=function()
		{
			if(xmlhttpobj.readyState==4)
			{
				if(xmlhttpobj.status==200)
				{
					
					var htmlxxx = xmlhttpobj.responseText;//获得返回值
					//a=window.parent.chat_view_area.document;
					a=window.parent.window.frames["chat_view_area"].document;
                    a.getElementById("loadAjaxTips").innerHTML=htmlxxx;
				}
				
			}
			
		}
		xmlhttpobj.send(null); 
	}
}
