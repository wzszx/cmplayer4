<?php
$uri = $_SERVER["REQUEST_URI"];
preg_match("/dbank.php\/(.+)\//",$uri,$code);
$code = $code[1];
$opts = array(
'http'=>array('method'=>"GET",'header'=>"User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.3)\r\n")
);//α��User-Agent
$context = stream_context_create($opts);
$url = "http://dl.dbank.com/".$code;//ԭʼ����ҳ��
$data = file_get_contents($url,false,$context);
preg_match("/downloadUrl=.(.*?)..class=.gbtn.btn-xz./", $data, $data);
$myurl = $data[1];//������ص�ַ
if($myurl){
header('Content-Type:application/force-download');//ǿ������
header("Location:".$myurl);
die();
}
else echo "Error";
?>