<?php
//ע���������޸����Ƿ��ֵ������ɼ�����̨�Ĵ���http://bbs.cenfun.com/thread-15368-1-1.html
//�������޸ĳ�ֱֻ����ת��ַ���������¼�Ŀ¼�б��ˣ������������š�
error_reporting(0);
$id = $_GET[id];
if ($id) {
        makeFlv($id);
        } else {
        makeXml();        
}
function makeXml() {
$port = $_SERVER['SERVER_PORT']; //ȡ�ö˿ںš�
if ($port==80)
$name = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["SCRIPT_NAME"];
else $name = 'http://'.$_SERVER['SERVER_NAME'].':'.$port.$_SERVER["SCRIPT_NAME"]; //��80Ĭ�϶˿�����Ե�ַ����˿ںš�
$xml="<list>";
$xml.="\n";
if(isset($_GET['y'])){
        $yy=urlencode($_GET['y']);
        $f='';
        if(isset($_GET['p'])){
                $p=$_GET['p'];
                for($k=$p;$k<=$p+3;$k++){
                $up="http://www.yinyuetai.com/search/index?page=".$k."&orderType=totalViews&keyword=".$yy."&videoSourceType=music_video";
                $f.=get_contents($up);
                }
        }else{
        for($p=1;$p<=4;$p++){
                $up="http://www.yinyuetai.com/search/index?page=".$p."&orderType=totalViews&keyword=".$yy."&videoSourceType=music_video";
                $f.=get_contents($up);
                }
        }
        //$f=mb_convert_encoding($f, 'GBK', 'UTF-8');
        $st=get_array($f);
        $sn=get_name($f);
        $list='';
        for($i=0;$i<79;$i++){
                @$list.='<m src="'.$name.'?id='.$st[$i].'" label="'.preg_replace("/[\<]*[\>]*/","",$sn[$i]).'" />';
                $list.="\n";
        }
        $xml.=$list;
        }else{
                $xml.="Please input your want to find singer";
        }
        $xml.="</list>";
        $nullurl='<m src="'.$name.'?id=" label="" />';
        $xml=str_replace($nullurl,"",$xml);           //ȥ����ID���С�
        $xml=preg_replace('/[\n][\n][\n]/',"",$xml);  //ȥ�����С�
        header("Content-Type: text/xml");
        echo $xml;
}

function makeFlv($id) {
        if (empty($id)) {
                return;        
                }
                $mtv_url="http://www.yinyuetai.com/mvplayer/get-video-info?flex=true&videoId=".$id;
                $mtv=get_contents($mtv_url);
                //$mtv=mb_convert_encoding($mtv, 'GBK', 'UTF-8');
                preg_match("/http:\/\/.*\.flv/i",$mtv,$flvs); //�����Ҹ�������ƥ�䡰http����ͷ���ԡ�.flv�������ĵ�ַ����/i���ƴ�Сд��
                @$flv=$flvs[0];
                if ($flv) {
                        header("Location: $flv"); //����е�ֱַ����ת��
                        //echo $flv;
                }
        }

function get_contents($url) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ch = curl_init();
        $timeout = 30;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        $c = curl_exec($ch);
        curl_close($ch);
        return $c;
}
function get_array($f){
        preg_match_all('|parent_per_([0-9]+)"|',$f,$s);
        return $s[1];
}
function get_name($s){
        //preg_match_all('|img alt="([^"]*)" src=|',$s,$c);
        preg_match_all('|class="img"><img alt="([^"]*)" src=|', $s, $c);
		return $c[1];
}
?>