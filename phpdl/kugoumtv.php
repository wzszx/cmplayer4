    <?

    /*
    $sexy=array('��'=>'0','Ů'=>'1','���'=>'2');
    $areas=array('��̨'=>'0','��½'=>'1','ŷ��'=>'2','����'=>'3','�ձ�'=>'4','����'=>'5');
    */
    error_reporting(0);
    header("Content-type: text/xml; charset=utf-8");
    $thisurl = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
    $classid = $_REQUEST['classid'];
    $sid = $_REQUEST['sid'];
    if($sid){
            Makesid($sid);
    }
    if ($classid) {
            MakeClass($classid);
    }else{
    $arr = array("��̨��","��̨Ů","��̨���","��½��","��½Ů","��½���","ŷ����","ŷ��Ů","ŷ�����","������","����Ů","�������","�ձ���","�ձ�Ů","�ձ����","����");
    $list = "<list>\n";
      foreach($arr as $k=>$v){
              $ks=$k+1;
                $list.="<m list_src=\"$thisurl?classid=$ks\" label=\"".$v."����\" />\n";
      } $list .= "</list>";
      echo $list;

    }
    function Makesid($sid){
            $mysql = new SaeMysql();
            $sql="SELECT * FROM `mtv` WHERE `sid`='".$sid."'";
            $datas=$mysql->getData($sql);
            $list = "<list>\n";
              foreach($datas as $r){
            $mvname=$r['mtvname'];
            $m=$r['mtvhash'];
            $mvurl="http://mvfiles.kugou.com/mp4/".$m{0}.$m{1}."/".$m{2}.$m{3}."/".$m.".mp4";
              $list.="<m label=\"$mvname\" src=\"$mvurl\" />\n";
              }
            $list .= "</list>";
            $mysql->closeDb();
            echo $list;
            exit;


    }
    function CreatXml($sql) {
            global $thisurl ;
            $mysql = new SaeMysql();
            $result = $mysql->getData($sql);
            $list = "<list>\n";
            foreach ($result as $r) {
                    $sid = $r['sid'];
                    $sname = htmlspecialchars($r['sname']);
                      $list .= "<m list_src=\"$thisurl?sid=$sid\" label=\"$sname\"  />\n";
                   
            }
            $list .= "</list>";
            unset ($thisurl);
            $mysql->closeDb();
            echo $list;
            exit;

    }

    function MakeClass($classid) {
              $area=floor(($classid-1)/3);
            $sex=($classid-1)%3;
            $sql = 'SELECT * FROM `mtvsinger` WHERE ';
              if($classid=='16'){
              $sql.="`area`='5'";
            }else{
            $sql.="`area`='".$area."'  and `sex`='".$sex."'";
            }
             //$sql .= " LIMIT 0 , 100"; //��ʾ������  ����ʾ����λ �ͽ�100�ĳɶ���λ Ȼ��ȥ��ǰ��ע��
               CreatXml($sql);
    }
    ?>