<?php

/**
 * sfRessource.class.php
 *
 * PHP version 5
 *
 * @category Script
 * @package  SfRessource
 * @author   ALI Hichem <ali.hichem@mail.com>
 */

/**
 * sfRessource
 *
 * @category Script
 * @package  SfRessource
 * @author   ALI Hichem <ali.hichem@mail.com>
 */
class sfRessource extends AppRetriver implements Retriver
{

    private $id;
    private $model;
    const SOURCE = "/sfEntityAttributeValuePlugin/config/resources.yml";

    /**
     * Class constructor
     *
     * @param <type> $name nom
     *
     * @return void
     */
    public function __construct($key)
    {
        $resources = sfYaml::load(sfConfig::get('sf_plugins_dir').self::SOURCE);        
        $this->id = $resources[$key]['id'];
        $this->model = $key;
        return;
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
        return new sfRessource($name);
    }

    /**
     * Retrive by Id
     *
     * @param object $id 
     *
     * @return object
     */
    public static function retriveById($id)
    {
        $resources = sfYaml::load(sfConfig::get('sf_plugins_dir').self::SOURCE);        
        foreach ($resources as $name => $options)
        {
            if ($resources[$name]['id'] == $id)
            {
                return new sfRessource($name);
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
     * Returns $model.
     *
     * @see sfRessource::$model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets $model.
     *
     * @param object $model nom du model
     *
     * @see sfRessource::$model
     *
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

}
