<?php

namespace LTools\Libraries;
use Illuminate\Support\Collection;
use JsonSerializable;

class VueMessage implements JsonSerializable
{
    protected $data;

    public function __construct()
    {
        $this->data = Collection::make();
    }

    /**
     * @param $code
     * @param $message
     * @return void
     */
    public function add($code, $message){
        $this->data->add([
            'code'=>$code, 'message'=>$message
        ]);
    }


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }
}
