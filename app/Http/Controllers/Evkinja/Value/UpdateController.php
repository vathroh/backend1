<?php

namespace App\Http\Controllers\Evkinja\Value;

use Symfony\Component\HttpFoundation\Response;
use App\Models\PersonnelEvaluationValue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function updateContent(Request $request, $id){
        $value = PersonnelEvaluationValue::find($id);
        $value->content = serialize($request->content);
        $value->userFinalResult = $request->userFinalResult;
        $value->userTotalScore = $request->userTotalScores;
        $value->recommendation = $request->recommendation;
        $value->finalResult = $request->finalResult;
        $value->totalScore = $request->totalScores;
        $value->issue = $request->issue;
        $value->save();

        return response([
            'message' => 'data berhasil disimpan'
        ], Response::HTTP_OK);
    }
}
