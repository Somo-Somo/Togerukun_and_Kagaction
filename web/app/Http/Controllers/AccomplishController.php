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
     * @param  string  $todo_uuid TodoのユニークID
     * @param  \Illuminate\Http\Request  $request
     * @param  App\UseCases\Accomplish\UpdateAction $update_action UseCaseで完了の更新処理を行う
     * @return \Illuminate\Http\Response
     */
    public function update(string $todo_uuid, Request $request, UpdateAction $update_action)
    {
        $todo = [
            'uuid' => $todo_uuid,
            'user_uuid' => $request->user()->uuid,
        ];

        $update_action->invoke($todo);

        $json = [
            'message' => '仮説の評価を更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $todo_uuid
     * @param  \Illuminate\Http\Request $request
     * @param  App\UseCases\Accomplish\DestoryAction $destroy_action UseCaseで完了の削除処理を行う
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $todo_uuid, Request $request, DestroyAction $destroy_action)
    {
        $todo = [
            'uuid' => $todo_uuid,
            'user_uuid' => $request->user()->uuid,
        ];

        $destroy_action->invoke($todo);

        $json = [
            'message' => '仮説の評価を取り消しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
