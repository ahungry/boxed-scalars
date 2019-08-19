# Boxed Scalars

Because working with plain old strings sucks, but working with fancy
strings is great.

# Language samples

## PHP

```php
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
    public function coerce ($s) {
        return preg_replace('/\D/', '', $s);
    }

    public function assert (string $s) {
        $len = strlen($s);
        if ($len !== 10) return false;
        preg_match('/\d+/', $s, $m);
        if (0 === count($m)) return false;
    }
}

class Business extends Map
{
    public static function getSchema () {
        return [
            'name'  => BusinessName::class,
            'ph'    => Phone::class,
        ];
    }
}

class TwoBusinesses extends Map
{
    public static function getSchema () {
        return [
            'b1' => Business::class,
            'b2' => Business::class,
        ];
    }
}

$good = new Phone('5551112222');
echo $good;

try {
    // Still bad even with coercion because it would be too short
    $bad = new Phone('1-800-order-pizza');
    echo $bad;
} catch (\Exception $e) {
    echo $e->getMessage();
}

// Sending too many keys - that's completely fine.
$business = Business::make(['x' => 'y', 'name' => 'My Great Venture', 'ph' => '5553332222']);
var_dump ($business);

try {
    $businessBad = Business::make(['x' => 'y', 'name' => 'My Great Venture', 'ph' => '']);
} catch (\Exception $e) {
    echo $e->getMessage();
}

$twoBusinesses = TwoBusinesses::make(
    [
        'b1' => ['name' => 'Blabla', 'ph' => '5553332222'],
        'b2' => ['name' => 'Blabla', 'ph' => '5553332222'],
    ]
);

var_dump ($twoBusinesses);
```
