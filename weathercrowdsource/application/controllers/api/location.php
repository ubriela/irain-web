<?php
/**
 * @SWG\Definition(definition="location", required={"latitude", "longitude"})
 */
class Location
{
    /**
     * @SWG\Property(type="float", format="float")
     */
    public $latitude;
    
    /**
     * @SWG\Property(type="float", format="float")
     */
    public $longitude;
}