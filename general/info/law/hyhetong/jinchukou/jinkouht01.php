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
      <div align="center">进口合同</font></div>
    </td>
  </tr>
  <tr>
    <td height="27" valign="top" class="TableData"> 
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR>
        　　合同号码：＿＿＿＿<BR>
        　　日期：＿＿＿＿<BR>
        　　地点：＿＿＿＿<BR>
        　　买方：＿＿＿＿<BR>
        　　卖方：＿＿＿＿<BR>
        　　兹经买卖双方同意，由买方购进，卖方出售下列货物，并按下列条款签订本合同：<BR>
        　　───────────┬───────┬────┬────┬──────<BR>
        　　(1)货物名称、规格、生 │　　　　　　　│　　　　│　　　　│<BR>
        　　产国别、制造工厂、包装│　(2)数　　　 │(3)单价 │(4)总　 │(5)装运期限<BR>
        　　及唛头　　　　　　　　│　　　　　　　│　　　　│　　　　│<BR>
        　　───────────┼───────┼────┼────┼──────<BR>
        　　每件货物上用不褪色的涂│　　　　　　　│　　　　│　　　　│<BR>
        　　每月交货数量料标明炉号│　　　　　　　│　　　　│　　　　│<BR>
        　　每项允许溢短装　　　　│　　　　　　　│　　　　│　　　　│<BR>
        　　必须一次交清、编号、　│　　　　　　　│　　　　│　　　　│　　　 <BR>
        　　尺码、目的口岸　　　　│　　　　　　　│　　　　│　　　　│<BR>
        　　，不得分批装，并标明下│　　　　　　　│　　　　│　　　　│<BR>
        　　列唛头运　　　　　　　│　　　　　　　│　　　　│　　　　│　　　<BR>
        　 　 │　　　　　　　│　　　　│　　　　│<BR>
        　　ContractNumber　　　│　　　　　　　│　　　　│　　　　│<BR>
        　　───────────│　　　　　　　│　　　　│　　　　│<BR>
        　　PortofDestination　　 │　　　　　　　│　　　　│　　　　│<BR>
        　　│　　　　　　　│　　　　│　　　　│<BR>
        　　───────────┴───────┴────┴────┴──────<BR>
        　　（６）装运口岸；<BR>
        　　（７）目的口岸；<BR>
        　　（８）付款条件：买方在收到卖方关于预计装船日期及准备装船的数量的通知后，应于装运前２０天通过北京中国银行开立以卖方为受益人的不可撤销的信用证。该信用证凭即期汇款票及本合同第（９）条规定的单据在开证行付款。<BR>
        　　（９）单据：各项单据均须使用与本合同相一致的文字，以便买方审核查对：<BR>
        　　Ａ．填写通知目的口岸对外贸易运输公司的空白抬头、空白背书的全套已装船的清洁提单（始本合同为ＦＯＢ价格条件时，提单应注明“运费到付”或“运费按租船合同办理”字样；如本合同为Ｃ＆Ｆ价格条件时，提单应注明“运费已付”字样）。<BR>
        　　Ｂ．发票：注明合同号、唛头、载货船名及信用证号；如果分批装运，须注明分批号。<BR>
        　　Ｃ．装箱单及／或重量单：注明合同号及唛头，并逐件列明毛重、净重和炉号。<BR>
        　　Ｄ．制造工厂的品质及数量／重量证明书。<BR>
        　　品质证明书内应列入根据合同规定的标准按炉号进行化学成分、机械性能及其它各种试验的实际试验结果。数量／重量证明书应按炉号列明重量。<BR>
        　　────────┬───┬──┬──┬──┬──┬──┬──┬──┬──<BR>
        　　份　　　单证│　　　│　　│　　│　　│　　│　　│　　│　　│<BR>
        　　寄　　　数　　　│　A　 │B　 │C　 │D　 │E　 │F　 │　　│　　│<BR>
        　　送　　　　　│　　　│　　│　　│　　│　　│　　│　　│　　│<BR>
        　　────────┼───┼──┼──┼──┼──┼──┼──┼──┼──<BR>
        　　送交议付银行　│　3　 │　4 │　3 │　3 │　1 │　1 │　　│　　│<BR>
        　　（正本）　　│　　　│　　│　　│　　│　　│　　│　　│　　│<BR>
        　　────────┼───┼──┼──┼──┼──┼──┼──┼──┼──<BR>
        　　送交议付银行　│　1　 │　　│　　│　　│　　│　　│　　│　　│<BR>
        　　（副本）　　│　　　│　　│　　│　　│　　│　　│　　│　　│<BR>
        　　────────┼───┼──┼──┼──┼──┼──┼──┼──┼──<BR>
        　　空邮目的口岸　│　　　│　　│　　│　　│　　│　　│　　│　　│<BR>
        　　外运公司　　│　2　 │　2 │　2 │　2 │　　│　　│　　│　　│<BR>
        　　（副本）　　│　　　│　　│　　│　　│　　│　　│　　│　　│<BR>
        　　────────┴───┴──┴──┴──┴──┴──┴──┴──┴──<BR>
        　　Ｅ．按本合同第（１１）条规定的装运通知电报抄本。<BR>
        　　Ｆ．按本合同第（１０）条规定的航行证明书（如本合同为Ｃ＆Ｆ价格条件时，需要此项证明书；如本合同为ＦＯＢ价格条件时，则不需此项证明书）。<BR>
        　　（１０）装运条件：<BR>
        　　Ａ．离岸价条款：<BR>
        　　ａ）装本合同货物的船只，由买方或买方运输代理人中国租船公司（地址：北京，二里沟。电报挂号ＺＨＯＮＧ　ＺＵ　ＰＥＫＩＮＧ）租订舱位。卖方负责货物的一切费用风险到货物装到船面为止。<BR>
        　　ｂ）卖方必须在合同规定的交货期限３０天前，将合同号码、货物名称、数量、装运口岸及预计货物运达装运口岸日期，以电报通知买方以便买方安排舱位。并同时通知买方在装港的船代理。若在规定期限内买方未接到前述通知，即作为卖方同意在合同规定期内任何日期交货，并由买方主动租订舱位。<BR>
        　　ｃ）买方应在船只受载期１２天前将船名，预计受载日期、装载数量、合同号码、船舶代理人，以电报通知卖方。卖方应联系船舶代理人配合，船期备货装船。如买方因故需要变更船只或更改船期时，买方或船舶代理人应及时通知卖方。<BR>
        　　ｄ）买方所租船只按期到达装运口岸后，如卖方不能按时备货装船，买方因而遭受的一切损失包括空舱费、延期费及／或罚款等由卖方负担。如船只不能于船舶代理人所确定的受载期内到达，在港口免费堆存期满后第１６天起发生的仓库租费、保险费由买方负担，但卖方仍负有载货船只到达装运口岸后立即将货物装船之义务并负担费用及风险。前述各种损失均凭原始单据核实支付。<BR>
        　　Ｂ．成本加运费价条款：<BR>
        　　卖方负责将本合同所列货物由装运口岸装达班轮到目的口岸，中途不得转船。货物不得用悬挂买方不能接受的国家的旗帜的船只装运。载货船只在驶抵本合同第（７）条规定的口岸前不得停靠台湾及／或台湾附近地区。<BR>
        　　（１１）装运通知：卖方在货物装船后，立即将合同号、品名、件数、毛重、净重、发票金额、载货船名及装船日期以电报通知买方。<BR>
        　　（１２）保险：自装船起由买方自理，但卖方应按本合同第（１１）条规定通知买方。如卖方未能按此办理，买方因而遭受的一切损失全由卖方负担。<BR>
        　　（１３）检验和索赔：货卸目的口岸，买方有权申请中国商品检验局进行检验。如发现货物的品质及／或数量／重量与合同或发票不符，除属于保险公司及／或船公司的责任外，买方有权在货卸目的口岸后９０天内，根据中国商品检验局出具的证明书向卖方提出索赔，因索赔所发生的一切费用（包括检验费用）均由卖方负担。ＦＯＢ价格条件时，如重量短缺，买方有权同时索赔短重部分的运费。<BR>
        　　（１４）不可抗力：由于人力不可抗拒事故，使卖方不能在合同规定期限内交货或者不能交货，卖方不负责任。但卖方必须立即以电报通知买方，并以挂号函向买方提出有关政府机关或者商会所出具的证明，以证明事故的存在。由于人力不可抗拒事故致使交货延期一个月以上时，买方有权撤销合同。卖方不能取得出口许可证，不得作为不可抗力。<BR>
        　　（１５）延期交货及罚款：除本合同第（１４）条人力不可抗拒原因外，如卖方不能如期交货，买方有权撤销该部分的合同，或经买方同意在卖方缴纳罚款的条件下延期交货。买方可同意给予卖方１５天的优惠期。罚款率为每１０天按货款总额的１％。不足１０天者按１０天计算。罚款自第１６天起计算，最多不超过延期货款总额的５％。<BR>
        　　（１６）仲裁：一切因执行本合同或与本合同有关的争执，应由双方通过友好方式协商解决。如经协商不能得到解决时，应提交北京中国国际贸易促进委员会对外贸易仲裁委员会，按照中国国际贸易促进委员会对外贸易仲裁委员会仲裁程序暂行规则进行仲裁。仲裁委员会的裁决为终局裁决，对双方均有约束力。仲裁费用除非仲裁委员会另有决定外，由败诉一方负担。<BR>
        　　（１７）附加条款：以上任何条款如与以下附加条款相抵触时，以以下附加条款为准。<BR>
        　　买方：＿＿（盖章）<BR>
        　　代表人：＿＿（签字）<BR>
        　　卖方：＿＿（盖章）<BR>
        　　代表人：＿＿（签字）<BR>
        　　＿＿年＿＿月＿＿日订立<BR>
        　　<BR>
        　　成套设备进口合同样本<BR>
        　　<BR>
        　　目录<BR>
        　　<BR>
        　　第一章　定义<BR>
        　　第二章　合同范围<BR>
        　　第三章　价格<BR>
        　　第四章　支付<BR>
        　　第五章　交货与交货条件<BR>
        　　第六章　包装与标记<BR>
        　　第七章　设计与设计联络<BR>
        　　第八章　标准与检验<BR>
        　　第九章　安装、试车和验收<BR>
        　　第十章　保证、索赔和罚款<BR>
        　　第十一章　侵权和保密<BR>
        　　第十二章　不可抗力<BR>
        　　第十三章　税费<BR>
        　　第十四章　仲裁<BR>
        　　第十五章　合同生效、终止及其他<BR>
        　　第十六条　法定地址<BR>
        　　<BR>
        　　附　件<BR>
        　　<BR>
        　　附件一　合同的供货范围和合同产品的内容（略）<BR>
        　　附件二“合同工厂”的规范及技术条件（略）<BR>
        　　附件三　技术资料的内容及交付日期（略）<BR>
        　　附件四　合同的分项价格（略）<BR>
        　　附件五　卖方技术人员的服务范围和待遇条件<BR>
        　　附件六　买方技术人员的培训范围和待遇条件<BR>
        　　附件七　卖方银行不可撤销的保证函（略）<BR>
        　　附件八　买方银行不可撤销的保证函（略）<BR>
        　　<BR>
        　　成套设备公司<BR>
        　　<BR>
        　　合同编号：＿＿＿＿<BR>
        　　签字日期：＿＿＿＿<BR>
        　　签字地点：＿＿＿＿<BR>
        　　中国，北京，中国技术进出口总公司（以下简称买方）为一方与＿＿国，＿＿市，＿＿公司（以下简称卖方）为另一方，双方授权代表同意就下列条款签订本合同：<BR>
        　　<BR>
        　　第一章　定义<BR>
        　　<BR>
        　　１．１　“买方”－－是指中国技术进出口总公司，或者该公司的法人代表、代理和财产继承者。<BR>
        　　１．２　“卖方”－－是指＿＿国＿＿公司，或者该公司的法人代表、代理和财产继承者。<BR>
        　　１．３　“合同产品”－－是指本合同附件一中所列的产品及其型号和规格。<BR>
        　　１．４　“技术资料”－－是指本合同附件二中所列的全部技术数据、图纸、设计、计算、操作、维修、产品检验资料。<BR>
        　　１．５　“合同工厂”－－是指买方使用卖方提供的技术和资料进行生产合同产品的场所，包括卖方提供的全套设备和备件，即＿＿省＿＿市＿＿工厂。<BR>
        　　１．６　“净销售价”－－是指合同产品的销售发票价格扣除包装费、运输费、保险费、佣金、商业折扣、税金和外购件等的费用后的余额。<BR>
        　　１．７　“技术服务”－－是指卖方根据本合同附件五和附件六中的规定，就合同产品的设计、制造、装配、检验、调试、操作等工作，向引进方提供的技术指导和技术培训。<BR>
        　　１．８　“商业性生产”－－是指合同工厂生产第＿＿台合同产品以后的生产。<BR>
        　　１．９　“合同生效日期”－－是指本合同的双方政府有关当局中的最后一方批准合同的日期。<BR>
        　　（注：可根据具体项目的需要增减上述定义）<BR>
        　　<BR>
        　　第二章　合同范围<BR>
        　　<BR>
        　　２．１　买方同意从卖方购买，卖方同意向买方出售＿＿成套设备项目（以下简称“合同工<BR>
        　　厂”），其中包括为保证合同工厂安全稳定地操作所需要的全部设备、材料和备件（以下简称“设备”），以及合同工厂装配、安装、试车、正常操作，生产和维修所需的全部技术和资料（以下简称“技术资料”）。<BR>
        　　卖方供货的具体内容，详见本合同附件一。<BR>
        　　卖方供应的“技术资料”，详见本合同附件三。<BR>
        　　２．２　卖方所供应的全部设备的技术性能和卖方对合同工厂设备的技术保证详见本合同附件二。<BR>
        　　２．３　卖方派遣有经验的、健康的和称职的技术人员到合同工厂现场对合同工厂的施工、安装、试车、投料试生产与考核进行技术指导。其人数、技术服务范围和待遇条件等见本合同附件五。<BR>
        　　２．４　卖方负责培训买方派遣的人员，其人数、培训地点，培训范围见本合同附件六。<BR>
        　　２．５　本合同签订后＿＿年内，根据买方的要求，卖方有义务以优惠价格提供买方的本“合同工厂”正常运行所需的全部备品备件。届时双方另签协议。<BR>
        　　<BR>
        　　第三章　价格<BR>
        　　<BR>
        　　３．１　卖方按本合同第二章规定提供合同工厂“设备”和“技术资料”的总价为＿＿（大写＿＿）。<BR>
        　　３．２　上述合同总价的分项价格如下：<BR>
        　　３．２．１　机械设备部分：<BR>
        　　Ａ．设备和材料费<BR>
        　　Ｂ．备品和备件费<BR>
        　　Ｃ．设计费<BR>
        　　Ｄ．技术资料费<BR>
        　　Ｅ．技术服务费<BR>
        　　Ｆ．技术培训费<BR>
        　　３．２．２　技术转让部分：<BR>
        　　Ａ．技术转让费<BR>
        　　Ｂ．设计费<BR>
        　　Ｃ．技术资料费<BR>
        　　Ｄ．技术服务费<BR>
        　　Ｅ．人员培训费<BR>
        　　上述分项价格清单，详见附件四。<BR>
        　　３．３　上述合同总价中的　设备　部分为ＦＯＢ＿＿港口买方指定的受载船支船面交的固定价格，并包括装船费，包装费以及将货物装到买方所指定的船面以前的一切费用，上述合同总价中的技术资料　部分是指和技术资料在北京机械交付以前的一切费用。<BR>
        　　<BR>
        　　第四章　支付（方案一）<BR>
        　　<BR>
        　　４．１　本合同买卖双方的支付均以电汇（Ｔ／Ｔ）进行。买方向卖方的付款应通过北京中国银行付给＿＿银行，卖方向买方的付款应通过＿＿银行付给北京中国银行。<BR>
        　　４．２　本合同第三章所规定的合同总价，按以下办法及比例由买方通过北京中国银行支付给卖方。<BR>
        　　４．２．１　合同总价的＿＿％计＿＿（大写：＿＿＿）。在买方收到卖方提交下列单据经审核无误后三十天内支付给卖方。<BR>
        　　（１）卖方国家有关当局出具的有效出口许可证的影印本一份，或有关当局出具的不需出口许可证的证明文件一份。<BR>
        　　（２）＿＿银行出具的以买方为受益人，金额为合同总价的＿＿％不可撤销的保证函正副本各一份（保证函格式见本合同附件七）。<BR>
        　　（３）金额为合同总价的形式发票一式六份。<BR>
        　　（４）即期汇票一式二份。<BR>
        　　（５）商业发票一式六份。<BR>
        　　上述单据卖方应于本合同生效之日起三十天内提交。<BR>
        　　买方在收到卖方按本合同第五章规定的货物装船通知后三十天内，经由北京中国银行出具以卖方为受益人，金额为合同总价的＿＿％的不可撤销的保证函给卖方（保函格式见本合同附件八）。<BR>
        　　４．２．２　合同总价的＿＿％，计＿＿（大写：＿＿），买方在收到卖方提交的下列单据经审核无误后三十天内支付给卖方：<BR>
        　　（１）全套清洁无疵、空白抬头、空白背书，并注明“运费到付通知目的港中国对外贸易运输公司”的海运提单正本三份，副本三份；<BR>
        　　（２）商业发票一式六份；<BR>
        　　（３）即期汇票一式二份；<BR>
        　　（４）装箱明细单一式六份；<BR>
        　　（５）质量和数量合格证书一式六份。<BR>
        　　４．２．３　合同总价的＿＿％，计＿＿（大写：（＿＿），在买方收到下列单据经审核无误后三十天内支付给卖方：<BR>
        　　（１）商业发票一式六份；<BR>
        　　（２）双方代表按本合同第九章规定签署的合同工厂交接验收证书的影印本一份。<BR>
        　　（３）即期汇票一式二份。<BR>
        　　４．２．４　合同总价的＿＿％，计＿＿（大写：＿＿），买方在按本合同第十章规定“合同工厂”保证期满后，并收到卖方提交的下列单据经审核无误后三十天内，即支付给卖方：<BR>
        　　（１）按本合同第十章规定的由买方出具的经卖方会签的“合同工厂”保证期满证明书副本一份。<BR>
        　　（２）商业发票一式六份。<BR>
        　　（３）即期汇票一式二份。<BR>
        　　４．２．５　按本合同第十章规定，如果卖方应支付赔款或／和罚款时，买方有权从上述任何一次付款中扣除。<BR>
        　　４．３　买、卖双方因履行本合同所发生的银行费用，在中国发生的，由买方负担；在中国以外发生的，由卖方负担。<BR>
        　　（注：方案一适用于现汇付款的情况）<BR>
        　　<BR>
        　　第四章　支付（方案二）<BR>
        　　<BR>
        　　４．１　本合同买卖双方的支付均以电汇（Ｔ／Ｔ）进行。买方向卖方的付款应通过北京中国银行付给＿＿银行，卖方向买方的付款应通过＿＿银行付给北京中国银行。<BR>
        　　４．２　本合同第三章所规定的合同总价，按以下办法及比例，由买方通过北京中国银行支付给卖方：<BR>
        　　４．２．１　合同总价的＿＿％，计＿＿（大写：＿＿），在买方收到卖方提交的下列单据经审核无误后不迟于三十天即支付给卖方：<BR>
        　　（１）卖方国家有关当局出具的出口许可证影印本一份，或有关当局出具的不需出口许可证的证明文件一份。<BR>
        　　（２）由＿＿银行出具的以买方为受益人金额为合同总的＿＿％的不可撤销的保证函正、副本各一份（保证函格式见本合同附件七）。<BR>
        　　（３）金额为合同总价的形式发票一式六份。<BR>
        　　（４）即期汇票一式二份。<BR>
        　　（５）商业发票一式六份。<BR>
        　　上述单据卖方应于本合同生效日起三十天内提交。<BR>
        　　４．２．２　合同总的＿＿％，计＿＿（大写：＿＿），在卖按本合同第五章规定交货时，买方在收到卖方提交的下列单据经审核无误后不迟于三十天，将每批交货总价的＿＿％支付给卖方：<BR>
        　　（１）全套清洁无疵、空白抬头、空白背书并注明“运费到付通知目的港中国对外贸易运输公司”的海运提单正本三份，副本三份；<BR>
        　　（２）商业发票一式六份；<BR>
        　　（３）即期汇票一式二份；<BR>
        　　（４）详细装箱单一式六份；<BR>
        　　（５）质量合格证一式六份。<BR>
        　　４．２．３　合同总价的＿＿％，计＿＿（大写：＿＿），在买方收到下列单据经审核无误后不迟于三十天支付给卖方：<BR>
        　　（１）商业发票一式六份。<BR>
        　　（２）双方代表按本合同第九章规定签署的合同工厂交接验收证书的影印本一份。<BR>
        　　（３）即期汇票一式二份。<BR>
        　　４．２．４　合同总的＿＿％，计＿＿（大写：＿＿）。在按本合同第十章规定“合同工厂”保证期满后，买方在收到卖方提交的下列单据经审核无误后不迟于三十天即支付给卖方：<BR>
        　　（１）商品发票一式六份；<BR>
        　　（２）双方代表按本合同第十章规定签署的合同工厂保证期结束的确认书影印本一份；<BR>
        　　（３）即期汇票一式二份。<BR>
        　　４．２．５　合同总价的＿＿％，计＿＿（大写：＿＿）。买方应自＿＿日起，于＿＿年内，每＿＿个月为一期，平均分期按下列办法支付给卖方。<BR>
        　　４．２．５．１　卖方应开立分＿＿期支付具有下述内容的远期汇票共＿＿份，每份正副本各一份于＿＿时提交买方：<BR>
        　　（１）票面金额为合同总的＿＿％，另加第＿＿章＿＿条规定的延期付款的利息，计（大写：＿＿）；<BR>
        　　（２）以＿＿日期为出票日，到期日分别为自出票日满＿＿个月；<BR>
        　　（３）以买方为付款人；<BR>
        　　（４）带有利息条款。利息按年息＿＿％计算自出票日起算至到期日止。具体金额如下：<BR>
        　　票期　　本金　　利息　　本利合计<BR>
        　　第一期<BR>
        　　第二期<BR>
        　　……<BR>
        　　合计<BR>
        　　４．２．５．２　买方收到汇票后，于＿＿时即对上述分＿＿期支付的汇票正本承兑，并交北京中国银行背书保证后送交卖方。<BR>
        　　４．２．５．３　卖方收到汇票后应在各期汇票到期日前，分别将汇票正本提交北京中国银行，由北京中国银行提请买方于汇票到期日后一天支付给卖方。<BR>
        　　４．３　按本合同第＿＿章和第＿＿章规定，如果卖方应支付赔款或／和罚款等有关款项时，买方在按本章第４．２．２条，４．２．３条４．２．４条规定支付货款时有权从货款中扣除。<BR>
        　　４．４　买方支付本章第４．２．１条所规定的货款时，应向卖方提交中国银行出具的以卖方为受益人金额为合同总价＿＿％和延期付款的利息的保证函（保证函格式详见本合同附件八）。<BR>
        　　４．５　买卖双方因履行本合同所发生的银行费用，在中国发生的，均由买方负担，在中国以外发生的，均由卖方负担。<BR>
        　　（注：方案二各项条款适用于延期付款的情况）<BR>
        　　<BR>
        　　第五章　交货与交货条件<BR>
        　　<BR>
        　　５．１　卖方应于本合同生效日后＿＿个月内分＿＿批将本“合同工厂”的“设备”交付完毕。<BR>
        　　总毛重大约为＿＿公吨。总体积大约为＿＿立方米。<BR>
        　　本合同工厂“设备”的交货港口为＿＿，目的港口为中国＿＿。<BR>
        　　５．２　卖方在合同生效日后＿＿月内，应向买方提交初步交货计划一式六份（包括合同号、项号、设备名称、型号、规格、数量、单价、总价、大约总重量、大约总体积、交货时间、交货港口、危险品的品名以及国际危规号等），并提出超大、超重设备的尺码（长、宽、高和体积）和大约重量，以及危险品、易燃品在运输保管方面的特殊要求和注意事项。不能拆卸的单体设备重量最大限度为三十公吨，体积最大限度为长１２米，宽２．７米，高３米。凡超过此限度的货物卖方应在本合同生效日后＿＿个月内向买方提供草图一式六份，经买方同意后，才能安排制造。买方应于收到上述草图后一个月内用电传或信件确认，否则卖方即开始制造。至迟不超过第一批交货前＿＿个月，卖方应向买方提交最终交货计划一式六份。内容包括合同号、批次、项号、名称、规格、数量、单价、总价、设备材料和危险品的大约毛净重、每件货物的大约尺寸（长、宽、高）体积、交货港、每批货物的交货时间以及超大、超重货物的外形包装草图和危险品运输措施及注意事项的说明。<BR>
        　　５．３　已装船的提单日期为设备的实际交付日期。<BR>
        　　５．４　卖方供应的每批设备，应为本章第５．１条规定的港口在买方指定的受载船只船面交货。<BR>
        　　“设备”的风险，卖方在买方指定的受载船只船面交货后，即由卖方转移给买方。<BR>
        　　５．５　在每批货物备妥待运前不迟于＿＿天，卖方应以电报通知买方如下内容：<BR>
        　　（１）合同号；<BR>
        　　（２）货物备妥待运日；<BR>
        　　（３）货物总体积；<BR>
        　　（４）货物总重量；<BR>
        　　（５）总包装数量；<BR>
        　　（６）装船港口名称；<BR>
        　　（７）重量超过二十公吨，尺寸超过１２×２．７×３米的每件货物的大约总毛重、总体积及名称；<BR>
        　　（８）危险品的品名、重量、国际危规号。<BR>
        　　同时卖方还应航寄给买方下列文件，每件一式六份：<BR>
        　　（１）发运货物的详细清单，包括合同号、序号、“设备”的名称、规格、型号、数量、单价、总价、单重、单件体积和总体积，每件货物的外形尺寸（长×宽×高）总件数和装船港口名称；<BR>
        　　（２）重量超过二十公吨或体积超过１２×２．７×３米的每件大件货物的外形包装草图；<BR>
        　　（３）易燃品和危险品的品名、性质、特殊防护措施及事故处理方法说明书；<BR>
        　　（４）对温度、震动等有特殊要求的货物在运输过程中的特殊注意事项证明书。<BR>
        　　上述文件另一份航寄目的港的中国对外贸易运输公司，作为买方安排运输和装卸工作的依据。<BR>
        　　５．６　所有设备交货应单机成套，安装用的专用工具，材料、易损件应随主机一同交付。如果有需要装在甲板上的“设备”，卖方应负责进行适当的包装及采取特殊保护措施。<BR>
        　　５．７　买方应于受载船只抵达交港口前不迟于十天将船名、预计抵达日期通知卖方。（如买方需要变更船只或改变船期，买方或买方船舶代理人应及时通知卖方。）<BR>
        　　５．８　如卖方未能在买方船只抵达交货港口时将货物备妥装船，买方因此而遭受的空舱费、船舶滞期费和有关费用，均由卖方负担，按轮船公司提出的有关单据，作为结算费用的依据。<BR>
        　　５．９　如卖方在受载船只预计抵达日期已将货物备妥而买方船只不能在预计抵达日期后三十天内抵达交货港口，这三十天内的有关仓储费、保险费等由卖方负担，但第三十一天起以后发生的仓储费、保险费，按卖方提供的原始凭证，由买方核实支付。但卖方仍有责任根据买方通知，在受载船只抵达交货港口后，由卖方负责交货，在此情况下，卖方不支付迟交罚款。<BR>
        　　５．１０　卖方应在每批货物装船后四十八小时内，将提单日期和号码、船名、“设备”名称、总价、总重、总体积、总件数和合同号以电传通知买方。如遇有第５．５条的大件货物及危险品，应逐件列明毛重和尺寸（长、宽、高）、品名、金额。买方因卖方未及时通知而未投保所造成的损失将由卖方负担。<BR>
        　　５．１１　在将货物装到船上后，卖方应于装船后将每批货物的整套交货文件（即、提单、商业发票装箱单和质量证明书各一份）随船在目的港提交给中国外贸运输公司。<BR>
        　　同时航寄买方上述单据副本各两份和检验记录、试验报告以及有关装配安装图纸各三份。<BR>
        　　５．１２　技术资料的内容和交付计划见本合同附件三。<BR>
        　　５．１３　卖方在技术资料发出前一周将大约件数、大约毛重、合同号和资料预计抵达北京和／或＿＿的日期用电传通知买方。在资料寄出后二十四小时内卖方需将发出日期、航次、空运单号、重量及资料件数、合同号以电传的方式通知买方。<BR>
        　　５．１４　“技术资料”到达目的机场的日期为实际交付日期。<BR>
        　　５．１５　卖方提供的“技术资料”应在北京和／或＿＿机场交付，上述资料的风险，在卖方在北京／或＿＿机场交付后即由卖方转移给买方，如果技术资料短少、丢失或损坏时，卖方应在收到买方通知二十一天内在北京和／或＿＿机场补充提供丢失或损坏部分，不再收取任何费用。<BR>
        　　５．１６　在每批“技术批准”交货后的两个工作日内，卖应将下述文件航寄给买方：<BR>
        　　Ａ．空运提单一式二份（通知目的机场的中国对外贸易运输公司，并注明合同号）<BR>
        　　Ｂ．技术文件的详细清单一式二份。<BR>
        　　<BR>
        　　第六章　包装与标记<BR>
        　　<BR>
        　　６．１　卖方交付的所有货物应具有适合远洋的内陆运输和多次搬运、装卸的新的坚固木箱包装。<BR>
        　　并应根据货物的特点和需要，加上防潮、防雨、防锈、防震、防腐蚀的保护措施，以保证货物安全无损地运抵安装地点。<BR>
        　　６．２　卖方对包装箱内和捆内的各散装部件均应系加标签，注明合同号、主机名称、部件名称以及该部件在装配图中的位号、零件号。备件和工具除注明上述内容外，尚需注明“备件”或“工具”字样。<BR>
        　　６．３　卖方应在每件包装箱的邻接四个侧面上，用不褪色的油漆以明显易见的英文字样印刷以下标记：<BR>
        　　（１）合同号；<BR>
        　　（２）唛头标记；<BR>
        　　（３）目的港；<BR>
        　　（４）收货人；<BR>
        　　（５）设备名称及项号；<BR>
        　　（６）箱号／件号；<BR>
        　　（７）毛重／净重（公斤）；<BR>
        　　（８）尺码（长×宽×高，以毫米表示）。<BR>
        　　凡重量为二公吨或超过二公吨的货物，应在包装箱的四个侧面以英文及国际贸易运输常用的标记、图案标明重量及挂绳和重心位置，以便装卸搬运。根据货物的特点和装卸、运输上的不同要求，在包装箱上应以英文明显地印刷“轻放”、“勿倒置”、“防雨”等字样以及相应的国际贸易通用的标记图案。<BR>
        　　６．４　对裸装货物应以金属标签注明上述有关内容。装在甲板上的大件货物，应带有足够的货物支架或包装垫木。<BR>
        　　６．５　每件包装箱内，应附有详细装箱单和质量合格证各一式二份，有关设备的技术文件一式二份。需要组装的设备部件附详细装配图一式二份。<BR>
        　　６．６　卖方交付的技术资料，应具有适合于长途运输、多次搬运、防潮和防雨的包装，每包技术资料的封面上应注明下述内容：<BR>
        　　（１）合同号：<BR>
        　　（２）收货人；<BR>
        　　（３）目的地；<BR>
        　　（４）唛头标记；<BR>
        　　（５）毛重（公斤）；<BR>
        　　（６）箱号／件号。<BR>
        　　每一包资料内应附有技术资料的详细清单一式二份，标明技术资料的序号、代号、名称和页数。<BR>
        　　６．７　凡由于卖方对货物包装不善，保管不良，致使货物遭到损坏或丢失时，卖方均应按本合同第十章的规定负责修理、更换或赔偿。<BR>
        　　<BR>
        　　第七章　设计与设计联络<BR>
        　　<BR>
        　　７．１　为使本“合同工厂”的建设工作顺利进行，买卖双方应按照本合同附件和本章的规定进行设计和设计联络。<BR>
        　　设计联络会议内容、时间、地点和参加人员，详见附件＿＿。<BR>
        　　７．２　卖方承担的设计工作范围详见本合同附件＿＿。卖方提交“技术资料”的要求、内容、份数和交付日期详见本合同附件＿＿。<BR>
        　　７．３　买方承担的设计范围详见本合同附件＿＿。买方向卖方提供的技术资料见本合同附件<BR>
        　　＿＿。卖方依此做为本“合同工厂”设计的依据。<BR>
        　　７．４　本合同生效之日起＿＿个月内卖方应将有关的标准、规范及其清单航空邮寄给买方。买方将对卖方提交的上述标准和规范提出意见，经双方讨论商定后予以更换，并作为卖方进行设计的依据。<BR>
        　　７．５　卖方在初步设计全部资料寄达北京后＿＿个星期内，应自费派遣技术人员来华解释设计，买方应协助办理入境签证和居留手续。在解释设计期间，买方有权提出改进意见，卖方对此应予充分考虑。初步设计经审核后双方签订协议书，该协议书即作为最终设计的依据。<BR>
        　　７．６　买方在收到卖方提供的最终设计全部资料后＿＿天内，应予确认。<BR>
        　　７．７　买方在本“合同工厂”设计过程中，认为有必要时，有权自费派遣技术人员到卖方的有关设计单位和制造厂了解与本“合同工厂”有关的数据和技术资料。卖方应协助办理入境证和居留手续，并免费提供所有与设计有关的技术资料和工作的方便条件。<BR>
        　　７．８　在执行本合同期间，买方提出与“合同工厂”有关设计和技术问题时，卖方应予及时答复，并免费提供有关资料。<BR>
        　　<BR>
        　　第八章　标准与检验<BR>
        　　<BR>
        　　８．１　卖方供应本合同工厂的“设备”的制造、选材、检验和试验，应按卖方国家和／或公司现行标准规范进行。<BR>
        　　本合同生效后＿＿个月内，卖方应将上述公司标准和规范一式六份和国家标准一式二份航寄买方。<BR>
        　　买方可就上述任何公司标准和规范提出意见。经双方讨论商定后予以更换并作为检验和试验的依据。<BR>
        　　８．２　卖方对其供应的全部“设备”应进行检验和试验，并向买方提交由制造厂或卖方出具的质量合格证和检验记录，以此作为本合同规定的质量保证的证明书。“设备”检验和试验的费用均由卖方负担。<BR>
        　　８．３　买方有权自费派遣检验人员到卖方国家会同卖方检验人员一起到制造厂车间对“设备”的制造和质量进行检验和试验。卖方应在设备进行装配和检验前三个月将检验日期通知买方，买方应在收到通知后一个月内将检验人员的名单通知卖方，以便卖方协助办理入境手续。主要设备的装配和检验应有买方人员在场，买方还应有权参加其他“设备”的检验和参加卖方及有关制造厂召开的有关“设备”的质量会议。<BR>
        　　８．４　买方检验人员若发现“设备”有缺陷和／或不符合本合同规定的规格时，有权提出意见，卖方应充分考虑并自费采取必要措施排除缺陷，当缺陷排除后，应再次检验和试验，由此引起的费用均由卖方负担。<BR>
        　　８．５　买方检验人员在卖方国家和制造厂的检验不代替“设备”运抵买方合同工厂现场的开箱检验，亦不能免除卖方按本合同第十章规定的保证责任。买方人员不签署任何证明文件。<BR>
        　　８．６　卖方应免费为买方人员提供方便的工作条件，如必需的技术文件、图纸、检验工具和仪器等。<BR>
        　　８．７　如买方不能在本章规定的期限内派出人员参加上述检验工作时，卖方将自行检验。<BR>
        　　８．８　卖方供应的全部“设备”的开箱检验应在合同工厂现场进行，卖方有权自费派遣他们的检验人员到合同工厂现场参加此项检验，买方应在检验前一个月将开箱日检验期通知卖方，并为卖方检验人员提供工作的方便。<BR>
        　　在双方会同开箱检验中如发现“设备”有短少、缺陷、损坏或包装与本合同规定不符合或质量标准与本合同８．１条和１０．１条规定不符时，应作详细记录，并由双方代表签字。如属卖方责任，此记录即为买方向卖方要求换货、修理或补齐的有效证明。<BR>
        　　８．９　如不属买方原因，卖方检验人员不能参加开箱检验时，买方有权自行开箱检验。如发现本合同７．８条所述问题系属卖方责任时，应委托中国商品检验局出具证明，以此作为买方向卖方要求换货、修理或补齐的有效证明。<BR>
        　　卖方接到买方索赔证书后，应立即无偿换货、补发短缺部分或降低货价，并负担由此产生的到安装现场的换货费用、风险以及买方的检验费用。如卖方对索赔有异议时，应在接到买方索赔证书后两个星期内提出异议，双方另行协商；逾期，索赔即作成立。<BR>
        　　卖方换货和／或补交货物的时间，不迟于卖方收到买方索赔证书后＿＿个月。<BR>
        　　８．１０　在开箱检验中，由于买方的原因，发现“设备”有损坏，通知卖方后，卖方应尽快补发、更换，其费用由买方负担。<BR>
        　　８．１１　上述检验并不能解除卖方对第九章、第十章所承担的责任。<BR>
        　　８．１２　在检验中，如发现卖方提供的检验所需的标准仍不完整或提供的不及时，经与卖方协商，买方有权按照买方国家现行标准进行检验。<BR>
        　　<BR>
        　　第九章　安装、试车和验收<BR>
        　　<BR>
        　　９．１　“安装”系指合同工厂全部设备、材料的装配、就位和联接等安装工作。“试车”系指机器和／或设备的单独或联动的试运转。<BR>
        　　“投料试生产”系指合同工厂投入原料和公用工程以试生产。<BR>
        　　“考核”系指检验本合同附件＿＿所规定的各项保证数值而进行的试验。<BR>
        　　“验收”系指如果考核结果表明，本合同附件＿＿所规定的各项保证指标能够全部达到，则合同工厂即为买方所验收。<BR>
        　　９．２　合同工厂的安装将在买方负责组织下和在卖方负责技术指导下进行，卖方有权对其进行详细设计有关的所有“设备”的安装以及界区接点的安装进行技术指导。<BR>
        　　合同工厂的试车、投料试生产和考核应在买方组织安排下和卖方技术指导下进行。在安装工作开始前二个月，双方各自授权一名代表处理合同工厂从安装到验收期有关合同工厂的全部技术工作。具体工作应由双方代表友好协商安排。双方代表应充分合作，使合同工厂在本合同生效日后＿＿个月内建设完毕。<BR>
        　　如果双方之间有任何问题和分歧时，双方将分析原因，澄清责任并在现场通过友好协商解决。<BR>
        　　９．３　在安装工作开始前，卖方技术人员应详细介绍安装方法和要求。在安装期间卖方人员应对安装工作进行技术指导并参加所有设备安装质量的检验和试验。卖方技术人员的重要技术指导应以书面提出。<BR>
        　　９．４　安装完毕后，如双方代表认为安装工作完全符合设计要求时，双方代表应按技术文件和图纸一起进行检验和试车。双方代表将签订同类设备安装证书及单机试车和机械与轩的系统联运证书。上述证书将以附件＿＿第＿＿条规定的工作日志为基础。<BR>
        　　如试车顺利完成，安装工作完全符合技术文件要求时，双方代表应在七天内在现场签署安装峻工证书。此证书签字日即为合同工厂安装及试车完成日。但此证书不能免除卖方按本合同第十章规定在投料试生产、考核期间和机械保证期内对设备和材料发现的缺陷所应负的责任。<BR>
        　　９．５　本章９．４条规定的试车完成后应尽快开始投料试生产，其开车日期由双方现场代表商定。<BR>
        　　投料试生产和考核所需的仪表校准、记录项目、取样方法和分析方法等详细程序应由卖方在安装及试车完成日前提出，并经双方代表讨论决定。<BR>
        　　在投料试生产前，买方应准备充足的维修工具、实验室及检验设施和熟练的操作、维修及测试人员，其中包括本合同附件＿＿第＿＿条所列的人员，并准备好按本合同附件＿＿所列必要数量和质量的全部原料，有关的公用工程。卖方技术人员可出入试验室和检验设施，以便取样分析。投料试生产和性能试验期间的采样、化验将在双方代表在场的情况下进行。<BR>
        　　除已同意的程序外，卖方技术人员认为必要的采样和化验，在经与买方代表协商后由买方进行。<BR>
        　　从试车到合同工厂验收期间，卖方可以使用买方库存的备品备件，如由于卖方责任卖方使用了买方库存的备品备件，卖方应及时在现场予以偿还。<BR>
        　　９．６　投料试生产期为首次投料生产开始日起的＿＿个月。在此期间，当合同工厂主要设备达到良好稳定运行后由双方代表商定首次考核日期。考核应按本合同附件＿＿的规定在卖方技术人员指导下进行。<BR>
        　　每次考核的结果应作出记录，在每次考核完成后三天内双方在性能考核报告上签字确认。<BR>
        　　９．７　如按本合同的附件＿＿规定的考核期内实现了本合同附件＿＿规定的全部保证数值时，双方代表应在五天内签署合同工厂验收证书一式四份，双方各执两份，此即视作合同工厂为买方所验收。<BR>
        　　如因卖方原因任何一次考核未能成功时，卖方应尽快在双方同意的期限内对“设备”进行必要的修理、更换和／或修改。修改后应重新按照合同附件＿＿的规定尽快再次考核，买方应大力协助。<BR>
        　　如上述修理、更换和／或修改在现场进行，所需费用（如工时费、材料费等）应在合同工厂交接验收前，由双方授权代表根据修理、更换和／或修改的情况商定，并由双方代表会签。<BR>
        　　如因卖方原因，需将任何设备运出中国以外进行修理或更换时，全部运费、修理或更换费用应由卖方负担。更换或修理的“设备”应在合同工厂现场交货。<BR>
        　　９．８　在本合同９．６　条规定的投料生产期间，本合同附件＿＿规定的保证数值如有任何一项或多项未能达到时，双方应会同研究，找出原因，澄清责任，并按以下规定处理：<BR>
        　　９．８．１　如由于卖方原因未能达到保证数值时，买方同意延长投料试生产期三个月，以便卖方对合同工厂进行改进并再次进行考核。如在延长的三个月中由于卖方原因仍未能达到保证数值时，买方同意再次延长投料试生产期三个月。如在再次延长的三个月期满时，由于卖方原因，考核仍失败，未能达到保证数值时，应按本合同第十章第１０．８条的规定办理。在两次延长的两个三个月内改进合同工厂所需全部费用和卖方技术人员的全部费用均由卖方负担。<BR>
        　　９．８．２　如由于买方原因未能达到保证数值时，投料试生产期应延长三个月。在此期间，买方将按本合同附件＿＿的规定继续支付卖方人员的全部费用均由买方负担。所需卖方技术人员的人数由双方讨论决定，如在此延长的三个月期满时，仍由于买方原因未能达到保证数值时，合同工厂应由买方所验收，双方应在七天内签署验收证书。<BR>
        　　然而卖方应协助买方采取一切必要措施，使合同工厂达到正常生产所需的指标。由此而产生的费用由买方负担。<BR>
        　　９．９　如由于买方原因，在按本合同第五章规定的第＿＿批“设备”交货之日起＿＿个月内合同工厂未能进行考核时，则按合同第四章第＿＿条由买方支付给卖款项应予实现，但不免除卖方的所有责任。<BR>
        　　９．１０　如由于买方原因，在按本合同第五章规定的第＿＿批“设备”和材料交货之日起＿＿个月内合同工厂未能进行考核时，合同工厂应为买方所验收，但卖方仍应承担协助买方合同工厂开车和运转的责任。提供服务的期间和条件应通过友好协商后达成协议。如由于卖方原因，上述第＿＿批交货延误，则上面所提的时间应相应顺延。<BR>
        　　９．１１　按本合同的９．７、９．８和９．９条规定的合同工厂的验收，并不能免除卖方对合同工厂的“设备”在机械保证期内应负的责任。<BR>
        　　<BR>
        　　第十章　保证、索赔和罚款<BR>
        　　<BR>
        　　１０．１　卖方保证其供应的本“合同工厂”的技术水平是先进的，“设备”是全新的，质量是优良的。设备和材料的选型均符合工艺、安全运行和操作长期使用的要求，并符合本合同附件＿＿和附件＿＿的规定。<BR>
        　　１０．２　卖方保证所交付的技术资料、图纸清晰、完整和正确并能满足“合同工厂”的设计、安装、运行和维修的要求并符合附件＿＿的规定。<BR>
        　　１０．３　在本“合同工厂”安装、试车期间，如果卖方提供的“设备”有缺陷，或由于卖方技术人员的指导错误或／和卖方提供的技术资料、图纸和证明书的错误造成“设备”的损坏，卖方应立即无偿换货或降低货价作为赔偿，并负担由此产生的到安装现场的换货费用和风险。如卖方对索赔有异议时，应在接到买方索赔证书后两个星期内提出复议，双方另行协商，逾期索赔即作成立。卖方换货期限不迟于证实属实卖方责任之日起＿＿个月。<BR>
        　　１０．４　卖方对本“合同工厂”“设备”的保证期为本“合同工厂”被买方验收后＿＿个月：如由于买方责任而影响本“合同工厂”安装、试车、验收时，则不超过卖方最后一批货物交付日期后＿＿个月。保证期满后，由买方出具本“合同工厂”保证期满证明书正、副本各一份给卖方。<BR>
        　　１０．５　在保证期内，如发现卖方提供的“设备”有缺陷或／和不符合合同规定时，如属卖方责任，则买方有权凭中国商品检验局出具的检验证书向卖方提出索赔。卖方接到买方索赔证书后，应立即无偿换货或降价货价，并负担由此产生的到安装现场的换货费用和风险（货物到达目的港后的风险由买方负责）。如卖方对索赔有异议时，应在接到买方索赔证书后二个星期内提出复议，双方另行协商，逾期索赔即作为成立。卖方换货的期限，应不迟于卖方收到买方索赔证书后＿＿个月。<BR>
        　　如属微小缺陷，可由买方自行消除，但由此引起的费用由卖方负担。<BR>
        　　１０．６　在保证期内，如由于卖方责任需要更换、修理有缺陷的“设备”而使本“合同工厂”停机时，则保证期应按实际停机时间作相应的延长。新更换和补充修复的“设备”的保证期为被买方验收后十二个月。<BR>
        　　１０．７　在保证期满后三十天内，买方出具有在保证期内发现的“设备”缺陷的索赔证书仍然有效。<BR>
        　　１０．８　如由于卖方责任，在考核试车不能达到本合同附件＿＿规定的一项或多项技术经济指标时，卖方必须采取有效措施在卖方收到买方书面通知后＿＿个月内使之达到各项保证指标并承担由此产生的一切费用。逾期如仍不能达到本合同附件＿＿所规定的保证指标时，卖方应承担罚款，其计算办法如下：<BR>
        　　卖方支付罚款，则本“合同”即为买方所验收，并由买方出具本“合同工厂”验收证书正、副本各一份交给卖方。<BR>
        　　１０．９　如由于卖方责任未能按合同第四章规定的交货期交货时，买方有权按下列比例向卖方收取罚款：<BR>
        　　迟交１至４周，每周罚迟交货物金额的＿＿％；<BR>
        　　迟交５至８周，每周罚迟交货物金额的＿＿％；<BR>
        　　迟交９周及以上，每周罚迟交货物金额的＿＿％；<BR>
        　　不满一周按一周计算。<BR>
        　　迟交货物的罚款总金额不超过合同总价的＿＿％<BR>
        　　卖方支付迟交罚款，并不解除卖方继续交货的义务。<BR>
        　　任何一批货物迟交超过＿＿个月时，买方有权终止部分或全部合同。<BR>
        　　<BR>
        　　第十一章　侵权和保密<BR>
        　　<BR>
        　　１１．１　卖方同意向买方转让非独占的，不可转让的权利，并允许买方在中华人民共和国内使用卖方的＿＿工艺进行“合同工厂”的工程设计、建设和操作，以设计、制造、销售和出口合同产品＿＿。其年产量为＿＿，其工艺说明见本合同附件＿＿，其品种规格详见本合同附件＿＿。<BR>
        　　卖方提供买方用于本合同工厂的专有技术和专利如下：<BR>
        　　专有技术登记号：<BR>
        　　专利登记号：<BR>
        　　专有技术、研究报告、资料等包括在本合同附件＿＿里。<BR>
        　　１１．２　在本合同生效后三十天内，卖方应向买方提供卖方国家有关当局签发的包括本合同第１１．１条所述的工艺的专利登记证书的影印本二份。<BR>
        　　１１．３　如果任何第三方对买方使用本合同第１１．１条所规定的专利和专有技术提出任何异议时，卖方应负责处理，买方对此无任何责任。<BR>
        　　１１．４　本合同生效后＿＿年内，如卖方对本合同第１１．１条所规定的专利和专有技术有所发明和改进时，不管其发明和改进是否已获得专利权，卖方均应向买方免费提供详细资料。买方有权将上述资料用于合同工厂。如有必要，关于技术指导的一切费用由买方根据双方同意的本合同第＿＿章和附件规定的条件负担。<BR>
        　　１１．５　本合同生效后＿＿年内，买方应对本合同１１．１条所规定的专有技术对任何第三方予以保密，对参加本合同工厂的计划、安装和施工等工作的其他单位除外，然而他们必须承担同样的保密义务。在保密年限内，若专有的一项或多项，被第三者公开后，买方不再承担保密义务。<BR>
        　　１１．６　卖方对买方所提供的设计基础和现场条件资料的保密期限不受上述时间的限制。<BR>
        　　<BR>
        　　第十二章　不可抗力<BR>
        　　<BR>
        　　１２．１　签约双方中的任何一方，由于战争及严重的火灾、水灾、台风、地震事件和其他双方同意的不可抗力事故而影响合同执行时，则延迟履行合同的期限，延迟的时间应相当于事故所影响的时间。<BR>
        　　１２．２　受事故影响一方应尽快将所发生的不可抗力事故的情况以电传或传真通知另一方，并在十四天内以航空挂号信件将有关当局出具的证明文件提交给另一方审阅确认。<BR>
        　　１２．３　当不可抗力事故终止或事故消除后，受事故影响的一方应尽快以电传或电报通知对方，并以航空挂号信证实。<BR>
        　　<BR>
        　　第十三章　税费<BR>
        　　<BR>
        　　１３．１　中国政府根据其现行税法对“买方”课征有关执行本合同的一切税费，由“买方”支付。<BR>
        　　１３．２　中国政府根据其现行税法对“卖方”课征有关执行本合同的一切税费，由卖方支付。<BR>
        　　１３．３　在中国境外课征有关和执行本合同所发生的一切税费，将由“卖方”支付。<BR>
        　　<BR>
        　　第十四章　仲裁（方案一）<BR>
        　　<BR>
        　　１４．１　因执行本合同所发生的或与本合同有关的一切争议，双方应通过友好协商解决。如协商仍不能达成协议时，则应提交仲裁解决。<BR>
        　　１４．２　仲裁地点在北京，由中国国际经济贸易仲裁委员会按该会仲裁规则进行仲裁。<BR>
        　　１４．３　仲裁裁决是终局裁决，对双方均有约束力。<BR>
        　　１４．４　仲裁费用由败诉方负担。<BR>
        　　１４．５　在仲裁期间，除了在仲裁过程中进行仲裁的部分外，合同的其他部分应继续执行。<BR>
        　　<BR>
        　　第十四章　仲裁（方案二）<BR>
        　　<BR>
        　　１４．１　因执行本合同所发生的或与本合同有关的一切争执，买卖双方应通过友好协商解决，如经协商仍不能达成协议，则应提交仲裁解决。<BR>
        　　１４．２　仲裁地点在被告所在国进行，如买方是被告，则在中国由中国国际经济贸易仲裁委员会根据该委员会的仲裁规则进行；如卖方是被告，则在＿＿＿＿＿＿，　　　　由＿＿＿＿＿＿根据该组织的仲裁程序进行。<BR>
        　　１４．３　仲裁裁决对双方均有约束力，双方均应履行。<BR>
        　　＿＿＿＿＿＿，由＿＿＿＿＿＿根据该组织的仲裁程序进行。<BR>
        　　１４．３　仲裁裁决对双方均有约束力，双方均应履行。<BR>
        　　１４．４　仲裁费用由败诉一方负担。<BR>
        　　１４．５　除了在仲裁过程中进行仲裁的那些部分外，在仲裁期间，合同其余部分应继续执行。<BR>
        　　（注：方案二的各项条款适用于仲裁在被告国进行的情况。）<BR>
        　　<BR>
        　　第十五章　合同生效、终止及其他<BR>
        　　<BR>
        　　１５．１　本合同由双方代表于＿＿签字。由各方分别向本国政府当局申请批请，以最后一方的批准日期为本合同生效日期。双方应尽最大努力在六十天内获得批准，用电报或电传通知对方，并用信件确认。<BR>
        　　本合同自签字之日起六个月仍不能生效，双方有权取消本合同。<BR>
        　　１５．２　本合同有效期从合同生效日算起共＿＿年，有效期满后本合同自动失效。<BR>
        　　１５．３　本合同期满时，双方的未了债权和债务，不受合同期满的影响，债务人应对债权人继续偿付未了债务。<BR>
        　　１５．４　本合同用英文写成一式四份，双方各执一式两份。<BR>
        　　１５．５　本合同附件＿＿至附件＿＿，为本合同不可分割的组成部分，与合同正文具有同等效力。<BR>
        　　１５．６　对本合同条款的任何变更、修改或增减，须经双方协商同意后授权代表签署书面文件，作为本合同的组成部分并具有同等效力。<BR>
        　　１５．７　在本合同有效期内，双方通讯以英文进行。正式通知应以书面形式，用挂号信邮寄，一式两份。<BR>
        　　１５．８　双方任何一方未能取得另一方事先同意前，不得将本合同项下的任何权利或义务转让给第三者。<BR>
        　　１５．９　除本合同规定的义务和责任外，双方中的任何一方都不承担任何其他义务和责任。<BR>
        　　<BR>
        　　第十六章　法定地址<BR>
        　　<BR>
        　　买　　方：中国技术进出口总公司<BR>
        　　地　　址：中国北京西郊二里沟<BR>
        　　电报挂号：ＴＥＣＨＩＭＰＯＲＴ　ＢＥＩＪＩＮＧ<BR>
        　　电传号：２２２４４　ＣＮＴＩＣ　ＣＮ<BR>
        　　传真号：<BR>
        　　卖　　方：<BR>
        　　地　　址：<BR>
        　　电报挂号：<BR>
        　　电传号：<BR>
        　　传真号：<BR>
        　　买方：＿＿＿＿<BR>
        　　（签字）<BR>
        　　卖方：＿＿＿＿<BR>
        　　（签字）<BR>
        　　<BR>
        　　<BR>
        　　附件五<BR>
        　　卖方技术人员的服务范围和待遇条件<BR>
        　　<BR>
        　　１．卖方技术人员的派遣<BR>
        　　为了使合同现场的建设顺利进行，卖方应向买方派遣技术熟练的、身体健康的、称职的技术人员到合同现场进行技术服务。卖方技术人员的专业、职务、人数、在华工作期限详见附表一。<BR>
        　　上述卖方技术人员的确切专业、人数、在华工作期限、到达和离开本合同工厂的日期，将根据本合同现场的建设任务的实际进展情况，由买卖双方协商确定。需要调整附表一规定的人／月数时，双方届时另议。<BR>
        　　２．卖方技术人员的职责和义务<BR>
        　　（１）卖方应从所派遣的技术人员中指定一名为卖方在合同现场的总代表，负责本合同范围内总的技术服务，并与买方合同现场总代表合作协商，解决有关工作和技术问题，但双方总代表未经双方授权无权修改合同。<BR>
        　　（２）代表卖方执行本合同规定范围内的施工、安装、调试、投料试车（包括功能试验和考核试验）、生产操作、生产工艺和维修方面的技术服务并应执行合同规定的卖方应承担的职责和义务。<BR>
        　　（３）详细讲解技术资料、图纸、工艺流程、设备性能、分析方法以及有关注意事项等，解答并解决买方提出的有关本合同范围内的技术问题。<BR>
        　　（４）为确保上述二２条和二３条的正确进行，卖方技术人员应在本合同服务范围内予以全面的、正确的技术服务和必要的示范操作。<BR>
        　　（５）协助买方在本合同现场培训安装、调试、生产、设备维修和分析检验人员，提高他们的技术水平。<BR>
        　　（６）卖方人员的技术指导应正确无误。如因指导错误所造成“设备”的损失，应由卖方负责更换、修理或补齐，费用均由卖方负担。对于卖方技术人员的技术指导，买方有关人员应予以尊重。<BR>
        　　３．卖方技术人员的技术服务费和支付办法<BR>
        　　（１）买方应支付给卖方技术人员如下技术服务费（按每人每天计算）：<BR>
        　　每人每天　　美元<BR>
        　　总代理（或主任工程师）<BR>
        　　工程师<BR>
        　　技术员<BR>
        　　操作工<BR>
        　　（２）卖方技术人员的技术服务费（每天）自抵达合同工厂现场之日起计算至离开合同工厂现场之日止。<BR>
        　　（３）星期天，中华人民共和国的法定节日为卖方技术人员在合同现场的有薪休息日。<BR>
        　　（４）对于卖方技术人员未经双方现场总代表同意的缺勤以及经双方总代表同意的事假，买方不支付其技术服务费，但经医生证明的病假除外。如果病假连续超过１５天，买方不再支付其技术服务费。<BR>
        　　（５）卖方技术人员的技术服务费按月结算，买方收到卖方每月开出的帐单一式四份和双方总代表签署的卖方技术人员记时卡，经审核无误后，于３０天内通过中国银行将卖方技术人员的月技术服务费汇到卖方指定的银行帐户上。如卖方开出的帐单有误，买方有权拒付有误的部分，但按时支付无误部分。<BR>
        　　４．工作联系工作制度<BR>
        　　（１）卖方在技术人员来华前两个月应将其姓名、性别、出生年月日、国籍、专业、职务、经历、工作单位、懂何种外语等通知买方，以便买方协助办理签证手续。<BR>
        　　卖方技术人员来华前七天，应用电报或电传将其姓名、解切启程日期、班机号、抵达日期、行李件数和重量等通知买方。<BR>
        　　（２）卖方技术人员到达本合同工厂后，由双方总代表协商确定卖方技术人员的工作安排和月度的工作计划。卖方技术人员应按双方商定的工作计划在买方的统一安排下进行工作。工作计划需要修改时，应经双方总代表协商确定。<BR>
        　　（３）卖方技术人员每周工作四十八小时，（每周工作六天，每天工作八小时）作息时间应按合同现场的规定进行。<BR>
        　　卖方技术人员如需加班时，需事前经双方总代表协商同意。正常工作日八小时以外的额外工作及星期天、节假日的工作都算加班。但卖方技术人员在投料试车、考核期间在每周四十八小时内的倒班不算加班。加班后应尽量在短期内安排倒休，每加班六小时倒休一天。如不能倒休的，可按每加班一小时考勤１．５小时，每小时按日技术服务费的八分之一支付加班费。加班费按本附件三５条规定支付。<BR>
        　　（４）卖方技术人员在华工作期限，自抵达合同现场之日起至离开合同现场之日止计算。<BR>
        　　（５）卖方技术人员每天工作的实际时数，有薪节假日和加班时数，按日记载在“记时卡”中，“记时卡”一式二份，由双方总代表签字，作为支付卖方技术人员日技术服务费和加班费的依据。<BR>
        　　（６）工作进度、每天完成的主要工作、发生的问题或责任事故以及解决的办法应用中、英文记载在“工作日志”中，每天由双方总代表签字一式两份，买卖双方各执一份。<BR>
        　　５．卖方技术人员的休假<BR>
        　　（１）卖方技术人员在华连续工作六个月以上者，可携带夫人和两名十五岁以下的子女来华，其费用均由卖方负担。<BR>
        　　（２）卖方技术人员在华连续工作十二个月并带家属者，每九个月可以回国无薪休假１５天。<BR>
        　　卖方技术人员在华连续工作六个月不带家属者，每六个月可以回国无薪休假１５天。<BR>
        　　上述卖方技术人员的休假所产生一切费用均由卖方负担。休假时间以不影响现场工作为前提，由双方总代表协商确定。１５天假期自卖方技术人员离开合同工厂之日起至回到合同工厂现场之日止。<BR>
        　　卖方同意在卖方人员休假期间不减轻其对合同工厂承担的义务。<BR>
        　　６．卖方技术人员及其家属在中国居留期间，应遵守中华人民共和国的法令以及合同工厂的规章制度。<BR>
        　　７．卖方技术人员应根据“中华人民共和国个人所得税法”交纳个人所得税。<BR>
        　　８．买方的责任和义务。<BR>
        　　（１）买方应为卖方技术人员在现场配备为执行合同工厂的任务所需要的一定数量的翻译人员。<BR>
        　　（２）买方应协助卖方技术人员及其家属办理入出中国国境和在中国居留期间的手续，费用由卖方自理。<BR>
        　　（３）买方应为卖方技术人员及其家属在中国居留期间的安全采取必要措施。<BR>
        　　（４）买方为卖方技术人员免费提供办公室和必要的工作用具及劳动保护用品和提供从住宿地点到本合同现场的上、下班交通工具。<BR>
        　　（５）买方为卖方技术人员免费提供在中国的医疗（镶牙、配眼镜、滋补营养品的费用除外）。<BR>
        　　（６）买方为卖方技术人员在本合同现场免费提供一间住房，室内设有必要的家具和卫生设备。按本附件五项第１和第２条规定，带有家属者，适当增加住房。<BR>
        　　（７）买方为卖方技术人员及其家属安排有中、西餐的膳食，但费用均由卖方技术人员自理。<BR>
        　　（８）买方为卖方技术人员及其家属安排洗衣和出租汽车服务，费用由卖方技术人员自理。<BR>
        　　（９）根据中国海关规定，买方协助卖方技术人员办理适当的个人或集体生活用品和其现场所需的技术资料、工具和仪器的入、出中国国境的手续，一切费用均由卖方负担。<BR>
        　　但上述物品在运进或运出前，卖方须将品名、数量、重量、提单号、金额、规格及进出口日期预先通知买方。<BR>
        　　９．其他<BR>
        　　（１）在不影响现场工作的前提下，经买方同意，卖方可以自费召回或更换卖方技术人员。在卖方技术人员在现场交接工作期间，买方只负担一人的技术服务费。<BR>
        　　（２）卖方技术人员连续生病１５天不能上班时，卖方应自费派遣同等技术水平的技术人员前来接替他的工作。<BR>
        　　（３）如遇重大原因，买方有权要求卖方更换任何一个卖方的技术人员，其更换费用由卖方负担。<BR>
        　　<BR>
        　　附件六<BR>
        　　买方技术人员的培训范围和待遇条件<BR>
        　　<BR>
        　　１．卖方同意接受买方＿＿名实习生包括翻译到＿＿国卖方工厂进行技术培训共＿＿天包括往返路程。<BR>
        　　２．卖方选派技术熟练的、称职的技术人员对买方技术人员进行技术指导和培训并解释本合同范围内的一切技术问题。<BR>
        　　３．卖方应保证买方技术人员在上述工厂各个不同岗位上进行操作、培训，使他们懂得和掌握设备的工艺、操作、检验、修理和维修等技术。<BR>
        　　４．培训期间，卖方应向买方技术人员免费提供试验仪器、工具、技术资料、图纸、参考资料、工作服、防护用具、其他必需品和合适的办公室。<BR>
        　　５．卖方应于培训前三个月向买方提出初步培训计划供买方研究，买方应于培训前一个月通知卖方被培训人员的姓名、性别、出生年月日、籍贯、职务、专业。最终培训计划应在买方技术人员到达卖方国家后按照合同上述规定和买方技术人员的实际需要，双方通过协商决定。<BR>
        　　６．培训开始前，卖方应向买方人员详细讲解操作规则和工作注意事项。<BR>
        　　７．卖方应向买方技术人员提供住房、膳食、交通的便利，费用由买方负担。如果发生疾病和工伤事故，卖方应采取一切必要的措施给予买方人员以尽可能好的照顾，费用由买方自理。但如果事故是由卖方引起的，其费用则由卖方负担。<BR>
        　　８．卖方应协助买方人员办理卖方国家的入出签证和居留期间的一切手续。<BR>
        　　９．卖方不向买方收取培训费用。<BR>
        　　１０．卖方应采取必要措施，保证买方人员在卖方国家居留期间的安全。<BR>
        　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>