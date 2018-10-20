<?php

namespace App\Response;
class Data
{
    protected $json;
    protected $formated;

    public function __construct(array $data)
    {
        $this->json = $data;
        $this->formated = false;
        return $this->getJson();
    }

    private function formatJson()
    {
        $this->json = json_encode($this->json);
        $this->formated = true;
        return $this;
    }

    private function isFomated()
    {
        return $this->formated;
    }

    public function getJson()
    {
        if ($this->isFomated()) {
            return $this->json;
        }
        else {
            return $this->formatJson()->json;
        }
    }
}
