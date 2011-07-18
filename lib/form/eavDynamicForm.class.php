<?php

/**
 * eavDynamicForm.class.php
 *
 * PHP version 5
 *
 * @category Form
 * @package  Eav
 * @author   ALI Hichem <ali.hichem@mail.com>
 */

/**
 * eavDynamicForm
 *
 * @category Form
 * @package  Eav
 * @author   ALI Hichem <ali.hichem@mail.com>
 */
class eavDynamicForm extends BaseForm
{
    const FORM_NAME = 'eav_Dynamics';

    /**
     * configure the form
     *
     * @return void
     */
    public function configure()
    {
        $options = $this->getDefaults();
        if ($options['destination_entity_id'] != "")
        {
            $nameFormat = self::FORM_NAME;
            $nameFormat .= "[" . $options['source_ressource_id'] . "-" . $options['source_entity_id'] . "]";
            $nameFormat .= "[" . $options['destination_ressource_id'] . "-" . $options['destination_entity_id'] . "]";
        }
        else
        {
            $nameFormat = uniqid('EAV_')."_";
            $nameFormat .= self::FORM_NAME;
            $nameFormat .= "[" . $options['source_ressource_id'] . "-" . $options['source_entity_id'] . "]";
            $nameFormat .= "[" . $options['destination_ressource_id'] . "][]";
        }
        $nameFormat .= "[%s]";
        $this->widgetSchema->setNameFormat($nameFormat);
        $this->disableCSRFProtection();
    }

    /**
     * Configure form fields
     *
     * @param <type> $formStructure form structure
     *
     * @return void
     */
    public function configureFields($formStructure, $values = NULL)
    {
        if (!empty($formStructure))
        {
            $options = $this->getDefaults();
            $this->eavDefaultValues = Doctrine_Query::create()
                            ->from('EavFullValues efv')
                            ->innerJoin('efv.Eav e')
                            ->innerJoin('e.EavGroups eg')
                            ->where(
                                    sprintf("eg.ressource_id = '%s' and eg.entity_id = '%s' ",
                                            $options['source_ressource_id'],
                                            $options['source_entity_id']))
                            ->execute();
            $this->eavValues = Doctrine_Query::create()
                            ->from('EavValues ev')
                            ->innerJoin('ev.EavFullValues efv')
                            ->innerJoin('efv.Eav e')
                            ->where(
                                    sprintf("ev.ressource_id = '%s' and ev.entity_id = '%s' ",
                                            $options['destination_ressource_id'],
                                            $options['destination_entity_id']))
                        ->execute();
            foreach ($formStructure as $field)
            {
                $this->configureField($field, $values);
            }
            $this->widgetSchema['separator'] = new sfWidgetFormInputHidden();
            $this->validatorSchema['separator'] = new sfValidatorString(array('max_length' => 255));
            $this->getWidget('separator')->setDefault(TRUE);
        }
    }

    /**
     * configure field by type
     *
     * @param <type> $field field
     *
     * @return void
     */
    public function configureField($field, $values)
    {
        $options = $this->getDefaults();
        $eavDefaultValues = $this->eavDefaultValues;
        $eavValues = $this->eavValues;
        switch ($field['class'])
        {
            case "input_text":
                $this->widgetSchema[$field['id']] = new sfWidgetFormInputText();
                $this->validatorSchema[$field['id']] = new sfValidatorString(array('max_length' => 255));
                $this->getWidget($field['id'])->setLabel($field['values']);
                if (isset($values[$field['id']]))
                {
                    $this->getWidget($field['id'])->setDefault($values[$field['id']]);
                }
                else
                {
                    foreach ($eavValues as $eavValue)
                    {
                        if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                        {
                            $this->getWidget($field['id'])->setDefault($eavValue->getValue());
                        }
                    }
                }
                break;
            case "textarea":
                $this->widgetSchema[$field['id']] = new sfWidgetFormTextarea();
                $this->validatorSchema[$field['id']] = new sfValidatorString();
                $this->getWidget($field['id'])->setLabel($field['values']);
                if (isset($values[$field['id']]))
                {
                    $this->getWidget($field['id'])->setDefault($values[$field['id']]);
                }
                else
                {
                    foreach ($eavValues as $eavValue)
                    {
                        if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                        {
                            $this->getWidget($field['id'])->setDefault($eavValue->getValue());
                        }
                    }
                }
                break;
            case "checkbox":
                $choices = array();
                foreach ($field['values'] as $item)
                {
                    $choices[$item['id']] = $item['value'];
                }
                $options = array('choices' => $choices, 'expanded' => true,
                    'multiple' => true,);
                $this->widgetSchema[$field['id']] = new sfWidgetFormChoice($options);
                unset($options['expanded']);
                $this->validatorSchema[$field['id']] = new sfValidatorChoice($options);
                $this->getWidget($field['id'])->setLabel($field['title']);
                if ($this->isNew())
                {
                    if (isset($values[$field['id']]))
                    {
                        $this->getWidget($field['id'])->setDefault($values[$field['id']]);
                    }
                    else
                    {
                        $defaultValueArray = array();
                        foreach ($eavDefaultValues as $eavDefaultValue)
                        {
                            if ($eavDefaultValue->getEav()->getId() == $field['id'] && $eavDefaultValue->getIsSelected())
                            {
                                $defaultValueArray[] = $eavDefaultValue->getId();
                            }
                        }
                        $this->getWidget($field['id'])->setDefault($defaultValueArray);
                    }
                }
                else
                {
                    $defaultValues = array();
                    if (isset($values[$field['id']]))
                    {
                        $defaultValues = $values[$field['id']];
                    }
                    else
                    {
                        foreach ($eavValues as $eavValue)
                        {
                            if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                            {
                                $defaultValues[] = $eavValue->getEavFullValueId();
                            }
                        }
                    }
                    $this->getWidget($field['id'])->setDefault($defaultValues);
                }
                break;
            case "radio":
                $choices = array();
                foreach ($field['values'] as $item)
                {
                    $choices[$item['id']] = $item['value'];
                }
                $options = array('choices' => $choices, 'expanded' => true,
                    'multiple' => false);
                $this->widgetSchema[$field['id']] = new sfWidgetFormChoice($options);
                unset($options['expanded']);
                $this->validatorSchema[$field['id']] = new sfValidatorChoice($options);
                $this->getWidget($field['id'])->setLabel($field['title']);
                if ($this->isNew())
                {
                    if (isset($values[$field['id']]))
                    {
                        $this->getWidget($field['id'])->setDefault($values[$field['id']]);
                    }
                    else
                    {
                        foreach ($eavDefaultValues as $eavDefaultValue)
                        {
                            if ($eavDefaultValue->getEav()->getId() == $field['id'] && $eavDefaultValue->getIsSelected())
                            {
                                $this->getWidget($field['id'])->setDefault($eavDefaultValue->getId());
                            }
                        }
                    }
                }
                else
                {
                    if (isset($values[$field['id']]))
                    {
                        $this->getWidget($field['id'])->setDefault($values[$field['id']]);
                    }
                    else
                    {
                        foreach ($eavValues as $eavValue)
                        {
                            if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                            {
                                $this->getWidget($field['id'])->setDefault($eavValue->getEavFullValueId());
                            }
                        }
                    }
                }
                break;
            case 'select':
                $choices = array();
                foreach ($field['values'] as $item)
                {
                    $choices[$item['id']] = $item['value'];
                }
                $options = array('choices' => $choices, 'expanded' => false,
                    'multiple' => false);
                $this->widgetSchema[$field['id']] = new sfWidgetFormChoice($options);
                unset($options['expanded']);
                $this->validatorSchema[$field['id']] = new sfValidatorChoice($options);
                $this->getWidget($field['id'])->setLabel($field['title']);
                if ($this->isNew())
                {
                    if (isset($values[$field['id']]))
                    {
                        $this->getWidget($field['id'])->setDefault($values[$field['id']]);
                    }
                    else
                    {
                        foreach ($eavDefaultValues as $eavDefaultValue)
                        {
                            if ($eavDefaultValue->getEav()->getId() == $field['id'] && $eavDefaultValue->getIsSelected())
                            {
                                $this->getWidget($field['id'])->setDefault($eavDefaultValue->getId());
                            }
                        }
                    }
                }
                else
                {
                    if (isset($values[$field['id']]))
                    {
                        $this->getWidget($field['id'])->setDefault($values[$field['id']]);
                    }
                    else
                    {
                        foreach ($eavValues as $eavValue)
                        {
                            if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                            {
                                $this->getWidget($field['id'])->setDefault($eavValue->getEavFullValueId());
                            }
                        }
                    }
                }
                break;
            case "label":
                $this->widgetSchema[$field['id']] = new sfWidgetFormLabel();
                $this->getWidget($field['id'])->setLabel($field['title']);
                $this->getWidget($field['id'])->setDefault($field['values'][0]['value']);
                break;
            default:
                break;
        }
    }

    /**
     * Check is new form
     *
     * @return boolean
     */
    public function isNew()
    {
        $options = $this->getDefaults();
        return $options['destination_entity_id'] == '' ? TRUE : FALSE;
    }

}