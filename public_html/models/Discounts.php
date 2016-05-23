<?php

class Discounts extends Model {

    public static $_table = 'discounts.php';

    public function Property() {

        return $this->has_one('Properties');

    }

}

?>