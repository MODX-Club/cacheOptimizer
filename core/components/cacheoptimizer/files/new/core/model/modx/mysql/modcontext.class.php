<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modcontext.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modContext_mysql extends modContext {
    public static function getResourceCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            
            // Get context setting
            $settings = array();
            if($result = $context->getMany('ContextSettings')){
                foreach($result as $r){
                    $settings[$r->get('key')]  = $r->get('value');
                }
            }
            
            // If resource map disabled, skip it
            if($context->xpdo->getOption('cacheoptimizer.resource_map_disabled', $settings , false)){
                return false;
            }
            
            $tblResource= $context->xpdo->getTableName('modResource');
            $tblContextResource= $context->xpdo->getTableName('modContextResource');
            $resourceFields= array('id','parent','uri');
            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings = array($context->get('key'), $context->get('key'));
            $sql = "SELECT {$resourceCols} FROM {$tblResource} `r` FORCE INDEX (`cache_refresh_idx`) LEFT JOIN {$tblContextResource} `cr` ON `cr`.`context_key` = ? AND `r`.`id` = `cr`.`resource` WHERE `r`.`id` != `r`.`parent` AND (`r`.`context_key` = ? OR `cr`.`context_key` IS NOT NULL) AND `r`.`deleted` = 0 GROUP BY `r`.`parent`, `r`.`menuindex`, `r`.`id`";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }
        }
        return $stmt;
    }

    public static function getWebLinkCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');
            $resourceFields= array('id','content');
            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings = array($context->get('key'), $context->get('key'));
            $sql = "SELECT {$resourceCols} FROM {$tblResource} `r` LEFT JOIN {$tblContextResource} `cr` ON `cr`.`context_key` = ? AND `r`.`id` = `cr`.`resource` WHERE `r`.`id` != `r`.`parent` AND `r`.`class_key` = 'modWebLink' AND (`r`.`context_key` = ? OR `cr`.`context_key` IS NOT NULL) AND `r`.`deleted` = 0 GROUP BY `r`.`id`";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }
        }
        return $stmt;
    }
}
