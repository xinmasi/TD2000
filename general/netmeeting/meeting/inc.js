function createxmlhttp()
{
	xmlhttpobj = false;
	try
	{
		xmlhttpobj = new ActiveXObject;
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
function getAjax(MEET_ID)
{
	var MEET_ID=MEET_ID;
	var xmlhttpobj = createxmlhttp();
	if(xmlhttpobj)
	{
		xmlhttpobj.open('get',"view_get.php?MEET_ID="+MEET_ID,true);//get方法
		xmlhttpobj.setRequestHeader("Cache-Control","no-cache"); 
		xmlhttpobj.setRequestHeader("If-Modified-Since","0"); 
	
		xmlhttpobj.onreadystatechange=function()
		{
			if(xmlhttpobj.readystate==4)
			{
				if(xmlhttpobj.status==200)
				{
					
					var htmlxxx = xmlhttpobj.responseText;//获得返回值
					a=window.parent.chat_view_area.document;
               a.getElementById("loadAjaxTips").innerHTML=htmlxxx;
					
				}
			}
		} 
		xmlhttpobj.send(null);
	}
}