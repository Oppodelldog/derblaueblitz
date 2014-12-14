<?php
/**
 * Created by PhpStorm.
 * User: nils
 * Date: 12.12.2014
 * Time: 17:48
 */

if(!defined('TYPO3_MODE')) {
    die('Access denied.');
}


    // The Backend-MenuItem in ClearCache-Pulldown
    if($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions']==null)
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'] = array();

    array_push($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'],'EXT:derblaueblitz/Classes/ClearCacheHook.php:&Bplusd\\derblaueblitz\\ClearCacheHook');

    // The AjaxCall to clear the cache
    if($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']==null)
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']=array();

    array_push($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'],'EXT:derblaueblitz/Classes/ClearCacheHook.php:&Bplusd\\derblaueblitz\\ClearCacheHook->clear');

