var xmlHttp;

function createXMLHttpRequest() { //����XMLHttpRequest

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

    if(!xmlHttp) alert("���ܴ���XMLHttpRequest����");

}

createXMLHttpRequest();
//�¼�������

function show()
{
return "1";
   //���readyStateΪ4����ʾ��Ӧ�Ѿ�����ȫ���ա�

   if(xmlHttp.readyState==4)

   {

      //�����õĽ��״̬����Ϊ200����ʾ�������������

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
