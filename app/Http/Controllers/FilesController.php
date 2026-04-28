<?php

namespace App\Http\Controllers;

use App\Http\Requests\fileRequest;
use App\Models\file_permissions;
use App\Models\Files;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FilesController extends Controller
{
    public function shoingForm(){
        $files = Files::all();
        $user = User::all();
        return view('addFile' , compact('files', 'user'));
    }
public function insert(fileRequest $request)
{
    $path = null;

    if ($request->hasFile('path')) {
        $path = $request->file('path')->store('files', 'public');
    }

    $file = Files::create([
        "name" => $request->name,
        "path" => $path,
        "type" => $request->type,
        "size" => $request->size,
        "uploaded_by" => auth()->id(),
    ]);

    file_permissions::create([
        "user_id"=> $request->user_id,
        "file_id"=> $file->id,
        "can_read"=> $request->canRead == "1",
        "can_print"=> $request->canPrint == "1",
        "can_delete"=> $request->canDelete == "1",
        "can_update"=> $request->canUpdate == "1",
        "can_copy"=> $request->canCopy == "1",
    ]);

    return redirect('/dashboard');
}
    public function index(){
        $files = Files::all();
        return view('/dashboard' , compact('files'));
    }
}
