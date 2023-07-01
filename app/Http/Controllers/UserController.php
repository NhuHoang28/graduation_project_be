<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ClassService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @var \App\Services\ClassService $class_service
     */
    public $class_service;
    /**
     * @var \App\Services\UserService $service
     */
    public $service;

    public function __construct(UserService $service, ClassService $class_service)
    {
        $this->service = $service;
        $this->class_service = $class_service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $is_teacher = $user->role_id === config('user.account_type_id.teacher');
        if ($is_teacher) {
        }
    }

    /**
     * get user information of the currently logged.
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function getMe(Request $request)
    {
        $info = $this->service->getMe($request->user()->id);
        return response()->json([
            'data' => $info
        ]);
    }
}
