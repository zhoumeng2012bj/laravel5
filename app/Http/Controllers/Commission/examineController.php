<?php

namespace App\Http\Controllers\Commission;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class examineController extends Controller
{
    
	public function auditlist(Request $request)
	{
			$htno = Input::get('htno');
			$xm = Input::get('xm');
			// $zh = Input::get('zh');
			$isfirst = Input::get('isfirst');
			$zt = Input::get('zt');
			$startdate = Input::get('startdate');
			$enddate = Input::get('enddate');
			$pageSize = Input::get('size');
			$page= Input::get('page');
			$client = new Client ([
					'base_uri' => $this->base_url,
			]);
			
			$user = Auth::user();
			$phone=$user->phone;
			$response = $client->request('GET', '/api/cw/ys/submit/auditlist',[
					'query' => [
							'page'=>$page,
							'size'=>$pageSize,
							'sdate'=>$startdate,
							'edate'=>$enddate,
							'xm'=>$xm,
							// 'zh'=>$zh,
							'phone'=>$phone,
							'isfirst'=>$isfirst,
							'zt'=>$zt,
							'htno' =>$htno,
							]
		 ]
			);
			echo $response->getBody();

	}
	//修改记录列表
	public function modify(Request $request)
	{
			$id = Input::get('id');
			$client = new Client ([
					'base_uri' => $this->base_url,
			]);
			
			$response = $client->request('GET', '/api/cw/ys/'.$id.'/getalterlist'
			);
			echo $response->getBody();

	}
	//提交记录列表
	public function submi(Request $request)
	{
			$srid = Input::get('srid');
			$client = new Client ([
					'base_uri' => $this->base_url,
			]);
			
			$response = $client->request('GET', '/api/cw/ys/submit/list',[
					'query' => [
							'srid'=>$srid,
							]
		 ]
			);
			echo $response->getBody();

	}
	
	public function addYXJ(Request $request)
	{
		//以后用户会从OMC取
		$u = Auth::user();
		$user=    Array(
			'personId'=>$u->id,
			'personName'=>$u->name,
		);

		$obj=array_merge($request->params,$user);
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);

		$r = $client->request('POST', '/api/cw/ys/alter/skje', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//应收管理修提交记录列表中的审批提交
	public function bleSubmi(Request $request)
	{
		//以后用户会从OMC取
		$u = Auth::user();
		$user= Array(
			'spuser'=>$u->id,
			'spusername'=>$u->name,
		);
		$obj=array_merge($request->params,$user);
		
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);

		$r = $client->request('POST', '/api/cw/ys/submit/audit', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//应收管理修改记录中的审批提交
	public function bleedidSu(Request $request)
	{
		//以后用户会从OMC取
		$u = Auth::user();
		$user= Array(
			'auditid'=>$u->id,
			'auditname'=>$u->name,
		);
		$obj=array_merge($request->params,$user);
		
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);

		$r = $client->request('POST', '/api/cw/ys/update/audit', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//应收计划列表
	public function planList(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('size');
		$isfirst = Input::get('isfirst');
		$htno = Input::get('htno');
		$xm = Input::get('xm');
		$zt = Input::get('zt');
		$dzzt = Input::get('dzzt');
		$sdate = Input::get('sdate');
		$edate = Input::get('edate');
		$user = Auth::user();
		$phone=$user->phone;
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		
		$response = $client->request('GET', '/api/cw/ys/plan/list',[
				'query' => [
					'page'=>$page,
					'size'=>$size,
					'isfirst'=>$isfirst,
					'htno'=>$htno,
					'xm'=>$xm,
					'zt'=>$zt,
					'dzzt'=>$dzzt,
					'sdate'=>$sdate,
					'edate'=>$edate,
					'phone'=>$phone,
				]
	 ]
		);
		echo $response->getBody();

	}
	//应收计划列表中的撤回
	public function withdrawlist(Request $request)
	{
		$obj=$request->params;
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);

		$r = $client->request('POST', '/api/cw/ys/plan/back', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//实收管理列表
	public function finance(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('pageSize');
		$zt = Input::get('zt');
		$sdate = Input::get('sdate');
		$edate = Input::get('edate');
		$fkzh = Input::get('fkzh');
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		
		$response = $client->request('GET', '/api/cw/ys/caiwulist',[
				'query' => [
					'page'=>$page,
					'size'=>$size,
					'zt'=>$zt,
					'sdate'=>$sdate,
					'edate'=>$edate,
					'fkzh'=>$fkzh,
				]
	 ]
		);
		echo $response->getBody();

	}
	//实收管理列表中的认领列表
	public function claim(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('pageSize');
		$htno = Input::get('htno');
		$xm = Input::get('xm');
		$zh = Input::get('zh');
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		
		$response = $client->request('GET', '/api/cw/ys/planRL/list',[
				'query' => [
					'page'=>$page,
					'size'=>$size,
					'htno'=>$htno,
					'xm'=>$xm,
					'zh'=>$zh,
				]
	 ]
		);
		echo $response->getBody();

	}
	//实收管理列表中的认领
	public function Claimsub(Request $request)
	{
		//以后用户会从OMC取
		$u = Auth::user();
		$user= Array(
			'createuser'=>$u->id,
			'createusername'=>$u->name,
		);
		$obj=array_merge($request->params,$user);
		
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);
		$r = $client->request('POST', '/api/cw/ys/caiwurenling', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//实收管理中的认领记录列表
	public function ClaimRecoed(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('pageSize');
		$id = Input::get('id');
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		
		$response = $client->request('GET', '/api/cw/ys/caiwurenlinglist',[
				'query' => [
					'page'=>$page,
					'size'=>$size,
					'id'=>$id,
				]
	 ]
		);
		echo $response->getBody();

	}
	//实收管理中的认领记录列表中的取消认领
	public function ClaimRdelt(Request $request)
	{
		$id = Input::get('id');
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		
		$response = $client->request('GET', '/api/cw/ys/submit/'.$id.'/qvxiaorenling'
		);
		echo $response->getBody();

	}
	//实收管理列表中的新增
	public function fSave(Request $request)
	{
		//以后用户会从OMC取
		$obj=$request->params;
		
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);
		$r = $client->request('POST', '/api/cw/ys/caiwuadd', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//实收管理中的查看上传凭证图片
	public function ImageList(Request $request){
		$id = Input::get('id');
		$client = new Client([
			'base_uri' => $this->base_url,
			'headers' =>['access_token'=>'XXXX','app_id'=>'123']
		]);
		$response = $client->request('GET', '/api/cw/ys/img/'.$id.'/query', [

		]);
		$res = json_decode($response->getBody());
		if($res->data){
			$data2 = [];
			foreach ($res->data as $key => $value){
				//新文件名
				$value->url = $value->uploadpath;
				$value->content =null;
				$data2[$key] = $value;
			}
			$res->data = $data2;
			echo json_encode($res);
		}
	}
	//实收管理列表中的上传凭证
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
				'tCwSrCaiwuId'=>$_POST['id'],
				'content'=>$image,
		];
		//以后用户会从OMC取
		
		$u = Auth::user();
		$user= Array(
			'createuser'=>$u->id,
			'createusername'=>$u->name,
		);
		$obj=array_merge($data,$user);
		$client = new Client([
				'base_uri' => $this->base_url,
				'headers' =>['access_token'=>'XXXX','app_id'=>'123']
		]);
		$response = $client->request('POST', '/api/cw/ys/img/upload', [
				'json' =>$obj
		]);
		echo $response->getBody();
	}
	//实收管理中的删除上传凭证图片
	public function ImageListDelete(Request $request){
		$id = Input::get('id');
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/ys/img/'.$id.'/del');
		echo $response->getBody();
	}
    //对账列表
    public function accountslist()
    {
        $page = Input::get('page');
        $size = Input::get('pageSize');
        $zt = Input::get('zt');
        $sdate = Input::get('sdate');
        $edate = Input::get('edate');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/ys/payreconciliation/list',[
                'query' => [
                    'page'=>$page,
                    'size'=>$size,
                    'zt'=>$zt,
                    'sdate'=>$sdate,
                    'edate'=>$edate,
                ]
            ]
        );
        echo $response->getBody();

    }
    //对账详情列表
    public function accountsdatalist()
    {
        $page = Input::get('page');
        $size = Input::get('pageSize');
        $zt = Input::get('zt');
        $sdate = Input::get('sdate');
        $edate = Input::get('edate');
        $xm = Input::get('xm');
        $id = Input::get('id');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/ys/planPay/list',[
                'query' => [
                    'page'=>$page,
                    'size'=>$size,
                    'caozuozhuangtai'=>$zt,
                    'sdate'=>$sdate,
                    'edate'=>$edate,
                    'xm'=>$xm,
                    'reconciliation_id'=>$id,
                ]
            ]
        );
        echo $response->getBody();
    }

    //对账处理
    public function accountsdatahandle(Request $request)
    {
        //以后用户会从OMC取
        $u = Auth::user();
        $user= Array(
            'createuser'=>$u->id,
            'createusername'=>$u->name,
        );
        $obj=array_merge($request->params,$user);

        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        $r = $client->request('POST', '/api/cw/ys/caiwurenling', [
            'json' => $obj
        ]);
        return $r->getBody();
    }
    //处理记录
    public function accountsdatahandlelist()
    {
        $id= Input::get('id');
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/ys/handle/'.$id.'/query'  );
        echo $response->getBody();
    }
}
