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
    public function insert(Request $request , string $id){
        $user = User::where("role" , "Admin")->get();
        $path = null;
        if($request->hasFile('path')){
            $path = $request->file('path')->store('files' , 'public');
        }
        $file= new Files();
        $file->create([
            "name"=> $request->name,
            "path"=> $path,
            "type"=> $request->type,
            "size"=> $request->size,
            "uploaded_by" => $user->id,
        ]);
        file_permissions::create([
            "user_id"=> $request->user_id,
            "file_id"=> $request->file_id,
            "can_read"=> $request->canRead,
            "can_print"=> $request->canPrint,
            "can_delete"=> $request->candelete,
            "can_update"=> $request->canUpdate,
            "can_copy"=> $request->canCopy,
        ]);
        return redirect('/dashbord');
    }
}
