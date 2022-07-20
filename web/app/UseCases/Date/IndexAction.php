<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;
use App\UseCases\Date\Converter\ScheduleListConverter;

class IndexAction
{
    protected $date_repository;
    protected $scheduleListConverter;

    /**
     * @param App\Repositories\Date\DateRepositoryInterface $dateRepositoryInterface
     * @param App\UseCases\Date\Converter\ScheduleListConverter $scheduleListConverter
     */
    public function __construct(DateRepositoryInterface $dateRepositoryInterface, ScheduleListConverter $scheduleListConverter)
    {
        $this->date_repository = $dateRepositoryInterface;
        $this->scheduleListConverter = $scheduleListConverter;
    }

    /**
     * 日付が付随しているTodoを取得して日付があるTodo一覧に展開する
     *
     * @param string $user_email
     * @return array $scheduleList 日付があるTodo一覧
     */
    public function invoke(string $user_email)
    {
        $fetchTodoAndDate = $this->date_repository->getDate($user_email);
        $scheduleList = $this->scheduleListConverter->invoke($fetchTodoAndDate);
        return $scheduleList;
    }
}
