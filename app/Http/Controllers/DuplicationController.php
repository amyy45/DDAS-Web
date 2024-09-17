<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DuplicationController extends Controller
{
    // Method to display downloaded files
    public function index()
    {
        // Get all downloaded files from the database
        $downloads = Download::all();
        return view('files.index', compact('downloads'));
    }

    // Method to download a file from a URL and store metadata in the database
    public function downloadFromUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');
        $fileName = basename($url);
        $filePath = storage_path('app/downloads/' . $fileName);

        // Check if the file already exists
        if (file_exists($filePath)) {
            // If the file exists, return a prompt with the file location
            $existingDownload = Download::where('file_name', $fileName)->first();
            return response()->json([
                'exists' => true,
                'file' => $existingDownload->file_path ?? $filePath,
            ]);
        }

        // Download the file from the URL
        $fileContents = file_get_contents($url);
        file_put_contents($filePath, $fileContents);

        // Store file metadata in the database
        Download::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'url' => $url,
        ]);

        return response()->json([
            'exists' => false,
            'file' => $filePath,
        ]);
    }
    
}
