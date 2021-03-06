<?php

/**
 * PluginEavTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginEavTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PluginEavTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PluginEav');
    }
    
    
    /**
     * Return Eav structure as a table
     * 
     * @param int $ressource_id resouce id
     * @param int $entity_id    entity id 
     *
     * @return array
     */
    public static function buildFormStructure($ressource_id, $entity_id)
    {
        $formStructure = array();
        $q = Doctrine_Query::create()
                        ->from('Eav e')
                        ->innerJoin('e.EavGroups eg')
                        ->innerJoin('e.EavFullValues efv')
                        ->where(sprintf("eg.ressource_id = '%s'", $ressource_id))
                        ->andWhere(sprintf("eg.entity_id = '%s'", $entity_id));
        if ($q->count() > 0)
        {
            $eavs = $q->execute();
            foreach ($eavs as $eav)
            {
                $eavType = sfEavType::retriveById($eav->getEavTypeId());
                $_eav = array(
                    "id" => $eav->getId(),
                    "multiple" => '',
                    "class" => $eavType->getName(),
                    "required" => "false"
                );
                if ($eavType->isShortType())
                {
                    //$fullValues = Doctrine_core::getTable('EavFullValues')->findOneByEavId($eav->getId());
                    $_eav['values'] = $eav->getLabel();
                }
                else
                {
                    $_eav['title'] = $eav->getLabel();
                    $_fullValues = array();
                    $fullValues = $eav->getEavFullValues(); //Doctrine_core::getTable('EavFullValues')->findByEavId($eav->getId());
                    foreach ($fullValues as $fullValue)
                    {
                        $isDefault = $fullValue->getIsSelected() ? 'true' : 'false';
                        $_fullValues[] = array(
                            "id" => $fullValue->getId(),
                            "value" => $fullValue->getValue(),
                            "default" => $isDefault);
                    }
                    $_eav['values'] = $_fullValues;
                }
                $formStructure[] = $_eav;
            }
        }
        return $formStructure;
    }
}