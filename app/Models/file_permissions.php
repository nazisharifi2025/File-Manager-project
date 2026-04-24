<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class file_permissions extends Model
{
    /** @use HasFactory<\Database\Factories\FilePermissionsFactory> */
    use HasFactory;
    protected $fillable = [
        "file_id",
        "user_id",
        "can_read",
        "can_delete",
        "can_update",
        "can_write",
        "can_print",
        "can_copy",
    ];
    public function user(){
        return $this->belongsTo(User::class , "user_id");
    }
    public function file(){
        return $this->belongsTo(Files::class , "file_id");
    }
}
