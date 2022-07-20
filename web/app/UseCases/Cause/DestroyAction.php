<?php

namespace App\UseCases\Cause;

use App\Repositories\Cause\CauseRepositoryInterface;

class DestroyAction
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
     * Repository介してDBから原因コメントを削除する
     *
     * @param array $cause
     */
    public function invoke(array $cause)
    {
        $this->cause_repository->destroyCause($cause);
    }
}
