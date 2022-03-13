<?php

namespace App\Http\Controllers;

use App\UseCases\Initialize\GetUserHasProjectAndHypothesisAction;
use App\UseCases\Project\IndexAction;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;


class Initialize extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request, 
        GetUserHasProjectAndHypothesisAction $getUserHasProjectAndHypothesisAction,
        IndexAction $indexAction
    )
    {
        $hypothesisList= $getUserHasProjectAndHypothesisAction->invoke($request->user()->email);
        $projectList= $indexAction->invoke($request->user()->email);

        $userHasProjectAndHypothesis = [
            'project' => $projectList,
            'hypothesis' => $hypothesisList,
        ];

        return response()->json($userHasProjectAndHypothesis, Response::HTTP_OK);
    }
}
