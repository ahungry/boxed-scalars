<?php
/**
 *  BoxedScalars.php --- Because plain old strings suck.
 *
 *  Copyright (C) 2019  Matthew Carter
 *
 *  Author: Matthew Carter <m@ahungry.com>
 *  Maintainer: Matthew Carter <m@ahungry.com>
 *  URL: https://github.com/ahungry/ahungry-powerset
 *  Version: 0.0.1
 *
 *  License:
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Commentary:
 *
 *  All data with a name has some meaning, so it should be identified as such.
 *
 *  News:
 *
 *  Changes since 0.0.0:
 *  - Created the project
 *
 *  Code:
 */

namespace Com\Ahungry\BoxedScalars;

abstract class Str {
    public function __construct (string $s) {
        $s = $this->coerce($s);
        if (false === $this->assert($s)) {
            throw new \InvalidArgumentException(
                "'" . $s . "'" . ' is invalid for type: ' . get_class($this) . PHP_EOL
            );
        }
        $this->s = $s;
    }

    public function __toString () {
        return $this->s;
    }

    public function coerce ($s) {
        return (string) $s;
    }

    public function assert (string $s) {
        return true;
    }
}

abstract class Map {
    // Should be in the same order as our constructor args.
    public static function getSchema () { return []; }

    public static function make ($m) {
        $def = static::getSchema();
        $self = new static ();

        foreach ($def as $k => $v) {
            if (method_exists($v, 'make')) {
                $class = $v::make ($m[$k]);
            } else {
                $class = new $v($m[$k]);
            }
            $self->{$k} = $class;
        }

        return $self;
    }

    // public function __construct ($m) {
    //     $this->make($m);
    // }
}
