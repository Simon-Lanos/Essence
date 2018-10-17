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
        foreach ($this->data as $key=>$value) {

            //si la ligne est un tableau
            if (is_array($this->data[$key])) {

                $partialData = $this->data[$key];

                $partials = '';
                //on parse le tableau
                foreach ($partialData as $va) {
                    //on charge la partial du nom du tableau
                    $partial = $this->loadPartial($key);
                    //on boucle sur les tableau pour remplacer les placeholder de la partial
                    foreach ($va as $k=>$v) {
                        $partial = str_replace('['.$k.']',$v, $partial);
                    }
                    //on stock la partial comléter dans cette variable
                    $partials .= $partial;
                }
                //on fini par rempacer la valeur de la ligne de départ par notre chaine de charactère
                $this->template = str_replace('['.$key.']',$partials, $this->template);
            }
            else {
                $this->template = str_replace('['.$key.']',$value, $this->template);
            }
        }
    }

    private function loadPartial($partial)
    {
        $partial = file_get_contents('./Assets/partials/'.$partial.'.php');
        return $partial;
    }
}
