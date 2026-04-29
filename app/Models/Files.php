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
    protected $fillable= [
        "name",
        "path",
        "type",
        "size",
        "uploaded_by"
    ];
    public function user(){
        return $this->belongsTo(User::class , "uploaded_by");
    }
   public function permissions()
{
    return $this->hasMany(file_permissions::class , 'file_id');
}

public function canRead()
{
    return $this->permissions()
        ->where('user_id', auth()->id())
        ->where('can_read', true)
        ->exists();
}
}
