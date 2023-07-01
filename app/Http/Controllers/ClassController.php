<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\Classes;
use App\Services\ClassService;
use Illuminate\Http\Request;

class ClassController extends Controller
{

    public $class_service;

    public function __construct(ClassService $class_service)
    {
        $this->class_service = $class_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $classes = $this->class_service->getListClass($params, $request->user()->id);
        return response()->json(['data' => $classes]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = Classes::find($id);
        return response()->json(['data' => $info]) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ClassRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ClassRequest $request, $id)
    {
        $class = Classes::findOrFail($id);
        $class->name = \Arr::get($request, 'name', null);
        $class->save();
        return response()->json(['message' => 'Class update successfully']);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Classes::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Class deleted successfully']);
    }
}
