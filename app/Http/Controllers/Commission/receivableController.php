<?php

namespace App\Http\Controllers\Commission;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class receivableController extends Controller
{
    /**
     * Display a listing of the resource.
     *显示应收款列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
        $response = $client->request('GET', '/api/cw/ys/list',[
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
     *保存
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $obj= $request->params ;
        unset($obj['omcId'])  ;
        //dd( json_encode($obj));
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/cw/ys/yixiangadd', [
            'json' => $obj
        ]);
        return $r->getBody();

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
     *更新应收款记录
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $obj=$request->params;
        unset($obj['omcId'])  ;
        //dd( json_encode($obj));
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/cw/ys/yixiangalter', [
            'json' => $obj
        ]);
        return  $r ->getBody();
    }
    /**
     * Update the specified resource in storage.
     *保存收款记录
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveShouKuan(Request $request)
    {

        //以后用户会从OMC取
        $u = Auth::user();
        $user=    Array(
            'personid'=>$u->id,
            'persnoname'=>$u->name,
        );


        $obj=array_merge($request->params,$user);
				// $obj=$request->params;
        // dd( json_encode( $obj));
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/cw/ys/submit', [
            'json' => $obj
        ]);
        return $r->getBody();
    }
    public function editDate(Request $request)
    {
        //以后用户会从OMC取

        $u = Auth::user();
        $user=    Array(
            'personId'=>$u->id,
            'personName'=>$u->name,
        );
        $obj=array_merge($request->params,$user);
     //  dd(  json_encode($obj));
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/cw/ys/alter/skrq', [
            'json' => $obj
        ]);
        return $r->getBody();
    }

    public function editMoney(Request $request)
    {
        //以后用户会从OMC取

        $u = Auth::user();
        $user=    Array(
            'personId'=>$u->id,
            'personName'=>$u->name,
        );
        $obj=array_merge($request->params,$user);
         //dd($obj);
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/cw/ys/alter/skje', [
            'json' => $obj
        ]);
        return $r->getBody();
    }

    public function destroy($id)
    {
       // dd($id);


    }

    public function  finishReceivable(Request $request)
    {
        $obj= $request->params ;
        // dd($obj);
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        $response = $client->request('GET', '/api/cw/ys/'.$obj["id"].'/finish',[
        ]);
        return  $response ->getBody();
    }
		
}
