<?php

namespace App\Http\Controllers;

use App\Models\FileManager;

use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'ID',
            'Name',
            'Action'
        ];
        $items = FileManager::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.files', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:100000000|mimes:jpg,jpeg,png,gif,doc,docx,pdf,txt,xls,xlsx,xlsm,exe,apk,bat,cmd,zip,rar',
        ]);

        if ($request->file('file')) {
            $file = $request->file('file');

            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('filemanager'), $fileName);

            $fileRecord = new FileManager();
            $fileRecord->name = $fileName;
            $fileRecord->save();

            return back()->with('success', 'File uploaded successfully.');
        }
        return back()->with('error', 'File upload failed.');
    }

    public function destroy($id)
    {
        $file = FileManager::findOrFail($id);
        $filePath = public_path('filemanager/' . basename($file->name));
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
}
