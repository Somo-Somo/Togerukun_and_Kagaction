<?php

namespace App\Http\Controllers;

use App\UseCases\Hypothesis\IndexAction;
use App\UseCases\Hypothesis\StoreAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class HypothesisController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param string $projectUuid
     * @param  \App\UseCases\Hypothesis\IndexAction $indexAction
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(string $projectUuid, Request $request, IndexAction $indexAction)
    {
        $user_email = $request->user()->email;
    
        $project = [
            $user_email,$projectUuid
        ];

        // ユースケースを実行し、レスポンスの元になるデータを受け取る
        $HypothesisList = $indexAction->invoke($project);

        return response()->json($HypothesisList, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UseCases\Hypothesis\StoreAction  $storeAction
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StoreAction $storeAction)
    {
        $hypothesis = [
            'name' => $request->name,
            'uuid' => (string) Str::uuid(),
            'parent_uuid' => $request->parent_uuid,
            'created_by_user_email' => $request->user()->email,
        ];

        $createdHypothesis = $storeAction->invoke($hypothesis);

        // 本当はResourcesにかきたいけど
       $json = [
            'parent' => $createdHypothesis['parent'],
            'hypothesis' => $createdHypothesis['hypothesis'],
            'message' => '仮設が追加されました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
