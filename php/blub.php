<?php

namespace Blub;

require __DIR__ . '/vendor/autoload.php';

use Com\Ahungry\BoxedScalars\Str;
use Com\Ahungry\BoxedScalars\Map;

// Nothing to enforce here, freeform data, but now the name is meaningful and future proof
// in all spots it is used.
class BusinessName extends Str {}

// Here, we want to actually use the data in some constrained way.
class Phone extends Str
{
    public function assert (string $s) {
        $len = strlen($s);
        if ($len !== 10) return false;
        preg_match('/\d+/', $s, $m);
        if (0 === count($m)) return false;
    }
}

class Business extends Map
{
    public function getSchema () {
        return [
            'name'  => BusinessName::class,
            'ph'    => Phone::class,
        ];
    }
}

class TwoBusinesses extends Map
{
    public function getSchema () {
        return [
            'b1' => Business::class,
            'b2' => Business::class,
        ];
    }
}

$good = new Phone('5551112222');
echo $good;

try {
    $bad = new Phone('1-800-order-pizza');
    echo $bad;
} catch (\Exception $e) {
    echo $e->getMessage();
}

// Sending too many keys - that's completely fine.
$business = new Business(['x' => 'y', 'name' => 'My Great Venture', 'ph' => '5553332222']);
var_dump ($business);

try {
    $businessBad = new Business(['x' => 'y', 'name' => 'My Great Venture', 'ph' => '']);
} catch (\Exception $e) {
    echo $e->getMessage();
}

$twoBusinesses = new TwoBusinesses(
    [
        'b1' => ['name' => 'Blabla', 'ph' => '5553332222'],
        'b2' => ['name' => 'Blabla', 'ph' => '5553332222'],
    ]
);
var_dump ($twoBusinesses);
