var xmlHttp;

function createXMLHttpRequest() { //建立XMLHttpRequest

    try {

     xmlHttp= new XMLHttpRequest();

   } catch (e) {

    try {

            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");

       } catch (ee) {

            try {

            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");

            } catch (err) {

             xmlHttp = false;

            }

       }

      }

    if(!xmlHttp) alert("不能创建XMLHttpRequest对象");

}

createXMLHttpRequest();
//事件处理函数

function show()
{
return "1";
   //如果readyState为4，表示响应已经被完全接收。

   if(xmlHttp.readyState==4)

   {

      //如果获得的结果状态代码为200，表示服务端正常返回

      if(xmlHttp.status==200)

      {

         return xmlHttp.responseXML;

      }

   }

}

function test(){
	xmlHttp.open("GET","http://localhost/general/reportshop/utils/misc.php",false);
	xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=GBK");
	xmlHttp.onreadystatechange=show;
	xmlHttp.send("action=timestamp&test=abc");
	return xmlHttp.responseText;
}
