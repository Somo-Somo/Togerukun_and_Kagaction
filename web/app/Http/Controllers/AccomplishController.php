<?php

namespace App\Http\Controllers;

use App\UseCases\Accomplish\UpdateAction;
use App\UseCases\Accomplish\DestroyAction;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class AccomplishController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  string  $todoUuid TodoのユニークID
     * @param  \Illuminate\Http\Request  $request
     * @param  UpdateAction $updateAction UseCaseで完了の更新処理を行う
     * @return \Illuminate\Http\Response
     */
    public function update(string $todoUuid, Request $request, UpdateAction $updateAction)
    {
        $todo = [
            'uuid' => $todoUuid,
            'user_email' => $request->user()->email,
        ];

        $updateAction->invoke($todo);

        $json = [
            'message' => '仮説の評価を更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $todoUuid
     * @param  \Illuminate\Http\Request $request
     * @param  DestoryAction $destoryAction UseCaseで完了の削除処理を行う
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
            'message' => '仮説の評価を取り消しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
