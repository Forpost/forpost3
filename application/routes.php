<?php
/* Example

 return array(
    '/regexp/' => array('controller','action','params')
);

*/

return array(
    '{^/news/(\d{1,6})$}' => array('news','show','$1'),
    '{^/news/([0-9a-z_-]+)$}si' => array('news','code','$1'),
    '{^/pics/([\da-zа-яё_\.\s-]+)$}ui' => array('file','showimg','$1'),
    '{^/files/([\da-zа-яё_\.\s-]+)$}ui' => array('file','downloadfile','$1'),
);
