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
      <div align="center">购销合同</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> 合同编号_____________</p>
      <p> 　　<BR> 
　　<BR> 
　　供方 ─────────────　　　　　　 签订日期_____________<BR> 
　　签证编号_____________　　　　　　　　　　　　 <BR> 
　　需方 ──────────-<BR> 
　　经充分协商，签订本合同，共同信守。<BR> 
　　<BR> 
　　一，品名、规格、数量、金额、交货日期：<BR> 
　　┌────┬────┬─┬──┬─┬──┬────┬─────────┐<BR> 
　　│品　名　│规格型号│单│数量│单│金额│交(提)货│超欠　　　　　　　│<BR> 
　　│　　　　│　　　　│位│　　│价│　　│日　期　│幅度％─────- │<BR> 
　　│　　　　│　　　　│　│　　│　│　　│　　　　│─────────│<BR> 
　　│　　　　│　　　　│　│　　│　│　　│　　　　│─────────│<BR> 
　　│　　　　│　　　　│　│　　│　│　　│　　　　│─────────│<BR> 
　　│　　　　│　　　　│　│　　│　│　　│　　　　│─────────│<BR> 
　　│　　　　│　　　　│　│　　│　│　　│　　　　│─────────│<BR> 
　　├────┴────┴─┴──┴─┴──┴────┴─────────│<BR> 
　　│货款共计人民币：(大写)　　　　　　　　　　　　　　　　　　　　　　　│<BR> 
　　└──────────────────────────────────┘<BR> 
　　二，质量技术标准：____________________________________________________<BR> 
　　三，原材料供应办法：__________________________________________________<BR> 
　　四，交(提)货办法，运输方式及地点：____________________________________<BR> 
　　五，质量检验及验收办法：______________________________________________<BR> 
　　六，包装要求及费用负担：______________________________________________<BR> 
　　七，结算方式及期限：__________________________________________________<BR> 
　　八，其它：____________________________________________________________<BR> 
　　九，供方经济责任：<BR> 
　　(1)产品、花色、品种、规格、质量、包装不符合同规定，需方同意收货的，按质论价；需方不同意收<BR> 
　　货的，供方应负责包修，包换，包退，并承担因而造成的损失。<BR> 
　　(2)未按合同规定的数量交货，少交而需方仍需要的，应照数补交；不需要的，可以退货，并承担损<BR> 
　　失；不能交货的，应偿付需方以不能交货的货款总值____________的罚金。<BR> 
　　(3)包装不符合规定，必须返修或重新包装，并承担因而支付的费用和损失；需方不要求返修或重新包<BR> 
　　装，而要求赔偿损失的应予赔偿损失。<BR> 
　　(4)未按合同规定时间交货，每延期一天，应偿付需方以延期交货部份货款总值千分之一的罚金。<BR> 
　　(5)不符合同规定的产品，在需方代保管期内应偿付需方实际支付的保管，保养费。<BR> 
　　(6)产品错发到货地点或接货人，除按合同规定负责运到指定的到货地点或接货人外，还应承担因而多<BR> 
　　付的运杂费和延期交货的损失。<BR> 
　　(7)其它：__________________________________________________________________<BR> 
　　十，需方经济责任：<BR> 
　　(1)变更产品品种，质量或包装的规格，给供方造成损失时，应偿付供方实际损失。<BR> 
　　(2)中途退货，偿付供方以退货部份货款总值_______________的罚金。<BR> 
　　(3)自提产品未按规定日期提货，每延期一天，应偿付供方以延期提货部份货款总值千分之一的罚金并<BR> 
　　承担实际支付的保管，保养费。<BR> 
　　(4)未按合同规定时间和要求提供原材料，技术资料，包装物的，除交货日期得以顺延外，每日应偿付<BR> 
　　供方顺延交货产品总值千分之一的罚金。<BR> 
　　(5)未按合同规定日期付款，每延期一天，应偿付供方以延期付款总额千分之一的罚金。<BR> 
　　(6)实行送货或代运的产品无故拒绝接货，应承担因而造成的损失和运输部门的罚金。<BR> 
　　(7)错填到货地点或接货人，应承担因此造成的损失。<BR> 
　　(8)其它：____________________________________________________________<BR> 
　　十一，供需双方由于人力不可抗拒和确非企业本身造成的原因而不能履行合同的，经双方协商和签证<BR> 
　　机关查实证明，可免予承担经济责任。<BR> 
　　十二，其它未尽事项，由双方协商另订附件。<BR> 
　　十三，本合同一式　份，自工商行政管理机关签证之日起生效。有效期到　年<BR> 
　　月　日。<BR> 
　　┌────┬──────┬───────┬─────────┐<BR> 
　　│供方　　│ 代表人　　 │　　　需方　　│　　 代表人　　　 │ <BR> 
　　├────┼──────┼───────┼─────────┤<BR> 
　　│地址　　│　电　话　　│　　　地址　　│　　　电　话　　　│　<BR> 
　　├────┼──────┼───────┼─────────┤　　　<BR> 
　　│开户　　│　帐　号　　│　　　开户　　│　　　帐　号　　　│　<BR> 
　　│银行　　│　　　　　　│　　　银行　　│　　　　　　　　　│　　<BR> 
　　├────┴────┬─┴───────┴─┬───────┤<BR> 
　　│供方业务主　　　　│需方业务主　　　　　　│签证机关　　　│　　　<BR> 
　　│管部门意见　　　　│管部门意见　　　　　　│　　　　　　　│　　　<BR> 
　　│　　　　　　　　　│　　　　　　　　　　　│　　　　　　　│　<BR> 
　　│　　　　　　　　　│　　　　　　　　　　　│　　　　　　　│<BR> 
　　│　　　年　月　日　│　　　　年　月　日　　│　　年　月　日│　<BR> 
　　└─────────┴───────────┴───────┘<BR> 
　　( 省　市工商行政管理局制订)<BR> 
　　<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>