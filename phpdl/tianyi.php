<?php
function get_date($url) {
$ch = curl_init();
$User_Agent = $_SERVER['HTTP_USER_AGENT'];
$Referer_Url = "http://biz.vsdn.tv380.com/";
$timeout = 3;
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_USERAGENT, $User_Agent);
curl_setopt ($ch, CURLOPT_REFERER, $Referer_Url);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$c = curl_exec($ch);
curl_close($ch);
return $c;
}

//����CMP�б�ʼ
function variety_list(){
        $Url = "http://www.netitv.com/a_flash/tysx_1_2/livelistTV/livePlayer_list.shtml";
        $list=''; 
        //��ȡ��Դ��ַ
        $l_str=preg(get_date($Url),'|<input type=(.*) />|imsU',true); //����Դҳ��������
        foreach($l_str as $value){
                preg_match_all("/\": (.*),/isU",$value,$ar); //��ȡ��Ҫ�ļ���ֵ�����鷽ʽ
                $lib=$ar[1][1]; //ȡ�������uuidֵ
                $lib1=$ar[1][0];//ȡ�������idֵ
                preg_match_all("/\": \"(.*)\"/isU",$value,$arr); //ȡ����
                $lname=$arr[1][0];
                $surl=$_SERVER["PHP_SELF"]; 
                $list.="<m type=\"2\" src=\"$surl?id=$lib-$lib1\" label=\"$lname\" />\n";
        }
return $list;
}

//ȡ������Դ��ʼ
function variety_id($id) {
        $uid = explode('-', $id);
        //ȡ��Ӧ��XML��Ĳ��ŵ�ַ
        $url="http://www.netitv.com/$uid[0]/proXml/$uid[1]_1.xml";
        //ȡ���ŵ�ַ�飬��Щ���ŵ�ַ�ж��������ȡ�����ַ��
        preg_match_all("/bit_stream=\"2\"(.*)<\/url>/isU",get_date($url),$ar); 
        $vid=$ar[1][0];//ȡ��һ��������
        //��ַ�����в�������http://��ͷ�Ŀ���ֱ��ʹ�ã��жϲ�����http
        if($vid!=='http://'){ 
                //�жϵ�ַ����С��100����ȡ�����ַ��
                if(strlen($vid)<=100){
                        //��Ϊǰ���Ѿ��Ѷ̵�ַ��http��ͷ��ֱ��ȡ�����ˡ�
                        preg_match_all("/bit_stream=\"1\"(.*)<\/url>/isU",get_date($url),$ar); 
                        $vid=$ar[1][0];
                }
                //�ٴ�����Ҫ��������
                preg_match_all("/CDATA\[(.*)\]/isU",$vid,$arr); 
                //ȡ���һ����
                $vdata=$arr[1][0];
                if(strlen($vdata)<=100){
                        //������ݳ���С��100����ȡ��ڶ������ݡ�
                        $vdata=$arr[1][1];
                }
                //$urll="http://biz.vsdn.tv380.com/playlive.php?$vdata";
                $urll=get_date("http://biz.vsdn.tv380.com/playlive.php?".$vdata);
        }
        else{
                $urll=get_date($vdata);
        }
          $urll= str_replace('" />','+',$urll);
          $urll= str_replace('rtmp://','http://',$urll);
        $addresstemp = strstr($urll,"http://");
        $address = strtok($addresstemp,"+");
        header("location:$address");
        //return $vdata;
        //return $urll;
}

$xml="<list>\n";
if(isset($_GET['id'])){
$xml.=variety_id($_GET['id']);
}else{
$xml.=variety_list();
}
$xml.="</list>\n";
echo $xml;

function preg($url, $preg, $bool) {
        if ($bool) {
                preg_match_all($preg, $url, $ar);
        } else {
                preg_match($preg, file_get_contents($url), $ar);
        }
        return $ar[1];
}
?>