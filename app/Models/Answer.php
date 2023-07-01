<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    public $timestamps = TRUE;

    protected $table = 'answer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assignment_id',
        'student_id',
        'is_submit',
        'content',
        'timeline',
        'review',
        'submit_at',
        'score',
    ];
}
