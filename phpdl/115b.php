<?php
/*
* �ռ���Ҫ֧��allow_url_fopen
* ������ʽ��http://����/115b.php/��ȡ��/xxx.xxx
*PS����ȡ��ͨ��· $myurl = $json->data[0]->url;
*PS����ȡ������· $myurl =  $json->data[1]->url;
*/
$uri = $_SERVER["REQUEST_URI"];
preg_match("/115b.php\/(.+)\//",$uri,$code);//�Լ��޸�
$code = $code[1];
$opts = array(
'http'=>array('method'=>"GET",'header'=>"User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.3)\r\n")
);//α��User-Agent
$context = stream_context_create($opts);
$url = "http://115.com/?ct=pickcode&ac=guest_download&pickcode=".$code."&r=".strtotime("now")."&token=f2afd690dd1cdfe9677a6dc1c018812d92280e12";
$data = file_get_contents($url,false,$context);
$data = str_replace("//","",$data);
$data = json_decode($data);
//print_r($json);
$myurl = $data->data[0]->url;
//��ͨ: $myurl = $data->data[0]->url;
//����: $myurl = $json->data[1]->url;
if($myurl){
header('Content-Type:application/force-download');//ǿ������
header("Location:".$myurl);
die();
}
else 
//echo "�Բ�����ȡ�벻���ڻ��ѹ��ڣ�";
header("Location:"."http://www.cenfun.com/");
die();
?>