<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/tools.php');	?>

	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['RSSLabel'];?></legend>
		<a href="<?php echo CalRoot;?>/index.php?com=rss" class="eventMain"><?php echo $hc_lang_tools['RSSLink'];?></a>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['Syndication'];?></legend>
		<b><?php echo $hc_lang_tools['Setup'];?></b>
		<ol>
			<li><?php echo $hc_lang_tools['Synd1'] . " <a href=\"" . CalRoot . "/js/syndication.css\" class=\"eventMain\" target=\"_blank\">" . $hc_lang_tools['Synd1B'] . "</a>.";?></li>
			<li><?php echo $hc_lang_tools['Synd2'];?></li>
			<li><?php echo $hc_lang_tools['Synd3'];?></li>
		</ol>
		<div style="line-height:15px;"><b><?php echo $hc_lang_tools['Stylesheet'];?></b> [ <a href="<?php echo CalRoot;?>/js/syndication.css" class="eventMain" target="_blank"><?php echo $hc_lang_tools['Template'];?></a> ]</div>
		<div class="frmOpt">
			<input name="stylesheet" id="styleshset" style="width:95%;" onfocus="this.select();" value='&lt;link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/js/syndication.css" /&gt;' />
		</div>
		<br />
		<b><?php echo $hc_lang_tools['Code'];?></b>

			<textarea style="width:95%;height:135px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">&lt;script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/js/syndication.php?s=0&amp;z=10&amp;t=1"&gt;
<?php
echo "//<!--
/*	" . $hc_lang_tools['CodeComment'] . "
	s = " . $hc_lang_tools['CodeS'] . ", 0 = " . $hc_lang_tools['CodeS0'] . ", 1 = " . $hc_lang_tools['CodeS1'] . ", 2 = " . $hc_lang_tools['CodeS2'] . ", 3 = " . $hc_lang_tools['CodeS3'] . "
	z = " . $hc_lang_tools['CodeZ'] . "
	t = " . $hc_lang_tools['CodeT'] . ", 1 = " . $hc_lang_tools['CodeT1'] . ", 0 = " . $hc_lang_tools['CodeT0'] . "
*/
//-->\n";?>
&lt;/script&gt;</textarea>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['OpenSearch'];?></legend>
		<?php echo $hc_lang_tools['OSDesc'];?>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_tools['MobileSite'];?></legend>
		<a href="<?php echo MobileRoot;?>" class="eventMain"><b><?php echo MobileRoot;?></b></a>
		<br /><br />
		<?php echo $hc_lang_tools['MobileDesc'];?>
		<br />
		(<?php echo $hc_lang_tools['MobileReq'];?>)
	</fieldset>