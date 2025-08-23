<?php

namespace App\Http\Controllers;

use App\Models\files;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240|mimes:pdf,docx,png,jpg,jpeg,odt', // max 10MB per file
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $storedName = uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $storedName, 'public');

                // Save to database
                $fileRecord = files::create([
                    'user_id'       => $request->user()->id,
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name'   => $storedName,
                    'path'          => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                ]);

                $uploadedFiles[] = $fileRecord;
            }
        }

        return response()->json([
            'message' => 'Files uploaded successfully!',
            'files' => $uploadedFiles
        ]);
    }
}
