<?php

namespace App\Http\Controllers;

use App\UseCases\Goal\StoreAction;
use Illuminate\Http\Request;
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
            'uuid' => $request->uuid,
            'parent_uuid' => $request->parentUuid,
            'created_by_user_email' => $request->user()->email,
        ];

        $storeAction->invoke($goal);

       // 本当はResourcesにかきたいけど
       $json = [
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
