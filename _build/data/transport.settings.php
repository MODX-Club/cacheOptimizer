<?php

$settings = array();


$settings['cacheoptimizer.resource_map_disabled']= $modx->newObject('modSystemSetting');
$settings['cacheoptimizer.resource_map_disabled']->fromArray(array(
    'key' => 'cacheoptimizer.resource_map_disabled',
    'value' => 0,
    'xtype' => 'combo-boolean',
    'namespace' => NAMESPACE_NAME,
    'area' => 'caching',
),'',true,true);


return $settings;