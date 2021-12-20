<?php

namespace App\Http\Controllers\Evkinja\Attachment;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoogleFolder;

class GoogleFolderController extends Controller
{
    public function evkinja(){
        return new \App\Http\Controllers\Evkinja\Attachment\RootFolderController;
    }

    public function folder($path){
        $folder =  explode('/', $path);
        $quarter = $this->createQuarterFolder($folder[2]);
        $district = $this->createDistrictFolder($quarter, $folder[3]);
        return $this->createPersonnelFolder($district, $folder[4]);
    }

    public function save($directories, $parent_folder_id, $new_folder_path, $new_folder){
        foreach($directories as $directory){
            $array = explode('/', $directory);
            $folder_id = $array[count($array)-1];

            if(GoogleFolder::where('id_folder', $folder_id)->doesntExist()){
                return $this->store($folder_id, $parent_folder_id, $new_folder_path, $new_folder);
            }
        } 
    }

    public function store($folder_id, $parent_folder_id, $new_folder_path, $new_folder){
        $metadata = Storage::disk('google')->getAdapter()->getMetadata($folder_id);

        return GoogleFolder::create([
            'parent_folder' => $parent_folder_id,
            'id_folder' => $folder_id,
            'nama_folder' => $metadata["name"],
            'path_folder' => $new_folder_path
        ]);


    }

    public function checkCreateFolder($parent_folder_id, $new_folder_path, $new_folder){
        $folder = GoogleFolder::where('path_folder', $new_folder_path);

        if (GoogleFolder::where('path_folder', $new_folder_path)->doesntExist()) {
            Storage::disk('google')->makeDirectory($new_folder);
            $directories = Storage::disk('google')->directories($parent_folder_id);

            return $this->save($directories, $parent_folder_id, $new_folder_path, $new_folder);
        }

        $data = [];
        $data['folder_id'] = $folder->first()->id_folder;
        $data['parent_folder_id'] = $parent_folder_id;
        $data['new_folder_path'] = $new_folder_path;
        $data['new_folder'] = $new_folder;

        return $data;
    }

    public function createQuarterFolder($folder){
        $parent_folder_id = $this->evkinja()->rootFolder();
        $new_folder = $parent_folder_id . '/' . $folder;
        $new_folder_path = 'WEBAPP/Evkinja/' . $folder;

        return $this->checkCreateFolder($parent_folder_id, $new_folder_path, $new_folder);
    }

    public function createDistrictFolder($quarter, $folder){
        $parent_folder_id = $quarter['folder_id'];
        $folderStr = strtolower($folder);
        $new_folder = $parent_folder_id . '/' . $folderStr;
        $new_folder_path = $quarter['new_folder_path'] . '/' . $folderStr;

        return $this->checkCreateFolder($parent_folder_id, $new_folder_path, $new_folder);
    }

    public function createPersonnelFolder($district, $folder){
        $parent_folder_id = $district['folder_id'];
        $folderStr = strtolower($folder);
        $new_folder = $parent_folder_id . '/' . $folderStr;
        $new_folder_path = $district['new_folder_path'] . '/' . $folderStr;

        return $this->checkCreateFolder($parent_folder_id, $new_folder_path, $new_folder);
    }
}
