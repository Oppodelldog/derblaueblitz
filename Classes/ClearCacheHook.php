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


class ClearCacheHook implements ClearCacheActionsHookInterface {

    /**
     * Add the blue flash as a new clear-cache toolbar item
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     * @return
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        // derblaueblitz for backend admins
        if ($GLOBALS['BE_USER']->isAdmin()) {
            $cacheActions[] = array(
                'id' => 'derblaueblitz',
                'title' => "Der Blaue Blitz!!!",
                'href' => $this->backPath . 'tce_db.php?vC=' . $GLOBALS['BE_USER']->veriCode() . '&cacheCmd=derblaueblitz&ajaxCall=1' . \TYPO3\CMS\Backend\Utility\BackendUtility::getUrlToken('tceAction'),
                'icon' => '<img src="'.ExtensionManagementUtility::extRelPath("derblaueblitz").ExtensionManagementUtility::getExtensionIcon(ExtensionManagementUtility::extPath("derblaueblitz")).'" width="18" height="16"/>'
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

        $res = $db->exec_TRUNCATEquery("cf_cache_hash");
        $res = $db->exec_TRUNCATEquery("cf_cache_hash_tags");
        $res = $db->exec_TRUNCATEquery("cf_cache_pages");
        $res = $db->exec_TRUNCATEquery("cf_cache_pagesection");
        $res = $db->exec_TRUNCATEquery("cf_cache_pagesection_tags");
        $res = $db->exec_TRUNCATEquery("cf_cache_pages_tags");
        $res = $db->exec_TRUNCATEquery("cf_cache_rootline");
        $res = $db->exec_TRUNCATEquery("cf_cache_rootline_tags");
        $res = $db->exec_TRUNCATEquery("cf_extbase_datamapfactory_datamap");
        $res = $db->exec_TRUNCATEquery("cf_extbase_datamapfactory_datamap_tags");
        $res = $db->exec_TRUNCATEquery("cf_extbase_object");
        $res = $db->exec_TRUNCATEquery("cf_extbase_object_tags");
        $res = $db->exec_TRUNCATEquery("cf_extbase_reflection");
        $res = $db->exec_TRUNCATEquery("cf_extbase_reflection_tags");
        $res = $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_queries");
        $res = $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_queries_tags");
        $res = $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_tablecolumns");
        $res = $db->exec_TRUNCATEquery("cf_extbase_typo3dbbackend_tablecolumns_tags");

        // rename typo3temp (renaming is faster than removing)
        $typo3temp = PATH_site . 'typo3temp';
        rename($typo3temp, $typo3temp.'_'.time());
    }
}