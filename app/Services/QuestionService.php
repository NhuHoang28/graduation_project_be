<?php

namespace App\Services;

use App\Models\Question;
use Arr;

class QuestionService
{

	public function createQuestion($data, $user_id)
	{
		$inserts = [
			'answer' => Arr::get($data, 'answer'),
			'content' => Arr::get($data, 'content'),
			'description' => Arr::get($data, 'description'),
			'has_answer' => Arr::get($data, 'has_answer'),
			'is_public' => Arr::get($data, 'is_public'),
			'timeline' => Arr::get($data, 'timeline'),
			'subject_id' => Arr::get($data, 'subject_id'),
			'user_id' => $user_id,
		];
		return Question::create($inserts);
	}

	public function libraryList($params, $user_id)
	{
		$limit = Arr::get($params, 'limit', config('pagination.limit'));
        $sort = Arr::get($params, 'sort', config('pagination.sort'));
        $order = Arr::get($params, 'order', config('pagination.order'));
        $description = Arr::get($params, 'description', '');
        $subject_name = Arr::get($params, 'subject_name', '');
        $teacher_name = Arr::get($params, 'teacher_name', '');
		return Question::select(['question.*', 'subject.name as subject_name', 'user_info.fullname as teacher_name'])
			->join('subject', 'subject.id', 'question.subject_id')
			->join('users', 'users.id', 'question.user_id')
			->join('user_info', 'user_info.user_id', 'users.id')
			->where('users.id', '<>', $user_id)
			->where('question.description', 'like', '%'. $description .'%')
			->where('subject.name', 'like', '%'. $subject_name .'%')
			->where('user_info.fullname', 'like', '%'. $teacher_name .'%')
			->isPublic()
			->orderBy($sort, $order)
            ->paginate($limit)
			->toArray();
	}

	public function questionList($params, $user_id)
	{
		$limit = Arr::get($params, 'limit', config('pagination.limit'));
        $sort = Arr::get($params, 'sort', config('pagination.sort'));
        $order = Arr::get($params, 'order', config('pagination.order'));
        $description = Arr::get($params, 'description', '');
        $subject_name = Arr::get($params, 'subject_name', '');
        $teacher_name = Arr::get($params, 'teacher_name', '');
		return Question::select(['question.*', 'subject.name as subject_name', 'user_info.fullname as teacher_name'])
			->join('subject', 'subject.id', 'question.subject_id')
			->join('users', 'users.id', 'question.user_id')
			->join('user_info', 'user_info.user_id', 'users.id')
			->where('users.id', $user_id)
			->where('question.description', 'like', '%'. $description .'%')
			->where('subject.name', 'like', '%'. $subject_name .'%')
			->where('user_info.fullname', 'like', '%'. $teacher_name .'%')
			->isPublic()
			->orderBy($sort, $order)
            ->paginate($limit)
			->toArray();
	}

	public function questionDetail($id)
	{
		$results = Question::select(['question.*', 'subject.name as subject_name', 'user_info.fullname as teacher_name'])
			->join('subject', 'subject.id', 'question.subject_id')
			->join('users', 'users.id', 'question.user_id')
			->join('user_info', 'user_info.user_id', 'users.id')
			->where('question.id', $id)
			->first();
		return $results ?? [];
	}

	function updateQuestion($params, $id)
	{
		$update = [
			'answer' => Arr::get($params, 'answer'),
			'content' => Arr::get($params, 'content'),
			'description' => Arr::get($params, 'description'),
			'has_answer' => Arr::get($params, 'has_answer', 0),
			'is_public' => Arr::get($params, 'is_public', 0),
			'timeline' => Arr::get($params, 'timeline'),
			'subject_id' => Arr::get($params, 'subject_id'),
		];
		return Question::where('id', $id)->update($update);
	}
}
