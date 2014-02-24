<?php

namespace DTA\MetadataBundle\Tests\Model\Data;
use DTA\MetadataBundle\Model\Data;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromArray()
    {
        $input = array(
            'publication_id' => '17223', 
            'person' => 'Anthus, Antonius', 
            'role' => 'synonym', 
            'comma_position' => '7', 
            'space_position' => '8', 
            'gnd' => null);
//        $person = Data\Person::createFromArray($input);
//        var_dump($input);
    }
}