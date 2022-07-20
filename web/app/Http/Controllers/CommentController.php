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
     * @param  string $todoUuid TodoのユニークID
     * @param  App\Http\Requests\CommentRequest $request コメントのバリデーション
     * @param  App\UseCases\Comment\StoreAction $storeAction UseCaseでコメントの投稿処理を行う
     * @return \Illuminate\Http\Response
     */
    public function store(string $todoUuid, CommentRequest $request, StoreAction $storeAction)
    {
        $comment = [
            'user_email' => $request->user()->email,
            'todo_uuid' => $todoUuid,
            'comment_uuid' => $request->uuid,
            'text' => $request->text
        ];

        $storeAction->invoke($comment);

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
     * @param  App\UseCases\Comment\UpdateAction $updateAction UseCaseでコメントの更新処理を行う
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, UpdateAction $updateAction)
    {
        $comment = [
            'user_email' => $request->user()->email,
            'uuid' => $request->uuid,
            'text' => $request->text
        ];

        $updateAction->invoke($comment);

        $json = [
            'message' => 'コメントを更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $commentUuid コメントのユニークID
     * @param  \Illuminate\Http\Request  $request
     * @param  App\UseCases\Comment\DestroyAction $destoryAction UseCaseでコメントの削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $commentUuid, Request $request, DestroyAction $destroyAction)
    {
        $comment = [
            'user_email' => $request->user()->email,
            'comment_uuid' => $commentUuid,
        ];

        $destroyAction->invoke($comment);

        $json = [
            'message' => 'コメントを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
