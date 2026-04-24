<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Files extends Model
{
    /** @use HasFactory<\Database\Factories\FilesFactory> */
    use HasFactory;
    use SoftDeletes;
}
