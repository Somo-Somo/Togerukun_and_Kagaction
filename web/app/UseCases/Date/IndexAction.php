<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;
use App\UseCases\Date\Converter\ScheduleListConverter;

class IndexAction
{
    protected $date_repository;
    protected $scheduleListConverter;

    public function __construct(DateRepositoryInterface $dateRepositoryInterface, ScheduleListConverter $scheduleListConverter)
    {
        $this->date_repository = $dateRepositoryInterface;
        $this->scheduleListConverter = $scheduleListConverter;
    }

    public function invoke(string $user_email)
    {
        $fetchTodoAndDate = $this->date_repository->getDate($user_email);
        $scheduleList = $this->scheduleListConverter->invoke($fetchTodoAndDate);
        return $scheduleList;
    }
}