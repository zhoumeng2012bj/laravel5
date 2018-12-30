<?php

namespace App\Http\Controllers\Report;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Excel;
use Mockery\Exception;

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
        $phone = $user->phone;
        $response = $client->request('GET', '/api/cw/yf/settlementList', [
                'query' => [
                    'page' => $page,
                    'size' => $size,
                    'zt' => $zt,
                    'htno' => $htno,
                    'xm' => $xm,
                    'sdate' => $sdate,
                    'edate' => $edate,
                    'listState' => $listState,
                    'zt2' => $zt2,
                    'phone' => $phone,

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
        $response = $client->request('GET', '/api/cw/yf/' . $id . '/getSubmitRecords'
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
        $response = $client->request('GET', '/api/cw/yf/' . $id . '/getRecordDeduction'
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
        $response = $client->request('GET', '/api/cw/yf/updateSettlement', [
                'query' => [
                    'id' => $id,
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
        $user = Array(
            'faqirenid' => $u->id,
            'faqiren' => $u->name,
        );
        $obj = array_merge($request->params, $user);

        // dd( json_encode( $obj));
        $client = new Client ([
            'base_uri' => $this->base_url,

        ]);

        $r = $client->request('POST', '/api/cw/yf/riskSubmit2', [
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
        $phone = $user->phone;
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/yf/financeList', [
                'query' => [
                    'page' => $page,
                    'size' => $size,
                    'htno' => $htno,
                    'xm' => $xm,
                    'sdate' => $sdate,
                    'edate' => $edate,
                    'tijiaoState' => $tijiaoState,
                    'phone' => $phone,
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
        $user = Array(
            'faqirenid' => $u->id,
            'faqiren' => $u->name,
        );
        $obj = array_merge($request->params, $user);

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
        $user = Array(
            'faqirenid' => $u->id,
            'faqiren' => $u->name,
        );
        $obj = array_merge($request->params, $user);

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
        $phone = $user->phone;
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/yf/planList', [
                'query' => [
                    'page' => $page,
                    'size' => $size,
                    'htno' => $htno,
                    'xm' => $xm,
                    'sdate' => $sdate,
                    'edate' => $edate,
                    'fukuanstate' => $fukuanstate,
                    'phone' => $phone,
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
        $response = $client->request('GET', '/api/cw/yf/updateWithdraw', [
                'query' => [
                    'tCwFcFinancesubmitId' => $tCwFcFinancesubmitId,
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
        $phone = $user->phone;
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/yf/paymentList', [
                'query' => [
                    'page' => $page,
                    'size' => $size,
                    'htno' => $htno,
                    'xm' => $xm,
                    'sdate' => $sdate,
                    'edate' => $edate,
                    'zhifustate' => $zhifustate,
                    'duizhangstate' => $duizhangstate,
                    'phone' => $phone,
                ]
            ]
        );
        echo $response->getBody();

    }

    //应付计划列表
    public function planExportExcel()
    {
        $htno = Input::get('htno');
        $xm = Input::get('xm');
        $sdate = Input::get('sdate');
        $edate = Input::get('edate');
        $user = Auth::user();
        $phone = $user->phone;
        $client = new Client ([
            'base_uri' => $this->base_url,
        ]);
        $response = $client->request('GET', '/api/cw/yf/getDaochuPlan', [
                'query' => [
                    'htno' => $htno,
                    'xm' => $xm,
                    'sdate' => $sdate,
                    'edate' => $edate,
                    'phone' => $phone,
                ]
            ]
        );
        try {
            $bk = $response->getBody();
            $res = json_decode($bk);
            if ($res->success) {
                $cellData = $res->data->data;
                // dd($cellData);
                if (count($cellData) > 0) {

                    Excel::create($res->data->piciCode, function ($excel) use ($cellData) {
                        $excel->sheet('score', function ($sheet) use ($cellData) {
                            $sheet->getStyle('F')->getNumberFormat()->setFormatCode("0.00");
                            $sheet->rows($cellData);

                        });
                    })->store('xls')->export('xls');
                    echo "导出成功";
                } else {
                    echo "无符合条件的数据";
                }
            } else {
                echo $res->msg;
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }

    public function planImportExcel()
    {
        //PHP上传失败
        if (!empty($_FILES['file']['error'])) {
            switch ($_FILES['file']['error']) {
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择文件。';
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
        $array = explode('.', $_FILES["file"]["name"]);
        $filename = $array[0] . date("YmdHis");
        $filenametype = $filename . '.' . $array[1];
        $filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploadfiles' . DIRECTORY_SEPARATOR . $filenametype;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
            try {
                $reader = Excel::load($filePath);//要开始导入文件，可以使用->load($filename)。回调是可选的。
                $reader = $reader->getSheet(0);//得到Excel的第一页内容，如下图3
                $list = $reader->toArray();
                $user = Auth::user();
                $data = [
                    'phone' => $user->phone,
                    'ss' => $list,
                    'piciUrl' => $filename,
                ];
                $client = new Client([
                    'base_uri' => $this->base_url,
                ]);
                $response = $client->request('POST', '/api/cw/yf/saveOrder', [
                    'json' => $data
                ]);

                $bk = $response->getBody();
                $res = json_decode($bk);
                if ($res->success) {
                    $cellData = $res->data->data;
                    if (count($cellData) > 0) {

                        Excel::create($res->data->piciCode, function ($excel) use ($cellData) {
                            $excel->sheet('score', function ($sheet) use ($cellData) {
                                // $sheet->getStyle ('F')->getNumberFormat()->setFormatCode ("0.00");
                                $sheet->rows($cellData);

                            });
                        })->store('xls');
                        //$res['data'] = route('download', ['file' => $res->data->piciCode.'.xls']);
                        // return  '/payable/planImportErrorExcel/'.$res->data->piciCode.'.xls'  ;
                        return $data = ['code' => 300, 'data' => '/payable/planImportErrorExcel/' . $res->data->piciCode . '.xls'];
                    } else {
                        echo "导入成功，无失败记录";
                    }
                } else {
                    echo "导入失败";
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "导入失败";
        }
    }

    public function planImportErrorExcel($file_name)
    {
        //print_r(phpinfo());die;
        $file = '..' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'exports' . DIRECTORY_SEPARATOR . $file_name;
        return response()->download($file);
    }
}
