<?php

$google_maps_api_key = get_field(['google_maps_api_key'], 'option');
$map_zoom = $item['map_zoom'];
$map_style = $item['map_style'];
$marker_image = $item['marker_image'];
list($lat, $lng) = explode(',', $item['marker_position']);
$marker_anchor = explode(',', $item['marker_image_anchor']);
$icon = json_encode([
    'url' => $marker_image['url'],
    'width' => $marker_image['width'],
    'height' => $marker_image['height'],
    'anchor' =>$marker_anchor,
]);
$image = $item['image'];

wp_enqueue_script('googleapis', "https://maps.googleapis.com/maps/api/js?key=$google_maps_api_key&callback=initMap", [], null, true);
?>
<script>
    var googleMap = { style:<?php echo $map_style ?: '[]'; ?>, zoom: <?php echo $map_zoom; ?>, center: {lat:<?php echo $lat; ?>, lng:<?php echo $lng; ?>}, icon:<?php echo $icon; ?>};
</script>

<div class="row">
    <div class="col-12">
        <div class="google-map-wrap">
            <div id="google-map">
            </div>
        </div>
    </div>
</div>
