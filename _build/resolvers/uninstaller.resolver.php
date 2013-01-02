<?php

$success= true;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_UNINSTALL:
        
        $modx = & $object->xpdo;

        $core_path = MODX_CORE_PATH;
        $cacheoptimizer_core_path = $modx->getOption('cacheoptimizer.core_path', null, $core_path.'components/cacheoptimizer/');

        if(!$core_path || !is_dir($core_path)){
            return false;
        }

        $path = 'model/modx/mysql/';
        
        if(!$modx->cacheManager->copyTree($cacheoptimizer_core_path.'files/original/core/', $core_path)){
            return false;
        }
        
        break;
}
return $success;