<?php

namespace App\Http\Controllers;

use App\Services\AnswerService;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * @var \App\Services\AnswerService $service
     */
    public $service;

    public function __construct(AnswerService $service) {
        $this->service = $service;
    }

    public function answerList($group_id, $assignment_id)
    {
        $results = $this->service->answerList($assignment_id);
        return response()->json([
            'data' => $results
        ], 200);
    }

    public function answerDetail($group_id, $assignment_id, $answer_id)
    {
        $results = $this->service->answerDetail($answer_id);
        return response()->json([
            'data' => $results
        ], 200);
    }

    public function answerUpdate(Request $request, $group_id, $assignment_id, $answer_id)
    {
        $requests = $request->all();
        $results = $this->service->answerUpdate($answer_id, $requests);
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
