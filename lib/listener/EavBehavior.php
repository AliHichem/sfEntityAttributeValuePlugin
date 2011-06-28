<?php

/**
 * EavBehavior
 *
 * @package     sfEntityAttributeValuePlugin
 * @subpackage  listener
 * @link        www.phpdoctrine.org
 * @since       1.2
 * @version     $Revision$
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class Doctrine_Template_Listener_EavBehavior extends Doctrine_Record_Listener
{

    /**
     * Array of options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * __construct
     *
     * @param array $options
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }

    /**
     * Pre insert method
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preInsert(Doctrine_Event $event)
    {
        
    }

    /**
     * post delete
     *
     * @param string $Doctrine_Event 
     * @return void
     */
    public function postDelete(Doctrine_Event $event)
    {
        
    }

    /**
     * Implement preDqlSelect() 
     * is being used in.
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preDqlSelect(Doctrine_Event $event)
    {
        
    }

    /**
     * Pre save
     * 
     * @param Doctrine_Event $event 
     * @return
     */
    public function preSave(Doctrine_Event $event)
    {
        parent::preSave($event);
    }

    /**
     * Post save
     *  
     * @param Doctrine_Event $event 
     * @return
     */
    public function postSave(Doctrine_Event $event)
    {
        $context = sfContext::hasInstance() ? sfContext::getInstance() : NULL;
        if ($context)
        {
            $class = get_class($event->getInvoker());
            $conn = Doctrine::getConnectionByTableName($class);
            $relations = Doctrine_core::getTable($class)->getRelations();
            $options = $this->getOptions();
            $resource = new sfRessource($event->getInvoker()->getTable()->getClassnameToReturn());
            if ($options['mode'] == "create")
            {
                $params = array(
                    "ressource_id" => $resource->getId(),
                    "entity_id" => $event->getInvoker()->getId()
                );
                $context->getEventDispatcher()->notify(new sfEvent($this, 'eav.db.save', array('params' => $params)));
            }
            elseif ($options['mode'] == "insert")
            {
                $foreignColumn = $event->getInvoker()->getTable()->getRelation($options['parent_resource'])->getLocal();
                $params = array(
                    "source_ressource_id" => sfRessource::retriveByName($options['parent_resource'])->getId(),
                    "source_entity_id" => $event->getInvoker()->get($foreignColumn),
                    "destination_ressource_id" => $resource->getId(),
                    "destination_entity_id" => $event->getInvoker()->getId());
                $context->getEventDispatcher()->notify(new sfEvent($this, 'eav.db.save_values', array('params' => $params)));
            }
        }
    }

}
