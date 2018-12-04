<?php

namespace App\Http\Controllers\Commission;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class accountsReceivableController extends Controller
{
    /**
     * Display a listing of the resource.
     *显示应收款记录
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Input::get('id');

        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        $response = $client->request('GET',  '/api/cw/ys/'.$id.'/getalterlist',[

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
     *保存应收款记录
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->params);
        $obj=array_merge($request->params );
        //dd($obj);
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/qd/compay/add', [
            'json' => $obj
        ]);
        return  $r ->getBody();

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

        $obj=array_merge($request->params,Array('tQdCompayId'=>$id));
        //dd($obj);
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/qd/compay/alter', [
            'json' => $obj
        ]);
        return  $r ->getBody();
    }

    /**
     * Remove the specified resource from storage.
     *删除应收款记录
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       dd($id);

        return $this->deleteCompany($id);
    }

    public  function deleteCompany($id)
    {

        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);
        $response = $client->request('POST', '/api/qd/compay/del',[
                'json' => [
                    'id'=>$id
                ]

            ]
        );
        return $response->getBody();
    }
		//催款跟进列表
		public function pressmoney(Request $request){
				$cwid = Input::get('cwid');
				$currentPage = Input::get('currentPage');
				$pageSize = Input::get('pageSize');
				$client = new Client([
						'base_uri' => $this->base_url,
				]);
				$response = $client->request('GET', '/api/follow/pressmoney/list?cwid='.$cwid.'&page='.$currentPage.'&size='.$pageSize, [
				]);
				
				echo $response->getBody();
		}
		public function addpressmoney(Request $request){
				$client = new Client([
						'base_uri' => $this->base_url,
				]);
				$user = Auth::user();
				$obj=array_merge($request->params,Array('createusername'=>$user->name,'createuser'=>$user->id));
				
				$response = $client->request('POST', '/api/follow/pressmoney/add', [
					'json' => $obj
				]);
				
				echo $response->getBody();
		}

}
