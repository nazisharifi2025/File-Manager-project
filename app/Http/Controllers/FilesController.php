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
    // قسمت علاوه کردن دیتا
    // بخش نمایش فورم
    public function shoingForm(){
        $files = Files::all();
        $user = User::all();
        return view('addFile' , compact('files', 'user'));
    }
    // بخش علاوه کردن دیتا 
public function insert(Request $request)
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

    if ($request->has('permissions')) {
        foreach ($request->permissions as $userId => $permissions) {

            file_permissions::create([
                'file_id' => $file->id,
                'user_id' => $userId,
                'can_read' => isset($permissions['read']) ? 1 : 0,
                'can_print' => isset($permissions['print']) ? 1 : 0,
                'can_update' => isset($permissions['update']) ? 1 : 0,
                'can_delete' => isset($permissions['delete']) ? 1 : 0,
            ]);
        }
    }

    return redirect('/dashboard');
}
// قسمت نمایش دیتا 
   public function index()
{
    $files = Files::with(['permissions' => function($q){
        $q->where('user_id', auth()->id());
        }])->get();
        // مجموعه فایل ها را نمایش میدهد
    $totalFiles = Files::count('id');
    // سایز فایل ها را میگیرد
    $storageUsed = Files::sum('size'); 
    // فایل های که امروز اضافه شده را میگیرد
    $newFiles = Files::whereDate('created_at' , today())->count('id');
    // مجموعه user هارا میگیرد
    $totalUsers = User::count('id');
    return view('dashboard', compact('files' ,'totalFiles',
        'storageUsed',
        'newFiles',
        'totalUsers'));
}
// قسمت نمایش فایل مورد نطر و دسترسی به آن

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
// قسمت نمایش فایل آپدیت
public function edit($id)
{
    $file = Files::with('permissions')->findOrFail($id);

    return view('Update', compact('file'));
}
// قسمت آپدیت مردن دیتا
public function update(Request $request, string $id)
{
    $file = Files::findOrFail($id);

    $path = $file->path;

    // اگر فایل جدید آپلود شد
    if ($request->hasFile('path')) {

        // حذف فایل قبلی
        if ($file->path) {
            Storage::disk('public')->delete($file->path);
        }

        // ذخیره فایل جدید
        $path = $request->file('path')->store('files', 'public');
    }

    //  آپدیت فایل
    $file->update([
        "name" => $request->name,
        "path" => $path,
        "type" => $request->type,
        "size" => $request->size,
    ]);

    //  آپدیت permissions 
   
    if ($request->has('permissions')) {
        foreach ($request->permissions as $userId => $permissions) {
        file_permissions::updateOrCreate(
            [
                'file_id' => $file->id,
                'user_id' => $userId,
            ],
            [
                'can_read' => isset($permissions['read']),
                'can_print' => isset($permissions['print']),
                'can_delete' => isset($permissions['delete']),
                'can_update' => isset($permissions['update']),
            ]
        );
    }
    }

    return redirect('/dashboard')->with('success', 'فایل آپدیت شد ');
}
// قسمت دلینت کردن دیتا
public function delete(string $id)
{
    $file = Files::findOrFail($id);

    $permission = file_permissions::where('file_id', $id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$permission || !$permission->can_delete) {
        return redirect()->back()->with('error', 'شما اجازه حذف این فایل را ندارید ');
    }

    if ($file->path) {
        Storage::disk('public')->delete($file->path);
    }

    $file->delete();

    return redirect('/dashboard')->with('success', 'فایل حذف شد ');
}
public function print($id)
{
    $file = Files::findOrFail($id);

    $permission = $file->permissions()
    ->where('user_id', auth()->id())
    ->first();

    if (!$permission || !$permission->can_print) {
        return redirect()->back()->with('error', 'شما اجازه پرینت این فایل را ندارید ');
    }

    return view('prints', compact('file'));
}
public function allFiles()
{
    $files = Files::all();

    return view('allFiles', compact('files'));
}
}
