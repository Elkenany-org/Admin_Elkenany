<?php

function convertToArabicDate($dateString)
{
    // Assuming the $dateString is in the format: 'Y-m-d H:i:s'

    $carbonDate = \Carbon\Carbon::parse($dateString)->locale('ar');

    $formattedDate = $carbonDate->isoFormat('dddd، DD MMMM YYYY hh:mm a');


    return $formattedDate;
}