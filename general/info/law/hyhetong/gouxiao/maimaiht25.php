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
      <div align="center">买卖合同（七）</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR> 
　　订立合同双方：<BR> 
　　<BR> 
　　购货单位：＿＿＿＿＿＿＿＿＿（以下简称甲方）<BR> 
　　供货单位：＿＿＿＿＿＿＿＿＿（以下简称乙方）<BR> 
　　<BR> 
　　第一条　其产品名称、规格、质量（技术指标）、单价、总价等，如表所列：<BR> 
　　┌─────┬─────┬─────┬────┬─────┬─────┐<BR> 
　　│材料名称及│规格（毫米│质量标准或│计量单位│单价（元）│合计（元）│<BR> 
　　│花色　　　│）及型号　│技术指标　│　　　　│　　　　　│　　　　　│<BR> 
　　├─────┼─────┼─────┼────┼─────┼─────┤<BR> 
　　│　　　　　│　　　　　│　　　　　│　　　　│　　　　　│　　　　　│<BR> 
　　├─────┼─────┼─────┼────┼─────┼─────┤<BR> 
　　│　　　　　│　　　　　│　　　　　│　　　　│　　　　　│　　　　　│<BR> 
　　├─────┼─────┼─────┼────┼─────┼─────┤<BR> 
　　│　　　　　│　　　　　│　　　　　│　　　　│　　　　　│　　　　　│<BR> 
　　└─────┴─────┴─────┴────┴─────┴─────┘<BR> 
　　第二条　产品包装规格及费用＿＿＿＿＿＿＿＿＿<BR> 
　　第三条　验收方法＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　第四条　货款及费用等付款及结算办法＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　第五条　交货规定<BR> 
　　１．交货方式：＿＿＿＿＿＿<BR> 
　　２．交货地点：＿＿＿＿＿＿<BR> 
　　３．交货日期：＿＿＿＿＿＿<BR> 
　　４．运输费：＿＿＿＿＿＿＿<BR> 
　　第六条　经济责任<BR> 
　　１．乙方应负的经济责任<BR> 
　　（１）产品花色、品种、规格、质量不符合本合同规定时，甲方同意利用者，按质论价。不能利用的，乙方应负责保修、保退、保换。由于上述原因致延误交货时间，每逾期一日，乙方应按逾期交货部分货款总值的万分之＿＿＿计算向甲方偿付逾期交货的违约金。<BR> 
　　（２）乙方未按本合同规定的产品数量交货时，少交的部分，甲方如果需要，应照数补交。甲方如不需要，可以退货。由于退货所造成的损失，由乙方承担。如甲方需要而乙方不能交货，则乙方应付给甲方不能交货部分货款总值的＿＿＿％的罚金。<BR> 
　　（３）产品包装不符合本合同规定时，乙方应负责返修或重新包装，并承担返修或重新包装的费用。如甲方要求不返修或不重新包装，乙方应按不符合同规定包装价值＿＿％的罚金付给甲方。<BR> 
　　（４）产品交货时间不符合同规定时，每延期一天，乙方应偿付甲方以延期交货部分货款总值万分之＿＿的罚金。<BR> 
　　（５）乙方未按照约定向甲方交付提取标的物单证以外的有关单证和资料，应当承担相关的赔偿责任。<BR> 
　　２．甲方应负的经济责任<BR> 
　　（１）甲方如中途变更产品花色、品种、规格、质量或包装的规格，应偿付变更部分货款（或包装价值）总值Ｘ％的罚金。<BR> 
　　（２）甲方如中途退货，应事先与乙方协商，乙方同意退货的，应由甲方偿付乙方退货部分货款总值＿＿％的罚金。乙方不同意退货的，甲方仍须按合同规定收货。<BR> 
　　（３）甲方未按规定时间和要求向乙方交付技术资料、原材料或包装物时，除乙方得将交货日期顺延外，每顺延一日，甲方应付给乙方顺延交货产品总值万分之＿＿的罚金。如甲方始终不能提出应提交的上述资料等，应视中途退货处理。<BR> 
　　（４）属甲方自提的材料，如甲方未按规定日期提货，每延期一天，应偿付乙方以延期提货部分货款总额万分之＿＿＿的罚金。<BR> 
　　（５）甲方如未按规定日期向乙方付款，每延期一天，应按延期付款总额万分之＿＿计算付给乙方，作为延期罚金。<BR> 
　　（６）乙方送货或代运的产品，如甲方拒绝接货，甲方应承担因而造成的损失和运输费用及罚金。<BR> 
　　第七条　产品价格如须调整，必须经双方协商。如乙方因价格问题而影响交货，则每延期交货一天，乙方应按延期交货部分总值的万分之＿＿作为罚金付给甲方。<BR> 
　　第八条　甲、乙任何一方如要求全部或部分注销合同，必须提出充分理由，经双方协商提出注销合同一方须向对方偿付注销合同部分总额＿＿％的补偿金。<BR> 
　　第九条　如因生产资料、生产设备、生产工艺或市场发生重大变化，乙方须变更产品品种、花色、规格、质量、包装时，应提前＿＿天与甲方协商。<BR> 
　　第十条　本合同所订一切条款，甲、乙任何一方不得擅自变更或修改。如一方单独变更、修改本合同，对方有权拒绝生产或收货，并要求单独变更、修改合同一方赔偿一切损失。<BR> 
　　第十一条　甲、乙任何一方如确因不可抗力的原因，不能履行本合同时，应及时向对方通知不能履行或须延期履行，部分履行合同的理由。在取得有关机构证明后，本合同可以不履行或延期履行或部分履行，并全部或者部分免予承担违约责任。<BR> 
　　第十二条　本合同在执行中如发生争议或纠纷，甲、乙双方应协商解决，解决不了时，双方可向仲裁机构申请仲裁或向人民法院提起诉讼。（两者选一）<BR> 
　　第十三条　本合同自双方签章之日起生效，到乙方将全部订货送齐经甲方验收无误，并按本合同规定将货款结算以后作废。<BR> 
　　第十四条　本合同在执行期间，如有未尽事宜，得由甲乙双方协商，另订附则附于本合同之内，所有附则在法律上均与本合同有同等效力。<BR> 
　　第十五条　本合同一式＿＿＿份，由甲、乙双方各执正本一份、副本＿＿份。<BR> 
　　<BR> 
　　订立合同人：<BR> 
　　<BR> 
　　甲方：＿＿＿＿＿＿（盖章）　　　　　　　　　乙方：＿＿＿＿（盖章）<BR> 
　　代理人：＿＿＿＿＿（盖章）　　　　　　　　　代理人：＿＿＿（盖章）<BR> 
　　负责人：＿＿＿＿＿（盖章）　　　　　　　　　负责人：＿＿＿（盖章）<BR> 
　　地址：＿＿＿＿＿＿＿＿　　　　　　　　　　　地址：＿＿＿＿＿＿<BR> 
　　电话：＿＿＿＿＿＿＿＿　　　　　　　　　　　电话：＿＿＿＿＿＿<BR> 
　　开户银行、帐号＿＿＿＿＿　　　　　　　　　　开户银行、帐号＿＿＿＿<BR> 
　　<BR> 
　　＿＿＿＿年＿＿月＿＿日<BR> 
　　<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>