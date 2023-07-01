<?php

namespace App\Models;

use App\Models\Traits\DateTimeTrait;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use DateTimeTrait;
}