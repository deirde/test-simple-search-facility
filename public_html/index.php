<?php

/**
 * The class.
 */
require_once 'Results.class.php';
$Results = new \Deirde\SimpleSearchFacility\Results($_REQUEST);

?>

<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.css.map" />
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>
        <script>
            $(function() {
                $(".datepicker").datepicker();
            });
        </script>
    </head>
    <body>
        <div clss="row">
            <div class="container">
                <div class="col-md-12">
                    <h1>
                        Simple search facility
                    </h1>
                </div>
            </div>
        </div>
        <div class="row">
            <form name="search" method="GET">
                <div class="container">
                    <div class="col-md-12">
                        <input type="text" name="location" class="form-control"
                           placeholder="Search.." value="<?php echo $Results->location; ?>">
                    </div>
                </div>
                <div class="container">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="near_beach"
                                   value="1" <?php echo (($Results->nearBeach == 1) ? 'checked' : null); ?>>Near the beach
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="accepts_pets"
                                   value="1" <?php echo (($Results->acceptsPets == 1) ? 'checked' : null); ?>>Accepts pets
                            </label>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="sleeps" placeholder="Sleeps (minimum)."
                           value="<?php echo $Results->sleeps; ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="beds" placeholder="Beds (minimum)."
                           value="<?php echo $Results->beds; ?>">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control datepicker" name="from" placeholder="From"
                           value="<?php echo $Results->from; ?>">
                    </div>
                </div>
                <div class="container">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-default">
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="container">

                <?php if ($Results->_totals() > 0) { ?>

                    <div class="col-md-12">
                        <?php echo 'Totals: ' . $Results->_totals(); ?>
                    </div>

                    <?php foreach ($Results->results() as $property) { ?>

                        <div class="col-md-12">
                            <h3>
                                <?php echo $property->property_name; ?>
                            </h3>
                            <br/>
                            <?php echo 'near_beach:' . $property->near_beach; ?>
                            <br/>
                            <?php echo 'accepts_pets:' . $property->accepts_pets; ?>
                            <br/>
                            <?php echo 'sleeps:' . $property->sleeps; ?>
                            <br/>
                            <?php echo 'beds:' . $property->beds; ?>
                            <br/>
                            <small>
                                <?php foreach ($property->PriceBands() as $priceBand) { ?>
                                    <?php echo $priceBand->start_date; ?> / <?php echo $priceBand->end_date; ?> -
                                    <?php echo $priceBand::currency()->getSymbol(NumberFormatter::CURRENCY_SYMBOL) . $priceBand->price; ?>
                                    <br/>
                                <?php } ?>
                            </small>
                            <hr/>

                        </div>

                    <?php } ?>

                    <nav>
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $Results->_totalPages(); $i++) { ?>

                                <li class="<?php echo (($Results->_currentPage == $i) ? 'active' : null); ?>">
                                    <a href="?<?php echo $Results->_paginationBaseUrl(); ?>&page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>

                            <?php } ?>

                        </ul>
                    </nav>

                <?php } else { ?>

                    <h3>
                        No results found.
                    </h3>

                <?php } ?>

            </div>
        </div>
    </body>
</html>
