<?php

/**
 * ProcessWire template file installed by the ServicePages module
 *
 */

$service = $modules->get('ServicePages'); 
if(!$service) throw new Wire404Exception('ServicePages module is not installed'); 
echo $service->execute();

