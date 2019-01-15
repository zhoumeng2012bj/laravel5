<?php

namespace App\Http\Controllers\BrokerCompany;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Excel;

class brokerCompanyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *显示渠道人员列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = Input::get('bk_name');
        $username = Input::get('username');
        $pageSize = Input::get('pageSize');
        $page = Input::get('page');
        $startdate = Input::get('startdate');
        $enddate = Input::get('enddate');
        $buildingname = Input::get('buildingname');
        $qvdaodengji = Input::get('qvdaodengji');
        $cid = Input::get('id');
        $tel= Input::get('bk_dianhua');
        $client = new Client ([
            'base_uri' => $this->base_url,
            'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', '/api/qd/person/list', [
            'query' => [
                'page' => $page,
                'size' => $pageSize,
                'compay' => $name,
                'uname' => $username,
                'loupan' => $buildingname,
                'qddj' => $qvdaodengji,
                'sdate' => $startdate,
                'edate' => $enddate,
                'compayid' => $cid,
                'tel'=>$tel,
            ]
        ]);
        return $response->getBody();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $info = Input::get();
//        if($info) {
//              return [
//                        'message' => '保存成功',
//                        'code' => 200,
//              ];
//        }

    }

    /**
     * Store a newly created resource in storage.
     *保存渠道人员
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        //dd( json_decode($request->params)) ;
        $r = $client->request('POST', '/api/qd/person/add', [
            'json' => $request->params
        ]);
        return $r->getBody();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        $response = $client->request('GET', '/api/qd/person/' . $id . '/get', [


            ]
        );
        echo $response->getBody();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *更新渠道人员
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $obj = $request->params;

        //dd($obj);
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/qd/person/alter', [
            'json' => $obj
        ]);
        return $r->getBody();
    }

    /**
     * Remove the specified resource from storage.
     *删除渠道人员
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        return $this->deleteCompany($id);
    }

    public function deleteCompany($id)
    {
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        $response = $client->request('GET', '/api/qd/person/{$$}/del');
        return $response->getBody();
    }

    /*
     * 批量删除
     * @param Request $request
     */
    public function batchRemoveBKUser(Request $request)
    {
        $ids = $request->params['ids'];

        $code = '200';
        $arr = explode(',', $ids);
        foreach ($arr as $item) {
            $status = $this->deleteCompany($item);
            if ($status->code != '200') {
                $code = $status->msg;
            }
        }
        return $code;
    }

    public function getbkNameList(Request $request)
    {

        $client = new Client([
            'base_uri' => $this->base_url,
            'timeout'  => 2.0,

        ]);
        $bkName = $request->params['name'];
        $parentid = $request->params['parentid'];
        $response = $client->request('GET', '/api/qd/compay/list', [
                'query' => [
                    'compay' => $bkName,
                    'zt' => 1,
                    'parentid'=>$parentid,
                ]
            ]
        );
        echo $response->getBody();

    }
		
		//获取银行账号
		public function getbankCNameList(Request $request)
		{
				$client = new Client([
						'base_uri' => $this->base_url,
						'timeout'  => 2.0,

				]);
				$bkName = Input::get('name');
				$response = $client->request('GET', '/api/contract/yhzh/banklist?name='.$bkName);
				echo $response->getBody();

		}
		
		//获取省
		public function getbankCName2List(Request $request)
		{
				$client = new Client([
						'base_uri' => $this->base_url,
						'timeout'  => 2.0,

				]);
				$bkName = Input::get('name');
				$response = $client->request('GET', '/api/contract/yhzh/citylist?name='.$bkName);
				echo $response->getBody();

		}

    //渠道等级
    public function getQDDJDicList(Request $request)
    {
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/qd/comm/person/qddj/list', [
            ]
        );
        echo $response->getBody();
    }

    //停用启用渠道人员状态
    public function changeBrokerCompanyUserStatus(Request $request)
    {
        $obj = $request->params;
        //  dd($obj);
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        if ($obj['status'] == 1) {
            $response = $client->request('GET', '/api/qd/person/' . $obj['id'] . '/start', [
                ]
            );
            echo $response->getBody();
        } else {
            $response = $client->request('GET', '/api/qd/person/' . $obj['id'] . '/stop', [
                ]
            );
            echo $response->getBody();
        }
    }
    //判断联系方式
    public function checkPhone(Request $request)
    {
        $obj = $request->params;
       // dd($obj);
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/qd/check/' . $obj['phone'] .'/'.$obj['id']. '/tel', [
            ]
        );
        echo $response->getBody();
    }
    //判断人员公司与姓名
    public function checkName(Request $request)
    {
        $obj = $request->params;
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/qd/person/checkcompayuname', [
                'query' => [
                    'compayid' => $obj['id'],
                    'uname' => $obj['name']
                ]
            ]
        );
        echo $response->getBody();
    }

    //导出Excel
    public function ExportExcel()
    {
        $name = Input::get('bk_name');
        $username = Input::get('username');
        $buildingname = Input::get('buildingname');
        $qvdaodengji = Input::get('qvdaodengji');
        $tel= Input::get('bk_dianhua');
        $dcts= Input::get('dcts');

        $sql = " select QD_PerName, zhiwu,tel,CompayName,gs_loupan, p.yslianxiren1, p.yslianxiren2,p.yslianxiren3, 
     IF(shifouweixin=0,'否','是'), tianjiahaoyourenshu, IF(shifoudaikanyoushi=0,'否','是'), daikancishu, 
    daikanduijierenshu, IF(shifouqianyueyoushifang=0,'否','是'), qianyuecishu, qianyueduijierenshu, qvdaodengji,p.beizhu
    from t_qd_person p LEFT JOIN t_qd_compay qd on p.T_QD_Compay_ID = qd.T_QD_Compay_ID
	left join (select personId,GROUP_CONCAT(dianhua)tel from t_qd_person_tel where qd_type=1 GROUP BY personId)dianhua on p.t_qd_person_id=dianhua.personId  ";
        $limitStart=($dcts-1)*6000+1;
        $limitEnd= 6000;
        $strWhere=" where 1=1 ";
        if (!empty($name)) {
            $strWhere = $strWhere . " and CompayName like '%" . $name . "%' ";

        }
        if (!empty($username)) {
            $strWhere = $strWhere . " and QD_PerName like '%" . $username . "%' ";

        }
        if (!empty($buildingname)) {
            $strWhere = $strWhere . " and gs_loupan  like '%" . $buildingname . "%' ";

        }
        if (!empty($qvdaodengji)) {
            $strWhere = $strWhere . " and qvdaodengji ='" . $qvdaodengji."'";

        }
        if (!empty($tel)) {
            $strWhere = $strWhere . " and tel like '%" . $tel . "%' ";

        }
        $count =  DB::connection('mysql3')->select("select count(*) as countNum from t_qd_person p LEFT JOIN t_qd_compay qd on p.T_QD_Compay_ID = qd.T_QD_Compay_ID
	    left join (select personId,GROUP_CONCAT(dianhua)tel from t_qd_person_tel where qd_type=1 GROUP BY personId)dianhua on p.t_qd_person_id=dianhua.personId  ".$strWhere) ;
        if($count[0]->countNum<=6000){
           $limitStart= 1;
           $limitEnd= 6000;
        }

        try {
            $sql=$sql.$strWhere."  limit ".$limitStart.", ".$limitEnd;
            $bk = DB::connection('mysql3')->select($sql);
            $cellData = $this->objToArray($bk);
           // dd(count($cellData) );
            //dd($cellData);
            if (count($cellData) > 0) {
                $headerData = ['姓名','职务','联系电话','渠道公司名称','项目名称','幼狮联系人1','幼狮联系人2','幼狮联系人3','是否添加微信好友','添加好友人数',
                    '是否带看幼狮','带看次数','带看对接人数','是否签约过幼狮','签约次数','签约对接人数','粘性等级','备注'];
                array_unshift($cellData, $headerData);
                //dd($cellData);
                Excel::create('渠道公司人员' . date("YmdHis"), function ($excel) use ($cellData) {
                    $excel->sheet('score', function ($sheet) use ($cellData) {
                        $sheet->rows($cellData);
                    });
                })->export('xls') ;
                echo "导出成功";
            } else {
                echo "无符合条件的数据";
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
