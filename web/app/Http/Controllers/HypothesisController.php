<?php

namespace App\Http\Controllers;

use App\UseCases\Hypothesis\StoreAction;
use App\UseCases\Hypothesis\UpdateAction;
use App\UseCases\Hypothesis\DestroyAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class HypothesisController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        unset($hypothesis['created_by_user_email']);
       $json = [
            'project' => $createdHypothesis['project'],
            'hypothesis' => $hypothesis,
            'hypothesisList' => $createdHypothesis['hypothesis'],
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
    public function update(Request $request, UpdateAction $updateAction)
    {
        $hypothesis = [
            'name' => $request->name,
            'uuid' => $request->uuid,
            'user_email' => $request->user()->email,
        ];

        $updateAction->invoke($hypothesis);

        $json = [
            'message' => '仮説名を更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $hypothesisUuid
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $hypothesisUuid, Request $request, DestroyAction $destroyAction)
    {
        $hypothesis = [
            'uuid' => $hypothesisUuid,
            'user_email' => $request->user()->email,
        ];

        $deletingHypothesis = $destroyAction->invoke($hypothesis);

        $json = [
            'hypothesis' => $deletingHypothesis,
            'message' => 'プロジェクトを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
