<?php

class Properties extends Model {

    public static $_table = 'properties';

    public function Locations() {

        return $this->has_one('Locations');

    }

    public function PriceBands() {

        return \Model::factory('PriceBands')->where('_fk_property', $this->__pk)->find_many();

    }

    public function Discounts() {

        return $this->has_many_through('Discounts');

    }

    public function Bookings() {

        return $this->has_many_through('Bookings');

    }

}

?>