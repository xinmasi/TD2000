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
      <div align="center">商业合同</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR> 
　　合同号：＿＿＿＿<BR> 
　　日　期：＿＿＿＿<BR> 
　　买方：＿＿＿＿<BR> 
　　电报：＿＿＿＿　电传：＿＿＿＿<BR> 
　　卖方：＿＿＿＿<BR> 
　　电报：＿＿＿＿＿　　　电传：＿＿＿＿<BR> 
　　按本合同条款，买方同意购入，卖方同意出售下述产品，谨此签约。<BR> 
　　１．品名、规格：＿＿＿＿<BR> 
　　单位：＿＿＿＿<BR> 
　　单价：＿＿＿＿<BR> 
　　总价：＿＿＿＿<BR> 
　　总金额：＿＿＿＿<BR> 
　　２．原产国别和生产厂：＿＿＿＿<BR> 
　　３．包装：<BR> 
　　须用坚固的木筱或纸箱包装。以宜于长途海运／邮寄／空运及适应气候的变化。并具备良好的防潮抗震能力。<BR> 
　　由于包装不良而引起的货物损伤或由于防护措施不善而引起货物锈蚀，卖方应赔偿由此而造成的全部损失费用。<BR> 
　　包装箱内应附有完整的维修保养、操作使用说明书。<BR> 
　　４．装运标记：<BR> 
　　卖方应在每个货箱上用不褪色油漆标明箱号、毛重、净重、长、宽、高并书以“防潮”、“小心轻放”、“此面向上”等字样和装运：<BR> 
　　５．装运日期：＿＿＿＿<BR> 
　　６．装运港口：＿＿＿＿<BR> 
　　７．卸货港口：＿＿＿＿<BR> 
　　８．保　　险：＿＿＿＿<BR> 
　　装运后由买方投保。<BR> 
　　９．支付条件：<BR> 
　　分下述三种情况：<BR> 
　　（１）采用信用证：<BR> 
　　买方收到卖方交货通知〔详见本合同条款１１（１）ａ〕，应在交货日前１５～２０天，由＿＿＿＿＿＿银行开出以卖方为受益人的与装运全金额相同的不可撤销信用证。卖方须向开证行出具１００％发票金额即期汇票并附装运单据（见本合同第１０款）。开证行收到上述汇票和装运单据即付以支付（电汇或航邮付汇）。信用证于装运日期后１５天内有效。<BR> 
　　（２）托收：<BR> 
　　货物装运后，卖方出具即期汇票，连同装运单据（见本合同第１０款），通过卖方所在地银行和买方＿＿＿＿＿＿＿＿银行提交给买方进行托收。<BR> 
　　（３）直接付款：<BR> 
　　买方收到卖方装运单据（见本合同第１０款）后７天内，以电汇或航邮向卖方支付货款。<BR> 
　　１０．单据：　　<BR> 
　　小海运<BR> 
　　全套洁净海运提单，标明“运费付讫”／“运费预付”，作成空白背书并加注目的港＿＿＿＿公司。<BR> 
　　（２）空运：<BR> 
　　空运提单副本一份，标明“运费付讫”／“运费预付”，寄交买方。<BR> 
　　（３）航邮：<BR> 
　　航邮收据副本一份，寄交买方。<BR> 
　　（４）发票一式五份，标明合同号和货运唛头（若货运唛头多于一个，发票需单独开列），发票根据有关合同详细填写。<BR> 
　　（５）由厂商出具的装箱清单一式两份。<BR> 
　　（６）由厂商出具的质量和数量保证书。<BR> 
　　（７）货物装运后立即用电报／信件通知买方。<BR> 
　　此外，货发１０天内，卖方将上述单据（第５条除外）航邮寄两份，一份直接寄买方，另一份直接寄目的港＿＿＿＿公司。<BR> 
　　１１．装运：<BR> 
　　（１）Ｆ．Ｏ．Ｂ．条款：<BR> 
　　ａ．卖方于合同规定的装运日期前３０天，用电报／信件将合同号、品名、数量、价值、箱号、毛重、装箱尺码和货抵装运港日期通知买方，以便买方租船订舱。<BR> 
　　ｂ．卖方船运代理＿＿＿＿公司＿＿＿＿<BR> 
　　（电报：＿＿＿＿），负责办理租船订舱事宜。<BR> 
　　ｃ．＿＿＿＿租船公司或其港口代理（或班轮代理），预计船达装运港１０天之前，即将船名、预计装货日期、合同号等通知卖方以便卖方安排装运。要求卖方与船方代理保持密切联系。当需要更换运载船舶及船舶提前、推迟抵达时，买方或其船方代理应及时通知卖方。<BR> 
　　若船在买方通知日后３０天内尚未抵达，则第３０天后仓储费和保险费用由买方承担。<BR> 
　　ｄ．若载运船舶如期抵达装运港，卖方因备货未妥而影响装船，则空舱费和滞期费均由卖方承担。<BR> 
　　ｅ．货物越过船舷并从吊钩卸下前，一切费用和风险由卖方承担；货物越过船舷并从吊钩卸下，一切费用和风险属买方。<BR> 
　　（２）Ｃ＆Ｆ条款下：<BR> 
　　ａ．在装运期内，卖方负责将货物从装运港运至目的港。不允许转船。<BR> 
　　ｂ．货物经航邮／空运时，卖方于本合同第５条规定的交货日前３０天，以电报／信件把交货预定期、合同号、品名、发票金额等通知买方。货物交办发运，卖方即刻以电报／信件将合同号、品名、发票金额、交办日期通知买方，以便买方及时投保。<BR> 
　　１２．装运通知<BR> 
　　货物业经全部装船，卖方应将合同号、品名、数量、发票金额、毛重、船名和启船日期等立即以电报／信件通知买方。若因卖方通知不及时致使买方不能及时投保，卖方则承担全部损失。<BR> 
　　１３．质量保证：<BR> 
　　卖方保证：所供货物，系由最好的材料兼以高超工艺制成，商标为新的和未经使用的，其质量和规格符合本合同所作的说明。自货到目的港起１２个月为质量保证期。<BR> 
　　１４．索赔：<BR> 
　　自货到目的港起９０天内，经发现货物质量、规格、数量与合同规定不符者，除那些应由保险公司或船方承担的部分外，买方可凭＿＿＿＿出具的商检证书，有权要求更换或索赔。<BR> 
　　卖方担保货到目的港起１２个月内，使用过程中由于材料质量低劣和工艺不佳而出现的损伤，买方立即以书面形式通知卖方并出具＿＿＿＿商检局开列的检验证书，提出索赔。商检验书乃索赔之依据。按买方索赔要求，卖方有责任立即排除货物之缺陷、全部或部分更换货物或根据缺陷情况将货物作降价处理。<BR> 
　　１５．不可抗力：<BR> 
　　在货物制造和装运过程中，由于发生不可抗力事故致使延期交货或不能交货，卖方概不负责。卖方于不可抗力事件发生后，即刻通知买方并在事发１４天内，以航空邮件将事故发生所在地当局签发的证书寄交买方以作证据。即使在此情况下，卖方仍有责任采取必要措施促使尽快交货。<BR> 
　　不可抗力事故发生后超过１０个星期而合同尚未履行完毕，买方有权撤销合同。<BR> 
　　１６．合同延期和罚款：<BR> 
　　除本合同１５条所述不可抗力原因，卖方若不能按合同规定如期交货，按照卖方确认的罚金支付，买方可同意延期交货，付款银行相应减少议定的支付金额，但罚款不得超过迟交货物总额的５％。卖方若逾期１０个星期仍不能交货，买方有权撤销合同。尽管合同已撤销，但卖方仍应如期支付上述罚金。<BR> 
　　１７．仲裁：<BR> 
　　凡涉及本合同或因执行本合同而发生的一切争执，应通过友好协商解决，如果协商不能解决，则可提交＿＿＿＿仲裁委员会根据该会暂定的仲裁法则和程序进行仲裁。仲裁将在＿＿＿＿进行，仲裁裁决是终局，对双方都有约束力。仲裁费用由败诉方承担。仲裁也可在双方均能接受的第三国进行。<BR> 
　　１８．附加条款：<BR> 
　　本合同原本两份。经双方签字，各执一份，仅此声明。<BR> 
　　卖方：＿＿＿＿　　买方：＿＿＿＿<BR> 
　　<BR> 
　　注：①　商业合同是一种通用合同。在国际贸易中，若双方对合同货物无特殊要求的条件下，一般都采用商业合同的内容和形式。<BR> 
　　<BR> 
　　<BR> 
　　<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>