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
      <div align="center"><?=_("专利合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="zhuanli/zhuanliht00.php"><?=_("专利实施合同")?></a></li>
        <li><a href="zhuanli/zhuanliht01.php"><?=_("专利实施许可合同")?></a></li>
        <li><a href="zhuanli/zhuanliht02.php"><?=_("专利实施许可合同书")?></a></li>
        <li><a href="zhuanli/zhuanliht03.php"><?=_("专利权转让协议书")?></a></li>
        <li><a href="zhuanli/zhuanliht04.php"><?=_("中外专利技术许可合同")?></a></li>
        <li><a href="zhuanli/zhuanliht05.php"><?=_("专利实施许可合同")?></a></li>
        <li><a href="zhuanli/zhuanliht06.php"><?=_("专利实施许可合同书")?></a></li>
        <li><a href="zhuanli/zhuanliht07.php"><?=_("撤回专利申请声明")?></a></li>
        <li><a href="zhuanli/zhuanliht08.php"><?=_("撤销专利权请求书")?></a></li>
        <li><a href="zhuanli/zhuanliht09.php"><?=_("放弃专利权声明")?></a></li>
        <li><a href="zhuanli/zhuanliht10.php"><?=_("复审请求书")?></a></li>
        <li><a href="zhuanli/zhuanliht11.php"><?=_("恢复权利请求书")?></a></li>
        <li><a href="zhuanli/zhuanliht12.php"><?=_("强制许可请求书")?></a></li>
        <li><a href="zhuanli/zhuanliht13.php"><?=_("权利要求书")?></a></li>
        <li><a href="zhuanli/zhuanliht14.php"><?=_("实施专利许可合同备案表")?></a></li>
        <li><a href="zhuanli/zhuanliht15.php"><?=_("实质审查请求书")?></a></li>
        <li><a href="zhuanli/zhuanliht16.php"><?=_("说明书")?></a></li>
        <li><a href="zhuanli/zhuanliht17.php"><?=_("说明书摘要")?></a></li>
        <li><a href="zhuanli/zhuanliht18.php"><?=_("宣告专利权无效请求书")?></a></li>
        <li><a href="zhuanli/zhuanliht19.php"><?=_("要求提交公开声明")?></a></li>
        <li><a href="zhuanli/zhuanliht20.php"><?=_("意见陈述书")?></a></li>
        <li><a href="zhuanli/zhuanliht21.php"><?=_("专利代理委托书")?></a></li>
        <li><a href="zhuanli/zhuanliht22.php"><?=_("专利请求书")?></a></li>
        <li><a href="zhuanli/zhuanliht23.php"><?=_("专利申请权转让合同")?></a></li>
        <li><a href="zhuanli/zhuanliht24.php"><?=_("专利权转让合同")?></a></li>
        <li><a href="zhuanli/zhuanliht25.php"><?=_("专利申请委托协议书")?></a></li>
        <li><a href="zhuanli/zhuanliht26.php"><?=_("专利实施许可合同")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>