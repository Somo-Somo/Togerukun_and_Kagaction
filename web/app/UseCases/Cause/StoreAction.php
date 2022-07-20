<?php

namespace App\UseCases\Cause;

use App\Repositories\Cause\CauseRepositoryInterface;

class StoreAction
{
    protected $cause_repository;

    /**
     * @param App\Repositories\Cause\CauseRepositoryInterface $causeRepositoryInterface
     */
    public function __construct(CauseRepositoryInterface $causeRepositoryInterface)
    {
        $this->cause_repository = $causeRepositoryInterface;
    }

    /**
     * 原因のコメントをRepository介してDBに保存
     *
     * @param array $cause
     */
    public function invoke(array $cause)
    {
        $this->cause_repository->storeCause($cause);
    }
}
