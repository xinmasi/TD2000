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
      <div align="center">运输合同</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR> 
　　订立合同双方：<BR> 
　　托运方：_________________________________________________；<BR> 
　　承运方：_________________________________________________．<BR> 
　　根据国家有关运输规定，经过双方充分协商，特订立本合同，以便双方共同遵守．一，货物名称、<BR> 
　　规格、数量、价款：<BR> 
　　｜货物编号｜　　品　名　　｜规格｜单位｜单价｜数量｜　金　额（元）　　｜<BR> 
　　｜________｜______________｜____｜____｜____｜____｜__________________｜<BR> 
　　｜________｜______________｜____｜____｜____｜____｜__________________｜<BR> 
　　｜________｜______________｜____｜____｜____｜____｜__________________｜<BR> 
　　｜________｜______________｜____｜____｜____｜____｜__________________｜<BR> 
　　二，包装要求：托运方必须按照国家主管机关规定的标准包装；没有统一规定包装标准的，应根据保证<BR> 
　　货物运输安全的原则进行包装，否则承运方有权拒绝承运．三，运输办法及运杂费负担：<BR> 
　　___________________________________________________________________________________________<BR> 
　　___________________________________<BR> 
　　四，托运时间及地点：<BR> 
　　___________________________________________________________________________________________<BR> 
　　___________________________________________<BR> 
　　五，到货时间及地点：<BR> 
　　___________________________________________________________________________________________<BR> 
　　___________________________________________<BR> 
　　六，收货人领取货物及验收办法：<BR> 
　　___________________________________________________________________________________________<BR> 
　　_________________________________<BR> 
　　七，付款办法：<BR> 
　　___________________________________________________________________________________________<BR> 
　　_________________________________________________<BR> 
　　八，违约责任：<BR> 
　　托运方责任：<BR> 
　　１．未按合同规定的时间和要求提供托运的货物，托运方应按货物价值的____％偿付给承运方违约金．<BR> 
　　２．由于在普通货物中夹带、匿报危险货物，错报笨重货物重量等而招致吊具断裂，货物摔损，吊机倾<BR> 
　　翻，爆炸，腐蚀等事故，托运方应承担赔偿责任．<BR> 
　　３．由于货物包装缺陷产生破损，致使其他货物或运输工具，机械设备被污染腐蚀、损坏，造成人身伤<BR> 
　　亡的，托运方应承担赔偿责任．<BR> 
　　４．在托运方专用线或在港、站公用线、专用铁道自装的货物，在到站卸货时，发现货物损坏，缺少，<BR> 
　　在车辆施封完好或无异状的情况下，托运方应赔偿收货人的损失．<BR> 
　　５．罐车发运货物，因未随车附带规格质量证明或化验报告，造成收货方无法卸货时，托运方应偿付承<BR> 
　　运方卸车等存费及违约金．<BR> 
　　承运方责任：<BR> 
　　１．不按合同规定的时间和要求配车（船）发运的承运方应偿付托运方违约金__元．２．承运方如将货<BR> 
　　物错运到货地点或接货人，应无偿运至合同规定的到货地点或接货人．如果货物逾期达到，承运方应偿<BR> 
　　付逾期交货的违约金．<BR> 
　　３．运输过程中货物灭失，短少，变质，污染，损坏，承运方应按货物的实际损失（包括包装费，运杂<BR> 
　　费）赔偿托运方．<BR> 
　　４．联运的货物发生灭失，短少，变质，污染，损坏应由承运方承担赔偿责任的，由终点阶段的承运方<BR> 
　　向负有责任的其他承运方追偿．<BR> 
　　５．在符合法律和合同规定条件下的运输，由于下列原因造成货物灭失，短少，变质，污染，损坏的，<BR> 
　　承运方不承担违约责任：<BR> 
　　①不可抗力；<BR> 
　　②货物本身的自然属性；<BR> 
　　③货物的合理损耗；<BR> 
　　④托运方或收货方本身的过错；<BR> 
　　九，本合同正本一式二份，合同双方各执一份；合同副本一式____份，送____等单位各留一份．<BR> 
　　托运方：__________________　　　　　承运方：_______________________<BR> 
　　代表人：__________________　　　　　代表人：_______________________<BR> 
　　地址：____________________　　　　　地址：_________________________<BR> 
　　电话：____________________　　　　　电话：_________________________<BR> 
　　开户银行：________________　　　　　开户银行：_____________________<BR> 
　　帐号：____________________　　　　　地址：_________________________<BR> 
　　____年____月______日．<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>