<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    public $timestamps = TRUE;

    protected $table = 'question';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'user_id',
        'subject_id',
        'is_public',
        'content',
        'timeline',
        'answer',
        'has_answer',
    ];

    /**
     * Scope a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsPublic($query) {
        return $query->where('is_public', 1);
    }

    /**
     * Scope a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasAnswer($query) {
        return $query->where('has_answer', 1);
    }

}
