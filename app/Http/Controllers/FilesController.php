<?php

namespace App\Http\Controllers;

use App\Models\files;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    use AuthorizesRequests;

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

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $perPage = 5;

        // Start query for the user's files
        $query = files::where('user_id', $user->id);

        // Apply search filter if provided
        Log::info('fetching files : '.$request->search);
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('original_name', 'like', '%'.$search.'%');
        }

        // Order and paginate
        $files = $query->orderBy('created_at', 'desc')
                    ->paginate($perPage);

        return response()->json($files);
    }

    public function download(files $file)
    {
        Log::info('Request To Download file id: '.$file->id);

        $this->authorize('view', $file);

        $path = storage_path('app/public/' . $file->path);

        if (!file_exists($path)) {
            Log::error('File not found: ' . $path);
            abort(404, 'File not found.');
        }

        return response()->download($path, $file->original_name);
    }

    public function destroy(files $file)
        {
            Log::info('Request to delete file id: ' . $file->id);

            // Authorize the user
            $this->authorize('delete', $file);

            // Delete the file from storage (if exists)
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
                Log::info('File deleted from storage: ' . $file->path);
            } else {
                Log::warning('File not found in storage: ' . $file->path);
            }

            // Delete the database record
            $file->delete();

            return response()->json(['message' => 'File deleted successfully']);
    }
}
