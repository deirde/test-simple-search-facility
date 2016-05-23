<?php

/**
 * Simple search facility.
 */
namespace Deirde\SimpleSearchFacility {

    require_once 'config.php';

    /**
     * Import models.
     */
    require_once 'models' . DIRECTORY_SEPARATOR . 'Bookings.php';
    require_once 'models' . DIRECTORY_SEPARATOR . 'Department.php';
    require_once 'models' . DIRECTORY_SEPARATOR . 'Discounts.php';
    require_once 'models' . DIRECTORY_SEPARATOR . 'Employee.php';
    require_once 'models' . DIRECTORY_SEPARATOR . 'Locations.php';
    require_once 'models' . DIRECTORY_SEPARATOR . 'PriceBands.php';
    require_once 'models' . DIRECTORY_SEPARATOR . 'Properties.php';

    class Results {

        public $location;
        public $nearBeach;
        public $acceptsPets;
        public $sleeps;
        public $beds;
        public $from;

        private $request = [];

        private $_pagination = 2;
        private $_offset = 1;
        public $_currentPage = 1;

        public function __construct($request) {

            $request = $this->sanitize($request);
            $request = $this->filterRules($request);

            $this->request = $request;

            /**
             * Attributes.
             */
            $this->atrLocation($request);
            $this->atrNearBeach($request);
            $this->atrAcceptsPets($request);
            $this->atrSleeps($request);
            $this->atrBeds($request);
            $this->atrFrom($request);

            $this->atrCurrentPage($request);

            /**
             * Page outside the range.
             */
            if ($this->_currentPage < 1 || $this->_currentPage > $this->_totalPages()) {
                $this->_currentPage = 1;
            }

        }

        /**
         * @return \GUMP
         * https://github.com/Wixel/GUMP
         */
        private function gump() {

            return new \GUMP();

        }

        /**
         * @param $request
         * @return array
         */
        private function sanitize($request) {

            return $this->gump()->sanitize($request);

        }

        /**
         * @param $request
         * @return array
         */
        private function filterRules($request) {

            $this->gump()->filter_rules(array(
                'location' => 'trim|sanitize_string',
                'near_beach' => 'trim|sanitize_numbers',
                'accepts_pets' => 'trim|sanitize_numbers',
                'sleeps' => 'trim|sanitize_numbers',
                'beds' => 'trim|sanitize_numbers',
                'page' => 'trim|sanitize_numbers',
            ));

            return $this->gump()->run($request);

        }

        /**
         * @param $request
         */
        private function atrLocation($request) {

            if (isset($request['location'])) {
                $this->location = $request['location'];
            }

        }

        /**
         * @param $request
         */
        private function atrNearBeach($request) {

            if (isset($request['near_beach'])) {
                $this->nearBeach = $request['near_beach'];
            }

        }

        /**
         * @param $request
         */
        private function atrAcceptsPets($request) {

            if (isset($request['accepts_pets'])) {
                $this->acceptsPets = $request['accepts_pets'];
            }

        }

        /**
         * @param $request
         */
        private function atrSleeps($request) {

            if (isset($request['sleeps'])) {
                $this->sleeps = $request['sleeps'];
            }

        }

        /**
         * @param $request
         */
        private function atrBeds($request) {

            if (isset($request['beds'])) {
                $this->beds = $request['beds'];
            }

        }

        /**
         * @param $request
         */
        private function atrFrom($request) {

            if (isset($request['from'])) {
                $this->from = $request['from'];
            }

        }

        /**
         * @param $request
         */
        private function atrCurrentPage($request) {

            if (isset($request['page'])) {
                $this->_currentPage = $request['page'];
            }

        }

        /**
         * @return bool|\ORM
         */
        private function location() {

            $response = \Model::factory('Locations')
                ->where_like('location_name', '%' . $this->location . '%')
                ->find_one();

            return $response;


        }

        /**
         * Not submitted.
         */
        private function emptyRequest() {

            return empty(array_keys($this->request));

        }

        /**
         * @return bool|\ORM|\ORMWrapper
         */
        private function properties() {

            $m_location = $this->location();
            $mm_properties = \Model::factory('Properties');
            if ($this->location && !$m_location) {

                $mm_properties = false;

            } else {

                if ($this->location) {
                    $mm_properties = $mm_properties->where('_fk_location', $m_location->__pk);
                }

                if ($this->nearBeach) {
                    $mm_properties = $mm_properties->where('near_beach', $this->nearBeach);
                }

                if ($this->acceptsPets) {
                    $mm_properties = $mm_properties->where('accepts_pets', $this->acceptsPets);
                }

                if ($this->sleeps) {
                    $mm_properties = $mm_properties->where_gte('sleeps', $this->sleeps);
                }

                if ($this->beds) {
                    $mm_properties = $mm_properties->where_gte('beds', $this->beds);
                }

            }

            return $mm_properties;

        }

        /**
         * @return int
         */
        public function _totals() {

            $response = false;

            if (!$this->emptyRequest() && $this->properties()) {
                $response = $this->properties()->count();
            }

            return $response;

        }

        /**
         * @return float
         */
        public function _totalPages() {

            return ceil($this->_totals() / $this->_pagination);

        }

        /**
         * @return mixed
         */
        public function _offset() {

            return ($this->_currentPage - 1) * $this->_offset;

        }

        /**
         * @return string
         */
        public function _paginationBaseUrl() {

            $response = '';

            if ($this->location) {
                $response .= 'location=' . $this->location;
            }

            if ($this->nearBeach) {
                $response .= '&near_beach=' . $this->nearBeach;
            }

            if ($this->acceptsPets) {
                $response .= '&accepts_pets=' . $this->acceptsPets;
            }

            if ($this->sleeps) {
                $response .= '&sleeps=' . $this->sleeps;
            }

            if ($this->beds) {
                $response .= '&beds=' . $this->beds;
            }

            return $response;

        }

        /**
         * @return array|bool|\IdiormResultSet
         */
        public function results() {

            $response = false;

            if (!$this->emptyRequest() && $this->properties()) {
                $response = $this->properties()->limit($this->_pagination)->offset($this->_offset())->find_many();
            }

            return $response;

        }

    }

}

?>