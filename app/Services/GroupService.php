<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Group;
use App\Models\Question;
use Arr;
use Carbon\Carbon;

class GroupService
{

    public function getGroupList($params, int $id)
    {
        $limit = Arr::get($params, 'limit', config('pagination.limit'));
        $sort = Arr::get($params, 'sort', config('pagination.sort'));
        $order = Arr::get($params, 'order', config('pagination.order'));
        return Group::select(['group.id', 'group.name', 'users.id as teacher_id', 'user_info.fullname as teacher_name'])
            ->join('users', 'users.id', 'group.user_id')
            ->join('user_info', 'users.id', 'user_info.user_id')
            ->where('users.id', $id)
            ->orderBy($order, $sort)
            ->paginate($limit);
    }

    public function listStudentInGroup($params, int $id)
    {
        $limit = Arr::get($params, 'limit', config('pagination.limit'));
        $sort = Arr::get($params, 'sort', config('pagination.sort'));
        $order = Arr::get($params, 'order', config('pagination.order'));
        return Group::select([
            'users.id',
            'users.id as student_id',
            'user_info.fullname as student_name',
            'user_info.address',
            'user_info.phone_number',
            'user_info.email',
            'user_info.date_of_birth',
            'user_info.gender',
            'user_info.description',
        ])
            ->join('student_group', 'student_group.group_id', 'group.id')
            ->join('users', 'users.id', 'student_group.user_id')
            ->join('user_info', 'users.id', 'user_info.user_id')
            ->where('group.id', $id)
            ->orderBy($order, $sort)
            ->paginate($limit);
    }

    public function listScoreStudentInGroup($group_id)
    {
        $students = Group::select(['users.id as student_id', 'user_info.fullname as student_name'])
            ->join('student_group', 'student_group.group_id', 'group.id')
            ->join('users', 'users.id', 'student_group.user_id')
            ->join('user_info', 'users.id', 'user_info.user_id')
            ->orderBy('users.id', 'asc')
            ->where('group.id', $group_id)
            ->get()
            ->toArray();

        $scores = Group::select([
            'group.name as group_name',
            'assignment.id as assignment_id',
            'assignment.title',
            'assignment_type.name',
            'users.id as student_id',
            'user_info.fullname as student_name',
            'answer.score'
        ])
            ->join('assignment', 'assignment.group_id', 'group.id')
            ->join('assignment_type', 'assignment_type.id', 'assignment.assignment_type_id')
            ->join('answer', 'answer.assignment_id', 'assignment.id')
            ->join('users', 'users.id', 'answer.student_id')
            ->join('student_group', 'student_group.user_id', 'users.id')
            ->join('user_info', 'users.id', 'user_info.user_id')
            ->where('group.id', $group_id)
            ->orderBy('users.id', 'asc')
            ->get()
            ->toArray();

        $results = [];
        foreach ($students as $key => $student) {
            $results['students'][] = $student['student_name'];
        }
        foreach ($scores as $key => $score) {
            $results['scores'][$key][] = $score['name'];
            $results['scores'][$key][] = $score['title'];
            foreach ($students as $student) {
                if (!empty($score['student_id']) && $score['student_id'] === $student['student_id']) {
                    $results['scores'][$key][] = $score['score'];
                } else {
                    $results['scores'][$key][] = 0;
                }
            }
        }
        return $results;
    }

    public function getGroupDetailById($id)
    {
        return Group::select(['group.id', 'group.name', 'users.id as teacher_id', 'user_info.fullname as teacher_name'])
            ->join('users', 'users.id', 'group.user_id')
            ->join('user_info', 'users.id', 'user_info.user_id')
            ->where('group.id', $id)
            ->first();
    }

    private function queryAssignmentList($id)
    {
        return Group::select(['question.*', 'assignment.*', 'assignment_type.name as assignment_type_name'])
            ->join('assignment', 'assignment.group_id', 'group.id')
            ->join('assignment_type', 'assignment_type.id', 'assignment.assignment_type_id')
            ->join('question', 'assignment.question_id', 'question.id')
            ->where('group.id', $id);
    }

    public function getAssignmentsEnded($id)
    {
        $to_day = Carbon::now();
        $query = $this->queryAssignmentList($id);
        return $query->whereDate('time_end', '<', $to_day)->get()->toArray();
    }

    public function getAssignmentsStarted($id)
    {
        $to_day = Carbon::now();
        $query = $this->queryAssignmentList($id);
        return $query->whereDate('time_start', '<', $to_day)->whereDate('time_end', '>=', $to_day)->get()->toArray();
    }

    public function getAssignmentsNotStart($id)
    {
        $to_day = Carbon::now();
        $query = $this->queryAssignmentList($id);
        return $query->whereDate('time_start', '>', $to_day)->get()->toArray();
    }

    public function getAssignmentById($group_id, $assignment_id)
    {
        $results = Group::select(['question.*','assignment.*'])
                ->join('assignment', 'assignment.group_id', 'group.id')
                ->join('question', 'assignment.question_id', 'question.id')
                ->where('group.id', $group_id)
                ->where('assignment.id', $assignment_id)
                ->first();
        return $results ?? [];
    }

    public function storeAssignmentGroupId($data, $id)
    {
        $assignment = [
            'assignment_type_id' => Arr::get($data, 'assignment_type_id'),
            'group_id' => $id,
            'title' => Arr::get($data, 'title'),
            'subtitle' => Arr::get($data, 'subtitle'),
            'time_start' => Arr::get($data, 'time_start'),
            'time_end' => Arr::get($data, 'time_end'),
            'question_id' => Arr::get($data, 'question_id'),
            'id' => Arr::get($data, 'id'),
        ];
        return Assignment::create($assignment);
    }

    public function updateAssignmentGroupId($data, $id, $assignment_id)
    {
        try {
            \DB::beginTransaction();
            $question_id = Arr::get($data, 'question_id');
            $question = [
                'user_id' => Arr::get($data, 'user_id'),
                'timeline' => Arr::get($data, 'timeline'),
                'answer' => Arr::get($data, 'answer'),
                'content' => Arr::get($data, 'content'),
                'has_answer' => Arr::get($data, 'has_answer'),
            ];
            Question::where('id', $question_id)->update($question);

            $assignment = [
                'assignment_type_id' => Arr::get($data, 'assignment_type_id'),
                'group_id' => $id,
                'title' => Arr::get($data, 'title'),
                'subtitle' => Arr::get($data, 'subtitle'),
                'time_start' => Arr::get($data, 'time_start'),
                'time_end' => Arr::get($data, 'time_end'),
                'question_id' => $question_id,
            ];
            Assignment::where('id', $assignment_id)->update($assignment);
            \DB::commit();
            return true;
        } catch (\Throwable $th) {
            \Log::debug($th);
            \DB::rollBack();
            return false;
        }
    }

    public function deleteAssignmentGroupId($assignment_id)
    {
        return Assignment::where('id', $assignment_id)->delete($assignment_id);
    }
}
