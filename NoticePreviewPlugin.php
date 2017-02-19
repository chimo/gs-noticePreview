<?php

if (!defined('GNUSOCIAL')) {
    exit(1);
}

class NoticePreviewPlugin extends Plugin
{
    const VERSION = '0.0.1';

    function onRouterInitialized($m)
    {
        $m->connect(
            'main/render-notice', array(
                    'action' => 'rendernotice'
                )
        );

        return true;
    }

    function onStartShowNoticeFormData($action)
    {
        $action->elementStart('div', array('id' => 'chr-notice-preview-container'));

        $action->elementStart('ul');

        $action->elementStart('li');
        $action->element('a', array('href' => '#chr-notice-compose'), 'Compose');
        $action->elementEnd('li');

        $action->elementStart('li');
        $action->element('a', array('href' => '#chr-notice-preview'), 'Preview');
        $action->elementEnd('li');

        $action->elementEnd('ul');

        $action->elementStart('div', array('id' => 'chr-notice-compose'));

        return true;
    }

    function onEndShowNoticeFormData($action)
    {
        // Close the 'Compose' container
        $action->elementEnd('div');

        // The 'Preview' container
        $action->elementStart('div', array('id' => 'chr-notice-preview'));
        // TODO: Get the content from the notice textbox and render it.
        $action->elementEnd('div');

        // Close the 'tabs' container
        $action->elementEnd('div');

        return true;
    }

    function onEndShowStyles($action)
    {
        $action->cssLink($this->path('css/chr-notice-preview.css'));
    }

    function onEndShowScripts($action)
    {
        $action->script($this->path('js/chr-notice-preview.js'));
    }

    function onPluginVersion(array &$versions)
    {
        $versions[] = array('name' => 'Notice Preview',
                            'version' => self::VERSION,
                            'author' => 'chimo',
                            'homepage' => 'https://github.com/chimo/gs-noticePreview',
                            'description' =>
                            // TRANS: Plugin description.
                            _m('Preview your notice before submitting it'));
        return true;
    }
}
