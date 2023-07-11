<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Group;
use App\Models\User;
use Arr;

class StudentService
{

    public function allAnswer($group_id, $assignment_id, $user_id)
    {
        $results = Answer::select(['answer.*', 'user_info.fullname as student_name'])
            ->join('assignment', 'assignment.id', 'answer.assignment_id')
            ->join('users', 'users.id', 'answer.student_id')
            ->join('user_info', 'user_info.user_id', 'users.id')
            ->where('assignment.id', $assignment_id)
            ->get()
            ->toArray();
        return $results ?? [];
    }
    public function answerDetail($group_id, $assignment_id, $user_id)
    {
        $results = Answer::select('answer.*')
            ->join('assignment', 'assignment.id', 'answer.assignment_id')
            ->join('users', 'users.id', 'answer.student_id')
            ->join('group', 'assignment.group_id', 'group.id')
            ->join('user_info', 'user_info.user_id', 'users.id')
            ->where('group.id', $group_id)
            ->where('assignment.id', $assignment_id)
            ->where('users.id', $user_id)
            ->first();
        return $results;
    }

    function listGroupOfStudent($user_id) {
        return Group::select(['group.*'])
            ->join('student_group', 'group.id', 'student_group.group_id')
            ->join('users', 'student_group.user_id', 'users.id')
            ->where('users.id', $user_id)
            ->get()
            ->toArray();
    }

    public function createAnswer($data)
	{
		return Answer::create($data);
	}

    public function StudentlistScore($group_id,  $user_id)
    {

        $scores = Group::select([
            'group.name as group_name',
            'assignment.id as assignment_id',
            'assignment.title',
            'assignment_type.name',
            'user_info.fullname as student_name',
            'answer.score',
            'student_id',
        ])
            ->join('assignment', 'assignment.group_id', 'group.id')
            ->join('assignment_type', 'assignment_type.id', 'assignment.assignment_type_id')
            ->join('answer', 'answer.assignment_id', 'assignment.id')
            ->join('users', 'users.id', 'answer.student_id')
            ->join('student_group', 'student_group.user_id', 'users.id')
            ->join('user_info', 'users.id', 'user_info.user_id')
            ->where('group.id', $group_id)
            ->where('student_id', $user_id)
            ->get()
            ->toArray();

        $results = [];
        foreach ($scores as $score) {
            $key = $score['assignment_id'];
            if (!isset($results['scores'][$key])) {
                $results['scores'][$key][] = $score['name'];
                $results['scores'][$key][] = $score['title'];
                $results['scores'][$key][] = $score['score'];
            }
        }
        return $results;
    }

}
