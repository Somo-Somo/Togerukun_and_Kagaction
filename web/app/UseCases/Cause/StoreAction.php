<?php

namespace App\UseCases\Cause;

use App\Repositories\Cause\CauseRepositoryInterface;

class StoreAction
{
    protected $cause_repository;

    /**
     * @param App\Repositories\Cause\CauseRepositoryInterface $cause_repository_interface
     */
    public function __construct(CauseRepositoryInterface $cause_repository_interface)
    {
        $this->cause_repository = $cause_repository_interface;
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
