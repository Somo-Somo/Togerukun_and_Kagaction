<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\Cause\StoreAction;
use App\UseCases\Cause\DestroyAction;
use App\Http\Requests\CauseRequest;
use \Symfony\Component\HttpFoundation\Response;

class CauseController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  string $todoUuid TodoのユニークID
     * @param  App\Http\Requests\CauseRequest $request 原因のバリデーション
     * @param  StoreAction $storeAction UseCaseで原因コメントの登録処理を行う
     * @return \Illuminate\Http\Response
     */
    public function store(string $todoUuid, CauseRequest $request, StoreAction $storeAction)
    {
        $cause = [
            'user_email' => $request->user()->email,
            'todo_uuid' => $todoUuid,
            'cause_uuid' => $request->uuid,
            'text' => $request->text
        ];

        $storeAction->invoke($cause);

        $json = [
            'message' => '原因を追加しました',
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
     * @param  string $causeUuid 原因コメントのユニークID
     * @param  \Illuminate\Http\Request  $request
     * @param  DestoryAction $destroyAction UseCaseで原因コメントの削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $causeUuid, Request $request, DestroyAction $destroyAction)
    {
        $cause = [
            'user_email' => $request->user()->email,
            'cause_uuid' => $causeUuid,
        ];

        $destroyAction->invoke($cause);

        $json = [
            'message' => '原因を削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
