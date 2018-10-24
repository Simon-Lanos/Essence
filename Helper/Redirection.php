<?php

namespace Helper;


class Redirection
{
    /**
     * @param $link string Controller::Action
     */
    static function redirectTo($link = null)
    {
        if (empty($link)) {
            $url = 'http://localhost/essence/';
        } else {
            $link = explode('::', $link);
            $url = '?controller='.$link[0].'&action='.$link[1];
        }

        $header = 'Location: ' . $url;

        header($header);
    }
}
