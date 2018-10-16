<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style>

<BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader"> 
      <div align="center"><?=_("民事裁决类")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="mscaijue/mscaijue00.php"><?=_("民事裁定书（先予执行用）")?></a></li>
        <li><a href="mscaijue/mscaijue01.php"><?=_("民事裁定书（按撤诉处理用）")?></a></li>
        <li><a href="mscaijue/mscaijue02.php"><?=_("民事裁定书（准许或不准撤诉用）")?></a></li>
        <li><a href="mscaijue/mscaijue03.php"><?=_("民事裁定书（中止或终结诉讼用）")?></a></li>
        <li><a href="mscaijue/mscaijue04.php"><?=_("民事裁定书（二审发回重审用）")?></a></li>
        <li><a href="mscaijue/mscaijue05.php"><?=_("民事裁定书（对原审驳回起诉的上诉案件用）")?></a></li>
        <li><a href="mscaijue/mscaijue06.php"><?=_("民事裁定书（对原审不予受理的上诉案件用）")?></a></li>
        <li><a href="mscaijue/mscaijue07.php"><?=_("民事裁定书（驳回支付令申请用）")?></a></li>
        <li><a href="mscaijue/mscaijue08.php"><?=_("民事裁定书（对管辖权有异议的上诉案件用）")?></a></li>
        <li><a href="mscaijue/mscaijue09.php"><?=_("民事裁定书（准许或不准撤回上诉用）")?></a></li>
        <li><a href="mscaijue/mscaijue10.php"><?=_("民事裁定书（终结特别程序用）")?></a></li>
        <li><a href="mscaijue/mscaijue11.php"><?=_("民事裁定书（指令下级法院再审或决定提审用）")?></a></li>
        <li><a href="mscaijue/mscaijue12.php"><?=_("民事裁定书（本院决定提起再审用）")?></a></li>
        <li><a href="mscaijue/mscaijue13.php"><?=_("民事裁定书（根据抗诉或申请提起再审用）")?></a></li>
        <li><a href="mscaijue/mscaijue14.php"><?=_("民事裁定书（解除财产保全用）")?></a></li>
        <li><a href="mscaijue/mscaijue15.php"><?=_("民事裁定书（诉讼财产保全用）")?></a></li>
        <li><a href="mscaijue/mscaijue16.php"><?=_("民事裁定用（诉前财产保全用）")?></a></li>
        <li><a href="mscaijue/mscaijue17.php"><?=_("民事裁定书（驳回起诉用）")?></a></li>
        <li><a href="mscaijue/mscaijue18.php"><?=_("民事裁定书（对管辖权提出异议的案件用）")?></a></li>
        <li><a href="mscaijue/mscaijue19.php"><?=_("民事裁定书（不予受理起诉用）")?></a></li>
        <li><a href="mscaijue/mscaijue20.php"><?=_("民事裁定书（终结公示催告程序用）")?></a></li>
        <li><a href="mscaijue/mscaijue21.php"><?=_("民事裁定书（终结督促程序用）")?></a></li>
        <li><a href="mscaijue/mscaijue22.php"><?=_("民事裁定书（不予执行仲裁裁决用）")?></a></li>
        <li><a href="mscaijue/mscaijue23.php"><?=_("民事裁定书（对债务人申请破产还债用）")?></a></li>
        <li><a href="mscaijue/mscaijue24.php"><?=_("民事裁定书（对债权人申请破产还债用）")?></a></li>
        <li><a href="mscaijue/mscaijue25.php"><?=_("民事裁定书（不予执行公证债权文书用）")?></a></li>
        <li><a href="mscaijue/mscaijue26.php"><?=_("民事裁定书（采取强制执行措施用）")?></a></li>
        <li><a href="mscaijue/mscaijue27.php"><?=_("民事裁定书（中止或终结执行裁判文书用）")?></a></li>
        <li><a href="mscaijue/mscaijue28.php"><?=_("民事裁定书（中止或者终结执行其他法律文书用）")?></a></li>
        <li><a href="mscaijue/mscaijue29.php"><?=_("民事裁定书（补正裁判文书中的笔误用）")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>