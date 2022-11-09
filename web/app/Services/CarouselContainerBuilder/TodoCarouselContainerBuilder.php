<?php

namespace App\Services\CarouselContainerBuilder;

use App\Models\Todo;
use App\Models\LineBotSvg;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;

/**
 * Todoのカル-セル生成クラス
 */
class TodoCarouselContainerBuilder
{
    /**
     *
     * Todoをカウントした結果の数を表示するBubbleContainer
     *
     * @param string $todo_type
     * @param int $count_todo_list
     * @return \LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
     */
    public static function createCountTodoBubbleContainer(string $todo_type, int $count_todo_list)
    {
        $result_count_todo_list_text = '📝' . ' ' . $count_todo_list;
        $result_count_todo_list_text_component  = new TextComponentBuilder($result_count_todo_list_text, 1);
        $result_count_todo_list_text_component->setGravity('bottom');
        $result_count_todo_list_text_component->setAlign('center');
        $result_count_todo_list_text_component->setSize('5xl');
        $result_count_todo_list_text_component->setOffsetBottom('8px');

        $report_count_todo_list_text = $todo_type . 'が' . $count_todo_list . '件見つかりました';
        $report_count_todo_list_text_component  = new TextComponentBuilder($report_count_todo_list_text, 1);
        $report_count_todo_list_text_component->setAlign('center');
        $report_count_todo_list_text_component->setWeight('bold');
        $report_count_todo_list_text_component->setWrap(true);

        $texts = [
            $result_count_todo_list_text_component,
            $report_count_todo_list_text_component
        ];
        $body_box = new BoxComponentBuilder('vertical', $texts);
        $body_box->setSpacing('lg');

        $bubble_container = new BubbleContainerBuilder();
        $bubble_container->setBody($body_box);
        return $bubble_container;
    }

    /**
     *
     * コンポーネントをひとまとめ。BubbleContainerの生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
     */
    public static function createTodoBubbleContainer(Todo $todo)
    {
        $bubble_container = new BubbleContainerBuilder();
        $bubble_container->setHeader(TodoCarouselContainerBuilder::createHeaderComponent($todo));
        $bubble_container->setBody();
        return $bubble_container;
    }

    /**
     *
     * Header
     *
     **/

    /**
     *
     * ヘッダーに必要なコンポーネント総集め。Headerコンポーネントの生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder
     */
    public static function createHeaderComponent(Todo $todo)
    {
        $header_array = [
            TodoCarouselContainerBuilder::createSubtitleBoxComponent($todo),
            Todo::createDateBoxComponent($todo),
            Todo::createTitleComponent($todo),
            Todo::createAccomplishGageComponent($todo),
        ];
        $header_component = new BoxComponentBuilder('vertical', $header_array);
        $header_component->setBackgroundColor('#ffffff');
        $header_component->setPaddingTop('16px');
        $header_component->setPaddingAll('12px');
        $header_component->setPaddingBottom('24px');

        return $header_component;
    }

    /**
     *
     * サブタイトル
     *
     **/

    /**
     * Todoのサブタイトル（親Todo）をひとまとめ。
     * Boxのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder
     */
    public static function createSubtitleBoxComponent(Todo $todo)
    {
        $subtitle_text_component = TodoCarouselContainerBuilder::createSubtitleTextComponent($todo);
        $subtitle_icon_component = TodoCarouselContainerBuilder::createSubtitleIconComponent($todo);
        return new BoxComponentBuilder(
            'baseline',
            [$subtitle_icon_component, $subtitle_text_component]
        );
    }

    /**
     * Todoのサブタイトル（親Todo）のテキストコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder
     */
    public static function createSubtitleTextComponent(Todo $todo)
    {
        if ($todo->depth === 0) {
            $subtitle_text = 'プロジェクト:「' . $todo->project->name . '」のゴール';
        } else {
            $parent_todo = Todo::where('uuid', $todo->parent_uuid)->first();
            $todo_or_habit = count($todo->habit) > 0 ? '習慣' : 'こと';
            $subtitle_text = '「' . $parent_todo->name . '」のためにやる' . $todo_or_habit;
        }
        $subtitle_text_component = new TextComponentBuilder($subtitle_text);
        $subtitle_text_component->setSize("xxs");
        $subtitle_text_component->setColor("#aaaaaa");
        $subtitle_text_component->setMargin("4px");

        return $subtitle_text_component;
    }

    /**
     * Todoのサブタイトル（親Todo）のアイコンのコンポーネント生成ビルダー
     *
     * @param Todo $todo
     * @return \LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder
     */
    public static function createSubtitleIconComponent(Todo $todo)
    {
        $url = $todo->depth === 0 ? LineBotSvg::GOAL_FLAG : LineBotSvg::TODO_TREE;
        $icon_component_builder = new IconComponentBuilder(
            $url, // 画像URL
            null, // margin
            "lg", // size
            null // aspectoRatio
        );
        $icon_component_builder->setOffsetTop('5px');
        return $icon_component_builder;
    }
}
