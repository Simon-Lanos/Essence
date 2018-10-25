<?php

namespace Helper;


abstract class Navigation
{
    /**
     * @param array $navArray text => link
     * @param array $options
     * @return string
     */
    static function createNav(array $navArray, array $options = array())
    {
        if (isset($options['navClass'])) {
            $nav = '<ul class="' . $options['navClass'] . '">';
        } else {
            $nav = '<ul>';
        }
        foreach ($navArray as $text => $link) {
            $href = 'http://localhost/essence/';
            if (!empty($link)) {
                $link = explode('::', $link);
                $href .= '?controller=' . $link[0] . '&action=' . $link[1];
            }

            if (isset($options['elementsClass'])) {
                $li = '<li class="' . $options['elementsClass'] . '"><a href="' . $href . '">' . $text . '</a></li>';
            } else {
                $li = '<li><a href="' . $href . '">' . $text . '</a></li>';
            }

            $nav .= $li;
        }
        $nav .= '</ul>';

        return $nav;
    }

    /**
     * @param $link string Controller::Action
     */
    static function redirectTo($link = null)
    {
        $url = 'http://localhost/essence/';
        if (empty($link)) {
            $link = explode('::', $link);
            $url .= '?controller=' . $link[0] . '&action=' . $link[1];
        }

        $header = 'Location: ' . $url;

        header($header);
    }
}
