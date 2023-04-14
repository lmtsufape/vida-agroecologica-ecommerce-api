<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/public/images/produtos/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $file = new File($path);
        $type = Storage::mimeType('public/images/produtos/' . $filename);

        $response = new Response(file_get_contents($file), 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
