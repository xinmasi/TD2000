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
      <div align="center">建筑安装工程投标书（标函）</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　__________（建设单位或招标办公室）　　　　　　　　　　　　　　　　　　　　<BR> 
　　在研究了__________建筑安装工程的招标条件和勘察，设计，施工图纸，以及参观了建筑安装工地以后，经我们认真研究核算，愿意承担上述全部工程的施工任务．我们的投标书（标函）内容如下：　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 <BR> 
　　______________________________________________________________________　　<BR> 
　　｜　｜工程名称　｜　　　　　　　　　　　　　｜建筑地点　｜　　　　　 ｜<BR> 
　　｜标｜__________｜__________________________｜__________｜___________｜<BR> 
　　｜　｜建筑面积　｜　　　　　　　　　　　　　｜建筑层数　｜　　　　　 ｜<BR> 
　　｜函｜__________｜__________________________｜__________｜___________｜<BR> 
　　｜　｜结构形式　｜　　　　　　　　　　　　　｜设计单位　｜　　　　　 ｜<BR> 
　　｜内｜__________｜__________________________｜__________｜___________｜<BR> 
　　｜　｜工程内容　｜　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜容｜__________｜___________________________________________________｜<BR> 
　　｜　｜包干形式　｜　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜__｜_______________________________________________________________｜<BR> 
　　｜　｜总　造　价　　　　｜　　　　　　　　　　｜每平方米造价｜　　　 ｜<BR> 
　　｜标｜__________________｜____________________｜____________｜_______｜<BR> 
　　｜　｜　｜直接费　　　　｜　　　　　　　　　　｜　｜直接费　｜　　　 ｜<BR> 
　　｜　｜其｜______________｜____________________｜其｜________｜_______｜<BR> 
　　｜价｜　｜间接费　　　　｜　　　　　　　　　　｜　｜间接费　｜　　　 ｜<BR> 
　　｜　｜　｜______________｜____________________｜　｜________｜_______｜<BR> 
　　｜　｜中｜材料差价　　　｜　　　　　　　　　　｜中｜材料差价｜　　　 ｜<BR> 
　　｜　｜　｜______________｜____________________｜　｜________｜_______｜<BR> 
　　｜　｜　｜其　　他　　　｜　　　　　　　　　　｜　｜其　他　｜　　　 ｜<BR> 
　　｜__｜__｜______________｜____________________｜__｜________｜_______｜<BR> 
　　｜　｜开工日期｜　　　　　　　　｜竣工日期　　　　｜合计天数　　　　 ｜<BR> 
　　｜工｜________｜________________｜________________｜_________________｜<BR> 
　　｜期｜形象进度｜　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜__｜________｜_____________________________________________________｜<BR> 
　　｜质｜达等｜　　　　　　　　　　　｜保量措｜　　　　　　　　　　　　 ｜<BR> 
　　｜量｜到级｜　　　　　　　　　　　｜证主施｜　　　　　　　　　　　　 ｜<BR> 
　　｜　｜　　｜　　　　　　　　　　　｜质要　｜　　　　　　　　　　　　 ｜<BR> 
　　｜__｜____｜______________________｜______｜_________________________｜<BR> 
　　｜施及工｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜工选机｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜方用械｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜法施　｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜______｜___________________________________________________________｜　　<BR> 
　　我们的企业概况如下<BR> 
　　______________________________________________________________________<BR> 
　　｜企业名称｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜________｜_________________________________________________________｜<BR> 
　　｜地　　址｜　　　　　　　　　　　　　　　　｜所有制类别｜　　　　　 ｜<BR> 
　　｜________｜________________________________｜__________｜___________｜<BR> 
　　｜审定企业施工级别｜　　　　　　　　　　　　｜平均人数　｜　　　　　 ｜<BR> 
　　｜________________｜________________________｜__________｜___________｜<BR> 
　　｜　　｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜企包｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜　括｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜业成｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜　立｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜简年｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜　限｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜历　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜____｜_____________________________________________________________｜<BR> 
　　｜　　｜工程师｜助工｜技术员｜五级｜平均｜　　｜　　｜　　　　　　　 ｜<BR> 
　　｜技　｜以　上｜　　｜　　　｜以上｜技术｜　　｜　　｜　　　　　　　 ｜<BR> 
　　｜术　｜人　数｜人数｜人　数｜人数｜等级｜　　｜　　｜　　　　　　　 ｜<BR> 
　　｜力　｜______｜____｜______｜____｜____｜____｜____________________ ｜<BR> 
　　｜量　｜　　　｜　　｜　　　｜　　｜　　｜　　｜　　｜　　　　　　　 ｜　<BR> 
　　｜____｜______｜____｜______｜____｜____｜____｜____｜______________ ｜<BR> 
　　｜施装｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜工备｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜机情｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜械况｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜____｜_____________________________________________________________｜<BR> 
　　｜　　｜　批准机关｜　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜营　｜__________｜_________________________________________________｜<BR> 
　　｜业　｜执照，号码｜　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜执　｜__________｜_________________________________________________｜<BR> 
　　｜照　｜　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　 ｜<BR> 
　　｜____｜_____________________________________________________________｜<BR> 
　　我们特此同意，在本投标书发出后的______天之内，我们都将受本投标书的约束，我们愿在这一期<BR> 
　　间（即从一九____年____月____日起至一九____年____月____日止）的任何时候接受贵单位的中标通<BR> 
　　知．一但我们的投标被接纳，我们将与贵单位共同协商，按招标书所列条款的内容正式签署<BR> 
　　__________________建筑安装工程施工合同，并切实按照合同的要求进行施工，保证按质，按量，按时<BR> 
　　完工．　　　　　　　　　　　　　　　　　　<BR> 
　　我们承诺，本投标书（标函）一经寄出，不得以任何理由更改，中标后不得拒绝签订施工合同和施工；一但本投标书中标，在签订正式合同之前，本投标书连同贵单位的中标通知，将构成我们与贵单位之间有法律约束力的协议文件．　　　　　　　　　　　　<BR> 
　　（如果招标书要求投标方提供银行或上级部门担保的，投标方应在投标书&lt;标函&gt;中附上一份银行或上级部门的履约保证书．）　　　　　　　　　　　　　　　　　　　 <BR> 
　　投标书发出日期：________年____月____日________时　　　　　　　　　　<BR> 
　　投标单位：_____________（公章）　　　　　　　　　　　　　　　　　　　 <BR> 
　　企业负责人：____________（盖章）　　　　　　　　　　　　　　　　　　　　　<BR> 
　　联系人：__________（盖章）　　　　　　　　　　　　　　　　　　　　　　　　<BR> 
　　电话：_______________　　　　　　　　　　　　　　　　　　　　　　　　 <BR> 
　　地址：_______________<BR> 
　　<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>