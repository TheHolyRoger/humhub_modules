<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use humhub\modules\devtools\models\forms\RichtextModel;
use humhub\widgets\Button;
use humhub\widgets\ModalButton;
use humhub\widgets\RichtextField;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this \humhub\components\View */
?>

<script>
    humhub.module('demo.richtext.preset', function (module, require, $) {
        let richtext = require('ui.richtext.prosemirror');

        let underline = {
            id: 'underline',
            schema: {
                marks: {
                    underline: {
                        parseDOM: [{tag: "u"}],
                        toDOM: () => {
                            return ["u"]
                        },
                        parseMarkdown: {u: {mark: "underline"}},
                        toMarkdown: {open: "_", close: "_", mixable: true, expelEnclosingWhitespace: true}
                    }
                }
            },
            registerMarkdownIt: (markdownIt) => {
                function renderEm (tokens, idx, opts, _, slf) {
                    var token = tokens[idx];
                    if (token.markup === '_') {
                        token.tag = 'u';
                    }
                    return slf.renderToken(tokens, idx, opts);
                }

                markdownIt.renderer.rules.em_open = renderEm;
                markdownIt.renderer.rules.em_close = renderEm;
            },
            menu: function menu(options) {
                return [
                    {
                        id: 'markUnderline',
                        mark: 'underline',
                        group: 'marks',
                        item: richtext.api.menu.markItem(options.schema.marks.underline, {
                            title: "Toggle underline",
                            icon: {
                                width: 24, height: 28,
                                path:'M0.75 3.484c-0.281-0.016-0.516-0.016-0.703-0.063l-0.047-1.375c0.203-0.016 0.406-0.016 0.625-0.016 0.547 0 1.141 0.016 1.75 0.063 1.469 0.078 2.344 0.109 2.594 0.109 0.891 0 1.766-0.016 2.625-0.047 0.844-0.031 1.609-0.063 2.281-0.078 0.656 0 1.109-0.016 1.344-0.031l-0.016 0.219 0.031 1v0.141c-0.625 0.094-1.266 0.141-1.937 0.141-0.625 0-1.031 0.125-1.234 0.391-0.141 0.156-0.203 0.844-0.203 2.063 0 0.375 0.016 0.672 0.016 0.906l0.016 3.578 0.219 4.375c0.063 1.266 0.313 2.312 0.797 3.156 0.359 0.609 0.859 1.094 1.5 1.437 0.938 0.5 1.859 0.734 2.766 0.734 1.062 0 2.063-0.141 2.984-0.438 0.547-0.172 1.062-0.422 1.547-0.797 0.484-0.359 0.828-0.688 1.016-1 0.406-0.625 0.672-1.234 0.828-1.781 0.219-0.766 0.328-1.953 0.328-3.578 0-2.797-0.203-2.875-0.438-6.406l-0.063-0.922c-0.047-0.672-0.156-1.141-0.375-1.375-0.344-0.359-0.75-0.547-1.203-0.531l-1.563 0.031-0.219-0.047 0.031-1.344h1.313l3.203 0.156c1.062 0.047 2.078-0.047 3.063-0.156l0.281 0.031c0.063 0.391 0.094 0.656 0.094 0.797s-0.031 0.297-0.063 0.484c-0.422 0.109-0.859 0.187-1.313 0.203-0.734 0.109-1.156 0.187-1.234 0.266-0.141 0.141-0.234 0.344-0.234 0.641 0 0.203 0.031 0.516 0.047 0.906 0 0 0.125 0.281 0.344 6.188 0.078 2.359-0.078 3.953-0.234 4.75s-0.375 1.437-0.641 1.906c-0.406 0.688-1 1.328-1.75 1.922-0.766 0.578-1.703 1.047-2.844 1.391s-2.469 0.516-3.984 0.516c-1.719 0-3.203-0.234-4.438-0.719s-2.172-1.125-2.797-1.906-1.062-1.797-1.297-3.047c-0.172-0.859-0.25-2.094-0.25-3.703v-5.203c0-1.969-0.094-3.078-0.266-3.328-0.25-0.359-1.016-0.578-2.297-0.609zM24 25.5v-1c0-0.281-0.219-0.5-0.5-0.5h-23c-0.281 0-0.5 0.219-0.5 0.5v1c0 0.281 0.219 0.5 0.5 0.5h23c0.281 0 0.5-0.219 0.5-0.5z'
                            },
                            sortOrder: 410})
                    }
                ]
            }
        };

        richtext.api.plugin.registerPlugin(underline);
        richtext.api.plugin.registerPreset('demo', {
            extend: 'full',
            exclude: ['emoji'],
            callback: function(addToPreset) {
                // Note the order here is important since the new plugin kind of overrules the em in some situations.
                addToPreset('underline', 'demo', {before: 'em'})
            }
        });
    });
</script>

<?php $form = ActiveForm::begin() ?>

<?= $form->field(new RichtextModel(), 'richtext')->widget(humhub\modules\content\widgets\richtext\RichTextField::class, [
        'id' => 'custom_richtext',
        'preset' => 'demo'
]) ?>

<br>

<?= ModalButton::submitModal(Url::to(['/devtools/richtext/custom-preset']),  Yii::t('DevtoolsModule.base', 'Submit')) ?>

<?php ActiveForm::end() ?>


