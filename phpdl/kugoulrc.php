    <?php
    $type=$_REQUEST[type];
    $alltype=array("2","video","flv","mp4","youku");
    if(in_array($type,$alltype)){
    exit;
    }
    error_reporting(0);
    $title = urldecode($_REQUEST[title]);
    $name = explode('-', $title);
    $size = sizeof($name);
    $lrc = baidu_lrc($name[0], $name[1]);
    if (!$lrc) {
            $lrc = qq_lrc($title);
    }
    if (!$lrc) {
            $lrc = "[ti:���û�ҵ�]";
    }
    echo $lrc;
    function file_data($url) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $ch = curl_init();
            $timeout = 8;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_REFERER, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
    }
    function baidu_data($url) {
            $str = file_data($url);
            if (preg_match('|<div class="iii">\s+<a href="([^"]+)" target|U', $str, $lrc)) {
                    $lrcstr = file_data($lrc[1]);
                    return $lrcstr;
            } else {
                    return;
            }
    }
    function qq_lrc($name) {
            $url = 'http://portalcgi.music.qq.com/fcgi-bin/music_mini_portal/cgi_mini_portal_search_json.fcg?search_input=' . urlencode($name) . '&start=1&return_num=20&utf8=0&outputtype=1';
            $str = file_data($url);
            if (preg_match('|songID":([0-9]+),"|U', $str, $lrcid)) {
                    //���ƥ�䵽����ID ��ִ�� ���򷵻�ʧ��
                    $lrcurl = 'http://portalcgi.music.qq.com/fcgi-bin/music_download/fcg_get_lyric.fcg?id=' . $lrcid[1];
                    $lrcstr = file_data($lrcurl);
                    return lrc_th($lrcstr);
            } else {
                    return;
            }
    }
    function baidu_lrc($songname, $singername) {
            if (!empty ($singername)) {
                    //�������Ϊ�� ��������ַΪ��
                    $url = 'http://mp3.baidu.com/m?f=3&tn=baidump3lyric&ct=150994944&lf=2&rn=10&word=' . $songname . '+' . $singername . '&lm=-1&oq=' . $songname . '+&rsp=0';
            } else {
                    //����������������ַΪ��
                    $url = 'http://mp3.baidu.com/m?f=ms&tn=baidump3lyric&ct=150994944&lf=2&rn=10&word=' . $songname . '&lm=-1';
            }
            $str = baidu_data($url);
            if (preg_match('|<!DOCTYPE|i', $str)) {
                    //�����⵽����ҳ �򷵻�
                    return;
            }
            elseif (empty ($str)) {
                    //���Ϊ���򷵻�
                    return;
            } else {
                    return lrc_th($str);
            }
    }
    function lrc_th($str) {
            $str = preg_replace("@(\w+)?\.?(\w+)\.(com|org|info|net|cn|biz|cc|uk|tk|jp|la|ru|us|ws)@U", '435861067.qzone.qq.com', $str);
            // �滻����
            $str = preg_replace("@\[by:\s?([^\]]+)\]@U", '[by:��ɽ��Ҷ]', $str);
            //�滻���������
            $str = preg_replace("@(\d+){5,11}@", '245054917', $str);
            //�滻5λ���ϵ�����ΪQQ
            $str = preg_replace("@�༭\s?��?:?\s?([^\[]+)\[@", '�༭����ɽ��Ҷ-QQ:435861067[', $str);
            //�滻�༭��
            return $str;
    }
    ?>