<?php

namespace App\Http\Controllers;

use App\UseCases\Todo\StoreAction;
use App\UseCases\Todo\UpdateAction;
use App\UseCases\Todo\DestroyAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
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
     * @param  \App\UseCases\Todo\StoreAction  $storeAction
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StoreAction $storeAction)
    {
        $todo = [
            'name' => $request->name,
            'uuid' => $request->uuid,
            'parent_uuid' => $request->parentUuid,
            'date' => $request->date,
            'user_email' => $request->user()->email,
        ];

        $storeAction->invoke($todo);

        $json = [
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
        $todo = [
            'name' => $request->name,
            'uuid' => $request->uuid,
            'user_email' => $request->user()->email,
        ];

        $updateAction->invoke($todo);

        $json = [
            'message' => '仮説名を更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $todoUuid
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $todoUuid, Request $request, DestroyAction $destroyAction)
    {
        $todo = [
            'uuid' => $todoUuid,
            'user_email' => $request->user()->email,
        ];

        $deletingTodo = $destroyAction->invoke($todo);

        $json = [
            'todo' => $deletingTodo,
            'message' => 'プロジェクトを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
