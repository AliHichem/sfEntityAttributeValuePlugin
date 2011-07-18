<?php

require_once dirname(__FILE__) . '/../bootstrap/unit.php';
require_once dirname(__FILE__) . '/../bootstrap/Doctrine.php';

$t = new lime_test(4);
$ressource_id = 1;
$entity_id = 1;

Eav::createEavGroupIfNotExists($ressource_id, $entity_id);
$eavGroup = EavGroupsTable::getInstance()->findOneByRessourceIdAndEntityId($ressource_id, $entity_id);
$structue = EavTable::buildFormStructure($ressource_id, $entity_id);

$t->is(get_class($eavGroup), 'EavGroups');
$t->is($eavGroup->getRessourceId(), $ressource_id);
$t->is($eavGroup->getEntityId(), $entity_id);
$t->is('array', gettype($structue));