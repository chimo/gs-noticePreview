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

        $raw_content = $this->arg("raw_content");
        $profile_id = $this->int("profile_id");

        $this->rendered = $this->render($raw_content, $profile_id);

        return true;
    }

    function render($raw_content, $profile_id)
    {
        $profile = Profile::getKV('id', $profile_id);

        Event::handle('ChrStartRenderNotice', array(&$raw_content, $profile/*, $parent*/));

        // TODO: we could try to figure out the parent notice, maybe
        $raw_content = common_render_content($raw_content, $profile, null);

        Event::handle('ChrEndRenderNotice', array(&$raw_content, $profile/*, $parent*/));

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

