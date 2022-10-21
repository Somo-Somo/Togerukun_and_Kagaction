<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;


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

    /**
     * どのくらいの頻度でやるか聞く
     *
     * @param string $user_name
     * @param array $todo
     * @return \LINE\LINEBot\MessageBuilder\MultiMessageBuilder()
     */
    public static function askHowOftenHabit(string $user_name, array $todo)
    {
        $actions = [];
        $oftens = ['毎日', '毎週', '毎月', '平日のみ', '休日のみ'];

        foreach ($oftens as $key => $often) {
            $text_component  = new TextComponentBuilder($often, 1);
            $text_component->setWeight('bold');
            $text_component->setGravity('center');
            $text_component->setAlign('center');
            $text_component_builders = [$text_component];
            $post_back_template_action = new PostbackTemplateActionBuilder(
                $often,
                'action=' . $action . '&todo_uuid=' . $parent_todo->uuid
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
        $question_message = '「' . $parent_todo->name . '」を達成するために「やること」と「習慣」どちらを追加しますか？';

        $multi_message_builder = new MultiMessageBuilder();
        $multi_message_builder->add(new TextMessageBuilder($question_message));
        $multi_message_builder->add(new FlexMessageBuilder($question_message, $carousels));

        return $multi_message_builder;
    }
}
