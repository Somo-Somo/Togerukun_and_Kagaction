<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;



class Habit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'user_uuid',
        'todo_uuid',
        'interval',
        'day',
        'consecutive_days',
        'created_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'interval' => 'integer',
        'day' => 'integer',
        'consecutive_days' => 'integer',
    ];

    /**
     * ユーザーに紐づく習慣
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    /**
     * todoに紐づく習慣
     *
     * @return
     */
    public function todo()
    {
        return $this->belongsTo(Todo::class, 'todo_uuid', 'uuid');
    }

    const HABIT = [
        'ASK_ABOUT_FREQUENCY' => true,
    ];

    const FREQUENCY = [
        '毎日' => 0,
        '毎週' => 1,
        '毎月' => 2,
        '平日のみ' => 3,
        '休日のみ' => 4,
    ];

    /**
     * どのくらいの頻度でやるか聞く
     *
     * @param string $user_name
     * @param array $parent_todo
     * @return \LINE\LINEBot\MessageBuilder\MultiMessageBuilder()
     */
    public static function askFrequencyHabit(string $user_name, array $todo)
    {
        $actions = [];
        $frequencies = ['毎日', '毎週', '毎月', '平日のみ', '休日のみ'];

        foreach ($frequencies as $key => $frequency) {
            $text_component  = new TextComponentBuilder($frequency, 1);
            $text_component->setWeight('bold');
            $text_component->setGravity('center');
            $text_component->setAlign('center');
            $text_component_builders = [$text_component];
            $post_back_template_action = new PostbackTemplateActionBuilder(
                $frequency,
                'action=ASK_ABOUT_FREQUENCY&value=' . $todo['uuid'] . '-' . Habit::FREQUENCY($frequency)
            );
            $box_component = new BoxComponentBuilder('vertical', $text_component_builders);
            $box_component->setAction($post_back_template_action);
            $box_component->setHeight('80px');
            $bubble_container = new BubbleContainerBuilder();
            $bubble_container->setBody($box_component);
            $bubble_container->setSize('nano');
            $actions[] = $bubble_container;
        }
        $carousels = new CarouselContainerBuilder($actions);
        $question_message = '「' . $todo['name'] . '」を達成するために「やること」と「習慣」どちらを追加しますか？';

        $multi_message_builder = new MultiMessageBuilder();
        $multi_message_builder->add(new TextMessageBuilder($question_message));
        $multi_message_builder->add(new FlexMessageBuilder($question_message, $carousels));

        return $multi_message_builder;
    }

    /**
     * 何曜日にやるか聞く
     *
     * @param string $todo_uuid
     * @param string $frequency
     * @return \LINE\LINEBot\MessageBuilder\MultiMessageBuilder()
     */
    public static function selectDayOfWeek(string $todo_uuid, string $frequency)
    {
        $actions = [];
        $day_of_weeks = ['日', '月', '火', '水', '木', '金', '土'];
        $todo = Todo::where('uuid', $todo_uuid)->first();

        foreach ($day_of_weeks as $key => $day_of_week) {
            $text_component  = new TextComponentBuilder($day_of_week, 1);
            $text_component->setWeight('bold');
            $text_component->setGravity('center');
            $text_component->setAlign('center');
            $text_component_builders = [$text_component];
            $post_back_template_action = new PostbackTemplateActionBuilder(
                $day_of_week,
                'action=ASK_ABOUT_FREQUENCY&value=' . $todo_uuid . '-' . $frequency . '-' . $key
            );
            $box_component = new BoxComponentBuilder('vertical', $text_component_builders);
            $box_component->setAction($post_back_template_action);
            $box_component->setHeight('80px');
            $bubble_container = new BubbleContainerBuilder();
            $bubble_container->setBody($box_component);
            $bubble_container->setSize('nano');
            $actions[] = $bubble_container;
        }
        $carousels = new CarouselContainerBuilder($actions);
        $question_message = '「' . $todo->name . '」を毎週何曜日に行いますか？';

        $multi_message_builder = new MultiMessageBuilder();
        $multi_message_builder->add(new TextMessageBuilder($question_message));
        $multi_message_builder->add(new FlexMessageBuilder($question_message, $carousels));

        return $multi_message_builder;
    }
}
