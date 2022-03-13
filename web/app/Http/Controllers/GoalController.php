<?php

namespace App\Http\Controllers;

use App\UseCases\Goal\StoreAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Symfony\Component\HttpFoundation\Response;

class GoalController extends Controller
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
    public function store(Request $request, StoreAction $storeAction)
    {
        $goal = [
            'name' => $request->name,
            'uuid' => (string) Str::uuid(),
            'parent_uuid' => $request->project['uuid'],
            'created_by_user_email' => $request->user()->email,
        ];

        $storeAction->invoke($goal);

       // 本当はResourcesにかきたいけど
       unset($goal['created_by_user_email']);
       $json = [
            'project' => $request->project,
            'goal' => $goal,
            'message' => 'ゴールが追加されました',
            'error' => '',
        ];

        return response()->json($json, Response::HTTP_CREATED);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
