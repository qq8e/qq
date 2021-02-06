<?php
$time_start = microtime(true);
define('ROOT', dirname(__FILE__).'/');
define('MATCH_LENGTH', 0.1*1024*1024); //字符串长度 0.1M 自己设置，一般够了。
define('RESULT_LIMIT',100);

function my_scandir($path){//获取数据文件地址
        $filelist=array();
        if($handle=opendir($path)){
        while (($file=readdir($handle))!==false){
         if($file!="." && $file !=".."){
             if(is_dir($path."/".$file)){
                $filelist=array_merge($filelist,my_scandir($path."/".$file));
                 }else{
                  $filelist[]=$path."/".$file;
                 }
            }
        }
     }
    closedir($handle);
    return $filelist;
}

function get_results($keyword){//查询
    $return=array();
    $count=0;
    $datas=my_scandir(ROOT."kieoidfrwq!!1123@#fewf"); //数据库文档目录
    if(!empty($datas))foreach($datas as $filepath){
        $filename = basename($filepath);
        $start = 0;
        $fp = fopen($filepath, 'r');
          while(!feof($fp)){
                fseek($fp, $start);
                $content = fread($fp, MATCH_LENGTH);
                $content.=(feof($fp))?"\n":'';
                $content_length = strrpos($content, "\n");
                $content = substr($content, 0, $content_length);
                $start += $content_length;
                $end_pos = 0;
                while (($end_pos = strpos($content, $keyword, $end_pos)) !== false){
                    $start_pos = strrpos($content, "\n", -$content_length + $end_pos);
                    $start_pos = ($start_pos === false)?0:$start_pos;
                    $end_pos = strpos($content, "\n", $end_pos);
                    $end_pos=($end_pos===false)?$content_length:$end_pos;
                    $return[]=array(
                       'f'=>$filename,
                       't'=>trim(substr($content, $start_pos, $end_pos-$start_pos))
                         );
                    $count++;
                    if ($count >= RESULT_LIMIT) break;
                  }
                unset($content,$content_length,$start_pos,$end_pos);
                if ($count >= RESULT_LIMIT) break;
                  }
        fclose($fp);
       if ($count >= RESULT_LIMIT) break;
     }
     return $return;
}
if(!empty($_POST)&&!empty($_POST['q'])){
    set_time_limit(0);				//不限定脚本执行时间
    $q=strip_tags(trim($_POST['q']));
    $results=get_results($q);
    $count=count($results);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>SGKst</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
    <style type="text/css">
    body,td,th{
    color: #FFF;
}
    a:link{
    color: #0C0;
    text-decoration: none;
}
    body{
    background-color: #000;
}
    a:visited {
    text-decoration: none;
    color: #999;
}
a:hover{
    text-decoration: none;
}
a:active{
    text-decoration: none;
    color: #F00;
}
    </style>
<script>
<!--
function check(form){
if(form.q.value==""){
  alert("Not null！");
  form.q.focus();
  return false;
  }
}
-->
</script>
</head>
    <body>
    <div id="container"><div id="header"><a href="http://www.baidu.com" ><h1>Social Engineering Data</h1></a></div><br /><br />
<form name="from" action="index.php" method="post">
    <div id="content">
    <div id="create_form">
    <label>Please input Keyword:<input class="inurl" size="26" id="unurl" name="q" value="<?php echo !empty($q)?$q:''; ?>"/></label>
    <p class="ali"><label for="alias">Key Words:</label><span>User,Email,QQ Account,Forum Account...</span></p>
    <p class="but"><input onClick="check(form)" type="submit" value="Search" class="submit" /></p>
    </form>
    </div>
  <?php
       if(isset($count))
       {
         echo 'Get ' .$count .' results,&nbsp;&nbsp;cost ' . (microtime(true) - $time_start) . " seconds";
         if(!empty($results)){
         echo '<ul>';
         foreach($results as $v){
         echo '<li>From_['.$v['f'].']_Datas <br />Content: '.$v['t'].'</li><br />';
           }
         echo '<br /><br /><font color=#ffff00><li>Resources from the Internet.<br />The information here does not represent the views of this site.</li></font>';
         echo '</ul>';
            }
         echo '<hr align="center" width="550" color="#2F2F2F" size="1"><font color="#ff0000">We cannot guarantee the entirely accurate,please voluntarily judge.';
         echo '<br />The data is not complete? Do you want to add or remove it?';
         echo '<br />Contact Email:anon@97bug.com</font>';
         echo '</ul>';
         }
         ?>
<div id="nav">
<ul><li class="current"><a href="#">Search Data</a></li><li><a href="html/about.html" target="_blank">Abouts</a></li></ul>
</div>
<div id="footer">
<p>Social Engineering Data &copy;2010-2013 Powered By <a href="#" target="_blank">SGKst<a></p><div style="display:none">
</div>
</div>
</body>
</html>