<?php

namespace App\Services;

use App\Models\Answer;
use Arr;
use Carbon\Carbon;

class AnswerService
{

    public function answerList($assignment_id)
    {
        $results = Answer::select(['answer.*', 'user_info.fullname as student_name'])
            ->join('assignment', 'assignment.question_id', 'answer.assignment_id')
            ->join('users', 'users.id', 'answer.student_id')
            ->join('user_info', 'user_info.user_id', 'users.id')
            ->where('answer.assignment_id', $assignment_id)
            ->get()
            ->toArray();
        return $results;
    }

    public function answerDetail($answer_id)
    {
        $results = Answer::select(['user_info.fullname as student_name', 'answer.*'])
            ->join('users', 'users.id', 'answer.student_id')
            ->join('user_info', 'user_info.user_id', 'users.id')
            ->where('answer.id', $answer_id)
            ->first();
        return $results ?? [];
    }

    public function answerUpdate($answer_id, $requests)
    {
        $answer_update = [
            'assignment_id' => Arr::get($requests, 'assignment_id'),
            'student_id' => Arr::get($requests, 'student_id'),
            'is_submit' => Arr::get($requests, 'is_submit'),
            'content' => Arr::get($requests, 'content'),
            'review' => Arr::get($requests, 'review'),
            'timeline' => Arr::get($requests, 'timeline'),
            'score' => Arr::get($requests, 'score'),
            'submit_at' => Carbon::now(),
        ];
        return Answer::where('answer.id', $answer_id)->update($answer_update);
    }


}
