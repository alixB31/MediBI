<?php
function getCache($filename, $ttl = 3600) {
    if (!file_exists($filename)) {
        return null;
    }

    if (time() - filemtime($filename) > $ttl) {
        return null;
    }

    $content = file_get_contents($filename);
    return json_decode($content, true);
}

function setCache($filename, $data) {
    file_put_contents($filename, json_encode($data));
}
?>