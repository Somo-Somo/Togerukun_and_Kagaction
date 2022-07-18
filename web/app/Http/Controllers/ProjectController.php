<?php

namespace App\Http\Controllers;

use App\UseCases\Project\IndexAction;
use App\UseCases\Project\StoreAction;
use App\UseCases\Project\UpdateAction;
use App\UseCases\Project\DestroyAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\UseCases\Project\IndexAction $indexAction UseCaseでプロジェクトの取得処理を行う
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexAction $indexAction)
    {
        $user_email = $request->user()->email;

        // ユースケースを実行し、レスポンスの元になるデータを受け取る
        $projectList = $indexAction->invoke($user_email);

        return response()->json($projectList, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  App\UseCases\Project\StoreAction $storeeAction UseCaseでプロジェクトの登録処理を行う
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StoreAction $storeAction)
    {
        // 後でRequestsに移行する
        $project = [
            'name' => $request->name,
            'uuid' => (string) Str::uuid(),
            'created_by_user_email' => $request->user()->email,
        ];

        // ユースケースを実行し、レスポンスの元になるデータを受け取る
        $createdProject = $storeAction->invoke($project);

        // 本当はResourcesにかきたいけど
        $json = [
            'project' => $createdProject,
            'message' => 'プロジェクトが追加されました',
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
     * @param  App\UseCases\Project\UpdateAction $updateAction UseCaseでプロジェクトの更新処理を行う
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UpdateAction $updateAction)
    {
        $project = [
            'name' => $request->name,
            'uuid' => $request->uuid,
            'user_email' => $request->user()->email,
        ];

        $updateAction->invoke($project);

        // 本当はResourcesにかきたいけど
        $json = [
            'message' => 'プロジェクト名を更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $projectUuid プロジェクトのユニークID
     * @param  \Illuminate\Http\Request $request
     * @param  App\UseCases\Project\DestoryAction $destoryAction UseCaseでプロジェクトの削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $projectUuid, Request $request, DestroyAction $destroyAction)
    {
        $project = [
            'uuid' => $projectUuid,
            'user_email' => $request->user()->email,
        ];

        $deletingProject = $destroyAction->invoke($project);

        // 本当はResourcesにかきたいけど
        $json = [
            'project' => $deletingProject,
            'message' => 'プロジェクトを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
