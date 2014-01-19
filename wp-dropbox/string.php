<?php

$string = '{"revision": 155, "rev": "9b0fb8fe0d", "thumb_exists": false, "bytes": 19303, "modified": "Tue, 27 Aug 2013 22:22:59 +0000", "client_mtime": "Tue, 27 Aug 2013 22:22:59 +0000", "path": "/peer_en/Zbli\ufffdeniowy w\ufffd\ufffdcznik indukcyjny umieszczony jest w pr\ufffdniowej obudowie monolitycznej w kszta\ufffdcie walca prostego.docx", "is_dir": false, "icon": "page_white_word", "root": "dropbox", "mime_type": "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "size": "18.9 KB"}';

$data = json_decode($string); 
echo $data->revision;

?>