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
      <div align="center">委托技术开发合同</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR> 
　　委托方：＿＿＿＿＿＿，以下简称甲方；<BR> 
　　法定代表人或负责人：＿＿＿＿＿＿＿＿；<BR> 
　　研究开发方：＿＿＿＿＿＿＿，以下简称乙方；<BR> 
　　法定代表人或负责人：＿＿＿＿＿＿＿＿＿＿＿。<BR> 
　　<BR> 
　　依据《中华人民共和国合同法》的有关规定，经双方当事人协商一致，签订本合同。<BR> 
　　１．项目名称：＿＿＿＿＿＿＿＿。<BR> 
　　（注：本参考格式适用于新技术、新产品、新材料、新工艺及其系统的研究开发活动。）<BR> 
　　２．本技术开发项目在国内外的现状、水平及发展趋势：＿＿＿＿＿＿＿。<BR> 
　　３．本研究开发成果应达到的技术水平＿＿＿＿＿＿＿＿＿。<BR> 
　　４．甲方的主要义务：<BR> 
　　（１）向乙方支付约定的项目投资（包括研究开发经费和报告。合同约定研究开发经费的一定比例作为科研补贴的，可以不单列报酬）<BR> 
　　项目投资总额为＿＿＿＿＿＿＿＿＿＿。<BR> 
　　其中：设备费＿＿＿＿＿＿；材料费＿＿＿＿＿＿＿＿；<BR> 
　　能源费＿＿＿＿＿＿＿＿；试验费＿＿＿＿＿＿＿＿；<BR> 
　　试制费＿＿＿＿＿＿＿＿；安装费＿＿＿＿＿＿＿＿；<BR> 
　　调式费＿＿＿＿＿＿＿＿；文件编制费＿＿＿＿＿＿；<BR> 
　　（２）按照如下方式分期支付上述项目投资：＿＿＿＿＿＿＿＿。<BR> 
　　（注：当事人通常可以选择下列支付方式：①实报实销的方式；②一次总算，分期支付，包干使用的方式；③“研究开发经费”＋提成费的方式）。<BR> 
　　（３）在合同生效后＿＿＿＿＿日内向乙方提供下列技术背景资料和原始数据：<BR> 
　　（４）甲方应向乙方提供如下的协助事项＿＿＿＿＿＿＿。<BR> 
　　（５）甲方应当及时进行如下事项接受研究开发成果＿＿＿＿＿。<BR> 
　　（注：如果合同中有专门的约定，委托方还有义务向研究开发方提供下列协助；提供研究开发样品、模具、根据应用目的和工艺可能提出明确的技术经济指标，对样品进行加工、测试；对工艺装备的安装、调试和维修，以及组织成果技术鉴定等等。）<BR> 
　　５．乙方的主要义务：<BR> 
　　（１）认真制定和实施研究开发计划。<BR> 
　　本研究开发项目的计划和速度（分阶段解决的主要技术问题、达到的目标和完成的时间）如下：＿＿＿＿＿＿。<BR> 
　　本研究开发项目所采用的主研究、试验方法和技术路线（包括工艺流程）如下：＿＿＿＿＿＿＿。<BR> 
　　（２）合理使用研究开发经费。乙方对研究开发经费的使用，应专款专用，不得挪作他用。<BR> 
　　（３）＿＿＿＿年＿＿月＿＿日前在＿＿＿＿＿地向甲方交付约定的研究开发成果（注：当事人可以约定采取下列一种或者几种方式提交研究开发成果）；<BR> 
　　①产品设计、工艺规程、材料配方和其他图纸、论文、报告等技术文件；②磁带、磁盘、计算机软件；③动物、植物新品种、微生物菌种；④成套技术设施。<BR> 
　　（４）提供下列必要的技术指导和技术服务工作＿＿＿＿＿＿＿。<BR> 
　　注：如果合同中有专门的约定，研究开发方还有义务向委托方提供下列协作事项：提供技术咨询服务（如市场预测、价值工程、可行性论证等）；对委托方人员进行技术培训；提供有关新的技术发展状况的情报资料；协助制定有关操作、工艺规程；提出技术开发总结报告或组织成果技术鉴定；此外，在不妨碍自己研究开发的正常工作的情况下，有义务接受委托方对自己履行合同和经费使用情况的检查。<BR> 
　　当事人双方除应履行上述各自主要义务外，还可以约定在合同的订立和履行过程中承担相互不断地通报合同履行情况的义务。尤其是那些对合同的订立或履行有妨碍的情况，如遇到情报交流上的障碍、技术风险以及研究开发经费超支或盈余等等）。<BR> 
　　６．甲方的违约责任：<BR> 
　　（１）甲方迟延支付研究开发经费，造成研究开发工作停滞、延误的，乙方不承担责任。甲方应当支付数额为投资总额＿＿＿％的违约金。逾期一定期限不支付研究开发经费或者报酬的，乙方有权解除合同，甲方应当返还技术资料或者有关技术成果补交应付的报酬，支付数额为项目投资总额＿＿＿＿％的违约金。<BR> 
　　（２）甲方未按照合同约定提供技术资料、原始数据和协作事项或者所提供的技术资料、原始数据和协作事项有重大缺陷，导致研究开发工作停滞、延迟、失败的，甲方应当承担责任，但乙方发现甲方所提供的资料和数据有明显错误而没有通知甲方复核更正和补充的，应当承担相应的责任。甲方逾期二个月不提供技术资料、原始数据和协作事项的，乙方有权解除合同，甲方应当支付数额为项目投资总额＿＿＿％的违约金。<BR> 
　　（３）甲方逾期二个月不接受工作成果的，乙方有权向合同外第三方转让或变卖工作成果。<BR> 
　　７．乙方的违约责任：<BR> 
　　（１）乙方未按计划实施研究开发工作的，甲方有权要求其实施研究开发计划并采取补救措施。乙方逾期二个月不实施研究开发计划的，甲方有权解除合同。乙方应当支付数额为项目投资总额＿＿＿％的违约金。<BR> 
　　（２）甲方将研究开发经费用于履行合同以外的目的，甲方有权制止并要求其退还相应的经费用于研究开发工作。因此造成研究开发工作停滞、延误或者失败的，乙方应当支付数额为项目投资总额＿＿＿＿％的违约金并赔偿损失经甲方催告后，逾期二个月未退还经费用于研究开发工作的，甲方有权解除合同。乙方应当支付违约金或者赔偿因此给委托方所造成的损失。<BR> 
　　（３）研究开发成果部分或者全部不附合合同约定条件的，乙方应当返还部分或者全部研究开发经费，支付数额为项目投资总额＿＿＿％的违约金。<BR> 
　　８．研究开发成果的归属和分享：<BR> 
　　履行本合同所完成的研究开发成果的专利权归＿＿＿＿＿方所有。<BR> 
　　注（１）：取得专利权的一方通常应允许另一方免费实施该项专利并可以优先受让该专利权。）<BR> 
　　注（２）：如果双方当事人没有就该研究开发成果申请专利的意图，双方可以对该非专利技术成果的使用权和转让权作出约定；如果合同没有约定，视双方都有使用和转让的权利，但是，根据法律规定，研究开发方在将技术成果交付委托方之前，不得向第三方转让。）<BR> 
　　注（３）：如果当事人依据互利有偿的原则，运用工业产权规范中常见的“普通许可、排它许可、独占许可”等方法，就可以比较圆满地体现出“权限与投资”之间的关系。即：<BR> 
　　①委托方向研究开发方支付了部分研究开发经费和报酬的，可对技术成果（包括专利技术和非专利技术，下同）享有免费普通实施权；研究开发方自己保留使用权和向第三方转让的权利。<BR> 
　　②委托方向研究开发方支付了全部的研究开发经费和报酬的，可对技术成果享有优先实施权；研究开发方在约定的期限或范围内，自己可保留使用权，但不得向第三方转让该成果。<BR> 
　　③委托方除了向研究开发方支付了全部的研究开发经费和报酬外，还支付了约定的“独占费用”的，则可在合同规定范围内对研究开发成果享有安全的使用权和转让权（独占权）；研究开发方自己不得使用亦不得向第三方转让该技术成果。<BR> 
　　注（４）：委托方如果有意获得该技术成果完整的专利申请权或专利权，也可以根据协商一致、平等有偿原则与研究开发方另外订立专利申请权或专利转让合同。<BR> 
　　９．保密条款：<BR> 
　　本合同有效期内，双方当事人应对下列技术资料承担保密义务＿＿＿＿本合同期满后＿＿＿年内，双方当事人应对下列技术资料承担保密义务＿＿＿。<BR> 
　　１０．技术风险的承担：<BR> 
　　在履行本合同中，因出现无法克服的技术困难，导致研究开发失败或部分失败的，由此造成的风险损失由＿＿＿＿方负担。<BR> 
　　当事人一方发现前款所列可能导致研究开发失败或部分失败的情形时，应当及时通知另一方并采取措施减少损失。没有及时通知并采取适当措施，致使损失扩大的，应就扩大的损失承担责任。<BR> 
　　１１．验收的标准和方法：＿＿＿＿＿。<BR> 
　　１２．合同争议和解决办法如下：＿＿＿＿＿＿＿＿。<BR> 
　　１３．有关名词和术语的解释：＿＿＿＿＿＿＿＿。<BR> 
　　本合同自双方当事人签字盖章之日起生效。<BR> 
　　<BR> 
　　甲方负责人（或授权代表）　　　　　　　乙方负责人（或授权代表）<BR> 
　　签名：＿＿＿＿＿（盖章）　　　　　　　签名：＿＿＿＿＿＿＿（盖章）<BR> 
　　签字时间：＿＿＿＿＿＿＿　　　　　　　签字时间：＿＿＿＿＿＿＿＿＿<BR> 
　　签字地点：＿＿＿＿＿＿＿　　　　　　　签字地点：＿＿＿＿＿＿＿＿＿<BR> 
　　开户银行：＿＿＿＿＿＿＿　　　　　　　开户银行：＿＿＿＿＿＿<BR> 
　　帐号：＿＿＿＿＿＿＿＿＿　　　　　　　帐号：＿＿＿＿＿＿＿＿＿<BR> 
　　甲方担保人（名称）：＿＿＿　　　　　　甲方担保人（名称）：＿＿＿＿<BR> 
　　地址：＿＿＿＿＿＿＿＿＿＿　　　　　　地址：＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　负责人（或授权代表）　　　　　　　　　负责人（或授权代表）<BR> 
　　签字：＿＿＿＿＿＿＿（盖章）　　　　　签字：＿＿＿＿＿＿＿（盖章）<BR> 
　　签字时间：＿＿＿＿＿＿＿　　　　　　　签字时间：＿＿＿＿＿＿＿＿＿<BR> 
　　签字地点：＿＿＿＿＿＿＿　　　　　　　签字地点：＿＿＿＿＿＿＿＿＿<BR> 
　　开户银行：＿＿＿＿＿＿＿　　　　　　　开户银行：＿＿＿＿＿＿<BR> 
　　<BR> 
　　<BR> 
　　<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>