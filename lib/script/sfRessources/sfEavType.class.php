<?php

/**
 * sfEavType.class.php
 *
 * PHP version 5
 *
 * @category Script
 * @package  SfEavType
 * @author   ALI Hichem <ali.hichem@mail.com>
 */

/**
 * sfEavType
 *
 * @category Script
 * @package  SfEavType
 * @author   ALI Hichem <ali.hichem@mail.com>
 */
class sfEavType extends AppRetriver implements Retriver
{

    private $id;
    private $name;
    private $shortTypes = array("input_text", 'textarea');
    const SOURCE = "/sfEntityAttributeValuePlugin/config/eav_types.yml";

    /**
     * Return title fields
     * 
     * @return string
     */
    public function getTitleField()
    {
        return $this->isShortType() ? "values" : "title";
    }

    /**
     * Check if is short type
     * 
     * @return boolean
     */
    public function isShortType()
    {
        return in_array($this->name, $this->shortTypes);
    }

    /**
     * Class constructor
     *
     * @param <type> $name nom
     *
     * @return void
     */
    public function __construct($key)
    {
        $options = sfYaml::load(sfConfig::get('sf_plugins_dir').self::SOURCE); 
        $this->id = $options[$key]['id'];
        $this->name = $key;
    }

    /**
     * Retrive by name
     *
     * @param object $name nom
     *
     * @return object
     */
    public static function retriveByName($name)
    {
        return new sfEavType($name);
    }

    /**
     * Retrive by Id
     *
     * @param object $name nom
     *
     * @return object
     */
    public static function retriveById($id)
    {
        $options = sfYaml::load(sfConfig::get('sf_plugins_dir').self::SOURCE); 
        foreach ($options as $name => $option)
        {
            if ($option['id'] == $id)
            {
                return new sfEavType($name);
            }
        }
    }

    /**
     * Returns $id.
     *
     * @see sfRessource::$id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets $id.
     *
     * @param object $id identifiant
     *
     * @see sfRessource::$id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns $name.
     *
     * @see sfEavType::$name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets $name.
     *
     * @param object $name nom
     *
     * @see sfEavType::$name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}