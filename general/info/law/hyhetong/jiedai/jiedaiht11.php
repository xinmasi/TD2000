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
      <div align="center">补偿贸易借款合同</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR> 
　　立合同单位：<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿（以下简称甲方）<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿（以下简称乙方）<BR> 
　　为明确责任，恪守信用，特订立本合同，共同遵守。<BR> 
　　互协条件：<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　投资时间及金额：<BR> 
　　投资时间共＿＿年零＿＿月。自１９＿＿年＿＿月＿＿日至１９＿＿年＿＿月＿＿日止。投资实际数额以支用凭证（并作为合同附件）分＿＿次或一次支用。投资总额＿＿＿＿万元。其中甲方投资＿＿＿＿万元，乙方投资＿＿＿＿万元。<BR> 
　　供货合同：<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　产品质量：<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　资金利息及偿还：<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　违约责任：<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　附加条件：<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿<BR> 
　　本合同正本一式＿＿份，甲方执＿＿份，乙方执＿＿份，双方代表签字后生效。本合同附件有＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿＿与合同有同等效力。<BR> 
　　本合同修改、补充须经双方协商方有效。<BR> 
　　甲　方：　　（盖章）　　　　　　　　　　　乙　方：　　（盖章）<BR> 
　　地　址：　　　　　　　　　　　　　　　　　地　址：<BR> 
　　法人代表：　　　　　　　　　　　　　　　　法人代表：<BR> 
　　开户银行及帐号：<BR> 
　　<BR> 
　　签约日期：　　　年　　　月　　　日<BR> 
　　签约地点：<BR> 
　　<BR> 
　　<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>