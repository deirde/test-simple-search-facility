<?php

class Locations extends Model {

    public static $_table = 'locations';

    public function Properties() {

        return $this->has_many_through('Properties');

    }

}

?>