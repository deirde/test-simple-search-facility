<?php

class PriceBands extends Model {

    public static $_table = 'price_bands';

    public function Property() {

        return $this->has_one('Properties');

    }

    static function currency() {

        return new \NumberFormatter('en_GB', \NumberFormatter::CURRENCY);

    }

}

?>