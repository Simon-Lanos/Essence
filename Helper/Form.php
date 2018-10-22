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
    private $data;
    private $isSubmitted;
    private $isHandled;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->form[0] = array();
        $this->register = array();
        $this->data = array();
        $this->isSubmitted = false;
        $this->isHandled = false;
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Ask the FormHelper to handle the request
     */
    public function handleRequest()
    {
        $this->handle();
        //try to fetch inputs that are register on the current form
        foreach ($this->register as $field) {
            if (isset($_POST[$field['name']])) {
                //pull the input value into the data array
                $this->data[$field['name']] = htmlspecialchars($_POST[$field['name']]);
                //set the form to a submitted state
                $this->submit();
            }
        }
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->isSubmitted;
    }

    public function getData()
    {
        if ($this->isHandled()) {
            return $this->data;
        } else {
            throw new \DomainException('The Form is not handled');
        }
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
                $field = $this->getTextInput($name, $options);
                break;
            case 2:
                $field = $this->getEmailInput($name, $options);
                break;
            case 3:
                $field = $this->getPasswordInput($name, $options);
                break;
            case 4:
                $field = $this->getSubmitButton($name, $options);
                break;
            default:
                die('The type '.$type.' does not exist');
        }

        if ($type != Form::SUBMIT_TYPE) {
            $this->register($name, $type);
        }

        $this->form[0][$name] = $field;
        return $this;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getTextInput($name, $options)
    {

        if ($options == null) {
            $input = '
                    <label for="'.$name.'">'.$name.'</label>
                    <input id="'.$name.'" type="text" name="'.$name.'">
                    ';
        } else {
            $input = '
                        <label for="'.$name.'" class="{labelClass}">{label}</label>
                        <input id="'.$name.'" class="{class}" type="text" name="'.$name.'">
                    ';

            if (isset($options['label'])) {
                $input = str_replace('{label}', $options['label'], $input);
            } else {
                $input = str_replace('{label}', $name, $input);
            }

            if (isset($options['class'])) {
                $input = str_replace('{class}', $options['label'], $input);
            } else {
                $input = str_replace('{class}', '', $input);
            }

            if (isset($options['labelClass'])) {
                $input = str_replace('{labelClass}', $options['labelClass'], $input);
            } else {
                $input = str_replace('{labelClass}', '', $input);
            }
        }

        return $input;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getEmailInput($name, $options)
    {

        if ($options == null) {
            $input = '
                    <label for="'.$name.'">'.$name.'</label>
                    <input id="'.$name.'" type="email" name="'.$name.'">
                    ';
        } else {
            $input = '
                        <label for="'.$name.'" class="{labelClass}">{label}</label>
                        <input id="'.$name.'" class="{class}" type="email" name="'.$name.'">
                    ';

            if (isset($options['label'])) {
                $input = str_replace('{label}', $options['label'], $input);
            } else {
                $input = str_replace('{label}', $name, $input);
            }

            if (isset($options['class'])) {
                $input = str_replace('{class}', $options['label'], $input);
            } else {
                $input = str_replace('{class}', '', $input);
            }

            if (isset($options['labelClass'])) {
                $input = str_replace('{labelClass}', $options['labelClass'], $input);
            } else {
                $input = str_replace('{labelClass}', '', $input);
            }
        }

        return $input;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getPasswordInput($name, $options)
    {

        if ($options == null) {
            $input = '
                    <label for="'.$name.'">'.$name.'</label>
                    <input id="'.$name.'" type="password" name="'.$name.'">
                    ';
        } else {
            $input = '
                        <label for="'.$name.'" class="{labelClass}">{label}</label>
                        <input id="'.$name.'" class="{class}" type="password" name="'.$name.'">
                    ';

            if (isset($options['label'])) {
                $input = str_replace('{label}', $options['label'], $input);
            } else {
                $input = str_replace('{label}', $name, $input);
            }

            if (isset($options['class'])) {
                $input = str_replace('{class}', $options['label'], $input);
            } else {
                $input = str_replace('{class}', '', $input);
            }

            if (isset($options['labelClass'])) {
                $input = str_replace('{labelClass}', $options['labelClass'], $input);
            } else {
                $input = str_replace('{labelClass}', '', $input);
            }
        }

        return $input;
    }

    /**
     * @param $name string
     * @param $options array|null
     * @return string
     */
    private function getSubmitButton($name, $options)
    {

        if ($options == null) {
            $button = '<button type="submit">'.$name.'</button>';
        } else {
            $button = '<button class="{class}" type="submit">{label}</button>';

            if (isset($options['label'])) {
                $button = str_replace('{label}', $options['label'], $button);
            } else {
                $button = str_replace('{label}', $name, $button);
            }

            if (isset($options['class'])) {
                $button = str_replace('{class}', $options['label'], $button);
            } else {
                $button = str_replace('{class}', '', $button);
            }
        }

        return $button;
    }

    /**
     * @param $name string
     * @param $type int
     */
    private function register($name, $type)
    {
        if (!in_array($name, $this->register)) {
            $this->register[] = [
                'name' => $name,
                'type' => $type
            ];
        } else {
            throw new \DomainException('One field is already named '.$name);
        }
    }

    /**
     * set isSubmitted to true
     */
    private function submit()
    {
        $this->isSubmitted = true;
    }

    /**
     * set isHandle to true
     */
    private function handle()
    {
        $this->isHandled = true;
    }

    private function isHandled()
    {
        return $this->isHandled;
    }
}
