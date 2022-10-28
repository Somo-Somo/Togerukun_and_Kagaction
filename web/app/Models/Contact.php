<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_uuid',
        'text',
        'contact_type',
        'created_at'
    ];

    const CONTACT = [
        'SELECT_CONTACT' => true,
        'SELECT_FEEDBACK' => true
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
     * どのくらいの頻度でやるか聞く
     *
     * @return \LINE\LINEBot\MessageBuilder\MultiMessageBuilder()
     */
    public static function createContactOrFeedbackMessageBuilder()
    {
        $actions = [];
        $select_message = 'お問い合わせまたは感想・要望どちらかお選びください。';
        $contactOrFeedback = ['お問い合わせ', '感想・要望'];
        foreach ($contactOrFeedback as $key => $either) {
            $text_component  = new TextComponentBuilder($either, 1);
            $text_component->setWeight('bold');
            $text_component->setGravity('center');
            $text_component->setAlign('center');
            $text_component_builders = [$text_component];
            if ($either === 'お問い合わせ') {
                $template_action_builder = new UriTemplateActionBuilder(
                    $either,
                    'https://forms.gle/CvCrCiu49zyvBe2W6'
                );
            } else {
                $template_action_builder = new PostbackTemplateActionBuilder(
                    $either,
                    'action=SELECT_FEEDBACK&value='
                );
            }

            $box_component = new BoxComponentBuilder('vertical', $text_component_builders);
            $box_component->setAction($template_action_builder);
            $box_component->setHeight('80px');
            $bubble_container = new BubbleContainerBuilder();
            $bubble_container->setBody($box_component);
            $bubble_container->setSize('nano');
            $actions[] = $bubble_container;
        }
        $carousels = new CarouselContainerBuilder($actions);
        $multi_message_builder = new MultiMessageBuilder();
        $multi_message_builder->add(new TextMessageBuilder($select_message));
        $multi_message_builder->add(new FlexMessageBuilder($select_message, $carousels));
        return $multi_message_builder;
    }
}
