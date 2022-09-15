<?php

namespace App\UseCases\Date;

use App\Repositories\Date\DateRepositoryInterface;
use App\UseCases\Date\Converter\ScheduleListConverter;

class IndexAction
{
    protected $date_repository;
    protected $schedule_list_converter;

    /**
     * @param App\Repositories\Date\DateRepositoryInterface $date_repository_interface
     * @param App\UseCases\Date\Converter\ScheduleListConverter $schedule_list_converter
     */
    public function __construct(DateRepositoryInterface $date_repository_interface, ScheduleListConverter $schedule_list_converter)
    {
        $this->date_repository = $date_repository_interface;
        $this->schedule_list_converter = $schedule_list_converter;
    }

    /**
     * 日付が付随しているTodoを取得して日付があるTodo一覧に展開する
     *
     * @param int $user_id
     * @return array $schedule_list 日付があるTodo一覧
     */
    public function invoke(int $user_id)
    {
        $fetch_todo_and_date = $this->date_repository->getDate($user_id);
        $schedule_list = $this->schedule_list_converter->invoke($fetch_todo_and_date);
        return $schedule_list;
    }
}
