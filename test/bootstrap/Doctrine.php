<?php

/**
 * sfEntityAttributeValuePlugin .
 *
 * @package    sfEntityAttributeValuePlugin
 * @subpackage test
 * @author     Ali hichem <ali.hichem@mail.com>
 * @version    SVN: $Id:
 */

require_once (dirname(__FILE__) . '/unit.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('admin', 'test', true);

new sfDatabaseManager($configuration);

Doctrine_Core::loadData(sfConfig::get('sf_test_dir') . '/fixtures');

sfContext::createInstance($configuration);