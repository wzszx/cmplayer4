    <?php
    $fname = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["SCRIPT_NAME"];
    //����fname������ָ����ַ������your.sinaapp.com/php/sohuhd.php
    $xml = "<?xml version=\"1.0\" encoding=\"GBK\" ?>\n<list>\n";

    if (isset ($_GET['u'])) //���������php������ĸΪu����ִ�и��ӳ���
    {
        function get_pagenumber($u) //�Ӻ�����ȡ��Ӱ������ÿ����ҳ����
        {
            global $fname;
            $t = explode('-', $u);//�ַ����ָ�� explode(�ָ���ʼ��,�ַ���,limit)
            $list = '';
            for ($i = 1; $i <= $t[1]; $i++)
            {
                    $q = 'http://so.tv.sohu.com/list_p11_p2_' . $t[0] . '_p3_p4-1_p5_p6_p70_p82_p9-1_p10' . $i . '_p11.html';
            //http://so.tv.sohu.com/list_p11_p2_u7231_u60c5_u7247_p3_p4-1_p5_p6_p70_p80_p9-1_p101 _p11.html
                    $list .= '<m label="��' . $i . 'ҳ" list_src="' . $fname . '?n=' . $q . '" />' . "\n";  //�趨��һ��ʶ����Ϊn
    //<m label="��һҳ" list_src="http://your.sinaapp.com/php/sohuhd.php? n=http://so.tv.sohu.com/list_p11_p2_u7231_u60c5_u7247_p3_p4-1_p5_p6_p70_p82_p9-1_p101_p11.html />
            }
            return $list;
        }
            $xml .= get_pagenumber($_GET['u']);
    }
    else   
         if (isset ($_GET['n'])) //���������php������ĸΪn����ִ�и��ӳ���
             {
               function get_movienumber($n) //�Ӻ�������ȡ��ǰ��Ӱҳ���ַ
                {
                     global $fname;
                     function file_data($url) //�Ӻ�������ȡ��ǰ��ҳ����
                                  {
                                      $user_agent = $_SERVER['HTTP_USER_AGENT'];
                                      $ch = curl_init();
                                      $timeout = 30;
                                       curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                                         curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                                        @ $c = curl_exec($ch);
                                        curl_close($ch);
                                    return $c;
                                 }
                     $str = file_data($n);
                     preg_match_all('|<h4><a href="([^<]+)</a></h4>|', $str, $a1);
                     //������ʽ��ƥ�亯������ȡ����
                     return $a1[1];
                     $list = '';
               
                  foreach ($a1[1] as $k => $v)
                     {
                         $a2 = explode('" target="_blank">', $v);
                         $list .= '<m label="' . $a2[1] . '" list_src="' . $fname . '?id=' . $a2[0] . '" />' . "\n";
                                                                                          //�趨��һ��ʶ����Ϊid
             //�Ա�<m label="�" list_src="http://your.sinaapp.com/php/sohuhd.php?id=http://tv.sohu.com/s2011/dyzuiai/" />
                     }
               
                  return $list;
               
               }
            $xml .= get_movienumber($_GET['n']);
             }
          
          else
               if (isset ($_GET['id'])) //���������php������ĸΪid����ִ�и��ӳ���
                {
                
                      function get_playadress($id) //�Ӻ�������ȡ��ǰӰƬʵ�ʲ��ŵ�ַ
                      {
                          $str = file_get_contents($id);
                          if (preg_match('|var vid="([0-9]+)|', $str, $as))
                             {
                                   $im = $as[1];
                             }
                         
                          else  
            
                                 if (preg_match('|<div class=area id=picFocus><a \n\nhref="([^"]+)"|ims', $str, $as))
                                    {
                                         $str1 = file_data($as[1]);
                                         preg_match('|var vid="([0-9]+)|', $str1, $as);
                                         $im = $as[1];
                   
                                    }
                                 $url = 'http://hot.vrs.sohu.com/vrs_flash.action?vid=' . $im;
                                 function file_data($url)
                                  {
                                      $user_agent = $_SERVER['HTTP_USER_AGENT'];
                                      $ch = curl_init();
                                      $timeout = 30;
                                       curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                                         curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                                        @ $c = curl_exec($ch);
                                        curl_close($ch);
                                    return $c;
                                 }
                                 
                                 $fp = file_data($url);
                                 preg_match_all('|[url]http://data.vod.itc.cn[/url]([^"]+)"|', $fp, $ar1);
                                 preg_match('|tvName":"([^"]+)","|', $fp, $name);
                                 preg_match('|"su":\["(.*)"\],"|', $fp, $ar2);
                                 $ar1 = $ar1[1];
                                 $ar2 = $ar2[1];
                                 $ar2 = explode('","', $ar2);
                                  @ $ar = array_combine($ar1, $ar2);
                                  $list = '';    //��ղ����б�
            
            
                                  foreach ($ar as $k => $v)
                                  {
                                        $u = 'http://220.181.61.229/?prot=2&file=' . $k . '&new=' . $v;
                                        $m = file_data($u);
                                        $s = explode('|', $m);
                                        @ $flv = $s[0] . $v . '?key=' . $s[3];
                                        $list .= '<m type="2" src="' . $flv . '" label="' . $name[1] . '." />';
                                        $list .= "\n";
                                  }
                                  
                                  return $list;
                     }
                        $xml .= get_playadress($_GET['id']);
                }
                else //ǰ�涼û��ƥ�䵽��˵��ȡ������ҳ��������
                     {   
                         function indexurl() //ȡ��ҳ�������ӵ�ַ����
                          {
                            global $fname;
                            $lb = array
                               (
                               '����' => 'u7231_u60c5_u7247-21',
                               '����' => 'u52a8_u4f5c_u7247-19',
                               'ϲ��' => 'u559c_u5267_u7247-14',
                               '�ƻ�' => 'u79d1_u5e7b_u7247-2',
                               'ս��' => 'u6218_u4e89_u7247-3',
                               '�ֲ�' => 'u6050_u6016_u7247-6',
                               '����' => 'u98ce_u6708_u7247-3',
                               '����' => 'u5267_u60c5_u7247-47',
                               '����' => 'u97f3_u4e50_u7247-1',
                               '����' => 'u52a8_u753b_u7247-1',
                               '��¼' => 'u7eaa_u5f55_u7247-1'
                              );
                            $list = '';
                            
                            foreach ($lb as $k => $v)
                              {
                                 $list .= '<m label="' . $k . '" list_src="' . $fname . '?u=' . $v . '" />' . "\n";
    //<m label="����"  list_src="http://your.sinaapp.com/php/sohuhd.php?u=u7231_u60c5_u7247-21" />
                              }
                        return $list;
                         }
                     $xml .= indexurl();
                     }
                     
    $xml .= "</list>\n";
    echo $xml;
    ?>