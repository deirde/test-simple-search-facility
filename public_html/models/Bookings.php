<?php

class Bookings extends Model {

    public static $_table = 'bookings';

    public function Property() {

        return $this->has_one('Properties');

    }

}

?>