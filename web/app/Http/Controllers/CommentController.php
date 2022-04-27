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
     * @param  string  $hypothesisUuid
     * @param  \Illuminate\Http\CommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(string $hypothesisUuid, CommentRequest $request, StoreAction $storeAction)
    {
        $comment = [
            'user_email' => $request->user()->email,
            'hypothesis_uuid' => $hypothesisUuid,
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
     * @param  \Illuminate\Http\CommentRequest  $request
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, DestroyAction $destroyAction)
    {
        $comment = [
            'user_email' => $request->user()->email,
            'uuid' => $request->uuid
        ];

        $destroyAction->invoke($comment);

        $json = [
            'message' => 'コメントを削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
