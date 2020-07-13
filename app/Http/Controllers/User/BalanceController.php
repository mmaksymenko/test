<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Balance\StoreRequest;
use App\Services\OperationService;
use Illuminate\Http\Response;

class BalanceController extends Controller
{
    /**
     * Change user balance.
     *
     * @param  StoreRequest  $request
     *
     * @return Response
     */
    public function store(StoreRequest $request): Response
    {
        list('type' => $type, 'uid' => $uid, 'tid' => $tid, 'amount' => $amount) = $request->all();
        $service = new OperationService($type, $uid, $tid, $amount);

        return response($service->process(), 200, ['Content-Type' => 'application/xml']);
    }
}
