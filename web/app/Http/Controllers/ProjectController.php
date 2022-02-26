<?php

namespace App\Http\Controllers;

use App\UseCases\Project\StoreAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StoreAction $storeAction)
    {
        // バリエーションに問題が無かった場合にはプロジェクトの作成
        $project = [
            'name' => $request->name,
            'uuid' => (string) Str::uuid(),
            'created_by_user_email' => $request->user()->email,
        ];


        // ユースケースを実行し、レスポンスの元になるデータを受け取る
        $created = $storeAction->invoke($storeAction);

        //プロジェクトの作成が完了するとjsonを返す
        $json = [
            'project' => $project,
            'message' => '新しいプロジェクトの追加を完了しました',
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
