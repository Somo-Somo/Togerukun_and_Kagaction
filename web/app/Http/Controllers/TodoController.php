<?php

namespace App\Http\Controllers;

use App\UseCases\Todo\StoreAction;
use App\UseCases\Todo\UpdateAction;
use App\UseCases\Todo\DestroyAction;
use App\Http\Requests\TodoRequest;
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
     * @param  App\Http\Requests\TodoRequest  $request
     * @param  \App\UseCases\Todo\StoreAction $store_action Todoの登録処理
     * @return \Illuminate\Http\Response
     */
    public function store(TodoRequest $request, StoreAction $store_action)
    {
        $todo = [
            'name' => $request->name,
            'uuid' => $request->uuid,
            'parent_uuid' => $request->parentUuid,
            'date' => $request->date,
            'user_email' => $request->user()->email,
        ];

        $store_action->invoke($todo);

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
     * @param  App\Http\Requests\TodoRequest  $request
     * @param  \App\UseCases\Todo\UpdateAction $update_action Todoの更新処理
     * @return \Illuminate\Http\Response
     */
    public function update(TodoRequest $request, UpdateAction $update_action)
    {
        $todo = [
            'name' => $request->name,
            'uuid' => $request->uuid,
            'user_email' => $request->user()->email,
        ];

        $update_action->invoke($todo);

        $json = [
            'message' => '仮説名を更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $todoUuid TodoのユニークID
     * @param  Illuminate\Http\Requestt  $request
     * @param  \App\UseCases\Todo\DestroyAction $destroy_action Todoの削除処理
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $todoUuid, Request $request, DestroyAction $destroy_action)
    {
        $todo = [
            'uuid' => $todoUuid,
            'user_email' => $request->user()->email,
        ];

        $destroy_action->invoke($todo);

        $json = [
            'message' => 'プロジェクトを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
