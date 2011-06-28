<?php

/**
 * sfWidgetFormEav
 *
 * @package     sfEntityAttributeValuePlugin
 * @subpackage  form
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Ali hichem <ali.hichem@mail.com>
 */
class sfWidgetFormEav extends sfWidgetForm
{

    public function __construct(sfForm $form, $options = array(), $attributes = array())
    {
        parent::__construct($options, $attributes);
        $this->form = $form;
    }

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * type: The widget type
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetForm
     */
    protected function configure($options = array(), $attributes = array())
    {
        
    }

    /**
     * Renders the widget.
     *
     * @param  string $name        The element name
     * @param  string $value       The value displayed in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $out = NULL;
        $context = sfContext::hasInstance() ? sfContext::getInstance() : NULL;
        if ($context)
        {
            $objStack = $this->form->getObject();
            if (array_key_exists('Doctrine_Template_EavBehavior', $objStack->getTable()->getTemplates()))
            {
                $this->templateOptions = $objStack->getTable()->getTemplate('Doctrine_Template_EavBehavior')->getOptions();
                $method = "prepareAs" . ucfirst($this->templateOptions['mode']);
                $out = $this->$method();
            }
        }
        return $out;
    }

    /**
     * Prepare for mode "create"
     * 
     * @return type 
     */
    public function prepareAsCreate()
    {
        $context = sfContext::hasInstance() ? sfContext::getInstance() : NULL;
        if ($context)
        {
            $context->getConfiguration()->getActive()->loadHelpers('Partial', 'I18N');
            $objStack = $this->form->getObject();
            $resource = new sfRessource($objStack->getTable()->getClassnameToReturn());
            $vars = array(
                "ressource_id" => $resource->getId(),
                "entity_id" => $objStack->getId());
            return get_component("eav", "form", $vars);
        }
    }

    /**
     * Prepare for mode "insert"
     *
     * @return type 
     */
    public function prepareAsInsert()
    {
        $context = sfContext::hasInstance() ? sfContext::getInstance() : NULL;
        if ($context)
        {
            $context->getConfiguration()->getActive()->loadHelpers('Partial', 'I18N');
            $objStack = $this->form->getObject();
            $resource = new sfRessource($objStack->getTable()->getClassnameToReturn());
            $foreignColumn = $objStack->getTable()->getRelation($this->templateOptions['parent_resource'])->getLocal();
            $vars = array(
                "source_ressource_id" => sfRessource::retriveByName($this->templateOptions['parent_resource'])->getId(),
                "source_entity_id" => $objStack->get($foreignColumn),
                "destination_ressource_id" => $resource->getId(),
                "destination_entity_id" => $objStack->getId());
            return get_component("eav", "render", $vars);
        }
    }

    /**
     * Gets the stylesheet paths associated with the widget.
     *
     * @return array An array of stylesheet paths
     */
    public function getStylesheets()
    {
        return array('/sfEntityAttributeValuePlugin/css/jquery.formbuilder.css' => 'all');
    }

    /**
     * Gets the JavaScript paths associated with the widget.
     *
     * @return array An array of JavaScript paths
     */
    public function getJavascripts()
    {
        return array(
            '/sfEntityAttributeValuePlugin/js/jquery-1.3.2.min.js',
            '/sfEntityAttributeValuePlugin/js/jquery.formbuilder.js',
            '/sfEntityAttributeValuePlugin/js/jquery.scrollTo-min.js');
    }

}
