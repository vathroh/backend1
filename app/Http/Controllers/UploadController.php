<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadPublic($folder, $file, $fileName){
        Storage::disk('public')->putFileAs($folder, $file, $fileName);
    }

    public function uploadGoogle($folder_id, $file, $fileName){
        Storage::disk('google')->putFileAs($folder_id, $file, $fileName);
    }
}
