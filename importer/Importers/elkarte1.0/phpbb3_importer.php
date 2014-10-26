<?php

class phpBB3 extends AbstractSourceImporter
{
	public function getName()
	{
		return 'phpBB3';
	}

	public function getVersion()
	{
		return 'ElkArte 1.0';
	}

	public function setDefines()
	{
		define('IN_PHPBB', 1);
	}

	public function loadSettings($path)
	{
		// Error silenced in case of odd server configurations (open_basedir mainly)
		if (@file_exists($path . '/config.php'))
		{
			require_once($path . '/config.php');
			return true;
		}
		else
			return false;
	}

	public function getPrefix()
	{
		global $dbname, $table_prefix;

		return '`' . $dbname . '`.' . $table_prefix;
	}

	public function getTableTest()
	{
		return 'users';
	}
}

/**
 * Utility functions
 */
function percent_to_px($percent)
{
	return intval(11*(intval($percent)/100.0));
}

function phpbb_replace_bbc($message)
{
	$message = preg_replace(
		array(
			'~\[quote=&quot;(.+?)&quot;\:(.+?)\]~is',
			'~\[quote\:(.+?)\]~is',
			'~\[/quote\:(.+?)\]~is',
			'~\[b\:(.+?)\]~is',
			'~\[/b\:(.+?)\]~is',
			'~\[i\:(.+?)\]~is',
			'~\[/i\:(.+?)\]~is',
			'~\[u\:(.+?)\]~is',
			'~\[/u\:(.+?)\]~is',
			'~\[url\:(.+?)\]~is',
			'~\[/url\:(.+?)\]~is',
			'~\[url=(.+?)\:(.+?)\]~is',
			'~\[/url\:(.+?)\]~is',
			'~\<a(.+?) href="(.+?)">(.+?)</a>~is',
			'~\[img\:(.+?)\]~is',
			'~\[/img\:(.+?)\]~is',
			'~\[size=(.+?)\:(.+?)\]~is',
			'~\[/size\:(.+?)?\]~is',
			'~\[color=(.+?)\:(.+?)\]~is',
			'~\[/color\:(.+?)\]~is',
			'~\[code=(.+?)\:(.+?)\]~is',
			'~\[code\:(.+?)\]~is',
			'~\[/code\:(.+?)\]~is',
			'~\[list=(.+?)\:(.+?)\]~is',
			'~\[list\:(.+?)\]~is',
			'~\[/list\:(.+?)\]~is',
			'~\[\*\:(.+?)\]~is',
			'~\[/\*\:(.+?)\]~is',
			'~\<img src=\"{SMILIES_PATH}/(.+?)\" alt=\"(.+?)\" title=\"(.+?)\" /\>~is',
		),
		array(
			'[quote author="$1"]',
			'[quote]',
			'[/quote]',
			'[b]',
			'[/b]',
			'[i]',
			'[/i]',
			'[u]',
			'[/u]',
			'[url]',
			'[/url]',
			'[url=$1]',
			'[/url]',
			'[url=$2]$3[/url]',
			'[img]',
			'[/img]',
			'[size=' . percent_to_px("\1") . 'px]',
			'[/size]',
			'[color=$1]',
			'[/color]',
			'[code=$1]',
			'[code]',
			'[/code]',
			'[list type=$1]',
			'[list]',
			'[/list]',
			'[li]',
			'[/li]',
			'$2',
		), $message);

	$message = preg_replace('~\[size=(.+?)px\]~is', "[size=" . ('\1' > '99' ? 99 : '"\1"') . "px]", $message);

	$message = strtr($message, array(
		'[list type=1]' => '[list type=decimal]',
		'[list type=a]' => '[list type=lower-alpha]',
	));
	$message = stripslashes($message);

	return $message;
}