import axios from 'axios';
let base = '';
let java = 'http://121.196.195.129:8557';

export const requestLogin = params => { return axios.post(`${base}/login`, params).then(res => res.data); };
export const logout = params => { return axios.post(`${base}/logout`, params).then(res => res.data); };

export const getUserListPage = params => { return axios.get(`${base}/user/list`, { params: params }); };
export const removeUser = params => { return axios.post(`${base}/user/delete`, { params: params }); };
export const addUser = params => { return axios.post(`${base}/user/store`, { params: params }); };
export const editUser = params => { return axios.post(`${base}/user/edit`, { params: params }); };
export const batchRemoveUser = params => { return axios.post(`${base}/user/batchRemoveUser`, { params: params }); };

export const getRoleListPage = params => { return axios.get(`${base}/role`, { params: params }); };
export const removeRole = params => { return axios.delete(`${base}/role/`+params.id, {}); };
export const addRole = params => { return axios.post(`${base}/role`, { params: params }); };
export const editRole = params => { return axios.put(`${base}/role/`+params.id, { params: params }); };
export const batchRemoveRole = params => { return axios.post(`${base}/user/batchRemoveUser`, { params: params }); };
export const getTotalRoleList = params => { return axios.get(`${base}/role/create`, { params: params }); };
export const getRoleList = params => { return axios.get(`${base}/role/`+params.id, { params: params }); };
export const setRoleList = params => { return axios.post(`${base}/user/role/`+params.id, { params: params }); };

export const getBrokerCompanyListPage = params => { return axios.get(`${base}/brokerCompany`, { params: params }); };
export const removeBrokerCompany = params => { return axios.delete(`${base}/brokerCompany/`+params.id, {}); };
export const addBrokerCompany = params => { return axios.post(`${base}/brokerCompany`, { params: params }); };
export const editBrokerCompany = params => { return axios.put(`${base}/brokerCompany/`+params.tQdCompayId, { params: params }); };

export const getBrokerCompanyUserListPage = params => { return axios.get(`${base}/brokerCompanyUser`, { params: params }); };
export const removeBrokerCompanyUser = params => { return axios.delete(`${base}/brokerCompanyUser/`+params.id, {}); };
export const addBrokerCompanyUser = params => { return axios.post(`${base}/brokerCompanyUser`, { params: params }); };
export const editBrokerCompanyUser = params => { return axios.put(`${base}/brokerCompanyUser/`+params.id, { params: params }); };
export const batchRemoveBrokerCompanyUser = params => { return axios.post(`${base}/brokerCompanyUser/batchRemoveBKUser`, { params: params }); };
export const getbkNameList = params =>{ return axios.post(`${base}/brokerCompanyUser/getbkNameList`, { params: params }); };
export const checkbkNameList = params =>{ return axios.post(`${base}/brokerCompany/checkbkNameList`, { params: params }); };

export const getChuFangCommissionListPage = params => { return axios.get(`${base}/chuFangCommission`, { params: params }); };
export const editChuFangCommission = params => { return axios.put(`${base}/chuFangCommission/`+params.id, { params: params }); };
export const editPayable = params => { return axios.put(`${base}/payable/`+params.id, { params: params }); };
export const addPayable = params => { return axios.post(`${base}/payable`, { params: params }); };
export const getShouFangCommissionListPage = params => { return axios.get(`${base}/shouFangCommission`, { params: params }); };
export const editShouFangCommission = params => { return axios.put(`${base}/shouFangCommission/`+params.id, { params: params }); };
export const selectCommissionPayType = params => { return axios.post(`${base}/Commission/contractPayType`, { params: params }); };
export const cancelClaim = params => { return axios.post(`${base}/receivableRecord/cancelClaim`, { params: params }); };
export const getReceivableListPage = params => { return axios.get(`${base}/receivable`, { params: params }); };
export const getReceiveList = params => { return axios.get(`${base}/financeReceivable`, { params: params }); };//财务实收款列表
export const financeSaveShouKuan = params => { return axios.post(`${base}/financeReceivable/saveShouKuan` , { params: params }); };
export const renling = params => { return axios.post(`${base}/financeReceivable` , { params: params }); };
export const editReceivable = params => { return axios.put(`${base}/receivable/`+params.id, { params: params }); };
export const addYXJReceivable = params => { return axios.post(`${base}/receivable`, { params: params }); };
export const saveShouKuan = params => { return axios.post(`${base}/receivable/saveShouKuan` , { params: params }); };
export const rejectPayable = params =>  { return axios.post(`${base}/financePayable/rejectPayable` , { params: params }); };
export const getReceivableRecordListPage = params => { return axios.get(`${base}/receivableRecord`, { params: params }); };
export const confirmPayable = params =>  { return axios.post(`${base}/financePayable/confirmPayable` , { params: params }); };
export const getAccountsReceivableListPage = params => { return axios.get(`${base}/accountsReceivable`, { params: params }); };
export const addReceivable = params => { return axios.post(`${base}/accountsReceivable`, { params: params }); };
export const removeReceivable = params => { return axios.delete(`${base}/accountsReceivable/`+params.id, {}); };
export const editMoney = params => { return axios.post(`${base}/payable/editMoney`, { params: params }); };
export const editDate = params => { return axios.post(`${base}/payable/editDate`, { params: params }); };
export const getPayableListPage = params => { return axios.get(`${base}/payable`, { params: params }); };
export const saveFuKuan = params => { return axios.post(`${base}/payable/saveFuKuan` , { params: params }); };
export const finishSK= params => { return axios.post(`${base}/shouFangCommission/finishSK` , { params: params }); };
export const finishFK= params => { return axios.post(`${base}/chuFangCommission/finishFK` , { params: params }); };
export const getPayableInfos = params => { return axios.get(`${base}/paymentRecord`, { params: params }); };
export const getPayableRecordList = params => { return axios.get(`${base}/payableRecord`, { params: params }); };
export const getMessageListPage = params => { return axios.get(`${base}/message`, { params: params }); };
export const AcceptMessage= params => { return axios.post(`${base}/message/acceptMessage` , { params: params }); };
export const RefuseMessage= params => { return axios.post(`${base}/message/refuseMessage` , { params: params }); };
export const getFinancePayableListPage = params => { return axios.get(`${base}/financePayable`, { params: params }); };
export const skeditMoney = params => { return axios.post(`${base}/receivable/editMoney`, { params: params }); };
export const skeditDate = params => { return axios.post(`${base}/receivable/editDate`, { params: params }); };
//查系统所有的权限
export const getPermissionListPage = params => { return axios.get(`${base}/permission/getAll`, { params: params }); };
//查XX的权限
export const getPermissionListOfRole = params => { return axios.get(`${base}/permission/role/`+params.id, {}); };
//查寻当前用户的权限
export const getPermissionList = params => { return axios.get(`${base}/permission/list`, { params: params }); };
export const setPermissionList = params => { return axios.post(`${base}/permission/update/`+params.id, { params: params }); };

//合同信息
export const addPurchaseContractInfo= params => { return axios.post(`${base}/purchaseContract`, { params: params }); };
export const reviewPurchaseContract= params => { return axios.post(`${base}/purchaseContract/review`, { params: params }); };
export const getPurchaseContractList = params => { return axios.get(`${base}/purchaseContract`, { params: params }); };
export const getPurchaseContractInfo = params => { return axios.get(`${base}/purchaseContract/`+params.id, {}); };
export const submitPurchaseContract = params => { return axios.get(`${base}/purchaseContract/`+params.id+'/edit', {params}); };
export const getPurchaseContractTiaoKuan = params => { return axios.get(`${base}/purchaseContract/create`, {}); };
export const editPurchaseContractTiaoKuan= params => { return axios.post(`${base}/purchaseContract/editTiaoKuan`, { params: params }); };
export const confirmPurchaseContract= params => { return axios.get(`${base}/purchaseContract/confirm`, {params:params}); };
export const optimizePurchaseContract= params => { return axios.put(`${base}/purchaseContract/`+params.id, {params:params}); };//二次优化
export const getOptimizePurchaseContract= params => { return axios.get(`${base}/purchaseContract/optimize`, {params:params}); };//二次优化
export const approvingPurchaseContract= params => { return axios.get(`${base}/purchaseContract/approving`, {params:params}); };//状态变更为：审核中
export const dumpingPurchaseContract= params => { return axios.get(`${base}/purchaseContract/confirming`, {params:params}); };//状态变更为：待确认
export const weiyuePurchaseContract= params => { return axios.get(`${base}/purchaseContract/violating`, {params:params}); };//状态变更为：违约中
export const endPurchaseContract= params => { return axios.get(`${base}/purchaseContract/terminated`, {params:params}); };//状态变更为：合同终止
export const youhuaPurchaseContract= params => { return axios.get(`${base}/purchaseContract/releasing`, {params:params}); };//状态变更为：优化中
export const youhuaPurchaseContractList= params => { return axios.get(`${base}/purchaseContract/releasedList`, {params:params}); };//状态变更为：优化中
export const youhuacgPurchaseContract= params => { return axios.get(`${base}/purchaseContract/released`, {params:params}); };//状态变更为：优化完成
export const weiYueInfoPurchaseContract= params => { return axios.post(`${base}/purchaseContract/weiYueInfo`, {params:params}); };//合同终止
export const weiYueSavePurchaseContract= params => { return axios.post(`${base}/purchaseContract/weiYueSave`, {params:params}); };//合同终止保存一些数据
export const copyImageList= params => { return axios.get(`${base}/purchaseContract/copyImageList`, {params:params}); };//扫描合同复印件列表
export const copyImageDelete= params => { return axios.get(`${base}/purchaseContract/deleteCopyImage`, {params:params}); };//删除扫描建
export const isCopyComplete= params => { return axios.post(`${base}/purchaseContract/isCopyComplete`, {params:params}); };//资料是否完整
export const isCopyCompleteList= params => { return axios.get(`${base}/purchaseContract/getCopyComplete`, {params:params}); };//列表
export const zhanghaoSavePurchaseContract= params => { return axios.post(`${base}/purchaseContract/zhanghaoSave`, {params:params}); };//保存付款账号的数据
export const getZhanghaoPurchaseContractList= params => { return axios.get(`${base}/purchaseContract/getZhanghaoList`, {params:params}); };//获取账号数据的列表
export const removeZhanghaoPurchaseContract= params => { return axios.get(`${base}/purchaseContract/deleteZhanghao`, {params:params}); };//删除扫描建
export const zhanghaoUpdataPurchaseContract= params => { return axios.post(`${base}/purchaseContract/zhanghaoAlter`, {params:params}); };//修改付款账号的数据
export const getHedanPurchaseContractList= params => { return axios.get(`${base}/purchaseContract/getHedanList`, {params:params}); };//获取账号数据的列表
export const hedanSavePurchaseContract= params => { return axios.post(`${base}/purchaseContract/hedanSave`, {params:params}); };//保存付款账号的数据
export const removeHedanPurchaseContract= params => { return axios.get(`${base}/purchaseContract/deleteHedan`, {params:params}); };//删除扫描建
export const hedanUpdataPurchaseContract= params => { return axios.post(`${base}/purchaseContract/updataHedan`, {params:params}); };//删除扫描建
export const yongyouUpdataPurchaseContract = params => { return axios.post(`${base}/purchaseContract/saveyongyou`, { params: params }); };//用友编号的保存

//出房合同
//合同信息
export const addSaleContractInfo= params => { return axios.post(`${base}/saleContract`, { params: params }); };
export const reviewSaleContract= params => { return axios.post(`${base}/saleContract/review`, { params: params }); };
export const getSaleContractList = params => { return axios.get(`${base}/saleContract`, { params: params }); };
export const getSaleContractInfo = params => { return axios.get(`${base}/saleContract/`+params.id, {}); };//获取合同详情
export const getNameSaleList = params =>{ return axios.post(`${base}/saleContract/getzyrNameList`, { params: params }); };//获取渠道自由经纪人
export const getjieyueSaleContractInfo = params => { return axios.get(`${base}/saleContract/jieyuelist`, {params:params}); };//获取解约协议列表
export const getdayinSaleContractInfo = params => { return axios.get(`${base}/saleContract/`+params.id, {}); };
export const getContractChuzuren= params => { return axios.get(`${base}/saleContract/getchuzuren`, {params:params}); };//出房合同获取收房合同的楼盘
export const submitSaleContract = params => {return axios.get(`${base}/saleContract/`+params.id+'/edit', {}); };
export const getJieyueSaleContract = params => { return axios.get(`${base}/saleContract/jieyue`, { params: params }); };
/*export const dumpingSaleContract = params => {return axios.get(`${base}/saleContract/`+params.id+'/edit', {}); };//状态变更为：待确认*/
export const approvingSaleContract= params => { return axios.get(`${base}/saleContract/approving`, {params:params}); };//状态变更为：审核中
export const confirmSaleContract= params => { return axios.get(`${base}/saleContract/confirm`, {params:params}); };//状态变更为：待确认
export const confirmedSaleContract= params => { return axios.get(`${base}/saleContract/confirmed`, {params:params}); };//状态变更为：

export const weiyueSaleContract= params => { return axios.get(`${base}/saleContract/violating`, {params:params}); };//状态变更为：违约中
export const endSaleContract= params => { return axios.get(`${base}/saleContract/terminated`, {params:params}); };//状态变更为：合同终止
export const buchongSaleContract= params => { return axios.get(`${base}/saleContract/TwiceReleasing`, {params:params}); };//状态变更为：补充协议中
export const buchongsbSaleContract= params => { return axios.get(`${base}/saleContract/TwiceReleased`, {params:params}); };//状态变更为：补充协议完成
export const buchongSaleContractList= params => { return axios.get(`${base}/saleContract/releasedList`, {params:params}); };//获取补充协议列表
export const jieyueSaleContract = params => { return axios.get(`${base}/saleContract/releasing`, { params: params }); };//状态变更为：解约中
export const jieyuewanSaleContract = params => { return axios.get(`${base}/saleContract/released`, { params: params }); };//状态变更为：解约完成
export const jieyueSaleContractInfo = params => { return axios.post(`${base}/saleContract/submit`, {params:params}); };
export const addSaleJieyueContractInfo = params => { return axios.post(`${base}/saleContract/jieyuesave`, { params: params });};
export const removeSaleContract = params => { return axios.post(`${base}/saleContract/delete`, { params: params }); };
export const weiYueInfoSaleContract= params => { return axios.post(`${base}/saleContract/weiYueInfo`, {params:params}); };//合同终止
export const weiYueSaveSaleContract= params => { return axios.post(`${base}/saleContract/weiYueSave`, {params:params}); };//合同终止保存一些数据
export const copySaleImageList= params => { return axios.get(`${base}/saleContract/copyImageList`, {params:params}); };//扫描合同复印件列表
export const copySaleImageDelete= params => { return axios.get(`${base}/saleContract/deleteCopyImage`, {params:params}); };//删除复印件
export const isCopySaleComplete= params => { return axios.post(`${base}/saleContract/isCopyComplete`, {params:params}); };
export const isCopySaleCompleteList= params => { return axios.get(`${base}/saleContract/getCopyComplete`, {params:params}); };//列表
export const getHedanSaleContractList= params => { return axios.get(`${base}/saleContract/getHedanList`, {params:params}); };//获取账号数据的列表
export const hedanSaveSaleContract= params => { return axios.post(`${base}/saleContract/hedanSave`, {params:params}); };//保存付款账号的数据
export const removeHedanSaleContract= params => { return axios.get(`${base}/saleContract/deleteHedan`, {params:params}); };//删除扫描建
export const hedanUpdataSaleContract= params => { return axios.post(`${base}/saleContract/updataHedan`, {params:params}); };//修改合单信息
export const getHedanqiannameSaleList = params => { return axios.post(`${base}/saleContract/getHedanqiannameList`, { params: params }); };//获取签单人姓名列表
export const getHedanbumenSaleList = params => { return axios.post(`${base}/saleContract/getHedanbumenList`, { params: params }); };//
export const yongyouUpdataSaleContract = params => { return axios.post(`${base}/saleContract/saveyongyou`, { params: params }); };//用友编号的保存

//获取楼盘，楼栋，房间号
export const getLoupanList = params =>{ return axios.get(`${base}/office/loupanList`, { params: params }); };
export const getLoudongList = params =>{ return axios.get(`${base}/office/loudongList`, { params: params }); };
export const getFanghaoList = params =>{ return axios.get(`${base}/office/fanghaoList`, { params: params }); };
export const getSaleFanghaoList = params =>{ return axios.get(`${base}/office/salefanghaoList`, { params: params }); };
export const createFanghao = params =>{ return axios.get(`${base}/office/createFanghao`, { params: params }); };
export const getSaleFanghaoChengzu = params =>{ return axios.get(`${base}/office/shengyuechengzu`, { params: params }); };

//工程合同
export const getDecorationList = params =>{ return axios.get(`${base}/decoration/index`, { params: params }); };
export const getDecorationDetail = params =>{ return axios.get(`${base}/decoration/show`, { params: params }); };
export const submitDecoration = params =>{ return axios.post(`${base}/decoration/submit`, { params: params }); };
export const storeDecoration = params =>{ return axios.post(`${base}/decoration/store`, { params: params }); };
export const statusDecoration = params =>{ return axios.get(`${base}/decoration/status`, { params: params }); };

//渠道跟进
export const getBrokerCompanyUserHistoryListPage = params => { return axios.get(`${base}/brokerCompanyUserHistory`, { params: params }); };

export const getBrokerCompanyHistoryListPage = params => { return axios.get(`${base}/brokerCompanyHistory`, { params: params }); };
export const addBrokerCompanyHistory = params => { return axios.post(`${base}/brokerCompanyHistory`, { params: params }); };
export const getGSSXDicList = params => { return axios.post(`${base}/brokerCompany/getGSSXDicList`, { params: params }); };
export const getFWDXDicList = params => { return axios.post(`${base}/brokerCompany/getFWDXDicList`, { params: params }); };
export const getYWQYDicList = params => { return axios.post(`${base}/brokerCompany/getYWQYDicList`, { params: params }); };
export const getXZQYDicList = params => { return axios.post(`${base}/brokerCompany/getXZQYDicList`, { params: params }); };
export const getJDDicList = params => { return axios.post(`${base}/brokerCompany/getJDDicList`, { params: params }); };
export const changeBrokerCompanyStatus = params => { return axios.post(`${base}/brokerCompany/changeBrokerCompanyStatus`, { params: params }); };
export const changeBrokerCompanyUserStatus = params => { return axios.post(`${base}/brokerCompanyUser/changeBrokerCompanyUserStatus`, { params: params }); };
export const getBrokerUserListPage = params => { return axios.get(`${base}/brokerUser`, { params: params }); };
export const addBrokerUser = params => { return axios.post(`${base}/brokerUser`, { params: params }); };
export const editBrokerUser = params => { return axios.put(`${base}/brokerUser/`+params.id, { params: params }); };
export const getBrokerUserHistoryListPage = params => { return axios.get(`${base}/brokerUserHistory`, { params: params }); };
export const addBrokerUserHistory = params => { return axios.post(`${base}/brokerUserHistory`, { params: params }); };
export const changeBrokerUserStatus = params => { return axios.post(`${base}/brokerUser/changeBrokerUserStatus`, { params: params }); };
export const getXYDJDicList = params => { return axios.post(`${base}/brokerCompany/getXYDJDicList`, { params: params }); };
export const getYSLXRDicList = params => { return axios.post(`${base}/brokerCompany/getYSLXRDicList`, { params: params }); };
export const getBrokerCompanyInfo = params => { return axios.get(`${base}/brokerCompany/`+params.id, {}); };
export const getQDDJDicList = params => { return axios.post(`${base}/brokerCompanyUser/getQDDJDicList`, { params: params }); };
export const addBrokerCompanyUserHistory= params => { return axios.post(`${base}/brokerCompanyUserHistory`, { params: params }); };
export const getBrokerCompanyUserInfo = params => { return axios.get(`${base}/brokerCompanyUser/`+params.id, {}); };
export const getBrokerUserInfo = params => { return axios.get(`${base}/brokerUser/`+params.id, {}); };
export const checkPhone = params => { return axios.post(`${base}/brokerCompanyUser/checkPhone`, { params: params }); };
export const checkName = params => { return axios.post(`${base}/brokerCompanyUser/checkName`, { params: params }); };
//版本控制
export const getContractVersionList = params =>{ return axios.get(`${base}/contractVersion/index`, { params: params }); };
export const changeContractVersionStatus = params =>{ return axios.get(`${base}/contractVersion/status`, { params: params }); };

export const getPayOrder = params =>{ return axios.get(`${base}/payOrder`, { params: params }); };
