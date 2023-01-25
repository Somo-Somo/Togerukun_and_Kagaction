<?php

namespace App\Http\Controllers;

use App\UseCases\Comment\StoreAction;
use App\UseCases\Comment\UpdateAction;
use App\UseCases\Comment\DestroyAction;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
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
     * @param  string $todo_uuid TodoのユニークID
     * @param  App\Http\Requests\CommentRequest $request コメントのバリデーション
     * @param  App\UseCases\Comment\StoreAction $store_action UseCaseでコメントの投稿処理を行う
     * @return \Illuminate\Http\Response
     */
    public function store(string $todo_uuid, CommentRequest $request, StoreAction $store_action)
    {
        $comment = [
            'user_uuid' => $request->user()->uuid,
            'todo_uuid' => $todo_uuid,
            'comment_uuid' => $request->uuid,
            'text' => $request->text
        ];

        $store_action->invoke($comment);

        $json = [
            'message' => 'コメントを追加しました',
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
     * @param  App\Http\Requests\CommentRequest $request コメントのバリデーション
     * @param  App\UseCases\Comment\UpdateAction $update_action UseCaseでコメントの更新処理を行う
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, UpdateAction $update_action)
    {
        $comment = [
            'user_uuid' => $request->user()->uuid,
            'uuid' => $request->uuid,
            'text' => $request->text
        ];

        $update_action->invoke($comment);

        $json = [
            'message' => 'コメントを更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $comment_uuid コメントのユニークID
     * @param  \Illuminate\Http\Request  $request
     * @param  App\UseCases\Comment\DestroyAction $destroy_action UseCaseでコメントの削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $comment_uuid, Request $request, DestroyAction $destroy_action)
    {
        $comment = [
            'user_uuid' => $request->user()->uuid,
            'comment_uuid' => $comment_uuid,
        ];

        $destroy_action->invoke($comment);

        $json = [
            'message' => 'コメントを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
