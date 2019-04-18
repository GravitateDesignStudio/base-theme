<?php
namespace WPUtil\Vendor;

abstract class MaxMind
{
	private static $remote_db = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';
	private static $record_cache = [];
    private static $local_db = false;

    public static $default_location = [
        'lat' => 0,
        'lng' => 0,
        'zip' => ''
	];

	/**
	 * Set the default location for MaxMind requests
	 *
	 * @param float $lat
	 * @param float $lng
	 * @param string $zip
	 * @return void
	 */
	public static function set_default_location(float $lat, float $lng, string $zip = ''): void
	{
        self::$default_location['lat'] = $lat;
        self::$default_location['lng'] = $lng;
        self::$default_location['zip'] = $zip;
    }

	/**
	 * Set the local MaxMind DB file to use for requests
	 *
	 * @param string $db_file
	 * @return string
	 */
	public static function set_local_db(string $db_file): string
	{
        if (!is_string($db_file) || !file_exists($db_file)) {
            return '';
        }

        self::$local_db = $db_file;

        return self::$local_db;
    }

	/**
	 * Get position information by IP address
	 * Returns an object with 'lat', 'lng', and 'zip' properties
	 *
	 * @param string $ip
	 * @return object
	 */
	public static function get_latlng_by_IP(string $ip): object
	{
        $ret_obj = (object)[
			'lat' => 0,
			'lng' => 0,
			'zip' => ''
		];

    	$record = self::get_record_for_IP($ip);

    	if ($record === false) {
			// use the default zip/coords if the IP lookup fails
			$ret_obj->lat = self::$default_location['lat'];
			$ret_obj->lng = self::$default_location['lng'];
			$ret_obj->zip = self::$default_location['zip'];
		} else {
			$ret_obj->lat = $record->location->latitude ? $record->location->latitude : 0;
			$ret_obj->lng = $record->location->longitude ? $record->location->longitude : 0;
			$ret_obj->zip = $record->postal->code ? $record->postal->code : '';
		}

    	return $ret_obj;
	}

	/**
	 * Get the zip code for an IP address
	 *
	 * @param string $ip
	 * @return string
	 */
	public static function get_zip_by_IP(string $ip): string
	{
		$record = self::get_record_for_IP($ip);

    	if ($record === false) {
			return self::$default_location['zip'];
		}

		return $record->postal->code ? $record->postal->code : self::$default_location['zip'];
	}

	/**
	 * Get the raw MaxMind record for an IP address
	 *
	 * @param string $ip
	 * @return bool|object
	 */
	public static function get_record_for_IP(string $ip)
	{
		if (!isset(self::$record_cache[$ip])) {
			if (!self::$local_db) {
                error_log(__METHOD__.' - MaxMind DB path not set');
				return false;
			}

            if (!class_exists('\GeoIp2\Database\Reader')) {
                error_log(__METHOD__.' - MaxMind DB PHP class not available -- please install via Composer');
                return false;
            }

            try {
				$reader = new \GeoIp2\Database\Reader(self::$local_db);
				self::$record_cache[$ip] = $reader->city($ip);
			} catch (\GeoIp2\Exception\AddressNotFoundException $e) {
				return false;
			}
		}

		return self::$record_cache[$ip];
	}
}
