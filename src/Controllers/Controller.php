<?php

namespace Rashidul\Chamomile\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Routing\Controller as BaseController;

//TODO add jqt token authorization for all endpoint
class Controller extends BaseController
{

    protected $model;

    protected $config;

    protected $request;

    protected $resource;


    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->request = $request;
            $this->resource = $request->route('resource');
            $this->config = $this->getConfig($this->resource);
            $this->model = new $this->config['model'];

            return $next($request);
        });
    }


    public function store()
    {

        $this->model::create([
            'name' => $this->request->get('name')
        ]);

        return response()->json([
            'success' => true,
            'message' => $this->config['name'] . ' created!'
        ]);
    }

    public function index()
    {

        //TODO format data for datatable
        //TODO search, filter, pagination
        return response()->json([
            'success' => true,
            'message' => 'Fetched data',
            'data' => $this->model::all()
        ]);
    }

    public function show($resource, $id)
    {

        return response()->json([
            'success' => true,
            'message' => 'Fetched data',
            'data' => $this->model::findOrFail($id) //TODO handle invalid ids
        ]);
    }

    public function delete($resource, $id)
    {
        //TODO handle invalid ids
        $model = $this->model::findOrFail($id);
        $model->delete(); //TODO try catch

        return response()->json([
            'success' => true,
            'message' => $this->config['name'] . ' deleted!'
        ]);
    }

    public function update($resource, $id)
    {
        //TODO handle invalid ids
        $model = $this->model::findOrFail($id);
        $model->update([
            'name' => $this->request->get('name')
        ]); //TODO try catch

        return response()->json([
            'success' => true,
            'message' => $this->config['name'] . ' updated!'
        ]);
    }

    public function config()
    {
        //$config = ConfigFormatter::format($this->config);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'config' => $this->config //TODO reformat the fields array, i.e. change string shortcuts to
            // proper key value pair
        ]);
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

}
