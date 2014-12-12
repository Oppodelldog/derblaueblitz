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


class ClearCacheHook implements ClearCacheActionsHookInterface {

    /**
     * Add the blue flash as a new clear-cache toolbar item
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        /** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $beUser */
        $beUser = $GLOBALS['BE_USER'];
        if ($beUser->isAdmin()) {
            $cacheActions[] = array(
                'id' => 'derblaueblitz',
                'title' => "Der Blaue Blitz!!!",
                'href' => $this->backPath . 'tce_db.php?vC=' . $beUser->veriCode() . '&cacheCmd=derblaueblitz&ajaxCall=1' . BackendUtility::getUrlToken('tceAction'),
                'icon' => '<img src="'.ExtensionManagementUtility::extRelPath("derblaueblitz").'blauerblitz.png" width="18" height="16"/>'
            );
        }
    }

    /**
   * This method is called by the blue flash using ajax
   * @param \array $_params
   * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
   */
        public static function clear($_params, $dataHandler) {

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

        // rename typo3temp (renaming is faster than removing)
        $typo3temp = PATH_site . 'typo3temp';
        rename($typo3temp, $typo3temp.'_'.time());
    }
}