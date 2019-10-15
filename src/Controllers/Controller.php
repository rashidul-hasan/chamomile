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
        //TODO permission, authorization, jwt token
        $data = $this->crud->index();

        return response()->json($data);
    }

    public function show($resource, $id)
    {

        $data = $this->crud->show($id);

        return response()->json($data);

    }

    public function delete($resource, $id)
    {
        //TODO handle invalid ids
        $data = $this->crud->delete($id);

        return response()->json($data);
    }

    public function update($resource, $id)
    {
        //TODO handle invalid ids
        $data = $this->crud->update($id);

        return response()->json($data);
    }

    public function config()
    {
        //$config = ConfigFormatter::format($this->config);

        $data = $this->crud->config();

        return response()->json($data);
    }



}
