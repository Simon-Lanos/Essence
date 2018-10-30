<?php

namespace Helper;

class Form
{
    const TEXT_TYPE = 1;
    const EMAIL_TYPE = 2;
    const PASSWORD_TYPE = 3;
    const SUBMIT_TYPE = 4;
    const FILE_TYPE = 5;

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
        $this->form = array();
        $this->register = array();
        $this->data = array();
        $this->isSubmitted = false;
        $this->isHandled = false;
    }

    /**
     * @return array
     */
    public function getForm()
    {
        return array($this->form);
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
            } elseif (isset($_FILES[$field['name']]) && $_FILES[$field['name']]['error'] === 0) {
                $this->data[$field['name']] = $_FILES[$field['name']];
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
     * @param $fieldName string
     * @param $type
     * @param $options null|array
     * @return $this
     */
    public function add($fieldName, $type, $options = array())
    {
        switch ($type) {
            case self::TEXT_TYPE:
                $field = $this->getInput($fieldName, 'text', $options);
                break;
            case self::EMAIL_TYPE:
                $field = $this->getInput($fieldName, 'email', $options);
                break;
            case self::PASSWORD_TYPE:
                $field = $this->getInput($fieldName, 'password', $options);
                break;
            case self::FILE_TYPE:
                $field = $this->getInput($fieldName, 'file', $options);
                break;
            case self::SUBMIT_TYPE:
                $field = $this->getSubmit($fieldName, $options);
                break;
            default:
                die('The type '.$type.' does not exist');
        }

        if ($type != Form::SUBMIT_TYPE) {
            $this->register($fieldName, $type);
        }

        $this->form[$fieldName] = $field;
        return $this;
    }

    /**
     * @param $fieldName string
     * @param $type string
     * @param $options array|null
     * @return string
     */
    private function getInput($fieldName, $type, $options)
    {
        if (empty($options)) {
            $input = '<input id="'.$fieldName.'" type="'.$type.'" name="'.$fieldName.'"/>';
            $label = '<label for="'.$fieldName.'">'.$fieldName.'</label>';
        } else {
            $input = '<input id="{id}" class="{class}"{required}type="'.$type.'" name="'.$fieldName.'"/>';
            $label = '<label for="{id}" class="{labelClass}">{label}</label>';
        }

        if (isset($options['label'])) {
            if ($options['label'] === false) {
                $label = '';
            } else {
                $label = str_replace('{label}', $options['label'], $label);
            }
        }

        if (isset($options['class'])) {
            $input = str_replace('{class}', $options['class'], $input);
        } else {
            $input = str_replace('{class}', '', $input);
        }

        if (isset($options['labelClass'])) {
            $label = str_replace('{labelClass}', $options['labelClass'], $label);
        } else {
            $label = str_replace('{labelClass}', '', $label);
        }

        if (isset($options['id'])) {
            $input = str_replace('{id}', $options['id'], $input);
            $label = str_replace('{id}', $options['id'], $label);
        } else {
            $input = str_replace('{id}', $fieldName, $input);
            $label = str_replace('{id}', $fieldName, $label);
        }

        if (isset($options['required'])) {
            $input = str_replace('{required}', ' required="true" ', $input);
        } else {
            $input = str_replace('{required}', ' ', $input);
        }

        $field = $label . $input;

        return $field;
    }

    /**
     * @param $fieldName string
     * @param $options array|null
     * @return string
     */
    private function getSubmit($fieldName, $options)
    {

        if ($options == null) {
            $button = '<button type="submit">'.$fieldName.'</button>';
        } else {
            $button = '<button class="{class}" type="submit">{label}</button>';

            if (isset($options['label'])) {
                $button = str_replace('{label}', $options['label'], $button);
            } else {
                $button = str_replace('{label}', $fieldName, $button);
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
