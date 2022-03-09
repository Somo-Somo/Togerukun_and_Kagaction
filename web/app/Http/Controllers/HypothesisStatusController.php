<?php

namespace App\Http\Controllers;

use App\UseCases\HypothesisStatus\UpdateAction;
use App\UseCases\HypothesisStatus\DestroyAction;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class HypothesisStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  string  $hypothesisUuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $hypothesisUuid, Request $request, UpdateAction $updateAction)
    {
        $hypothesis = [
            'uuid' => $hypothesisUuid,
            'status' => $request->status,
            'user_email' => $request->user()->email,
        ];

        $updateAction->invoke($hypothesis);

        $json = [
            'message' => '仮説の評価を更新しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $hypothesisUuid
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $hypothesisUuid, Request $request, DestroyAction $destroyAction)
    {
        $hypothesis = [
            'uuid' => $hypothesisUuid,
            'status' => $request->click,
            'user_email' => $request->user()->email,
        ];

        $destroyAction->invoke($hypothesis);

        $json = [
            'message' => '仮説の評価を取り消しました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_OK);
    }
}
