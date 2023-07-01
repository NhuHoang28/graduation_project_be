<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    public $timestamps = TRUE;

    protected $table = 'assignment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'title',
        'subtitle',
        'question_id',
        'assignment_type_id',
        'time_start',
        'time_end',
    ];

}
