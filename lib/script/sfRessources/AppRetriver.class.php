<?php

/**
 * AppRetriver.class.php
 *
 * PHP version 5
 *
 * @category Script
 * @package  AppRetriver
 * @author   ALI Hichem <ali.hichem@mail.com>
 */

/**
 * AppRetriver
 *
 * @category Script
 * @package  AppRetriver
 * @author   ALI Hichem <ali.hichem@mail.com>
 */
interface Retriver
{
    public static function retriveByName($name);

    public static function retriveById($id);
}

class AppRetriver
{

    private $id;
    private $model;

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