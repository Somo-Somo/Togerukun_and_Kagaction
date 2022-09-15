<?php

namespace App\Http\Controllers;

use App\UseCases\Date\UpdateAction;
use App\UseCases\Date\DestroyAction;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class DateController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  string $todo_uuid TodoのユニークID
     * @param  \Illuminate\Http\Request  $request
     * @param  App\UseCases\Date\UpdateAction $update_action UseCaseで日付の更新処理を行う
     * @return \Illuminate\Http\Response
     */
    public function update(string $todo_uuid, Request $request, UpdateAction $update_action)
    {
        $todo = [
            'uuid' => $todo_uuid,
            'date' => $request->date,
            'user_id' => $request->user()->id,
        ];

        $update_action->invoke($todo);

        $json = [
            'message' => '日付を設定しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $todo_uuid TodoのユニークID
     * @param  \Illuminate\Http\Request $request
     * @param  App\UseCases\Date\DestroyAction $destroy_action UseCaseで日付の削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $todo_uuid, Request $request, DestroyAction $destroy_action)
    {
        $todo = [
            'uuid' => $todo_uuid,
            'user_id' => $request->user()->id,
        ];

        $destroy_action->invoke($todo);

        $json = [
            'message' => '日付を取り消しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
