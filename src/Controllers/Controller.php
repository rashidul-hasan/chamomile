<?php

namespace Rashidul\Chamomile\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

//TODO add jqt token authorization for all endpoint
class Controller
{

    public function store(Request $request, $resource)
    {

        $config = $this->getConfig($resource);

        // get model class
        $model = new $config['model'];

        $model::create([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'success' => true,
            'message' => $config['name'] . ' created!'
        ]);
    }

    public function index(Request $request, $resource)
    {

        $config = $this->getConfig($resource);

        $model = new $config['model'];

        //TODO format data for datatable
        //TODO search, filter, pagination
        return response()->json([
            'success' => true,
            'message' => 'Fetched data',
            'data' => $model::all()
        ]);
    }

    public function show($resource, $id)
    {

        $config = $this->getConfig($resource);

        $model = new $config['model'];

        return response()->json([
            'success' => true,
            'message' => 'Fetched data',
            'data' => $model::findOrFail($id) //TODO handle invalid ids
        ]);
    }

    public function delete($resource, $id)
    {
        //TODO handle invalid ids
        $config = $this->getConfig($resource);

        $modelClass = new $config['model'];
        $model = $modelClass::findOrFail($id);
        $model->delete(); //TODO try catch

        return response()->json([
            'success' => true,
            'message' => $config['name'] . ' deleted!'
        ]);
    }

    public function update(Request $request, $resource, $id)
    {
        //TODO handle invalid ids
        $config = $this->getConfig($resource);

        $modelClass = new $config['model'];
        $model = $modelClass::findOrFail($id);
        $model->update([
            'name' => $request->get('name')
        ]); //TODO try catch

        return response()->json([
            'success' => true,
            'message' => $config['name'] . ' updated!'
        ]);
    }

    public function config($resource)
    {
        $config = $this->getConfig($resource);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'config' => $config //TODO reformat the fields array, i.e. change string shortcuts to
            // proper key value pair
        ]);
    }


    private function getConfig($resource)
    {
        $basepath = config('chamomile.base_path');
        $configFile = "$basepath/$resource/config.php"; //TODO check of resurce is valid
        $config = [];
        if (!empty($configFile)) {
            $config = include $configFile;
        }
        return $config;
    }

}
