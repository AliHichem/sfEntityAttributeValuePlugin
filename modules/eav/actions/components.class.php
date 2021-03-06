<?php

/**
 * components.class.php
 *
 * PHP version 5
 *
 * @category Composants
 * @package  Eav
 * @author   ALI Hichem <ali.hichem@mail.com>
 */

/**
 * eavComponents
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @category Composants
 * @package  Eav
 * @author   ALI Hichem <ali.hichem@mail.com>
 */
class eavComponents extends sfComponents
{

    /**
     * From component
     *
     * @param sfRequest $request A request object
     *
     * @return void
     */
    public function executeForm(sfWebRequest $request)
    {
        if ($request->hasParameter('eav_dynamics_json'))
        {
            $this->eav_dynamics_json = $request->getParameter('eav_dynamics_json');
        }
    }

    /**
     * Render component
     *
     * @param sfWebRequest $request requête
     *
     * @return void
     */
    public function executeRender(sfWebRequest $request)
    {
        $options = array(
            "source_ressource_id" => $this->source_ressource_id,
            "source_entity_id" => $this->source_entity_id,
            "destination_ressource_id" => $this->destination_ressource_id,
            "destination_entity_id" => $this->destination_entity_id);
        $this->formId = eavDynamicForm::FORM_NAME . '-' . implode("-", $options);
        $this->eavDynamicForm = new eavDynamicForm($options);
        $formStructure = EavTable::buildFormStructure($this->source_ressource_id, $this->source_entity_id);
        if ($this->destination_entity_id == "" && $request->isMethod('post') && isset($_POST[eavDynamicShowForm::FORM_NAME]))
        {
            $keys = array_keys($_POST);
            foreach ($keys as $key)
            {
                if (preg_match('~EAV_~', $key, $matches))
                {
                    $_POST[eavDynamicForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id] = $_POST[$key][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id];
                    unset($_POST[$key][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id]);
                }
            }
            $values = $this->prepareValues($_POST[eavDynamicForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id]);
            $this->eavDynamicForm->configureFields($formStructure, $values);
        }
        else
        {
            if (isset($_POST[eavDynamicForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id . "-" . $this->destination_entity_id]))
            {
                $values = $_POST[eavDynamicForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id . "-" . $this->destination_entity_id];
                $this->eavDynamicForm->configureFields($formStructure, $values);
            }
            else
            {
                $this->eavDynamicForm->configureFields($formStructure);
            }
        }
    }

    /**
     * Show component
     *
     * @param sfWebRequest $request requête
     *
     * @return void
     */
    public function executeShow(sfWebRequest $request)
    {
        $options = array(
            "source_ressource_id" => $this->source_ressource_id,
            "source_entity_id" => $this->source_entity_id,
            "destination_ressource_id" => $this->destination_ressource_id,
            "destination_entity_id" => $this->destination_entity_id);
        $this->formId = eavDynamicShowForm::FORM_NAME . '-' . implode("-", $options);
        $this->eavDynamicShowForm = new eavDynamicShowForm($options);
        $formStructure = EavTable::buildFormStructure($this->source_ressource_id, $this->source_entity_id);
        if ($this->destination_entity_id == "" && $request->isMethod('post') && isset($_POST[eavDynamicShowForm::FORM_NAME]))
        {
            $keys = array_keys($_POST);
            foreach ($keys as $key)
            {
                if (preg_match('~EAV_~', $key, $matches))
                {
                    $_POST[eavDynamicShowForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id] = $_POST[$key][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id];
                    unset($_POST[$key][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id]);
                }
            }
            $values = $this->prepareValues($_POST[eavDynamicShowForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id]);
            $this->eavDynamicShowForm->configureFields($formStructure, $values);
        }
        else
        {
            if (isset($_POST[eavDynamicShowForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id . "-" . $this->destination_entity_id]))
            {
                $values = $_POST[eavDynamicShowForm::FORM_NAME][$this->source_ressource_id . "-" . $this->source_entity_id][$this->destination_ressource_id . "-" . $this->destination_entity_id];
                $this->eavDynamicShowForm->configureFields($formStructure, $values);
            }
            else
            {
                $this->eavDynamicShowForm->configureFields($formStructure);
            }
        }
    }

    /**
     * Preapre values
     * 
     * @param type $values
     * @return type 
     */
    protected function prepareValues(&$values)
    {
        $data = array();
        foreach ($values as $index => $value)
        {
            $keys = array_keys($value);
            $pk = $keys[0];
            if (is_array($value[$pk]))
            {
                if (!isset($data[$pk]))
                {
                    $data[$pk] = $value[$pk];
                }
                else
                {
                    $data[$pk] = array_merge($data[$pk], $value[$pk]);
                }
            }
            else
            {
                $data[$pk] = $value[$pk];
            }
            if ($pk == "separator")
            {
                unset($values[$index]);
                return $data;
            }
            unset($values[$index]);
        }
    }

}
