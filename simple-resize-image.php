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

        $new_width = $width * ($max_height / $height);
        $new_height = $height * ($max_width / $width);
        $offset_w = ($max_width-$width)/2;
        $offset_h = ($max_height-$height)/2;
        if($width < $max_width && $height < $max_height) {
            imagecopyresampled($dst_img, $src_img, $offset_w, $offset_h, 0, 0, $width, $height, $width, $height);
        }
        elseif ($max_width > $width && $max_height < $height) {
            imagecopyresampled($dst_img, $src_img, $offset_w, 0, 0, 0, $new_width, $max_height, $width, $height);
        } elseif($max_width < $width && $max_height > $height) {
            imagecopyresampled($dst_img, $src_img, 0, $offset_h, 0, 0, $max_width, $new_height, $width, $height);
        } else {
            if($max_width >= $max_height) {
                $new_width1 = $width * ($max_height / $height);
                $offset_w1 = ($max_width - $new_width1) / 2;
                imagecopyresampled($dst_img, $src_img, $offset_w1, 0, 0, 0, $new_width1, $max_height, $width, $height);
            } else {
                $new_height1 = $height * ($max_width / $width);
                $offset_h1 = ($max_height - $new_height1) / 2;
                imagecopyresampled($dst_img, $src_img, 0, $offset_h1, 0, 0, $max_width, $new_height1, $width, $height);
            }
        }
        imagecreatetruecolor($max_width,$max_height);
        $image($dst_img, $dst_dir, $quality);

        if ($dst_img)
            imagedestroy($dst_img);
        if ($src_img)
            imagedestroy($src_img);
    }
    return $dst_dir;
}
thumbByWidth('twitter.png',80, 80);