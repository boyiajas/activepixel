<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImageHelper
{


    //Image Helper Functions - Returns Full image path to correct sizes
    // Mini returns Slider Thumb Size
    public static function imgMini($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_143_83' . substr($image, $extension_pos);
        return $thumb;
    }

    // Thumb returns List View Size
    public static function imgThumb($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_265_163' . substr($image, $extension_pos);
        return $thumb;
    }

    // Map returns Property Map View Image
    public static function imgMap($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_400_161' . substr($image, $extension_pos);
        return $thumb;
    }

    // Full size Image for Slider
    public static function imgLarge($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_835_467' . substr($image, $extension_pos);
        return $thumb;
    }

    // Facebook OG Tag
    public static function imgFacebook($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_1200_630' . substr($image, $extension_pos);
        return $thumb;
    }

    // Landscape size Image for Slider
    public static function imgLandscape($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_1920_600' . substr($image, $extension_pos);
        return $thumb;
    }

    //manage Property Image

    public static function imgManProp($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_265_163' . substr($image, $extension_pos);
        return $thumb;
    }

    //blog Small Image

    public static function imgSmlBlog($image)
    {
        $extension_pos = strrpos($image, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($image, 0, $extension_pos) . '_small' . substr($image, $extension_pos);
        return $thumb;
    }

    public static function getLastQuery()
    {
        $queries = DB::getQueryLog();
        return $queries;
    }



}

?>
