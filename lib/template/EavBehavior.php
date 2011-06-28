<?php

/**
 * Easily adds EavBehavior functionality to a record.
 *
 * @package     sfEntityAttributeValuePlugin
 * @subpackage  template
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class Doctrine_Template_EavBehavior extends Doctrine_Template
{

    /**
     * Array of options
     *
     * @var string
     */
    protected $_options = array('mode', 'parent_resource');

    /**
     * __construct
     *
     * @param string $array
     * @return void
     */
    public function __construct(array $options = array())
    {
        $_options = array();
        foreach ($this->_options as $key)
        {
            $_options[$key] = sfConfig::get("app_eav_behavior_{$key}");
        }
        $this->_options = Doctrine_Lib::arrayDeepMerge($_options, $options);
    }

    /**
     * Set table definition 
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $this->addListener(new Doctrine_Template_Listener_EavBehavior($this->_options));
    }

    /**
     * Get options
     * 
     * @return type 
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Render Eav form show
     * 
     * @return type 
     */
    public function showEav()
    {
        $context = sfContext::hasInstance() ? sfContext::getInstance() : NULL;
        if ($context)
        {
            $context->getConfiguration()->getActive()->loadHelpers('Partial', 'I18N');
            $objStack = $this->getInvoker();
            $resource = new sfRessource($objStack->getTable()->getClassnameToReturn());
            $options = $this->getOptions();
            $foreignColumn = $objStack->getTable()->getRelation($options['parent_resource'])->getLocal();
            $vars = array(
                "source_ressource_id" => sfRessource::retriveByName($options['parent_resource'])->getId(),
                "source_entity_id" => $objStack->get($foreignColumn),
                "destination_ressource_id" => $resource->getId(),
                "destination_entity_id" => $objStack->getId());
            $data = get_component("eav", "show", $vars);
            return $data;
        }
    }

}
