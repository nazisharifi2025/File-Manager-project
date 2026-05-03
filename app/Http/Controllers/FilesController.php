<?php

namespace App\Http\Controllers;

use App\Http\Requests\fileRequest;
use App\Models\file_permissions;
use App\Models\Files;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function shoingForm(){
        $files = Files::all();
        $user = User::all();
        return view('addFile' , compact('files', 'user'));
    }
public function insert(fileRequest $request)
{
    dd($request->all());
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

    if ($request->has('permissions')) {
        foreach ($request->permissions as $userId => $permissions) {

            file_permissions::create([
                'file_id' => $file->id,
                'user_id' => $userId,
                'can_read' => isset($permissions['read']) ? 1 : 0,
                'can_print' => isset($permissions['print']) ? 1 : 0,
                'can_update' => isset($permissions['update']) ? 1 : 0,
                'can_delete' => isset($permissions['delete']) ? 1 : 0,
                'can_copy' => isset($permissions['copy']) ? 1 : 0,
            ]);
        }
    }

    return redirect('/dashboard');
}
   public function index()
{
    $files = Files::with(['permissions' => function($q){
        $q->where('user_id', auth()->id());
        }])->get();
        // Gate::authorize('print' , $files);

    return view('dashboard', compact('files'));
}

public function view($id)
{
    $file = Files::findOrFail($id);

    $hasAccess = $file->permissions()
        ->where('user_id', auth()->id())
        ->where('can_read', true)
        ->exists();

    if (!$hasAccess) {
        return redirect()->back()->with('error', 'شما اجازه دسترسی به این فایل را ندارید');
    }

    return response()->file(storage_path('app/public/' . $file->path));
}
public function update(Request $request , string $id){
    $file =  Files::findOrFila($id);
    $path = null ;
    if($file->path){
        Storage::disk('public')->delete($id);
    }
    if($request->hasFile('path')){
        $request->file('path')->store('files' , 'public');
    }
    $file->update([
        "name" => $request->name,
        "path" => $path,
        "type" => $request->type,
        "size" => $request->size,
    ]);
    $file->permissions->update([
        "user_id"=> $request->user_id,
        "file_id"=> $file->id,
        "can_read"=> $request->canRead == "1",
        "can_print"=> $request->canPrint == "1",
        "can_delete"=> $request->canDelete == "1",
        "can_update"=> $request->canUpdate == "1",
        "can_copy"=> $request->canCopy == "1",
    ]);
}
public function delete(string $id)
{
    $file = Files::findOrFail($id);

    if ($file->path) {
        Storage::disk('public')->delete($file->path);
    }

    $file->delete();

    return redirect('/dashboard')->with('success', 'فایل حذف شد');
}
}
