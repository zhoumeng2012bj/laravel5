<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function (){
    return view('home');
});
Route::get('/yezhuweituo',function(){
   return view('yezhuweituo');
});
Route::resource('test', 'TestController');
Route::get('hello','TestController@index');
Route::resource('role', 'Rbac\RoleController');
//收房合同分组
Route::group(['prefix' => 'purchaseContract'], function () {
    Route::post('addCopyImage','Contract\purchaseContractController@addCopyImage');
    Route::post('review','Contract\purchaseContractController@review');
    Route::post('weiYueInfo','Contract\purchaseContractController@weiYueInfo');
    Route::post('weiYueSave','Contract\purchaseContractController@weiYueSave');
    Route::post('isCopyComplete','Contract\purchaseContractController@isCopyComplete');
    Route::get('optimize','Contract\purchaseContractController@getOptimize');
		Route::post('opssplan','Contract\purchaseContractController@opssplanadd');//新增优化方案
		Route::get('viewoptimi','Contract\purchaseContractController@viewoptimi');
		Route::post('formfollow','Contract\purchaseContractController@formfollow');//新增优化方案
		Route::get('vieformfollow','Contract\purchaseContractController@vieformfollow');//新增优化方案
		Route::get('violate','Contract\purchaseContractController@violate');//新增优化方案
		Route::post('addviolate','Contract\purchaseContractController@addviolate');//新增优化方案
    Route::post('editTiaoKuan','Contract\purchaseContractController@editTiaoKuan');
    Route::get('approving','Contract\purchaseContractController@changeStatus');
    Route::get('preApproving','Contract\purchaseContractController@changeStatus');//初审合同-审核前
    Route::get('confirm','Contract\purchaseContractController@changeStatus');
    Route::get('confirming','Contract\purchaseContractController@changeStatus');
    Route::get('violating','Contract\purchaseContractController@changeStatus');
    Route::get('terminated','Contract\purchaseContractController@changeStatus');
    Route::get('releasing','Contract\purchaseContractController@changeStatus');
    Route::get('released','Contract\purchaseContractController@released');
    Route::get('releasedList','Contract\purchaseContractController@releasedList');
    Route::get('copyImageList','Contract\purchaseContractController@copyImageList');
    Route::get('deleteCopyImage','Contract\purchaseContractController@deleteCopyImage');
    Route::get('getCopyComplete','Contract\purchaseContractController@getCopyComplete');
    Route::post('zhanghaoSave','Contract\purchaseContractController@zhanghaoSave');
    Route::get('getZhanghaoList','Contract\purchaseContractController@getZhanghaoList');
    Route::post('deleteZhanghao','Contract\purchaseContractController@deleteZhanghao');
    Route::post('zhanghaoAlter','Contract\purchaseContractController@zhanghaoAlter');
    Route::post('hedanSave','Contract\purchaseContractController@hedanSave');
    Route::get('getHedanList','Contract\purchaseContractController@getHedanList');
    Route::get('deleteHedan','Contract\purchaseContractController@deleteHedan');
    Route::get('summary','Contract\purchaseContractController@summary');
    Route::get('cancelled','Contract\purchaseContractController@changeStatus');
    Route::post('updataHedan','Contract\purchaseContractController@updataHedan');
    Route::post('saveyongyou','Contract\purchaseContractController@saveyongyou');
    Route::post('checkbianhao','Contract\purchaseContractController@checkbianhao');
    Route::post('changeZhanghao','Contract\purchaseContractController@changeZhanghao');
});
Route::resource('purchaseContract','Contract\purchaseContractController');
//装饰合同
Route::group(['prefix' => 'decoration'], function () {
    Route::get('index','Contract\decorationController@index');
    Route::get('show','Contract\decorationController@show');
    Route::post('submit','Contract\decorationController@submit');
    Route::post('store','Contract\decorationController@store');
    Route::get('status','Contract\decorationController@status');
});
//合同版本
Route::group(['prefix' => 'contractVersion'], function () {
    Route::get('index','Contract\contractVersionController@index');
    Route::get('status','Contract\contractVersionController@status');
});

//Route::get('purchaseContract/review','Contract\purchaseContractController@review');


Route::resource('chuFangCommission','Commission\ChuFangCommissionController');
Route::resource('shouFangCommission','Commission\ShouFangCommissionController');




Route::get('receivable/audit','Commission\examineController@auditlist');//审核列表(首期)
Route::get('receivable/modify','Commission\examineController@modify');//修改记录列表
Route::get('receivable/submi','Commission\examineController@submi');//提交记录列表
Route::post('receivable/addYXJ','Commission\examineController@addYXJ');
Route::post('receivable/bleSubmi','Commission\examineController@bleSubmi');//应收管理修提交记录列表中的审批提交
Route::post('receivable/bleedidSu','Commission\examineController@bleedidSu');//应收管理修改记录中的审批提交
Route::get('receivable/submi','Commission\examineController@submi');//提交记录列表
Route::get('receivable/planList','Commission\examineController@planList');//应收计划列表
Route::post('receivable/withdrawlist','Commission\examineController@withdrawlist');//应收计划列表中的撤回
Route::get('receivable/finance','Commission\examineController@finance');//实收管理列表
Route::get('receivable/claim','Commission\examineController@claim');//实收管理列表中的认领列表
Route::post('receivable/Claimsub','Commission\examineController@Claimsub');//实收管理列表中的认领
Route::get('receivable/ClaimRecoed','Commission\examineController@ClaimRecoed');//实收管理中的认领记录列表
Route::get('receivable/ClaimRdelt','Commission\examineController@ClaimRdelt');//实收管理中的认领记录列表中的取消认领
Route::post('receivable/fSave','Commission\examineController@fSave');//实收管理列表中的新增
Route::post('examine/addCopyImage','Commission\examineController@addCopyImage');//实收管理列表中的上传凭证
Route::get('receivable/ImageList','Commission\examineController@ImageList');//实收管理中的查看上传凭证图片
Route::get('receivable/ImageListDelete','Commission\examineController@ImageListDelete');//实收管理中的查看上传凭证图片的删除

Route::get('payable/settlement','Report\settlementController@settlement');//应付款管理列表(结算)
Route::get('payable/payment','Report\settlementController@payment');//应付中付款记录列表
Route::get('payable/deduction','Report\settlementController@deduction');//应付中付款记录列表中的扣款记录列表
Route::get('payable/submissionpayable','Report\settlementController@submissionpayable');//应付款管理列表(结算)中的提交付款
Route::post('payable/riskSubmi','Report\settlementController@riskSubmi');//应付款管理(风控)列表中的提交付款
Route::get('payable/amont','Report\settlementController@amont');//财务修改金额列表
Route::post('payable/subCareful','Report\settlementController@subCareful');//财务修改金额列表中的提交
Route::post('payable/commission','Report\settlementController@commission');//财务修改金额列表中的代付扣款
Route::get('payable/plan','Report\settlementController@plan');//应付计划列表
Route::get('payable/withdrawPaya','Report\settlementController@withdrawPaya');//应付计划列表中的撤回
Route::get('payable/actualPayment','Report\settlementController@actualPayment');//实付管理列表





Route::resource('receivable','Commission\receivableController');
Route::resource('financeReceivable','Report\financeReceivableController');
Route::resource('accountsReceivable','Commission\accountsReceivableController');
Route::resource('receivableRecord','Commission\receivableRecordController');
Route::post('receivable/saveShouKuan','Commission\receivableController@saveShouKuan');
Route::post('financeReceivable/saveShouKuan','Report\financeReceivableController@saveShouKuan');
Route::resource('payable','Report\payableController');
Route::resource('payableRecord','Report\payableRecordController');
Route::resource('paymentRecord','Report\paymentRecordController');
Route::post('shouFangCommission/finishSK','Commission\ShouFangCommissionController@finishSK');
Route::post('payable/saveFuKuan','Report\payableController@saveFuKuan');
Route::post('payable/editDate','Report\payableController@editDate');
Route::post('payable/editMoney','Report\payableController@editMoney');
Route::resource('financePayable','Report\financePayableController');
Route::post('financePayable/rejectPayable','Report\financePayableController@rejectPayable');
Route::post('financePayable/confirmPayable','Report\financePayableController@confirmPayable');
Route::post('receivable/editDate','Commission\receivableController@editDate');
Route::post('receivable/editMoney','Commission\receivableController@editMoney');
Route::post('receivableRecord/cancelClaim','Commission\receivableRecordController@cancelClaim');
Route::post('payable/finishPayable','Report\payableController@finishPayable');
Route::post('receivable/finishReceivable','Commission\receivableController@finishReceivable');
//消息列表
Route::resource('message','Message\messageController');

Route::post('message/acceptMessage','Message\messageController@acceptMessage');
Route::post('message/refuseMessage','Message\messageController@refuseMessage');
Route::post('chuFangCommission/finishFK','Commission\ChuFangCommissionController@finishFK');
//出房合同分组
Route::group(['prefix' => 'saleContract'], function () {
    Route::post('addCopyImage','Contract\saleContractController@addCopyImage');
    Route::get('getchuzuren','Contract\saleContractController@getchuzuren');
    Route::post('submit','Contract\saleContractController@submit');
    Route::get('jieyue','Contract\saleContractController@jieyue');
    Route::post('getzyrNameList','Contract\saleContractController@getzyrNameList');
    Route::get('approving','Contract\saleContractController@approving');
    Route::get('preApproving','Contract\saleContractController@preApproving');
    Route::get('confirm','Contract\saleContractController@confirm');
    Route::get('confirmed','Contract\saleContractController@confirmed');
    Route::get('violating','Contract\saleContractController@violating');
    Route::get('terminated','Contract\saleContractController@terminated');
    Route::post('buchongsave','Contract\saleContractController@buchongsave');
    Route::get('optimize','Contract\saleContractController@optimize');
    Route::get('buchongtijiao','Contract\saleContractController@getbuchongtijiao');
    Route::get('TwiceReleasing','Contract\saleContractController@TwiceReleasing');
    Route::get('TwiceReleased','Contract\saleContractController@TwiceReleased');
    Route::get('releasedList','Contract\saleContractController@releasedList');
    Route::get('releasing','Contract\saleContractController@releasing');
    Route::get('released','Contract\saleContractController@released');
    Route::post('review','Contract\saleContractController@review');
    Route::post('jieyuesave','Contract\saleContractController@jieyuesave');
    Route::get('jieyuelist','Contract\saleContractController@jieyuelist');
    Route::post('weiYueInfo','Contract\saleContractController@weiYueInfo');
    Route::post('weiYueSave','Contract\saleContractController@weiYueSave');
    Route::get('copyImageList','Contract\saleContractController@copyImageList');
    Route::get('deleteCopyImage','Contract\saleContractController@deleteCopyImage');
    Route::post('isCopyComplete','Contract\saleContractController@isCopyComplete');
    Route::get('getCopyComplete','Contract\saleContractController@getCopyComplete');
    Route::post('hedanSave','Contract\saleContractController@hedanSave');
    Route::get('getHedanList','Contract\saleContractController@getHedanList');
    Route::get('deleteHedan','Contract\saleContractController@deleteHedan');
    Route::get('cancelled','Contract\saleContractController@cancelled');
    Route::post('updataHedan','Contract\saleContractController@updataHedan');
    Route::post('getHedanqiannameList','Contract\saleContractController@getHedanqiannameList');
    Route::post('getHedanqiannamephoneList','Contract\saleContractController@getHedanqiannamephoneList');
    Route::post('getHedanbumenList','Contract\saleContractController@getHedanbumenList');
    Route::post('saveyongyou','Contract\saleContractController@saveyongyou');
    Route::post('omcdata','Contract\saleContractController@omcdata');
    Route::post('checkbianhao','Contract\saleContractController@checkbianhao');
});
Route::resource('saleContract','Contract\saleContractController');//这要放到confirm方法的后面，因为放到confirm的前面会把confirm的这个路径和它的这个路由混要了
//权限
Route::group(['prefix' => 'permission'], function () {
    Route::get('getAll','Rbac\PermissionController@getAll');
    Route::get('role/{id}','Rbac\PermissionController@getPermission');
    Route::get('update','Rbac\PermissionController@update');
	Route::get('pressmoney','Commission\accountsReceivableController@pressmoney');//催款跟进列表
    Route::get('list','Rbac\PermissionController@getPermissionList');
	Route::post('addpressmoney','Commission\accountsReceivableController@addpressmoney');//催款跟进列表
    Route::post('update/{id}','Rbac\PermissionController@update');
});
//消息
Route::group(['prefix' => 'message'], function () {
    Route::post('acceptMessage','Message\messageController@acceptMessage');
    Route::post('refuseMessage','Message\messageController@refuseMessage');
});
//渠道公司
Route::group(['prefix' => 'brokerCompany'], function () {
    Route::get('ExportExcel','BrokerCompany\brokerCompanyController@ExportExcel');
    Route::post('checkbkNameList','BrokerCompany\brokerCompanyController@checkbkNameList');
    Route::post('getGSSXDicList','BrokerCompany\brokerCompanyController@getGSSXDicList');
    Route::post('getFWDXDicList','BrokerCompany\brokerCompanyController@getFWDXDicList');
    Route::post('getYWQYDicList','BrokerCompany\brokerCompanyController@getYWQYDicList');
    Route::post('getXZQYDicList','BrokerCompany\brokerCompanyController@getXZQYDicList');
    Route::post('getJDDicList','BrokerCompany\brokerCompanyController@getJDDicList');
    Route::post('changeBrokerCompanyStatus','BrokerCompany\brokerCompanyController@changeBrokerCompanyStatus');
    Route::post('getXYDJDicList','BrokerCompany\brokerCompanyController@getXYDJDicList');
    Route::post('getYSLXRDicList','BrokerCompany\brokerCompanyController@getYSLXRDicList');

});
//渠道公司人员
Route::group(['prefix' => 'brokerCompanyUser'], function () {
    Route::post('getbkNameList','BrokerCompany\brokerCompanyUserController@getbkNameList');
    Route::post('changeBrokerCompanyUserStatus','BrokerCompany\brokerCompanyUserController@changeBrokerCompanyUserStatus');
    Route::post('getQDDJDicList','BrokerCompany\brokerCompanyUserController@getQDDJDicList');
    Route::post('checkPhone','BrokerCompany\brokerCompanyUserController@checkPhone');
    Route::post('checkName','BrokerCompany\brokerCompanyUserController@checkName');
    Route::get('ExportExcel','BrokerCompany\brokerCompanyUserController@ExportExcel');
});
//自由经纪人
Route::group(['prefix' => 'brokerUser'], function () {
    Route::post('changeBrokerUserStatus','BrokerCompany\brokerUserController@changeBrokerCompanyUserStatus');
    Route::get('ExportExcel','BrokerCompany\brokerUserController@ExportExcel');
});
Route::resource('brokerUser','BrokerCompany\brokerUserController');
Route::resource('brokerCompanyHistory','BrokerCompany\brokerCompanyHistoryController');
Route::resource('brokerCompanyUserHistory','BrokerCompany\brokerCompanyUserHistoryController');
Route::resource('brokerUserHistory','BrokerCompany\brokerUserHistoryController');

Route::post('Commission/contractPayType','Commission\CommissionController@selectCommissionPayType');

Route::post('logout','Auth\LoginController@logout');
Route::post('login','Auth\LoginController@index');

//用户
Route::group(['prefix' => 'user'], function () {
    Route::get('list','UserController@getlist');
    Route::post('delete','UserController@delete');
    Route::post('store','UserController@addUser');
    Route::post('edit','UserController@editUser');
    Route::post('role/{id}','UserController@setRole');
    Route::post('batchRemoveUser','UserController@batchRemoveUser');
    Route::post('setPassword','UserController@setPassword');
    Route::post('checkPassword','UserController@checkPassword');
    Route::post('setPass','UserController@setPass');
});
Route::get('shoufangReport/ExportExcel','Report\ShouFangReportController@ExportExcel');
Route::get('chufangReport/ExportExcel','Report\ChuFangReportController@ExportExcel');
Route::get('chanpinReport/ExportExcel','Report\chanPinHeYueReportController@ExportExcel');
Route::get('fangyuanXKReport/ExportExcel','Report\fangYuanXKReportController@ExportExcel');
Route::get('jinggengReport/ExportExcel','Report\jinggengReportController@ExportExcel');
Route::get('projectReport/ExportExcel','Report\projectReportController@ExportExcel');
Route::get('projectSaleReport/ExportExcel','Report\projectSaleReportController@ExportExcel');
Route::get('coreDataReport/ExportExcel','Report\coreDataReportController@ExportExcel');
Route::get('daikanReport/ExportExcel','Report\daikanReportController@ExportExcel');
Route::get('chanpinReport/omcindex','Report\chanPinHeYueReportController@omcindex');
//合同房源
Route::group(['prefix' => 'office'], function () {
    Route::get('loupanList','Contract\officeController@loupanList');
    Route::get('loudongList','Contract\officeController@loudongList');
    Route::get('fanghaoList','Contract\officeController@fanghaoList');
    Route::get('salefanghaoList','Contract\officeController@salefanghaoList');
    Route::get('createFanghao','Contract\officeController@createFanghao');
    Route::get('shengyuechengzu','Contract\officeController@shengyuechengzu');
    Route::get('loudongRules','Contract\officeController@loudongRules');
    Route::get('getAppUserList','Contract\officeController@getAppUserList');
    Route::post('appUserSave','Contract\officeController@appUserSave');
    Route::post('appUserAlter','Contract\officeController@appUserAlter');
    Route::post('deleteAppUser','Contract\officeController@deleteAppUser');
    Route::get('getUserListByPhone','Contract\officeController@getUserListByPhone');
});
Route::resource('payOrder','Report\payOrderController');
Route::resource('brokerCompany','BrokerCompany\brokerCompanyController');
Route::resource('brokerCompanyUser','BrokerCompany\brokerCompanyUserController');
Route::resource('shoufangReport', 'Report\ShouFangReportController');
Route::resource('chufangReport', 'Report\ChuFangReportController');
Route::resource('chanpinReport', 'Report\chanPinHeYueReportController');
Route::resource('fangyuanXKReport', 'Report\fangYuanXKReportController');
Route::resource('jinggengReport', 'Report\jinggengReportController');
Route::resource('projectReport', 'Report\projectReportController');
Route::resource('projectSaleReport', 'Report\projectSaleReportController');

//message
Route::post('/sendMessage','Api\MessageController@create');
Route::get('/readMessage/{id}','Api\MessageController@read');
Route::get('/receiveMessage/{send_to_id}/sys/{sys}','Api\MessageController@index');

Route::resource('coreDataReport', 'Report\coreDataReportController');
Route::resource('daikanReport', 'Report\daikanReportController');

//owner
Route::group(['prefix' => 'api/v1','middleware' => 'throttle:60,1'],function (){
    Route::post('/owner','Api\OwnerController@create');
    Route::post('/owner/login','Api\OwnerController@login');
    Route::post('/owner/resetPassword','Api\OwnerController@resetPassword')->middleware(\App\Http\Middleware\CheckAccessToken::class);;
    Route::post('/owner/editPassword','Api\OwnerController@editPassword')->middleware(\App\Http\Middleware\CheckAccessToken::class);
    Route::get('/owner/sendVerificationCode/{phone}','Api\OwnerController@sendVerificationCode');
    Route::get('/owner/{id}','Api\OwnerController@index')->middleware(\App\Http\Middleware\CheckAccessToken::class);
    Route::post('/owner/verifyCode','Api\OwnerController@verifyCode');
    Route::post('/owner/createOwner','Api\OwnerController@createOwner')->middleware(\App\Http\Middleware\CheckAccessToken::class);
    Route::get('/owner/{id}/info','Api\OwnerController@yzfyInfo');
});


Route::resource('commossionAudit', 'Commission\commissionAuditController');
//佣金审批
Route::group(['prefix' => 'commossionAudit'], function () {
    Route::post('auditComm','Commission\commissionAuditController@auditComm');
    Route::post('payComm','Commission\commissionAuditController@payComm');
    Route::post('commBalance','Commission\commissionAuditController@commBalance');
    Route::get('approval/{id}','Commission\commissionAuditController@approval');
});

//hello world1

