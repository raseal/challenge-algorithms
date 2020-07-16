<?php

function sym_of_two($first_set, $second_set): array
{
	$blacklist = [];
	$diff = [];
	$set_to_diff = [$first_set, $second_set];

	foreach($set_to_diff as $set) {
		$unique = array_unique($set);

		foreach($unique as $element) {
			if( isset($blacklist[$element]) || isset($diff[$element]) ) {
				$blacklist[$element] = $element;
				unset($diff[$element]);
				continue;
			}

			$diff[$element] = $element;
		}
	}

	sort($diff);

	return $diff;
}

function sym(...$args): array
{
	$given_sets = count($args);

	if($given_sets <= 1) {
		// Impossible cases, but.....
		return $args[0];
	}

	if($given_sets == 2) {
		// Our happy path :)
		return sym_of_two($args[0], $args[1]);
	}

	// We need to calculate the diff of the first two sets and remove them...
	$diff = sym_of_two($args[0], $args[1]);
	$remaining = array_slice($args, 2);

	//...and then we apply the sym until all sets are evaluated
	foreach($remaining as $set) {
		$diff = sym_of_two($diff, $set);
	}

	return $diff; 

}

// Let's try the algorithm with some tests!
try {
	evaluate([1, 4], sym([1, 2, 3],[2, 3, 4]));
	evaluate([3, 4, 5], sym([1, 2, 3], [5, 2, 1, 4]));
	evaluate([3, 4, 5], sym([1, 2, 3, 3], [5, 2, 1, 4]));
	evaluate([3, 4, 5], sym([1, 2, 3], [5, 2, 1, 4, 5]));
	evaluate([1, 4, 5], sym([1, 2, 5], [2, 3, 5], [3, 4, 5]));
	evaluate([1, 4, 5], sym([1, 1, 2, 5], [2, 2, 3, 5], [3, 4, 5, 5])); 
	evaluate([2, 3, 4, 6, 7], sym([3, 3, 3, 2, 5], [2, 1, 5, 7], [3, 4, 6, 6], [1, 2, 3]));
	evaluate([1, 2, 4, 5, 6, 7, 8, 9], sym([3, 3, 3, 2, 5], [2, 1, 5, 7], [3, 4, 6, 6], [1, 2, 3], [5, 3, 9, 8], [1]));

	echo "All tests passed!";


} catch(Exception $e) {
	echo $e->getMessage()."<br>";
}

/*
 * The purpose of this function is only to make sure the algorithm works as expected.
*/
function evaluate($expected, $value): bool
{
	if($expected !== $value) {
		throw new Exception('ERROR: Expecting ['.implode(',', $expected).'], got ['.implode(',', $value).']');
	}

	return true;
}
