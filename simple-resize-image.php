<?php
function thumbByWidth($src, $max_width, $max_height, $quailty = 100)
{
    if(!file_exists($src)) {
        $src = 'assets/client/img/no-image-available.jpg';
    }
    $path = pathinfo($src);
    $dst_dir = $path['dirname'] . '/' . $path['filename'] . "_" . $max_width . '_' . $max_height . "." . $path['extension'];
    if (! file_exists($dst_dir)) {
        $imgsize = getimagesize($src);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img, true);
        $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
        imagefilledrectangle($dst_img, 0, 0, $max_width, $max_height, $transparent);
        $src_img = $image_create($src);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        // if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if($width < $max_width && $height < $max_height) {
            $offset_w = ($max_width-$width)/2;
            $offset_h = ($max_height-$height)/2;
            imagecopyresampled($dst_img, $src_img, $offset_w, $offset_h, 0, 0, $width, $height, $width, $height);
            imagecreatetruecolor($max_width,$max_height);
        }
        elseif ($width_new > $width) {
            // cut point by height
            $h_point = (($height - $height_new) / 2);
            // copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else {
            // cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $dst_dir, $quality);

        if ($dst_img)
            imagedestroy($dst_img);
        if ($src_img)
            imagedestroy($src_img);
    }
    return $dst_dir;
}
thumbByWidth('1424785052_circle-twitter-128.png',1000, 100);