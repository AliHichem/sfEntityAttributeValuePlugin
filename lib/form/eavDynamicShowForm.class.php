<?php

/**
 * eavDynamicShowForm.class.php
 *
 * PHP version 5
 *
 * @category Form
 * @package  Eav
 * @author   ALI Hichem <ali.hichem@mail.com>
 */

/**
 * eavDynamicShowForm
 *
 * @category Form
 * @package  Eav
 * @author   ALI Hichem <ali.hichem@mail.com>
 */
class eavDynamicShowForm extends BaseForm
{
    const FORM_NAME = 'eav_Dynamics_Show';

    /**
     * configure the from
     *
     * @return void
     */
    public function configure()
    {
        $options = $this->getDefaults();
        $nameFormat = self::FORM_NAME;
        $nameFormat .= "[" . $options['source_ressource_id'] . "-" . $options['source_entity_id'] . "]";
        $nameFormat .= "[" . $options['destination_ressource_id'] . "-" . $options['destination_entity_id'] . "]";
        $nameFormat .= "[%s]";
        $this->widgetSchema->setNameFormat($nameFormat);
        $this->disableCSRFProtection();
    }

    /**
     * Configure les champs
     *
     * @param <type> $formStructure structure du formulaire
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
     * @param <type> $field champ
     *
     * @return void
     */
    public function configureField($field, $values)
    {
        $options = $this->getDefaults();
        $eavDefaultValues = $this->eavDefaultValues;
        $eavValues = $this->eavValues ;
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

                $defaultValues = array();
                foreach ($eavValues as $eavValue)
                {
                    if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                    {
                        $defaultValues[] = $eavValue->getEavFullValues()->getValue();
                    }
                }
                $defaultValues = implode(', ', $defaultValues);
                $this->getWidget($field['id'])->setDefault($defaultValues);

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
                
                foreach ($eavValues as $eavValue)
                {
                    if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                    {
                        $this->getWidget($field['id'])->setDefault($eavValue->getEavFullValues()->getValue());
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
                
                foreach ($eavValues as $eavValue)
                {
                    if ($eavValue->getEavFullValues()->getEav()->getId() == $field['id'])
                    {
                        $this->getWidget($field['id'])->setDefault($eavValue->getEavFullValues()->getValue());
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

}