<?php 

namespace Phalcon {

	abstract class Db {

		const FETCH_LAZY = 1;

		const FETCH_ASSOC = 2;

		const FETCH_NAMED = 11;

		const FETCH_NUM = 3;

		const FETCH_BOTH = 4;

		const FETCH_OBJ = 5;

		const FETCH_BOUND = 6;

		const FETCH_COLUMN = 7;

		const FETCH_CLASS = 8;

		const FETCH_INTO = 9;

		const FETCH_FUNC = 10;

		const FETCH_GROUP = 65536;

		const FETCH_UNIQUE = 196608;

		const FETCH_KEY_PAIR = 12;

		const FETCH_CLASSTYPE = 262144;

		const FETCH_SERIALIZE = 524288;

		const FETCH_PROPS_LATE = 1048576;

		public static function setup($options){ }

	}
}
