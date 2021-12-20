<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoogleDriveController extends Controller
{

    public function folder_($parent_folder_code,  $new_folder_name, $new_folder_code)
    {
        $parent_folder_path = google_folder::where('kode_folder', $parent_folder_code)->get('path_folder')[0]['path_folder'];
        $parent_folder_id = google_folder::where('kode_folder', $parent_folder_code)->get('id_folder')[0]['id_folder'];
        $new_folder_path = $parent_folder_path . '/' .  $new_folder_name;
        $new_folder =  $parent_folder_id . '/' .  $new_folder_name;

        $this->GoogleDriveFolder($parent_folder_id, $new_folder_path, $new_folder_code, $new_folder);
    }

}
