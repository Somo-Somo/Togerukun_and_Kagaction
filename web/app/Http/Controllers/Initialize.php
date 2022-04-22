<?php

namespace App\Http\Controllers;

use App\UseCases\Initialize\GetUserHasProjectAndHypothesisAction;
use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;


class Initialize extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UseCases\Initialize\GetUserHasProjectAndHypothesisAction  $getUserHasProjectAndHypothesisAction
     * @param \App\UseCases\Project\IndexAction $projectIndexAction
     * @param \App\UseCases\Date\IndexAction $dateIndexAction
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request, 
        GetUserHasProjectAndHypothesisAction $getUserHasProjectAndHypothesisAction,
        \App\UseCases\Project\IndexAction $projectIndexAction,
        \App\UseCases\Date\IndexAction $dateIndexAction,
    )
    {
        $hypothesisList= $getUserHasProjectAndHypothesisAction->invoke($request->user()->email);
        $projectList= $projectIndexAction->invoke($request->user()->email);
        $scheduleList= $dateIndexAction->invoke($request->user()->email);

        $userHasProjectAndHypothesis = [
            'project' => $projectList,
            'hypothesis' => $hypothesisList,
            'schedule' => $scheduleList,
        ];

        return response()->json($userHasProjectAndHypothesis, Response::HTTP_OK);
    }
}
