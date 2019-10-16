<?php

namespace Rashidul\Chamomile\Services;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Rashidul\Chamomile\Helper\ConfigReader;
use Rashidul\Chamomile\Helper\ModelHelper;
use Illuminate\Container\Container;

class Crud
{

    use ValidatesRequests;

    protected $model;

    protected $config;

    protected $request;

    protected $resource;

    protected $configReader;

    protected $container;

    protected $responseData = [];
    /**
     * Crud constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
        $this->resource = $request->route('resource');
        $this->config = $this->getConfig($this->resource);
        $this->model = new $this->config['model'];
        $this->configReader = new ConfigReader($this->config);
        $this->container = Container::getInstance();
    }

    public function store()
    {
        try{
            $this->validate($this->request, $this->configReader->getvalidationRules(), [], $this->configReader->getFieldsWithLabels());
        } catch (\Exception $exception) {
            throw new \Exception("Validation error");
        }
        $this->model = ModelHelper::fillWithRequestData($this->model, $this->configReader->getFields(), $this->request);

        $this->callHookMethod('storing');

        try{
            if ($this->model->save()){
                $this->responseData['success'] = true;
                $this->responseData['message'] = $this->configReader->getEntityName() . ' Created!';
                $this->responseData['item'] = $this->model;

                // many to many
//                ModelHelper::updateManyToManyRelations($this->model, $this->request);

                $this->callHookMethod('stored');

            } else {
                $this->responseData['success'] = false;
                $this->responseData['message'] = 'Something went wrong';
            }
        } catch (QueryException $e){
            $this->responseData['success'] = false;
            $this->responseData['message'] = 'Something went wrong';
        }

        return $this->responseData;
    }

    public function index()
    {
        return [
            'success' => true,
            'message' => 'Fetched data',
            'data' => $this->model::all()
        ];
    }

    public function show($id)
    {
        return [
            'success' => true,
            'message' => 'Fetched data',
            'data' => $this->model::findOrFail($id) //TODO handle invalid ids
        ];
    }

    public function delete($id)
    {
        $model = $this->model::findOrFail($id);
        $model->delete(); //TODO try catch

        return [
            'success' => true,
            'message' => $this->config['name'] . ' deleted!'
        ];
    }

    public function update($id)
    {
        try
        {
            $this->model = $this->model->findOrFail($id);
        }
        catch (\Exception $e)
        {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        try{
            $this->validate($this->request, $this->configReader->getvalidationRules(), [], $this->configReader->getFieldsWithLabels());
        } catch (\Exception $exception) {
            throw new \Exception("Validation error");
        }
        $this->model = ModelHelper::fillWithRequestData($this->model, $this->configReader->getFields(), $this->request);

        $this->callHookMethod('updating');

        try{
            if ($this->model->update()){
                $this->responseData['success'] = true;
                $this->responseData['message'] = $this->configReader->getEntityName() . ' Updated';
                $this->responseData['item'] = $this->model;

                $this->callHookMethod('updated');

            } else {
                $this->responseData['success'] = false;
                $this->responseData['message'] = 'Something went wrong';
            }
        } catch (QueryException $e){
            $this->responseData['success'] = false;
            $this->responseData['message'] = 'Something went wrong';
        }

        return $this->responseData;

    }

    public function config()
    {
        $config = ConfigFormatter::format($this->config);
        return [
            'success' => true,
            'message' => 'Success',
            'config' => $config //TODO reformat the fields array, i.e. change string shortcuts to
            // proper key value pair
        ];
    }
    private function getConfig($resource)
    {
        //TODO check if all required keys are defined in config.php file
        $basepath = config('chamomile.base_path');
        $configFile = "$basepath/$resource/config.php"; //TODO check of resurce is valid
        $config = [];
        try {
            if (!empty($configFile)) {
                $config = include $configFile;
            }
        } catch (\Exception $e) {
            //TODO use a global handler to return 'invalid resource' error
        }
        return $config;
    }

    protected function callHookMethod($name)
    {
        if (method_exists($this, $name))
        {
            $this->container->call([$this, $name]);
        }
    }
}