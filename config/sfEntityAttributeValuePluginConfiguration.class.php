<?php

/**
 * sfEntityAttributeValuePlugin configuration.
 * 
 * @package    sfEntityAttributeValuePlugin
 * @subpackage config
 * @author     Ali hichem <ali.hichem@mail.com>
 * @version    SVN: $Id: 
 */
class sfEntityAttributeValuePluginConfiguration extends sfPluginConfiguration
{

    /**
     * @see sfPluginConfiguration
     */
    public function initialize()
    {
        
    }

    /**
     * Sets up the plugin.
     * 
     * This method can be used when creating a base plugin configuration class for other plugins to extend.
     */
    public function setup()
    {
        $dispatcher = $this->dispatcher;
        $dispatcher->connect('eav.db.save', array('eav', 'saveIntoDb'));
        $dispatcher->connect('eav.db.save_values', array('eav', 'saveValuesIntoDb'));
    }

}
