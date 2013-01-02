<?php

$success= true;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $modx = & $object->xpdo;

        $core_path = MODX_CORE_PATH;
        $cacheoptimizer_core_path = $modx->getOption('cacheoptimizer.core_path', null, $core_path.'components/cacheoptimizer/');

        if(!$core_path || !is_dir($core_path)){
            return false;
        }

        $path = 'model/modx/mysql/';
        
        // save original files
        $file = $cacheoptimizer_core_path.'files/original/core/'.$path.'modcontext.class.php';
        if(!file_exists($file)){
            if(!$modx->cacheManager->copyFile( $core_path.$path.'modcontext.class.php', $file )){
                return false;
            }
        }
        
        if(!$modx->cacheManager->copyTree($cacheoptimizer_core_path.'files/new/core/', $core_path)){
            return false;
        }
        
        break;
}
return $success;