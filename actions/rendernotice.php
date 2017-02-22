<?php

if (!defined('GNUSOCIAL')) {
        exit(1);
}

class RenderNoticeAction extends Action
{
    private $rendered;

    function prepare(array $args=array())
    {
        parent::prepare($args);

        $user = common_current_user();
        $profile = $user->getProfile();

        $raw_content = $this->arg("raw_content");
        $parent_notice_id = $this->int("parent_notice");

        $this->rendered = $this->render($raw_content, $profile, $parent_notice_id);

        return true;
    }

    function render($raw_content, $profile, $parent_notice_id)
    {
        $parent_notice = Notice::getKV('id', $parent_notice_id);

        if (!$parent_notice instanceof Notice) {
            $parent_notice = null;
        }

        if (Event::handle('ChrStartRenderNotice', array(&$raw_content, $profile, &$render, $parent_notice))) {
            if ($render !== false) {
                $raw_content = common_render_content($raw_content, $profile, $parent_notice);
            }
        }

        Event::handle('ChrEndRenderNotice', array(&$raw_content, $profile, $parent_notice));

        return $raw_content;
    }

    function handle()
    {
        parent::handle();

        $this->showPage();
    }

    function showPage()
    {
        // TODO: handle non-ajax calls
        if (GNUsocial::isAjax()) {
            $this->startHTML('text/xml;charset=utf-8');
            $this->elementStart('head');
            $this->element('title', null, 'Rendered Notice');
            $this->elementEnd('head');
            $this->elementStart('body');

            $this->elementStart('div', array('id' => 'chr-rendered-notice'));
            $this->raw($this->rendered);
            $this->elementEnd('div');

            $this->elementEnd('body');
            $this->endHTML();
            exit();
        }
    }
}

