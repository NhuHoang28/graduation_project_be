<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuestionService;

class QuestionController extends Controller
{

    /**
     * @var \App\Services\QuestionService $service
     */
    public $service;

    public function __construct(QuestionService $service) {
        $this->service = $service;
    }

    public function store(Request $request) {
        $data = $request->all();
        $result = $this->service->createQuestion($data, $request->user()->id);
        if (!$result) {
            return response()->json([
                'message' => 'something went wrong'
            ], 500);
        }
        return response()->json($result, 200);
    }

    public function libraryList(Request $request)
    {
        $params = $request->all();
        $library_list = $this->service->libraryList($params, $request->user()->id);
        return response()->json([
            'data' => $library_list
        ], 200);
    }

    public function questionList(Request $request)
    {
        $params = $request->all();
        $question_list = $this->service->questionList($params, $request->user()->id);
        return response()->json([
            'data' => $question_list
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $question_list = $this->service->questionDetail($id);
        return response()->json([
            'data' => $question_list
        ], 200);
    }

    function updateQuestion(Request $request, $id) {
        $params = $request->all();
        $results = $this->service->updateQuestion($params, $id);
        if (!$results) {
            return response()->json([
                'message' => 'something went wrong'
            ], 500);
        }
        return response()->json([
            'message' => 'Successfully'
        ], 200);
    }
}
