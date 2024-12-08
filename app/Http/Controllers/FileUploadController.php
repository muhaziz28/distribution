<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class FileUploadController extends Controller
{
    public function process(Request $request): string
    {
        // We don't know the name of the file input, so we need to grab
        // all the files from the request and grab the first file.
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }

        $requestKey = array_key_first($files);
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        return $file->store(
            path: 'tmp/' . now()->timestamp . '-' . Str::random(20)
        );
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|string',
        ]);
        $file = new File(Storage::path($validated['file']));

        $fileLocation = Storage::disk('public')->putFile('files', $file);

        // hapus tmp kalo udah berhasil simpan file ke public
        Storage::deleteDirectory('tmp');

        if ($fileLocation) {
            return response()->json([
                'message' => 'File berhasil disimpan.',
                'file_path' => $fileLocation,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan file.',
            ], 500);
        }
    }

    public function revert(Request $request)
    {
        try {
            $filePath = $request->getContent();
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            $folderPath = dirname($filePath);
            if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                Storage::deleteDirectory($folderPath);
            }

            return response('', 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus file.', 'error' => $e->getMessage()], 500);
        }
    }
}
