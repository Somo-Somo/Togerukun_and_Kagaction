<?php

namespace App\Services\CarouselContainerBuilder;

use App\Models\Todo;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
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
            Todo::createSubtitleBoxComponent($todo),
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
}
