<?php

namespace Rashidul\Chamomile\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Routing\Controller as BaseController;
use Rashidul\Chamomile\Services\Crud;

//TODO add jqt token authorization for all endpoint
class Controller extends BaseController
{

    private $crud;


    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->crud = new Crud($request);
            return $next($request);
        });
    }


    public function store()
    {

        //TODO permission, authorization, jwt token
        $data = $this->crud->store();

        return response()->json($data);
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



}
