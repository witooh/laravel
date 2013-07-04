<?php

use Witooh\Extender\Eloquent;

class Content extends Eloquent {

    protected $table="content";

    public function category(){
        return $this->belongsTo('Category');
    }
}