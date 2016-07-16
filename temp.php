<?php
/**
 * Created by JetBrains PhpStorm.
 * User: abdullah alamin
 * Date: 6/29/14
 * Time: 2:30 AM
 * To change this template use File | Settings | File Templates.
 */
$array = array(
    '@attributes' => array(
        'advertiser' => '9-0ca9e4c2-807b-49bb-934a-40e03242d08d',
    ),
    'source' => array(
        'id' => $row['id'],
    ),
    'unit_details' => array(
        'kind' => $row['estateName'],
        'changeover_day' => 'flexible',
        'min_los' => $row['default_min_los'],
        'floor_area' => $row['id'],
        'title' => $row['title'],
        'headline' => $row['title'],
        'description' => array(
            'paragraph' => $row['content'],
            'max_occupancy' => $row['room'],
            'bedrooms' => array(
                '@attributes' => array(
                    'number' => $row['room'],
                )
            ),
            'bathrooms' => array(
                '@attributes' => array(
                    'number' => $row['bathroom'],
                )
            ),
            'images' => array(
                '@attributes' => array(
                    'last_updated' => date('Y-m-dTH:i:s')//'2012-05-07T19:34:46.743Z',
                ),
                'image' => self::get_HW_Images($row['photoGallery']),

            ),
            'latitude' => $row['lat'],
            'longitude' => $row['lng'],
            'country' => $row['country'],
            'state' => $row['province'],
            'city' => $row['city'],
            'street' => $row['address'],
            'postcode' => $row['postal_code'],
            /*'locale' => array(
                'description' => array(
                    'paragraph' => "Just a ten-minute walk from the shore of beautiful Lake Travisâ€”one of the most desired locations in the region for outdoor recreation, including fishing, boating, swimming, and picnicking."),
            ),*/
            'unit_attributes' => array(
                'attribute' => $row['aminities'],
            ),
        ),
        'availability' => array(
            '@attributes' => array(
                'available' => false,
            ),
            'range' => self::GetHW_Unavailability($row['unavailability']) ,
        ),
        'rates' => array(
            'description' => array(
                'paragraph' => "",
            ),
            'currency' => 'USD',
            'rate' => self::GetHW_Rates($row['optional_rates'])//array(
        ,
        ),
    ),
);