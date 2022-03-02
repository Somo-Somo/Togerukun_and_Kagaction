<?php

namespace App\Http\Controllers;

use App\UseCases\Project\IndexAction;
use App\UseCases\Project\StoreAction;
use App\UseCases\Project\DestroyAction;
use App\Http\Resources\Project\CreatedProjectResource;
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
     * @param   $request
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
     * @param  string  $ProjectUuid
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $ProjectUuid, Request $request, DestroyAction $destroyAction)
    {
        $project = [
            'uuid' => $ProjectUuid,
            'user_email' => $request->user()->email,
        ];

        $deletingProject = $destroyAction->invoke($project);

        return response()->json($deletingProject, Response::HTTP_OK);
    }
}
