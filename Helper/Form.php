<?php

namespace Helper;

class Form
{
    const TEXT_TYPE = 1;
    const EMAIL_TYPE = 2;
    const PASSWORD_TYPE = 3;
    const SUBMIT_TYPE = 4;

    private $form;
    private $register;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->form[0] = array();
        $this->register = array();
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param $name string
     * @param $type
     * @param $options null|array
     * @return $this
     */
    public function add($name, $type, $options = null)
    {
        switch ($type) {
            case 1:
                $entry = $this->getTextEntry($name, $options);
                break;
            case 2:
                $entry = $this->getEmailEntry($name, $options);
                break;
            case 3:
                $entry = $this->getPasswordEntry($name, $options);
                break;
            case 4:
                $entry = $this->getSubmit($name, $options);
                break;
            default:
                die('The type '.$type.' does not exist');
        }

        if ($type != Form::SUBMIT_TYPE) {
            $this->register($name);
        }

        $this->form[0][$name] = $entry;
        return $this;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getTextEntry($name, $options)
    {

        if ($options == null) {
            $entry = '
                    <label for="'.$name.'">'.$name.'</label>
                    <input id="'.$name.'" type="text" name="'.$name.'">
                    ';
        } else {
            $entry = '
                        <label for="'.$name.'" class="{labelClass}">{label}</label>
                        <input class="{class}" type="text">
                    ';

            if (isset($options['label'])) {
                $entry = str_replace('{label}', $options['label'], $entry);
            } else {
                $entry = str_replace('{label}', $name, $entry);
            }

            if (isset($options['class'])) {
                $entry = str_replace('{class}', $options['label'], $entry);
            } else {
                $entry = str_replace('{class}', '', $entry);
            }

            if (isset($options['labelClass'])) {
                $entry = str_replace('{labelClass}', $options['labelClass'], $entry);
            } else {
                $entry = str_replace('{labelClass}', '', $entry);
            }
        }

        return $entry;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getEmailEntry($name, $options)
    {

        if ($options == null) {
            $entry = '
                    <label for="'.$name.'">'.$name.'</label>
                    <input id="'.$name.'" type="email" name="'.$name.'">
                    ';
        } else {
            $entry = '
                        <label for="'.$name.'" class="{labelClass}">{label}</label>
                        <input class="{class}" type="email">
                    ';

            if (isset($options['label'])) {
                $entry = str_replace('{label}', $options['label'], $entry);
            } else {
                $entry = str_replace('{label}', $name, $entry);
            }

            if (isset($options['class'])) {
                $entry = str_replace('{class}', $options['label'], $entry);
            } else {
                $entry = str_replace('{class}', '', $entry);
            }

            if (isset($options['labelClass'])) {
                $entry = str_replace('{labelClass}', $options['labelClass'], $entry);
            } else {
                $entry = str_replace('{labelClass}', '', $entry);
            }
        }

        return $entry;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getPasswordEntry($name, $options)
    {

        if ($options == null) {
            $entry = '
                    <label for="'.$name.'">'.$name.'</label>
                    <input id="'.$name.'" type="password" name="'.$name.'">
                    ';
        } else {
            $entry = '
                        <label for="'.$name.'" class="{labelClass}">{label}</label>
                        <input class="{class}" type="password">
                    ';

            if (isset($options['label'])) {
                $entry = str_replace('{label}', $options['label'], $entry);
            } else {
                $entry = str_replace('{label}', $name, $entry);
            }

            if (isset($options['class'])) {
                $entry = str_replace('{class}', $options['label'], $entry);
            } else {
                $entry = str_replace('{class}', '', $entry);
            }

            if (isset($options['labelClass'])) {
                $entry = str_replace('{labelClass}', $options['labelClass'], $entry);
            } else {
                $entry = str_replace('{labelClass}', '', $entry);
            }
        }

        return $entry;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getSubmit($name, $options)
    {

        if ($options == null) {
            $entry = '<button type="submit">'.$name.'</button>';
        } else {
            $entry = '<button class="{class}" type="submit">{label}</button>';

            if (isset($options['label'])) {
                $entry = str_replace('{label}', $options['label'], $entry);
            } else {
                $entry = str_replace('{label}', $name, $entry);
            }

            if (isset($options['class'])) {
                $entry = str_replace('{class}', $options['label'], $entry);
            } else {
                $entry = str_replace('{class}', '', $entry);
            }
        }

        return $entry;
    }

    /**
     * @param $name string
     */
    private function register($name)
    {
        if (!in_array($name, $this->register)) {
            $this->register[] = $name;
        } else {
            throw new \DomainException('One field is already named '.$name);
        }
    }
}
