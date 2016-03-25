<?php
/**
 * Applies a mask to all frames in avisynch script
 *
 * Usage: php diff_overlay.php /path/to/frames/video.avs 1 /path/to/mask.png /path/to/frames/ /path/to/output_masked_folder/
 */

// a file with Avisynth script
$avs = $argv[1];

// blend level (float between 0-1)
$blend = $argv[2];

// mask file
$mask = $argv[3];

// base path to frame files
$frame_directory = $argv[4];

// directory of output masked frames
$masked_directory = $argv[5];

// Frame entries in the format like the following
// ImageSource("frame_0000.jpg", start = 1, end = 16, fps = 10)
$frames = file($avs);

foreach ($frames as $frame) {
    $frame = preg_replace('/.*\((.*)\).*/', '$1', $frame);
    $params = explode(',', $frame);

    // first is a file name
    $filename = trim(array_shift($params), '" ');

    #passthru("composite -compose ${blend} ${mask} ${frame_directory}/${filename} ${masked_directory}/${filename}");
    passthru("convert ${mask} -alpha set -channel a -evaluate multiply ${blend} ${frame_directory}/${filename} -compose overlay -composite ${masked_directory}/${filename}");
}
