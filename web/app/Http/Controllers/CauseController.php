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
     * @param  string $todo_uuid TodoのユニークID
     * @param  App\Http\Requests\CauseRequest $request 原因のバリデーション
     * @param  App\UseCases\Cause\StoreAction $store_action UseCaseで原因コメントの登録処理を行う
     * @return \Illuminate\Http\Response
     */
    public function store(string $todo_uuid, CauseRequest $request, StoreAction $store_action)
    {
        $cause = [
            'user_id' => $request->user()->id,
            'todo_uuid' => $todo_uuid,
            'cause_uuid' => $request->uuid,
            'text' => $request->text
        ];

        $store_action->invoke($cause);

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
     * @param  string $cause_uuid 原因コメントのユニークID
     * @param  \Illuminate\Http\Request  $request
     * @param  App\UseCases\Cause\DestoryAction $destroy_action UseCaseで原因コメントの削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $cause_uuid, Request $request, DestroyAction $destroy_action)
    {
        $cause = [
            'user_id' => $request->user()->id,
            'cause_uuid' => $cause_uuid,
        ];

        $destroy_action->invoke($cause);

        $json = [
            'message' => '原因を削除しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
