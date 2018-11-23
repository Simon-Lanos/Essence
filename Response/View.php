<?php

namespace App\Response;
class View
{

    private $header;
    private $template;
    private $footer;
    private $data;

    public function __construct($template, array $data = [], $header = 'header', $footer = 'footer')
    {
        try {
            $this->template = file_get_contents('./Assets/template/'.$template.'.php');
            $this->header= file_get_contents('./Assets/template/'.$header.'.php');
            $this->footer = file_get_contents('./Assets/template/'.$footer.'.php');
        }
        catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        $this->data = $data;
    }

    public function display()
    {
        $this->parse();
        return $this->header.$this->template.$this->footer;
    }

    private function parse()
    {
        foreach ($this->data as $key => $value) {
            //si la ligne est un tableau
            if (is_array($value)) {

                //initialise la partial
                $partialTemplate = $this->getPartial($key);
                $partial = '';

                //on boucle sur le tableau
                foreach ($value as $data) {
                    //ajoute un instance completé de la partial
                    $partial .= $this->loadPartial($partialTemplate, $data);
                }
                $this->template = str_replace('[' . $key . ']', $partial, $this->template);
            } else {
                $this->template = str_replace('[' . $key . ']', $value, $this->template);
            }
        }
    }

    /**
     * @param $partial
     * @return bool|string
     */
    private function getPartial($partial)
    {
        $partial = file_get_contents('./Assets/partials/'.$partial.'.php');
        return $partial;
    }

    /**
     * @param string $partial
     * @param array $partialData
     * @return string
     */
    private function loadPartial($partial, array $partialData)
    {
        foreach ($partialData as $key => $data) {
            if (is_array($data)) {

                $subPartial = $this->getPartial($key);
                $completeSubPartial = '';

                foreach ($data as $value) {
                    $completeSubPartial .= $this->loadPartial($subPartial, $value);
                }
                $partial = str_replace('[' . $key . ']', $completeSubPartial, $partial);
            } else {
                $partial = str_replace('[' . $key . ']', $data, $partial);
            }
        }
        return $partial;
    }
}
