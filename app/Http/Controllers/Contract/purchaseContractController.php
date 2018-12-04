<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Api\MessageController;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class purchaseContractController extends Controller
{
    public function index()
    {
        $pn = Input::get('pn');
        $cnt = Input::get('cnt');
        $selectItem = Input::get('selectItem');
        $status = Input::get('zhuangtai');
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        $yongyouid = Input::get('yongyouid');
        if(!$pn){
            $pn = 1;
        }
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
            $response = $client->request('GET', '/api/contract/sf/list',[
                'query'=>[
                    'pn'=>$pn,
                    'cnt'=>$cnt,
                    'selectItem'=>$selectItem,
                    'status'=>$status,
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                    'yongyouid'=>$yongyouid,
                    'createId'=>Auth::user()->id,
                    ]
            ]);
            echo $response->getBody();
    }

    /**
     * Show the form for creating a new resource.
     *    合同新增的时候获取条款信息
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        $response = $client->request('GET', '/api/contract/sf/create');
        echo $response->getBody();

    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->params;
        //数据格式化
        $client = new Client([
            'base_uri' => $this->base_url,

            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $data = $request->params;
        if($request->params['dailirenName']==null){
            $data['dailirenName'] = '';
        }
        if($request->params['dailirenTel']==null){
            $data['dailirenTel'] = '';
        }
        if($request->params['dailirenId']==null){
            $data['dailirenId'] = '';
        }
        if($request->params['yingyezhizhao']==null){
            $data['yingyezhizhao'] = '';
        }
        $data['jiafangfeiyong'] = implode(',',$data['jiafangfeiyong']);
        $data['yifangfeiyong'] = implode(',',$data['yifangfeiyong']);
        $data['createId'] = Auth::user()->id;
        $response = $client->request('POST', '/api/contract/sf/save', [
            'json' => $data,
        ]);
        $res = $response->getBody();
        $res = json_decode($res);
        $res->data->yifangfeiyong = explode(',',$res->data->yifangfeiyong);
        $res->data->jiafangfeiyong = explode(',',$res->data->jiafangfeiyong);
        echo json_encode($res)  ;
    }

    /**
     * Display the specified resource.
     * 根据合同ID获取合同的信息
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/sf/'.$id);
        $res = $response->getBody();
        $res = json_decode($res);
        if($res->code!='200'){
            echo $response->getBody();
            exit;
        }
        $res->data->yifangfeiyong = explode(',',$res->data->yifangfeiyong);
        $res->data->jiafangfeiyong = explode(',',$res->data->jiafangfeiyong);
        $obj = $this->get_month_day($res->data->startdate,$res->data->enddate);
        //计算所有的免租期之和
        $free_month=0;
        $free_day=0;
        if(count($res->data->mianzuqiList)>0){
            foreach ($res->data->mianzuqiList as $key=>$value){
                $free_month += $this->DiffDate(
                    date('y-m-d',$value->startdate/1000),
                    date('y-m-d',strtotime(date('y-m-d',$value->enddate/1000).'+ 1 day'))
                    )[1];
                $free_day += $this->DiffDate(
                    date('y-m-d',$value->startdate/1000),
                    date('y-m-d',strtotime(date('y-m-d',$value->enddate/1000).'+ 1 day'))
                    )[2];
            }
        }
        if($free_day>30&&count($res->data->mianzuqiList)>1){
            $free_month += intval($free_day/30);
            $free_day = $free_day%30;
        }
        $res->data->nian = $obj->y;
        $res->data->yue = $obj->m;
        $res->data->ri = $obj->d;
        $res->data->free_month = $free_month;
        $res->data->free_day  = $free_day;
        echo json_encode($res)  ;
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response1 = $client->request('GET', '/api/contract/sf/'.$id.'/submit');
        $contract = $this->getContractInfo($id);//合同信息
        $loupan = '';
        foreach ($contract->officeList as $office){
            $loupan .= $office->loupanName.'-'.$office->loudongName.'-'.$office->fanghao.',';
        }
        $loupan = rtrim($loupan,',');
        $this->sendMessage(11,$loupan,$contract->createId);
        $this->sendGJMessage($loupan,$contract->createId);
        echo $response1->getBody();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 二次优化
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('POST', '/api/contract/sf/buchongXieyi/save', [
            'json' => $request->params
        ]);
        echo $response->getBody();
    }
    /*
     * 合同审核 结果
     * hetongid,hetongbianhao,shenherenid,shenherenname,content,result,ShenheFlg
     */
    public function review(Request $request){
        $contract = $this->getContractInfo($request->params['hetongid']);
        $data = $request->params;
        $data['hetongbianhao'] = $contract->bianhao;
        $data['shenherenid'] = Auth::user()->id;
        $data['shenherenname'] =Auth::user()->name;

        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        //根据合同当前的状态判断是初审还是复审
        if($request->params['shenheFlg']==0){
            //如果复审通过，短信发给法务
            if($data['result']==1){
                $loupan = '';
                foreach ($contract->officeList as $office){
                    $loupan .= $office->loupanName.'-'.$office->loudongName.'-'.$office->fanghao.',';
                }
                $loupan = rtrim($loupan,',');
                $this->sendMessage(12,$loupan,$contract->createId);
            }
            $response = $client->request('POST', '/api/contract/sf/chushen', [
                'json' => $data
            ]);
        }elseif ($request->params['shenheFlg']==1){
            $response = $client->request('POST', '/api/contract/sf/shenhe', [
                'json' => $data
            ]);
        }
        echo $response->getBody();
    }
    /*
     * 修改合同条款api/contract/hetong/update/tiao
     * */
    public function editTiaoKuan(Request $request){
        $data['id'] = $request->params['id'];
        $client = new Client([
            'base_uri' => $this->base_url,

            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        //条
        if(array_key_exists('kuanList', $request->params)){
            $requestUrl = '/api/contract/hetong/update/tiao';
            $data['title'] = $request->params['title'];
        }
        //款
        if(array_key_exists('xiangList', $request->params)){
            $requestUrl = '/api/contract/hetong/update/kuan/id/'.$data['id'];
            $data['content'] = $request->params['content'];
        }
        //项
        if(array_key_exists('kuanid', $request->params)){
            $requestUrl = '/api/contract/hetong/update/xiang';
            $data['content'] = $request->params['content'];
        }
        $response = $client->request('POST', $requestUrl, [
            'json' => $data
        ]);
        echo $response->getBody();
    }
    //合同状态变为：优化成功  执行两个动作：协议改为已提交的状态，合同改为已经优化的状态
    public function released(){
        $id = Input::get('hetongid');
        $xyid = Input::get('xyid');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/sf/'.$id.'/released');
        $response = $client->request('GET', '/api/contract/sf/buchongXieyi/'.$xyid.'/confrim?id='.$xyid);
        echo $response->getBody();
    }
    //优化协议的列表
    public function releasedList(){
        $id = Input::get('id');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response1 = $client->request('POST', 'api/contract/sf/buchongXieyi/query?hetongId='.$id);
        //根据合同id获取房间信息
        $response2 = $client->request('GET', '/api/contract/sf/'.$id);
        $res = $response2->getBody();
        $res = json_decode($res);
        $loupan = $res->data->officeList[0]->loupanName;
        $loudong = $res->data->officeList[0]->loudongName;
        $fanghao = $res->data->officeList[0]->fanghao;
        $bianhao = $res->data->bianhao;

        $res = $response1->getBody();
        $res = json_decode($res);
        foreach ($res->data as $key=>$value){
            $value->loupanName = $loupan;
            $value->loudongName = $loudong;
            $value->fanghao = $fanghao;
            $value->bianhao = $bianhao;
        }
      echo json_encode($res);

    }

    /*
     * /api/contract/sf/buchongXieyi/3
     * 获取该合同的优化协议
     * */
    public function getOptimize(){
        $id = Input::get('id');
        $hetongid = Input::get('hetongid');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);

        if($hetongid){
            $url = '/api/contract/sf/buchongXieyi?hetongId='.$hetongid;
        }elseif($id){
            $url = '/api/contract/sf/buchongXieyi?id='.$id;
        }
        $response = $client->request('GET',$url );
        echo $response->getBody();
    }
    //合同终止的时候提交合同ID，然后获取应付信息
    public function weiYueInfo(Request $request){
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('POST', '/api/cw/comm/inithtwy', [
            'json' => $request->params
        ]);
        echo $response->getBody();

    }
    /*
     * api/contract/sf/weiyue/save
     * */
    public function weiYueSave(Request $request){
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('POST', '/api/contract/sf/weiyue/save', [
            'json' => $request->params
        ]);
        echo $response->getBody();
    }
    /*isCopyComplete
     * 扫描合同复印件列表copyImageList
     * */
    public function copyImageList(Request $request){
        $id = Input::get('id');
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('GET', '/api/contract/sf/img/'.$id.'/query', [

        ]);
        $res = json_decode($response->getBody());
        if($res->data){
            $data2 = [];
            foreach ($res->data as $key => $value){
                //新文件名
                $value->url = $value->path;
                $value->content =null;
                $data2[$value->type][] = $value;
            }
            $res->data = $data2;
            echo json_encode($res);
        }
    }

    public function addCopyImage(){
        //PHP上传失败
        if (!empty($_FILES['file']['error'])) {
            switch($_FILES['file']['error']){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            return $error;
        }
        $fp = fopen($_FILES["file"]["tmp_name"],"rb");
        $image = fread($fp,$_FILES["file"]["size"]);
        $image = base64_encode($image);
        $data = [
            'hetongid'=>$_POST['id'],
            'type'=>$_GET['type'],
            'content'=>$image,
        ];
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('POST', '/api/contract/sf/img/upload', [
            'json' =>$data
        ]);
        echo $response->getBody();
    }
    /*
     * 删除图片
     * */
    public function deleteCopyImage(){
        $id = Input::get('id');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/sf/img/'.$id.'/del/');
        echo $response->getBody();

    }
    /*
     * 资料是否齐全
     * /api/contract/sf/img/set
     * */
    public function isCopyComplete(Request $request){
//        return $request->params;
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('POST', '/api/contract/sf/img/set', [
            'json' =>$request->params
        ]);
        echo $response->getBody();
    }
    public function getCopyComplete(){
        $id = Input::get('id');
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('GET', '/api/contract/sf/img/'.$id.'/isComplete');
        echo $response->getBody();
    }
    /**
     * 计算两个日期之间差几年几个月几天
     * @param  [string] $date1 [2000-11-05]
     * @param  [string] $date2 [2000-11-05]
     * @return [arr]        [
     *    t => year
     *    m => month
     *    d => day
     * ]
     */
    public function get_month_day($startdate,$enddate)
    {
        $startdate = $startdate/1000;
        $enddate   = $enddate/1000;
        $startdate = date_create(date('Y-m-d',$startdate));
        $enddate   = date_add(date_create(date('Y-m-d',$enddate)),date_interval_create_from_date_string('1 day'));
        $bian = date_diff($startdate,$enddate);
        return $bian;
    }
    public function DiffDate($date1, $date2) {
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
    //付款账号的提交
    public function zhanghaoSave(Request $request){
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $user = Auth::user();
        $param=array_merge($request->params,Array('username'=>$user->name,'userid'=>$user->id));
        $response = $client->request('POST', '/api/contract/yhzh/add', [
            'json' => $param
        ]);
        echo $response->getBody();
    }
    //付款账号的提交
    public function zhanghaoAlter(Request $request){
        //dd(33333);
        $client = new Client([
            'base_uri' => $this->base_url,

            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $user = Auth::user();
        $param=array_merge($request->params,Array('username'=>$user->name,'userid'=>$user->id));
        $response = $client->request('POST', '/api/contract/yhzh/alter', [
            'json' => $param
        ]);
        echo $response->getBody();
    }
    //获取账号数据列表
    public function getZhanghaoList()
    {
        $htid = Input::get('htid');
        $page = Input::get('page');
        $size = Input::get('size');
        $httid = Input::get('httid');
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/yhzh/list',
            [
                'query' => [
                    'htid' => $htid,
                    'httid' =>  $httid,
                    'page' => $page,
                    'size' => $size,
                ]
            ]
    );
        echo $response->getBody();
        /*if(!$page){
            $pn = 1;
        }*/
    }
    public function deleteZhanghao(Request $request){
        $user = Auth::user();
        $param=array_merge($request->params,Array('username'=>$user->name,'userid'=>$user->id));
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/yhzh/delete',[
            'query' =>$param
        ]);
        echo $response->getBody();
    }
    public function changeZhanghao(Request $request){
        $user = Auth::user();
        $param=array_merge($request->params,Array('username'=>$user->name,'userid'=>$user->id,'httype'=>0));
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/yhzh/changeState',[
        'query' =>$param
            ]);
        echo $response->getBody();
    }
    //合单的提交
    public function hedanSave(Request $request){
        //dd(33333);
        $client = new Client([
            'base_uri' => $this->base_url,

            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('POST', '/api/contract/sf/splitbill/create', [
            'json' => $request->params
        ]);
        echo $response->getBody();
    }
    //获取合单数据列表
    public function getHedanList()
    {
        //dd(12121);
        $id = Input::get('htid');
        $pn = Input::get('pn');
        $cnt = Input::get('cnt');
        //dd($id);
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/sf/splitbill/query?id='.$id.'&pn='.$pn.'&cnt='.$cnt);
        echo $response->getBody();

    }
    public function deleteHedan(){
        //dd(111);
        $id = Input::get('id');
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('GET', '/api/contract/sf/splitbill/delete?id='.$id);
        echo $response->getBody();
    }
    public function updataHedan(Request $request){
        //dd(22);
        $client = new Client([
            'base_uri' => $this->base_url,

            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('POST', '/api/contract/sf/splitbill/update', [
            'json' => $request->params
        ]);
        echo $response->getBody();
    }
    //用友编号的保存
    public function saveyongyou(Request $request){
        //dd(111);
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $data = $request->params;
        if($request->params['yongyouid']==null){
            $data['yongyouid'] = '';
        }
        $response = $client->request('POST', '/api/contract/sf/saveyongyou', [
            'json' => $data
        ]);
        echo $response->getBody();
    }
    public function summary(){
        $id = Input::get('id');
        $httype = Input::get('httype');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/proof/get?htid='.$id.'&httype='.$httype);
        echo $response->getBody();

    }
    /**
     * @param $property_id
     * 当解约和合同终止的时候改变omc房源的状态
     */
    protected function omcPropertyStatus($property_id)
    {
        $client = new Client([
            'base_uri' => $this->omc_url,
        ]);
        $json=['fyid'=>$property_id];
        $client->post('/yhcms/web/updateFyZt.do',['json'=>$json]);
    }

    /**
     * @param Request $request 改变合同状态
     */
    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = substr($request->path(),17);
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('GET', '/api/contract/sf/'.$id.'/'.$status);
        echo $response->getBody();

        $contract = $this->getContractInfo($id);
        switch ($status)
        {
            case 'cancelled':
                foreach ($contract->officeList as $item){
                    $this->omcPropertyStatus($item->omcId);
                }
                break;
            case 'terminated':
                foreach ($contract->officeList as $item){
                    $this->omcPropertyStatus($item->omcId);
                }
                break;
            case 'confirm':
                $this->omcSginfo($contract);
                break;
            default:
        }
    }

    /**
     * @param $id 合同ID
     * @return mixed
     */
    public function getContractInfo($id)
    {
        $client = new Client([
            'base_uri' => $this->base_url,
            'headers' =>['access_token'=>'XXXX','app_id'=>'123']
        ]);
        $response = $client->request('GET', '/api/contract/sf/'.$id);
        $res = json_decode($response->getBody());
        $contract = $res->data;
        return $contract;
    }

    /**
     * @param $contract 合同信息
     */
    public function omcSginfo($contract)
    {
        $client2  = new Client ([
            'base_uri' => $this->omc_url,
        ]);
        $json = ['parameters'=>[]];
        foreach ($contract->officeList as $item){
            $json['parameters'][] = [
                'fyid'=>$item->omcId,
                'mzq'=>date('Y-m-d',$contract->mianzuqiList[0]->startdate/1000),
                'sgtime'=>date('Y-m-d',$contract->shoufangdate/1000),
                'sgprice'=>$contract->zujinList[0]->price,
            ];
        }
        $client2->post('/yhcms/web/jcsj/addFyMzq.do',[
            'json'=>$json
        ]);
    }

    public function sendMessage($type,$loupan,$createId){
        //第一次提交的发送信息给初审人
        $user =User::find($createId) ;//1.获取用户信息
        if(!empty($user->parentId)) {
            $send_to_id = $user->parentId;
            $parent = User::find($send_to_id);
            if (!empty($parent) && !empty($parent->phone)) {
                $send_to_name = $parent->name;
                $phone = $parent->phone;
                $content = $type == 11 ? "合同初审" : "合同复审";
                $data = [
                    "send_from_id" => $user->id,
                    "send_from_name" => $user->name,
                    "send_from_sys" => "erp",
                    "send_to_id" => $send_to_id,
                    "send_to_name" => $send_to_name,
                    "send_to_sys" => "erp",
                    "phone" => $phone,
                    "type" => $type,
                    "is_message" => 1,
                    "is_web" => 1,
                    "content" => $content,
                    "title" => $content,
                    "loupan" => $loupan
                ];
                $message = new MessageController();
                $message->createMessage($data);
            }
        }
    }
    public function sendGJMessage($loupan,$createId){
        //第一次提交的发送信息给初审人
        $qianyueren = User::find($createId);//1.获取用户信息
        if(!empty($qianyueren->butlerId)) {
        $butler = User::find($qianyueren->butlerId);
        if(!empty($butler)&&!empty($butler->phone)) {
            $send_to_name = $butler->name;
            $phone = $butler->phone;
            $content = "合同初审";
            $data = [
                "send_from_id" => $qianyueren->id,
                "send_from_name" => $qianyueren->name,
                "send_from_sys" => "erp",
                "send_to_id" => $butler->id,
                "send_to_name" => $send_to_name,
                "send_to_sys" => "erp",
                "phone" => $phone,
                "type" => 13,
                "is_message" => 1,
                "is_web" => 1,
                "content" => $content,
                "title" => $content,
                "loupan" => $loupan,
                "qianyueren" => $qianyueren->name,
                "date" => date('Y-m-d H:i:s', time()),
                "httype" => "收房",
            ];
            $message = new MessageController();
            $message->createMessage($data);
        }
        }
    }
    //编号查找合同是否存在
    public function checkbianhao(Request $request){
        //dd(111);
        $bianhao = $request->params['bianhao'];
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/contract/sf/checkBianhao/'.$bianhao.'/check', [
        ]);
        echo $response->getBody();
    }
		
		//新增优化方案
		public function opssplanadd(Request $request){
				//return $request->params;
				//数据格式化
				$client = new Client([
						'base_uri' => $this->base_url,
						'headers' =>['access_token'=>'XXXX','app_id'=>'123']
				]);
// 						$json=[
// 							'optimizationnum'=>'3',
// 							'plancontext'=>'111111111111',
// 							'plandate'=>'2018-11-13',
// 							'hetongid'=>'111',
// 							'hetongtid'=>'0',
// 							'createuser'=>'11',
// 							'createusername'=>'11111111',
// 							];
				//获取当前登录用户的名称和id
				$user = Auth::user();
				$obj=array_merge($request->params,Array('createusername'=>$user->name,'createuser'=>$user->id));
				
				$response = $client->request('POST', '/api/follow/plan/add', [
					 'json' => $obj
				]);
				
				//echo输出的值就是要返回值
				echo $response->getBody();
		}
		public function viewoptimi(Request $request){
				$htid = Input::get('htid');
				$httid = Input::get('httid');
				//return $request->params;
				//数据格式化
				$client = new Client([
						'base_uri' => $this->base_url,
				]);
				$response = $client->request('GET', '/api/follow/plan/list?htid='.$htid.'&httid='.$httid, [
				]);
				
				//echo输出的值就是要返回值
				echo $response->getBody();
		}
		public function formfollow(Request $request){
				//return $request->params;
				//数据格式化
				$client = new Client([
						'base_uri' => $this->base_url,
				]);
				//获取当前登录用户的名称和id
				$user = Auth::user();
				$obj=array_merge($request->params,Array('createusername'=>$user->name,'createuser'=>$user->id));
				
				$response = $client->request('POST', '/api/follow/optimization/add', [
					 'json' => $obj
				]);
				
				echo $response->getBody();
		}
		public function vieformfollow(Request $request){
				$htid = Input::get('htid');
				$httid = Input::get('httid');
				$currentPage = Input::get('currentPage');
				$pageSize = Input::get('pageSize');
				//return $request->params;
				//数据格式化
				$client = new Client([
						'base_uri' => $this->base_url,
				]);
				$response = $client->request('GET', '/api/follow/optimization/list?htid='.$htid.'&httid='.$httid.'&page='.$currentPage.'&size='.$pageSize, [
				]);
				
				//echo输出的值就是要返回值
				echo $response->getBody();
		}
		public function violate(Request $request){
				$htid = Input::get('htid');
				$httid = Input::get('httid');
				$currentPage = Input::get('currentPage');
				$pageSize = Input::get('pageSize');
				$client = new Client([
						'base_uri' => $this->base_url,
				]);
				$response = $client->request('GET', '/api/follow/break/list?htid='.$htid.'&httid='.$httid.'&page='.$currentPage.'&size='.$pageSize, [
				]);
				
				echo $response->getBody();
		}
		public function addviolate(Request $request){
				$client = new Client([
						'base_uri' => $this->base_url,
				]);
				$user = Auth::user();
				$obj=array_merge($request->params,Array('createusername'=>$user->name,'createuser'=>$user->id));
				
				$response = $client->request('POST', '/api/follow/break/add', [
					'json' => $obj
				]);
				
				echo $response->getBody();
		}
		
}
