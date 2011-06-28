<?php

/**
 * init-config Task.
 *
 * @package    eav
 * @subpackage Task
 * @author     Ali hichem
 */
class initConfigTask extends sfBaseTask
{

    protected $models,
    $tables;

    /**
     * configure
     * @return 
     */
    protected function configure()
    {
        $this->namespace = 'eav';
        $this->name = 'init-config';
        $this->briefDescription = "Initialize sfEntityAttributeValuePlugin configuration files";
        $this->detailDescription = <<<EOF
The [eav:init:config]  task will add (or update) configuration file from your schema.yml.
Note: Every time schema.yml is modified , you will have the run this task
EOF;
    }

    /**
     * execute and print on the terminal screen the results of task
     * 
     * @param object $arguments [optional]
     * @param object $options [optional]
     * @return void 
     */
    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $this->logSection("Process",'Start generating configs...');
        $this->schemaDir = sfConfig::get('sf_config_dir') . "/doctrine/schema.yml";
        $this->pluginConfDir = sfConfig::get('sf_plugins_dir') . "/sfEntityAttributeValuePlugin/config";
        try
        {
            $this->resourcesFile = $this->pluginConfDir . "/resources.yml";
            $this->buildResourcesFileIfNotExists();
            $resources = sfYaml::load($this->resourcesFile);
            $schemaModels = $this->retriveSchemaModels();
            if (is_array($schemaModels))
            {
                if (is_array($resources))
                {
                    $schemaModels = array_diff($schemaModels,array_keys($resources));
                }
                $this->updateResourceFile($schemaModels);
            }
            $this->logSection('Precess', "end");
        }
        catch (exception $e)
        {
            $this->logBlock($e->getMessage(), 'ERROR_LARGE');
        }
    }

    /**
     * Retrive model from all schema files
     * @return type 
     */
    public function retriveSchemaModels()
    {
        $schemaModels = array();
        $finder = sfFinder::type('file')->name('schema.yml');
        $schemaPaths = $this->retriveDoctrineSchemas();
        foreach ($finder->in($schemaPaths) as $item)
        {
            $schema = sfYaml::load($item);
            if (is_array($schema))
            {
                $schemaModels = array_merge(array_keys($schema),$schemaModels);
            }
        }
        return array_unique($schemaModels);
    }
    
    /**
     * Build resource file if not exists
     * 
     * @return
     */
    protected function buildResourcesFileIfNotExists()
    {
        $resourceFile = $this->resourcesFile ;
        if (!is_file($resourceFile))
        {
            $fileHandle = fopen($resourceFile, 'w');
            $content = "# sfEntityAttributeValuePlugin - resource file \n\n";
            fwrite($fileHandle, $content);
            fclose($fileHandle);
        }
    }

    /**
     * Retrive doctrine schema files
     * 
     * @return string 
     */
    public function retriveDoctrineSchemas()
    {
        $schemas = array(sfConfig::get('sf_config_dir').'/doctrine');
        $dir = sfConfig::get('sf_plugins_dir');
        $ignores = array('.channels','.registry');
        $finder = sfFinder::type('directory')->maxdepth(0)->sort_by_name()->ignore_version_control()->discard($ignores)->prune($ignores) ;
        foreach ($finder->in($dir) as $path)
        {
            if (is_file($path.'/config/doctrine/schema.yml'))
            {
                $schemas[] = $path.'/config/doctrine' ;
            }
        }
        return $schemas;
    }
    
    /**
     * Update resource file
     * 
     * @param type $schemaModels 
     * @return 
     */
    public function updateResourceFile($schemaModels)
    {
        $lastId = $this->retriveLastRessourceId();
        $resourceFile = $this->resourcesFile ;
        if (is_file($resourceFile))
        {
            try
            {
                if (!empty($schemaModels))
                {
                    $fileHandle = fopen($resourceFile, 'a');
                    $i = $lastId + 1;
                    foreach ($schemaModels as $model)
                    {
                        usleep(200000);
                        $this->logSection("New resource found" ," '{$model}', affected id : '{$i}' ");
                        fwrite($fileHandle, "\n{$model}:\n  id: {$i}");
                        $i ++;
                    }
                    fclose($fileHandle);
                } 
                else 
                {
                    $this->logSection('Result', "No new resource found");
                }
            }
            catch (exception $e)
            {
                throw "\nError when creating file [{$resourceFile}] : \n" . $e->getMessage();
            }
        }
    }
    
    /**
     * Retrive last resource Id
     * 
     * @return type 
     */
    public function retriveLastRessourceId()
    {
        $this->resourcesFile = $this->pluginConfDir . "/resources.yml";
        $resources = sfYaml::load($this->resourcesFile);
        if (is_array($resources))
        {
            $lastItem = end($resources);
            return $lastItem['id'];
        }
        else
        {
            return 0;
        }
    }
}

