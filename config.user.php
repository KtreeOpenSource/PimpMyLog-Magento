<?php
/*! pimpmylog - 1.7.9 - 10b502eaf17be208850be61febb044c2fdb86207*/
/*
 * pimpmylog
 * http://pimpmylog.com
 *
 * Copyright (c) 2015 Potsky, contributors
 * Licensed under the GPLv3 license.
 */
?>
<?php if(realpath(__FILE__)===realpath($_SERVER["SCRIPT_FILENAME"])){header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');die();}?>
<?php
$generalFormat = array (
        'regex' => '|^(.*)$|U',
        'export_title' => 'Log',
        'match' => 
        array (
          'Log' => 1,
        ),
        'types' => 
        array (
          'Log' => 'txt',
        ),
        'exclude' => 
        array (
          'Log' => 
          array (
            0 => '/PHP Stack trace:/',
            1 => '/PHP *[0-9]*\. /',
          ),
        ),
      );
$accessLogFormat = array (
        'regex' => ' |^(.*) (.*) (.*) \[(.*)\] "(.*) (.*) (.*)" ([0-9]*) (.*) "(.*)" "(.*)"( [0-9]*/([0-9]*))*$|U',
        'export_title' => 'URL',
        'match' => 
        array (
          'Date' => 4,
          'IP' => 1,
          'CMD' => 5,
          'URL' => 6,
          'Code' => 8,
          'Size' => 9,
          'Referer' => 10,
          'UA' => 11,
          'User' => 3,
          'μs' => 13,
        ),
        'types' => 
        array (
          'Date' => 'date:H:i:s',
          'IP' => 'ip:geo',
          'URL' => 'txt',
          'Code' => 'badge:http',
          'Size' => 'numeral:0b',
          'Referer' => 'link',
          'UA' => 'ua:{os.name} {os.version} | {browser.name} {browser.version}/100',
          'μs' => 'numeral:0,0',
        ),
        'exclude' => 
        array (
          'URL' => 
          array (
            0 => '/favicon.ico/',
            1 => '/\.pml\.php.*$/',
          ),
          'CMD' => 
          array (
            0 => '/OPTIONS/',
          ),
        ),
      );
	  
$magentoLogFormat = array (
	'type'=> 'PHP',
  'regex' => '@^(.*)\-(.*)\-(.*)T(.*)\:(.*)\:(.*)\+(.*)\:(.*)\ (.*)\ \((.*)\)\:\ (((.*) in (.*) on line (.*))|(.*))$@U',
  'match' => 
  array (
    'Date' => 
    array (
      0 => 3,
      1 => '-',
      2 => 2,
      3 => '-',
      4 => 1,
      5 => ' ',
      6 => 4,
      7 => ':',
      8 => 5,
      9 => ':',
      10 => 6,
    ),
    'Severity' => 9,
    'Error' => 
    array (
      0 => 12,
      1 => 13,
    ),
    'File' => 14,
    'Line' => 15,
  ),
  'types' => 
  array (
    'Date' => 'date:d-m-Y H:i:s',
    'Severity' => 'badge:severity',
    'File' => 'pre:/-69',
    'Line' => 'numeral',
    'Error' => 'pre',
  ),
  'exclude' => 
        array (
    'Log' => 
    array (
      0 => '\/PHP Stack trace:\/',
      1 => '\/PHP *[0-9]*\. \/',
    ),
  ),
);
$files = array ();
//Dynamic Folders
$directories = array(
					'Nginx Error Logs'=>array('match'=>'error','directory'=>'/var/log/nginx/','format'=>'general'),
					'Nginx Access Logs'=>array('match'=>'access','directory'=>'/var/log/nginx/','format'=>'access'),
					'Syslog Error Logs'=>array('match'=>'syslog','directory'=>'/var/log/','format'=>'general'),
					'Mysql Error Logs'=>array('match'=>'mysql','directory'=>'/var/log/','format'=>'general'),
					'Magento Logs'=>array('match'=>'.log','directory'=>'/var/www/devzeb/var/log/','format'=>'magento'),
					'Magento Reports'=>array('match'=>'','directory'=>'/var/www/devzeb/var/report/','format'=>'general')
				);

foreach($directories as $magentoDirKey=>$directory)
if ($handle = opendir($directory['directory'])) {
	switch($directory['format']) {
		case 'access' :
			$format = $accessLogFormat;
			break;
		case 'magento' :
			$format = $magentoLogFormat;
			break;
		default:
			$format = $generalFormat;
	} 
	$sortFiles = array();
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && (!$directory['match'] || ((strpos($entry, $directory['match']) !== false) && (strpos($entry, '.gz') === false)))) {
			$sortFiles[$entry] = array('filename'=>$entry,'filetime'=>filemtime($directory['directory'].$entry));
		}
    }
	closedir($handle);
	usort($sortFiles, function($a, $b) {
	    return $a['filetime'] - $b['filetime'];
	});
$sortFiles = array_reverse($sortFiles);
	foreach($sortFiles as $sortKey=>$sortFile) {
		$files[$sortFile['filename']] = array (
      'display' =>$sortFile['filename'],
      'path' => $directory['directory'].$sortFile['filename'],
      'refresh' => 5,
      'max' => 10,
      'notify' => true,
      'multiline' => '',
	"tags"    => array(array('main_tag'=>$magentoDirKey,'sub_tag'=>date ("d-m-Y", $sortFile['filetime']))),
      'format' => $format,
    );
	}

    
}



$pimpConfig = array (
  'globals' => 
  array (
    '_remove_me_to_set_AUTH_LOG_FILE_COUNT' => 100,
    '_remove_me_to_set_AUTO_UPGRADE' => false,
    '_remove_me_to_set_CHECK_UPGRADE' => true,
    '_remove_me_to_set_EXPORT' => true,
    '_remove_me_to_set_FILE_SELECTOR' => 'bs',
    '_remove_me_to_set_FOOTER' => '&copy; <a href="http://www.potsky.com" target="doc">Potsky</a> 2007-' . YEAR . ' - <a href="http://pimpmylog.com" target="doc">Pimp my Log</a>',
    '_remove_me_to_set_FORGOTTEN_YOUR_PASSWORD_URL' => 'http://support.pimpmylog.com/kb/misc/forgotten-your-password',
    '_remove_me_to_set_GEOIP_URL' => 'http://www.geoiptool.com/en/?IP=%p',
    '_remove_me_to_set_GOOGLE_ANALYTICS' => 'UA-XXXXX-X',
    '_remove_me_to_set_HELP_URL' => 'http://pimpmylog.com',
    '_remove_me_to_set_LOCALE' => 'gb_GB',
    '_remove_me_to_set_LOGS_MAX' => 50,
    '_remove_me_to_set_LOGS_REFRESH' => 0,
    '_remove_me_to_set_MAX_SEARCH_LOG_TIME' => 5,
    '_remove_me_to_set_NAV_TITLE' => '',
    '_remove_me_to_set_NOTIFICATION' => true,
    '_remove_me_to_set_NOTIFICATION_TITLE' => 'New logs [%f]',
    '_remove_me_to_set_PIMPMYLOG_ISSUE_LINK' => 'https://github.com/potsky/PimpMyLog/issues/',
    '_remove_me_to_set_PIMPMYLOG_VERSION_URL' => 'http://demo.pimpmylog.com/version.js',
    '_remove_me_to_set_PULL_TO_REFRESH' => true,
    '_remove_me_to_set_SORT_LOG_FILES' => 'default',
    '_remove_me_to_set_TAG_DISPLAY_LOG_FILES_COUNT' => true,
    '_remove_me_to_set_TAG_NOT_TAGGED_FILES_ON_TOP' => true,
    '_remove_me_to_set_TAG_SORT_TAG' => 'default | display-asc | display-insensitive | display-desc | display-insensitive-desc',
    '_remove_me_to_set_TITLE' => 'Pimp my Log',
    '_remove_me_to_set_TITLE_FILE' => 'Pimp my Log [%f]',
    '_remove_me_to_set_UPGRADE_MANUALLY_URL' => 'http://pimpmylog.com/getting-started/#update',
    '_remove_me_to_set_USER_CONFIGURATION_DIR' => 'config.user.d',
    '_remove_me_to_set_USER_TIME_ZONE' => 'Europe/Paris',
  ),
  'badges' => 
  array (
    'severity' => 
    array (
      'debug' => 'success',
      'info' => 'success',
      'notice' => 'default',
      'Notice' => 'info',
      'warn' => 'warning',
      'error' => 'danger',
      'crit' => 'danger',
      'alert' => 'danger',
      'emerg' => 'danger',
      'Fatal error' => 'danger',
      'Parse error' => 'danger',
      'Warning' => 'warning',
    ),
    'http' => 
    array (
      1 => 'info',
      2 => 'success',
      3 => 'default',
      4 => 'warning',
      5 => 'danger',
    ),
  ),
  'files' => $files,
); 
echo json_encode($pimpConfig)
?>

