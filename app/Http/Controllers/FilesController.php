<?php

namespace App\Http\Controllers;

use App\Models\file_permissions;
use App\Models\Files;
use App\Models\User;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    public function shoingForm(){
        $files = Files::all();
        $user = User::all();
        return view('addFile' , compact('files', 'user'));
    }
    public function insert(Request $request){
    $path = null;

    if($request->hasFile('path')){
        $path = $request->file('path')->store('files' , 'public');
    }

    $user = User::where("role" , "Admin")->first();

    $file = Files::create([
        "name"=> $request->name,
        "path"=> $path,
        "type"=> $request->type,
        "size"=> $request->size,
        "uploaded_by" => $user->id,
    ]);

    file_permissions::create([
        "user_id"=> $request->user_id,
        "file_id"=> $file->id,
    "can_read"=> $request->canRead == "true" ? 1 : 0,
    "can_print"=> $request->canPrint == "true" ? 1 : 0,
    "can_delete"=> $request->canDelete == "true" ? 1 : 0,
    "can_update"=> $request->canUpdate == "true" ? 1 : 0,
    "can_copy"=> $request->canCopy == "true" ? 1 : 0,
    ]);

    return redirect('/dashboard');
}
   }
