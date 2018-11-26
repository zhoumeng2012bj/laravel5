<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contract\saleContractController;
use App\models\Role;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Excel;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arr = [1, 2, -4, 4, 10, -3, 4, -5, 1];
        //1。找出所有的连续子数组
        //1.1先实现一个切片的函数
        //1.2弄出所有可能的的开始和结束
        $len = count($arr);
        $childrenValues = [];
        for ($i=0;$i<$len;$i++){
            for($j=0;$j<$len;$j++){
                if($j>=$i){
                    $newArr=$this->spliceArr($arr,$i,$j);
                    $childrenValues[array_sum($newArr)] =  $newArr ;
//                    echo $i."+".$j.'<br>';
                }

            }
        }
        //2.计算每个子数组的和
        //3。找出最大
         ksort($childrenValues);
         $res = array_pop( $childrenValues);
        var_dump($res);exit;
        var_dump(env('CONTRACT_ID'));exit;
        $contract = new saleContractController();
        $info = $contract->getContractInfo(4464);//合同信息
        echo '<pre>';
        var_dump($info->xsOffice);

        exit;

        $loupan = '';
        foreach ($info->xsOffice as $office){
            $loupan .= $office->loupanName.'-'.$office->loudongName.'-'.$office->fanghao.',';
        }
        $loupan = rtrim($loupan,',');
        var_dump($loupan);
        exit;

//      $str="1,dsf,3ee,34r,rf,d,we,dfewr,erf";
//      $s="";
//      $ss=[];
//      for ($i=0;$i< strlen($str);$i++ ){
//          if($str[$i]==","){
//              $ss[]=$s;
//              $s="";
//          }else{
//              $s=$s.$str[$i];
//          }
//
//      }
//        $ss[]=$s;
//      print_r($ss);


    }
    public function spliceArr($arr,$start,$end){
        $newArr=[];
        foreach ($arr as $key => $item){
            if($key>=$start&&$key<=$end){
                $newArr[]=$arr[$key];
            }
        }
        return $newArr;
    }
    function DiffDate($date1, $date2) {
        if (strtotime($date1) > strtotime($date2)) {
            $ymd = $date2;
            $date2 = $date1;
            $date1 = $ymd;
        }
        list($y1, $m1, $d1) = explode('-', $date1);
        list($y2, $m2, $d2) = explode('-', $date2);
        $y = $m = $d = $_m = 0;
        $math = ($y2 - $y1) * 12 + $m2 - $m1;
        $y = round($math / 12);
        $m = intval($math % 12);
        $d = (mktime(0, 0, 0, $m2, $d2, $y2) - mktime(0, 0, 0, $m2, $d1, $y2)) / 86400;
        if ($d < 0) {
            $m -= 1;
            $d += date('j', mktime(0, 0, 0, $m2, 0, $y2));
        }
        $m < 0 && $y -= 1;
        return array($y, $m, $d);
    }
    public function objToArray($data){
        //1.获取数据
        $data2 = [];
        foreach ($data as $key=>$value){
            foreach ($value as $k=>$v){
                $data2[$key][] = $v;
            }
        }
        return $data2;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return 1;
        $fp = fopen($_FILES["file"]["tmp_name"],"rb");
        $image = fread($fp,$_FILES["file"]["size"]);
        $image = base64_encode($image);
        $data = [
            'id'=>$_POST['id'],
            'type'=>$_GET['type'],
            'image'=>$image,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function removeDir($dirName)
    {
        if(! is_dir($dirName))
        {
            return false;
        }
        $handle = @opendir($dirName);
        while(($file = @readdir($handle)) !== false)
        {
            if($file != '.' && $file != '..')
            {
                $dir = $dirName . '/' . $file;
                is_dir($dir) ? removeDir($dir) : @unlink($dir);
            }
        }
        closedir($handle);

        return rmdir($dirName) ;
    }
    public function  mHash($key){
        $md5=substr(md5($key),0,8);
        $seed=31;
        $hash=0;
        for ($i=0;$i<8;$i++){
            $hash=$hash*$seed+ord($md5{$i});
        }
        return $hash&0x7FFFFFFF;
    }

    public  function binToDec($num){
        $length = strlen($num);
        $value = 0;
        $n = $length;
        for ($i=0;$i<$length;$i++){
            $value += (int)$num[$i]*pow(2,$n-1);
            $n--;
        }
        return $value;
    }

}
