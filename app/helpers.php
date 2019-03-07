<?php

if (! function_exists('dd'))
{
    function dd($data)
    {
        echo '<pre style="font-family: Consolas;font-size: 12px;line-height:22px;color: #225">';
        print_r($data);
        die;
    }
}