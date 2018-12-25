<?php

namespace App\Http\Controllers\Report;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class settlementController extends Controller
{
  //应付款管理列表(结算)
	public function settlement(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('pageSize');
		$htno = Input::get('htno');
		$xm = Input::get('xm');
		$sdate = Input::get('sdate');
		$edate = Input::get('edate');
		$listState = Input::get('listState');
		$zt = Input::get('zt');
		$zt2 = Input::get('zt2');
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$user = Auth::user();
		$phone=$user->phone;
		$response = $client->request('GET', '/api/cw/yf/settlementList',[
				'query' => [
					'page'=>$page,
					'size'=>$size,
					'zt'=>$zt,
					'htno'=>$htno,
					'xm'=>$xm,
					'sdate'=>$sdate,
					'edate'=>$edate,
					'listState'=>$listState,
					'zt2'=>$zt2,
					'phone'=>$phone,
					
				]
	 ]
		);
		echo $response->getBody();
 
	}
	//应付中付款记录列表
	public function payment(Request $request)
	{
		$id = Input::get('id');
		
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/yf/'. $id .'/getSubmitRecords'
		);
		echo $response->getBody();
	
	}
	//应付中付款记录列表中的扣款记录列表
	public function deduction(Request $request)
	{
		$id = Input::get('id');
		
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/yf/'. $id .'/getRecordDeduction'
		);
		echo $response->getBody();
	
	}
	//应付款管理列表(结算)中的提交付款
	public function submissionpayable(Request $request)
	{
		$id = Input::get('id');
		
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/yf/updateSettlement',[
					'query' => [
							'id'=>$id,
							]
		 ]
		);
		echo $response->getBody();
	
	}
	//应付款管理(风控)列表中的提交付款
	public function riskSubmi(Request $request)
	{
		//以后用户会从OMC取
		$u = Auth::user();
		$user= Array(
			'faqirenid'=>$u->id,
			'faqiren'=>$u->name,
		);
		$obj=array_merge($request->params,$user);
		
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);

		$r = $client->request('POST', '/api/cw/yf/riskSubmit', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//财务修改金额列表
	public function amont(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('pageSize');
		$htno = Input::get('htno');
		$xm = Input::get('xm');
		$sdate = Input::get('sdate');
		$edate = Input::get('edate');
		$tijiaoState = Input::get('tijiaoState');
		$user = Auth::user();
		$phone=$user->phone;
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/yf/financeList',[
					'query' => [
							'page'=>$page,
							'size'=>$size,
							'htno'=>$htno,
							'xm'=>$xm,
							'sdate'=>$sdate,
							'edate'=>$edate,
							'tijiaoState'=>$tijiaoState,
							'phone'=>$phone,
							]
		]
		);
		echo $response->getBody();
	
	}
	//财务修改金额列表中的提交
	public function subCareful(Request $request)
	{
		//以后用户会从OMC取
		$u = Auth::user();
		$user= Array(
			'faqirenid'=>$u->id,
			'faqiren'=>$u->name,
		);
		$obj=array_merge($request->params,$user);
		
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);
		$r = $client->request('POST', '/api/cw/yf/financeSubmit', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//财务修改金额列表中的代付扣款
	public function commission(Request $request)
	{
		//以后用户会从OMC取
		$u = Auth::user();
		$user= Array(
			'faqirenid'=>$u->id,
			'faqiren'=>$u->name,
		);
		$obj=array_merge($request->params,$user);
		
		// dd( json_encode( $obj));
		$client = new Client ([
			'base_uri' => $this->base_url,

		]);
		$r = $client->request('POST', '/api/cw/yf/deductionSubmit', [
			'json' => $obj
		]);
		return $r->getBody();
	}
	//应付计划列表
	public function plan(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('size');
		$htno = Input::get('htno');
		$xm = Input::get('xm');
		$sdate = Input::get('sdate');
		$edate = Input::get('edate');
		$fukuanstate = Input::get('fukuanstate');
		$user = Auth::user();
		$phone=$user->phone;
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/yf/planList',[
					'query' => [
							'page'=>$page,
							'size'=>$size,
							'htno'=>$htno,
							'xm'=>$xm,
							'sdate'=>$sdate,
							'edate'=>$edate,
							'fukuanstate'=>$fukuanstate,
							'phone'=>$phone,
							]
		]
		);
		echo $response->getBody();
	
	}
	//应付计划列表中的撤回
	public function withdrawPaya(Request $request)
	{
		$tCwFcFinancesubmitId = Input::get('tCwFcFinancesubmitId');
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/yf/updateWithdraw',[
					'query' => [
							'tCwFcFinancesubmitId'=>$tCwFcFinancesubmitId,
							]
		]
		);
		echo $response->getBody();
	
	}
	//实付管理列表
	public function actualPayment(Request $request)
	{
		$page = Input::get('page');
		$size = Input::get('size');
		$htno = Input::get('htno');
		$xm = Input::get('xm');
		$sdate = Input::get('sdate');
		$edate = Input::get('edate');
		$zhifustate = Input::get('zhifustate');
		$duizhangstate = Input::get('duizhangstate');
		$user = Auth::user();
		$phone=$user->phone;
		$client = new Client ([
				'base_uri' => $this->base_url,
		]);
		$response = $client->request('GET', '/api/cw/yf/paymentList',[
					'query' => [
							'page'=>$page,
							'size'=>$size,
							'htno'=>$htno,
							'xm'=>$xm,
							'sdate'=>$sdate,
							'edate'=>$edate,
							'zhifustate'=>$zhifustate,
							'duizhangstate'=>$duizhangstate,
							'phone'=>$phone,
							]
		]
		);
		echo $response->getBody();
	
	}

}
