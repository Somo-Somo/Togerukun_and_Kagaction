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
     * @param  object  $todo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $todoUuid, Request $request, UpdateAction $updateAction)
    {
        $todo = [
            'uuid' => $todoUuid,
            'date' => $request->date,
            'user_email' => $request->user()->email,
        ];

        $updateAction->invoke($todo);

        $json = [
            'message' => '日付を設定しました',
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

        $destroyAction->invoke($todo);

        $json = [
            'message' => '日付を取り消しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
