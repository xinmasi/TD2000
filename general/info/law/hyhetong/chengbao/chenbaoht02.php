<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?><style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style><BODY class="bodycolor">
<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader">
      <div align="center">减亏（补贴）包干合同</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR> 
　　发包方：＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿，简称甲方；<BR> 
　　承包方：＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿，简称乙方，企业经营者＿＿＿＿＿＿＿＿＿＿＿。<BR> 
　　甲、乙双方经协商，签订本合同。<BR> 
　　一、承包期限：＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　二、承包形式为下列第＿＿种：<BR> 
　　１．定额补贴，超亏不补，减亏全留<BR> 
　　（１）承包期间，财政共给乙方亏损补贴＿＿＿＿＿元，其中＿＿＿＿年＿＿＿＿元；＿＿＿＿年＿＿＿＿元；＿＿＿＿年＿＿＿＿元。<BR> 
　　（２）乙方亏损超出上述定额，由乙方自行解决。解决办法为：＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　（３）乙方亏损数额低于上述定额，低于定额的部分全部留给乙方。<BR> 
　　２．亏损补贴，减亏分成<BR> 
　　乙方的全部亏损，由财政给予补贴：减亏部分，由甲、乙双方按下述比例分成；＿＿＿＿年，甲方＿＿＿％，乙方＿＿＿％；＿＿＿＿年，甲方＿＿＿％，乙方＿＿＿％；＿＿＿＿年，甲方＿＿＿％，乙方＿＿＿％。<BR> 
　　三、减亏留成的使用<BR> 
　　乙方对减亏的留成，按下述比例建立生产发展、奖励、福利三项基金：＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　四、主要经济技术指标＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　五、技术改造任务<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　六、固定资产的增值和维护<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　七、承包期间，甲方有权根据法律和合同的规定监督检查乙方的生产经营情况；有权＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿，有权＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　承包期间，甲方不得在法律和合同的规定之外随意干涉乙方的生产经营活动，并有义务（一）＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿；（二）＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿；（三）＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿。<BR> 
　　八、承包期间，乙方享有国家法律、法规、政策规定的经营管理自主权。<BR> 
　　企业经营者＿＿＿＿＿＿＿为乙方法定代表人，对企业经营管理中的下列事项，有依法自主决定权；＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　九、违约责任<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　十、对企业经营者的奖惩<BR> 
　　奖励：＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　处罚：＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　十一、争议的解决方式<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　十二、合同的生效条件和生效日期<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　十三、其他事项<BR> 
　　１．＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　２．＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　３．＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　４．＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　附件：１．＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　２．＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　３．＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　甲方：（公章）＿＿＿＿＿＿　　　　乙方：（公章或个人签章）＿＿＿＿＿<BR> 
　　地址＿＿＿＿＿＿＿＿＿＿＿　　　　地址＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　法人代表（签章）＿＿＿＿＿　　　　企业经营者（签章）＿＿＿＿＿＿＿＿<BR> 
　　代理人（签章）＿＿＿＿＿＿<BR> 
　　签约日期：　　　年　　月　　日<BR> 
　　签约地点：<BR> 
　　〔说明〕承包方即乙方如是个人时，应在合同最后署个人姓名并盖章，企业经营者（签章）一栏不必填写。<BR> 
　　<BR> 
　　<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>