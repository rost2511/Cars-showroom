<?php
/**
 * Template part for taxonomies.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

?>

<?php
$args = array(
	'taxonomy' => 'engine',
	'hide_empty' => true,
	'number' => '1',
	'object_ids' => $post->ID,
);
$terms = get_terms($args);
echo '<p>Engine: ' . $terms[0]->name . '</p>'; ?>




<?php
$args = array(
	'taxonomy' => 'brand',
	'hide_empty' => true,
	'number' => '1',
	'object_ids' => $post->ID,
	'parent' => '0'
);
$terms = get_terms($args);
foreach ($terms as $term) {
	$args = array(
		'taxonomy' => 'brand',
		'hide_empty' => true,
		'number' => '1',
		'object_ids' => $post->ID,
		'child_of' => $term->ID
	);
	$child_terms = get_terms($args);
	foreach ($child_terms as $child_term) {
		echo '<p>Brand: ' . $term->name . ' </p>';
		echo '<p>Model: '. $child_term->name . ' </p>';
	}
}
?>
