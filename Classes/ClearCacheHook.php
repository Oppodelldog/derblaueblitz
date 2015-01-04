<?php
/**
 * Created by PhpStorm.
 * User: nils
 * Date: 12.12.2014
 * Time: 17:53
 */

namespace Bplusd\derblaueblitz;

use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClearCacheHook implements ClearCacheActionsHookInterface {

    /**
     * Add the blue flash as a new clear-cache toolbar item
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['derblaueblitz']);
        $enableBlue = $settings['enableBlue'];
        if(!isset($enableBlue)) $enableBlue = 1;
        $enablePink = $settings['enablePink'];
        if(!isset($enablePink)) $enablePink = 0;

        /** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $beUser */
        $beUser = $GLOBALS['BE_USER'];
        if ($beUser->isAdmin()) {

            if($enableBlue){
                $item = array(
                    'id' => 'derblaueblitz',
                    'title' => "Flush CF Caching Tables",
                    'href' => 'tce_db.php?vC=' . $beUser->veriCode() . '&cacheCmd=derblaueblitz&ajaxCall=1' . BackendUtility::getUrlToken('tceAction'),
                    'icon' => '<img src="'.ExtensionManagementUtility::extRelPath("derblaueblitz").'blauerblitz.png" width="18" height="16"/>'
                );
                array_push($cacheActions,$item);
            }

            if($enablePink){
                $pinkTitle="Remove typo3temp";
                if($settings['PinkMove']==1) $pinkTitle ="Move typo3temp";
                $item = array(
                    'id' => 'derpinkeblitz',
                    'title' => $pinkTitle,
                    'href' => 'tce_db.php?vC=' . $beUser->veriCode() . '&cacheCmd=derpinkeblitz&ajaxCall=1' . BackendUtility::getUrlToken('tceAction'),
                    'icon' => '<img src="'.ExtensionManagementUtility::extRelPath("derblaueblitz").'pinkerblitz.png" width="18" height="16"/>'
                );
                array_push($cacheActions,$item);
            }

        }
    }

    /**
       * This method is called by the blue flash using ajax
       * @param \array $_params
       * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
       */
    public static function clear($_params, $dataHandler) {

        switch($_params["cacheCmd"]){

            case "derblaueblitz":
                self::clearBlue();
            break;

            case "derpinkeblitz":
                self::clearPink();
            break;

        }
    }

    protected static function clearBlue(){
        /** @var \TYPO3\CMS\Core\Database\DatabaseConnection $db */
        $db = $GLOBALS['TYPO3_DB'];

        $db->exec_TRUNCATEquery("cf_cache_hash");
        $db->exec_TRUNCATEquery("cf_cache_hash_tags");
        $db->exec_TRUNCATEquery("cf_cache_pages");
        $db->exec_TRUNCATEquery("cf_cache_pagesection");
        $db->exec_TRUNCATEquery("cf_cache_pagesection_tags");
        $db->exec_TRUNCATEquery("cf_cache_pages_tags");
        $db->exec_TRUNCATEquery("cf_cache_rootline");
        $db->exec_TRUNCATEquery("cf_cache_rootline_tags");
        $db->exec_TRUNCATEquery("cf_extbase_datamapfactory_datamap");
        $db->exec_TRUNCATEquery("cf_extbase_datamapfactory_datamap_tags");
        $db->exec_TRUNCATEquery("cf_extbase_object");
        $db->exec_TRUNCATEquery("cf_extbase_object_tags");
        $db->exec_TRUNCATEquery("cf_extbase_reflection");
        $db->exec_TRUNCATEquery("cf_extbase_reflection_tags");
        $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_queries");
        $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_queries_tags");
        $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_tablecolumns");
        $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_tablecolumns_tags");

    }


    public static function clearPink() {

        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['derblaueblitz']);
        $tempdir = GeneralUtility::getFileAbsFileName('typo3temp');

        if($settings['PinkMove']!=1) {


            if (is_dir($tempdir)) {
                self::deleteDir($tempdir);
            }
            if (!file_exists($tempdir)) {
                mkdir($tempdir, 0777, true);
            }
        }
        else{
            // rename typo3temp (renaming is faster than removing)
            if (is_dir($tempdir)){
                rename($tempdir,$tempdir."_".time());
            }
        }
    }

    /**
     * removes a directory including all subdirectories and files
     * @param $path
     * @return bool
     */
    private function deleteDir($path) {

        if (is_dir($path) === true)
        {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file)
            {
                self::deleteDir(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        }

        else if (is_file($path) === true)
        {
            return unlink($path);
        }

        return false;
    }
}