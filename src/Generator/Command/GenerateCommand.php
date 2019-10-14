<?php

namespace Rashidul\Chamomile\Generator\Command;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Rashidul\RainDrops\Generator\Helper;

class GenerateCommand extends Command
{

    protected $signature = 'chamomile:init
                            {entity : The name of the API.}
                            {--fields= : Fields for the tables.}';

    protected $description = 'Generate an API';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $entity = $this->argument('entity');
        $basepath = config('chamomile.base_path');

        $modelNamespace = $this->getModelNamespace($entity);


        // create config.php
        $configFile = "$basepath/$entity/config.php";
        $stub = $this->files->get($this->getConfigStub());
        $stub = str_replace('{{Name}}', $entity, $stub);
        $stub = str_replace('{{url}}', strtolower($entity), $stub);
        $stub = str_replace('{{modelNamespace}}', $modelNamespace, $stub);
        $stub = str_replace('{{modelName}}', $entity, $stub);
        if (! $this->files->isDirectory(dirname($configFile))) {
            $this->files->makeDirectory(dirname($configFile), 0777, true, true);
        }
        $this->files->put($configFile, $stub);

        // create model
        $modelFile = "$basepath/$entity/$entity.php";
        $stub = $this->files->get($this->getModelStub());
        $stub = str_replace('{{modelNamespace}}', $modelNamespace, $stub);
        $stub = str_replace('{{modelName}}', $entity, $stub);
        if (! $this->files->isDirectory(dirname($modelFile))) {
            $this->files->makeDirectory(dirname($modelFile), 0777, true, true);
        }
        $this->files->put($modelFile, $stub);
        /*$dir = str_plural($entity);
        $namespace = "Crud\\$dir";

        // create controller
        $controllerPath = base_path() . '/crud/' . $dir . '/CrudController.php';
        $stub = $this->files->get($this->getStub());
        $stub = str_replace('DummyNamespace', $namespace, $stub);
        $stub = str_replace('DummyClass', "CrudController", $stub);
        $stub = str_replace('{{modelName}}', $entity, $stub);

        if (! $this->files->isDirectory(dirname($controllerPath))) {
            $this->files->makeDirectory(dirname($controllerPath), 0777, true, true);
        }
        $this->files->put($controllerPath, $stub);

        // create model
        $modelPath = base_path() . '/crud/' . $dir . '/' . $entity .'.php';
        $stub = $this->files->get($this->getModelStub());
        $stub = str_replace('DummyNamespace', $namespace, $stub);
        $stub = str_replace('DummyClass', $entity, $stub);
        $fields = $this->generateFieldsArray(rtrim($this->option('fields'), ';'));
        $helper =  new Helper();
        $fieldsStr = $helper->arrayAsString($fields);
        $stub = str_replace('{{fields}}', $fieldsStr, $stub);
        $this->files->put($modelPath, $stub);*/

        $this->info('You\'re Done! Yeee!');


    }

    protected function getConfigStub()
    {
        /*return config('raindrops.crud.generator.custom_template')
            ? config('raindrops.crud.generator.stubs') . '/controller.stub'
            : __DIR__ . '/../stubs/config.stub';*/
        return __DIR__ . '/../stubs/config.stub';
    }

    protected function getModelStub()
    {
        /*return config('raindrops.crud.generator.custom_template')
            ? config('raindrops.crud.generator.stubs') . '/model.stub'
            : __DIR__ . '/../stubs/model.stub';*/

        return __DIR__ . '/../stubs/model.stub';
    }

    protected function generateFieldsArray($option)
    {
        $fields = explode(';', $option);


        $data = [];

        if ($fields) {
            foreach ($fields as $field) {

                $fieldArray = explode('#', $field);

                $fieldOptionsArray = [];

                $fieldName = trim($fieldArray[0]);

                // build options array
                //label
                $fieldOptionsArray['label'] = ucwords(str_replace("_", " ", $fieldName));

                // type
                if (isset($fieldArray[1]))
                {
                    $fieldOptionsArray['type'] = trim($fieldArray[1]);
                }

                // select options
                // syntax: name#select#option1,option2,option3
                if ( isset($fieldArray[1]) && $fieldArray[1] === 'select')
                {
                    $fieldOptionsArray['type'] = trim($fieldArray[1]);

                    $fieldOptionsArray['options'] = [];
                    // if options are provided for the select type
                    // those will be in the 3rd key
                    if (isset($fieldArray[2]))
                    {
                        $options = [];
                        $optionArray = explode(',', $fieldArray[2]);
                        foreach ($optionArray as $option)
                        {
                            $options[$option] = str_replace('_', ' ', ucwords($option));
                        }
                        $fieldOptionsArray['options'] = $options;
                    }
                }

                // currency
                if ( isset($fieldArray[1]) && $fieldArray[1] === 'currency')
                {
                    $fieldOptionsArray['type'] = trim($fieldArray[1]);

                    //$fieldOptionsArray['options'] = [];
                    // if options are provided for the select type
                    // those will be in the 3rd key
                    if (isset($fieldArray[2]))
                    {
                        //$options = [];
                        $optionArray = explode(',', $fieldArray[2]);
                        /*foreach ($optionArray as $option)
                        {
                            $options[$option] = str_replace('_', ' ', ucwords($option));
                        }*/
                        $fieldOptionsArray['precision'] = (int) $optionArray[1];
                    }

                    // if format option is provided
                    if (isset($fieldArray[3]))
                    {
                        $fieldOptionsArray['format'] = $fieldArray[3];
                    }
                }

                // type checkbox
                // syntax: field_name#checkbox
                if ( isset($fieldArray[1]) && $fieldArray[1] === 'checkbox')
                {
                    // need to add a $casts array in the model
                    // and set this field to cast as boolean
                    $fieldOptionsArray['type'] = trim($fieldArray[1]);
                    $this->casts[$fieldName] = 'boolean';
                }


                $data[$fieldName] = $fieldOptionsArray;

            }
        }

        return $data;

    }

    private function getModelNamespace($entity)
    {
        $base_namespace = config('chamomile.base_namespace');
        return $base_namespace . '\\' . $entity;

    }

}
