<?php

/* * *************************************************************************************************** */
// Upload Mimes
/* * *************************************************************************************************** */

add_filter('upload_mimes','nimbus_remove_mime_types');

function nimbus_remove_mime_types($default_mimes){
    $mimes_to_remove = array("png", "gif", "jpg", "pdf", "doc", "docx", "ppt", "pptx", "odt", "xls", "xlsx", "mp3", "odt", "odt", "wav", "mp4");
	foreach ($mimes_to_remove as $mime) {
        unset( $default_mimes[$mime] );
    }
}

add_filter('upload_mimes', 'nimbus_upload_mimes');

function nimbus_upload_mimes ( $existing_mimes=array() ) {

$nimbus_image_mime_types = nimbus_get_option('nimbus_image_mime_types');
$nimbus_app_mime_types = nimbus_get_option('nimbus_app_mime_types');
$nimbus_txt_mime_types = nimbus_get_option('nimbus_txt_mime_types');
$nimbus_audio_mime_types = nimbus_get_option('nimbus_audio_mime_types');
$nimbus_video_mime_types = nimbus_get_option('nimbus_video_mime_types');
$nimbus_font_mime_types = nimbus_get_option('nimbus_font_mime_types');

$existing_mimes['extension'] = 'mime/type';

// Font Types

if (!empty($nimbus_font_mime_types['ttf'])) {  
    $existing_mimes['ttf'] = 'application/octet-stream';
} 

if (!empty($nimbus_font_mime_types['otd'])) {  
    $existing_mimes['otf'] = 'application/octet-stream';
} 

// Image Types

if (!empty($nimbus_image_mime_types['bm'])) {  
    $existing_mimes['bm'] = 'image/bmp';
} 

if (!empty($nimbus_image_mime_types['bmp'])) {  
    $existing_mimes['bmp'] = 'image/bmp';
} 

if (!empty($nimbus_image_mime_types['gif'])) {  
    $existing_mimes['gif'] = 'image/gif';
} 

if (!empty($nimbus_image_mime_types['ico'])) {  
    $existing_mimes['ico'] = 'image/x-icon';
}  

if (!empty($nimbus_image_mime_types['jpeg'])) {  
    $existing_mimes['jpeg'] = 'image/jpeg';
} 

if (!empty($nimbus_image_mime_types['jpg'])) {  
    $existing_mimes['jpg'] = 'image/jpeg';
} 

if (!empty($nimbus_image_mime_types['pbm'])) {  
    $existing_mimes['pbm'] = 'image/x-portable-bitmap';
}  

if (!empty($nimbus_image_mime_types['png'])) {  
    $existing_mimes['png'] = 'image/png';
}

if (!empty($nimbus_image_mime_types['tif'])) {  
    $existing_mimes['tif'] = 'image/tiff';
} 

if (!empty($nimbus_image_mime_types['tiff'])) {  
    $existing_mimes['tiff'] = 'image/tiff';
} 


// App Types

if (!empty($nimbus_app_mime_types['ai'])) {  
    $existing_mimes['ai'] = 'application/postscript';
} 

if (!empty($nimbus_app_mime_types['doc'])) {  
    $existing_mimes['doc'] = 'application/msword';
} 

if (!empty($nimbus_app_mime_types['docx'])) {  
    $existing_mimes['docx'] = 'application/msword';
} 

if (!empty($nimbus_app_mime_types['eps'])) {  
    $existing_mimes['eps'] = 'application/postscript';
}  

if (!empty($nimbus_app_mime_types['gz'])) {  
    $existing_mimes['gz'] = 'application/x-gzip';
}

if (!empty($nimbus_app_mime_types['gzip'])) {  
    $existing_mimes['gzip'] = 'application/x-gzip';
} 

if (!empty($nimbus_app_mime_types['odt'])) {  
    $existing_mimes['odt'] = 'application/vnd.oasis.opendocument.text';
} 

if (!empty($nimbus_app_mime_types['pdf'])) {  
    $existing_mimes['pdf'] = 'application/pdf';
}

if (!empty($nimbus_app_mime_types['ppt'])) {  
    $existing_mimes['ppt'] = 'application/powerpoint';
}  

if (!empty($nimbus_app_mime_types['pptx'])) {  
    $existing_mimes['pptx'] = 'application/powerpoint';
} 

if (!empty($nimbus_app_mime_types['psd'])) {  
    $existing_mimes['psd'] = 'application/octet-stream';
}

if (!empty($nimbus_app_mime_types['xls'])) {  
    $existing_mimes['xls'] = 'application/excel';
}    

if (!empty($nimbus_app_mime_types['xlsx'])) {  
    $existing_mimes['xlsx'] = 'application/excel';
}

if (!empty($nimbus_app_mime_types['zip'])) {  
    $existing_mimes['zip'] = 'application/zip';
} 

// Text types

if (!empty($nimbus_txt_mime_types['css'])) {  
    $existing_mimes['css'] = 'text/css';
} 

if (!empty($nimbus_txt_mime_types['htm'])) {  
    $existing_mimes['htm'] = 'text/html';
} 

if (!empty($nimbus_txt_mime_types['html'])) {  
    $existing_mimes['html'] = 'text/html';
}

if (!empty($nimbus_txt_mime_types['js'])) {  
    $existing_mimes['js'] = 'text/javascript';
} 

if (!empty($nimbus_txt_mime_types['text'])) {  
    $existing_mimes['text'] = 'text/plain';
}

if (!empty($nimbus_txt_mime_types['txt'])) {  
    $existing_mimes['txt'] = 'text/plain';
} 

if (!empty($nimbus_txt_mime_types['xml'])) {  
    $existing_mimes['xml'] = 'text/xml';
} 


// Audio types

if (!empty($nimbus_audio_mime_types['au'])) {  
    $existing_mimes['au'] = 'audio/basic';
} 

if (!empty($nimbus_audio_mime_types['snd'])) {  
    $existing_mimes['snd'] = 'audio/basic';
} 

if (!empty($nimbus_audio_mime_types['mid'])) {  
    $existing_mimes['mid'] = 'audio/mid';
} 

if (!empty($nimbus_audio_mime_types['rmi'])) {  
    $existing_mimes['rmi'] = 'audio/mid';
} 

if (!empty($nimbus_audio_mime_types['mp3'])) {  
    $existing_mimes['mp3'] = 'audio/mpeg';
}

if (!empty($nimbus_audio_mime_types['aif'])) {  
    $existing_mimes['aif'] = 'audio/x-aiff';
} 

if (!empty($nimbus_audio_mime_types['aifc'])) {  
    $existing_mimes['aifc'] = 'audio/x-aiff';
} 

if (!empty($nimbus_audio_mime_types['aiff'])) {  
    $existing_mimes['aiff'] = 'audio/x-aiff';
} 

if (!empty($nimbus_audio_mime_types['m3u'])) {  
    $existing_mimes['m3u'] = 'audio/x-mpegurl';
} 

if (!empty($nimbus_audio_mime_types['ra'])) {  
    $existing_mimes['ra'] = 'audio/x-pn-realaudio';
} 

if (!empty($nimbus_audio_mime_types['ram'])) {  
    $existing_mimes['ram'] = 'audio/x-pn-realaudio';
} 

if (!empty($nimbus_audio_mime_types['wav'])) {  
    $existing_mimes['wav'] = 'audio/wav';
}


// Video types

if (!empty($nimbus_video_mime_types['flv'])) {  
    $existing_mimes['flv'] = 'video/x-flv';
} 

if (!empty($nimbus_video_mime_types['mp4'])) {  
    $existing_mimes['mp4'] = 'video/mp4';
} 

if (!empty($nimbus_video_mime_types['m3u8'])) {  
    $existing_mimes['m3u8'] = 'application/x-mpegURL';
} 

if (!empty($nimbus_video_mime_types['ts'])) {  
    $existing_mimes['ts'] = 'video/MP2T';
}

if (!empty($nimbus_video_mime_types['3gp'])) {  
    $existing_mimes['3gp'] = 'video/3gpp';
} 

if (!empty($nimbus_video_mime_types['mov'])) {  
    $existing_mimes['mov'] = 'video/quicktime';
} 

if (!empty($nimbus_video_mime_types['wmv'])) {  
    $existing_mimes['wmv'] = 'video/x-ms-wmv';
} 

if (!empty($nimbus_video_mime_types['webm'])) {  
    $existing_mimes['webm'] = 'video/webm';
} 


return $existing_mimes;

}

?>