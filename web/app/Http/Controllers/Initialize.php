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
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, GetUserHasProjectAndHypothesisAction $getUserHasProjectAndHypothesisAction)
    {
        $userHasProjectAndHypothesis= $getUserHasProjectAndHypothesisAction->invoke($request->user()->email);

        return response()->json($userHasProjectAndHypothesis, Response::HTTP_OK);
    }
}
