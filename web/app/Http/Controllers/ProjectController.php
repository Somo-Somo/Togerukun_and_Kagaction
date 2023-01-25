<?php

namespace App\Http\Controllers;

use App\UseCases\Project\IndexAction;
use App\UseCases\Project\StoreAction;
use App\UseCases\Project\UpdateAction;
use App\UseCases\Project\DestroyAction;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  App\Http\Requests\ProjectRequest  $request
     * @param  App\UseCases\Project\IndexAction $index_action UseCaseでプロジェクトの取得処理を行う
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectRequest $request, IndexAction $index_action)
    {
        $user_uuid = $request->user()->uuid;

        // ユースケースを実行し、レスポンスの元になるデータを受け取る
        $project_list = $index_action->invoke($user_uuid);

        return response()->json($project_list, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ProjectRequest $request
     * @param  App\UseCases\Project\StoreAction $store_action UseCaseでプロジェクトの登録処理を行う
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request, StoreAction $store_action)
    {
        // 後でRequestsに移行する
        $project = [
            'name' => $request->name,
            'uuid' => (string) Str::uuid(),
            'created_by_user_uuid' => $request->user()->uuid,
        ];

        // ユースケースを実行し、レスポンスの元になるデータを受け取る
        $created_project = $store_action->invoke($project);

        // 本当はResourcesにかきたいけど
        $json = [
            'project' => $created_project,
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
     * @param  App\Http\Requests\ProjectRequest  $request
     * @param  App\UseCases\Project\UpdateAction $update_action UseCaseでプロジェクトの更新処理を行う
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, UpdateAction $update_action)
    {
        $project = [
            'name' => $request->name,
            'uuid' => $request->uuid,
            'user_uuid' => $request->user()->uuid,
        ];

        $update_action->invoke($project);

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
     * @param  string $project_uuid プロジェクトのユニークID
     * @param  Illuminate\Http\Request $request
     * @param  App\UseCases\Project\DestoryAction $destroy_action UseCaseでプロジェクトの削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $project_uuid, Request $request, DestroyAction $destroy_action)
    {
        $project = [
            'uuid' => $project_uuid,
            'user_uuid' => $request->user()->uuid,
        ];

        $destroy_action->invoke($project);

        // 本当はResourcesにかきたいけど
        $json = [
            'message' => 'プロジェクトを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
