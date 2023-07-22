<?php


namespace Hoho9155\CityByPostcodeTrait;

trait CityByPostcodeTrait
{
    public function getCity($postcode) 
    {
        $api_key = env('GOOGLE_MAPS_GEOCODING_API', '');

        // Replace spaces with plus signs in the postcode
        $postcode = urlencode($postcode);

        // Google Maps Geocoding API endpoint
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$postcode}&key={$api_key}";

        // Use an HTTP client to make the request (GuzzleHTTP in this example)
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        // Parse the JSON response
        $data = json_decode($response->getBody(), true);

        // Check if the response status is OK
        if ($data['status'] === 'OK') {
            // Extract the city name from the results
            foreach ($data['results'][0]['address_components'] as $component) {
                if (in_array('locality', $component['types'])) {
                    return $component['long_name'];
                }
            }
        }

        // Return null if the city name couldn't be found
        return null;
    }
}