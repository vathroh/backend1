<?php

namespace App\Http\Controllers\Evkinja\Attachment;

use App\Http\Controllers\Evkinja\ValueController;
use App\Http\Controllers\UploadController;
use App\Models\PersonnelEvaluationUpload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function up(){
        return new UploadController;
    }

    public function value(){
        return new ValueController;
    }

    public function setting(){
        return new \App\Http\Controllers\Evkinja\SettingController;
    }

    public function google(){
        return new \App\Http\Controllers\GoogleDriveController;
    }

    public function googleFolder(){
        return new \App\Http\Controllers\Evkinja\Attachment\GoogleFolderController;
    }

    public function index($id){
        return PersonnelEvaluationUpload::where('personnel_evaluation_value_id', $id)->get();
    }

    public function upload(Request $request, $id){
        $folder = $this->folder($id);
        $file = $request->file('file');
        $fileName = $this->fileName($file);
        $folder_id = $this->googleFolder()->folder($folder)['folder_id'];


        return $this->up()->uploadGoogle($folder_id, $file, $fileName);
        //return $this->up()->uploadPublic($folder, $file, $fileName);
    }

    public function fileName($file){
        $originalFileName   = $file->getClientOriginalName();
        $fileExtension      = $file->getClientOriginalExtension();
        $fileNameOnly       = pathinfo($originalFileName, PATHINFO_FILENAME);
        return $fileName    = str_slug($fileNameOnly) . "-" . time() . "." . $fileExtension;
    }

    public function folder($id){
        $setting = $this->setting()->meNow(); 
        $user = $this->value()->thisValueUser($id);
        $name = implode('-', explode(' ', strtolower($user['name'])));
        return $folder             = 'WEBAPP/Evkinja/' . 'Triwulan-' . $setting->quarter . '-Tahun-' . $setting->year . '/' . $user['district'] . '/' . $name;
    }

    public function create(){
        personnel_evaluation_upload::create([
            'path'                              => $folder,
            'file_name'                         => $fileName,
            'personnel_evaluation_value_id'     => $request->valueId,
            'personnel_evaluation_criteria_id'  => $request->criteriaId,
            'personnel_evaluation_aspect_id'    => $request->aspectId
        ]);
    }
}
