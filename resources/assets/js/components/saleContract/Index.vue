<template>
    <el-row >
        <el-form :inline="true" :model="filters" class="demo-form-inline">
            <el-form-item label="">
                <el-input v-model="filters.name" placeholder="请输入项目"></el-input>
            </el-form-item>
            <el-form-item label="">
                <el-select v-model="filters.status" placeholder="请选择合同状态">
                    <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-date-picker
                        v-model="filters.startDate"
                        type="date"
                        placeholder="开始时间"
                        @keyup.enter.native="saleContractList"
                >
                </el-date-picker>
            </el-form-item>
            <el-form-item>
                <el-date-picker
                        v-model="filters.endDate"
                        type="date"
                        placeholder="结束时间"
                        @keyup.enter.native="purchaseContractList"
                >
                </el-date-picker>
            </el-form-item>
            <el-form-item label="">
                <el-input v-model="filters.yongyouid" @keyup.enter.native="purchaseContractList" placeholder="请输入用友编号"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search" @click="saleContractList">搜索</el-button>
                <el-button v-if="fun('saleContractAdd')" type="primary" class="el-icon-plus" @click="addContract"> 新增</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="lists" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
            <el-table-column prop="bianhao" label="编号" width="200" >
            </el-table-column>
            <el-table-column prop="loupanName" label="楼盘"  >
            </el-table-column>
            <el-table-column prop="loudongName" label="楼栋"   >
            </el-table-column>
            <el-table-column prop="fanghao" label="房间号"   >
            </el-table-column>
            <el-table-column prop="zhuangtai" label="状态" :formatter="formatStatus"  >
            </el-table-column>
            <el-table-column prop="qianyuedate" label="签约日" :formatter="changeDate"  >
            </el-table-column>
            <el-table-column prop="updatetime" label="更新日期"  :formatter="changeUpdateTime"  >
            </el-table-column>
            <!--<el-table-column prop="yongyouid" label="用友编号" >
            </el-table-column>-->
            <el-table-column
                    label="用友编号"
                    width="200"
                    v-if="fun('setYongYou')"
            >
                <template slot-scope="scope">
                    <el-input v-model="scope.row.yongyouid" @blur="updatayongyouid(scope.$index, scope.row)"></el-input>
                </template>
            </el-table-column>
            <el-table-column
                    label="用友编号"
                    width="200"
                    prop="yongyouid"
                    v-if="!fun('setYongYou')"
            >
            </el-table-column>
            <el-table-column label="操作" width="170">
                <template slot-scope="scope">
                    <el-dropdown   menu-align="start">
                        <el-button type="primary" size="normal" splitButton="true">
                            操作<i class="el-icon-caret-bottom el-icon--right"></i>
                        </el-button>
                        <el-dropdown-menu slot="dropdown" >
                            <el-dropdown-item v-if="fun('saleContractIndex')" ><el-button @click="handlSee(scope.$index, scope.row)">查看合同</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[0,4,5,6,7,8,9,12,13,14,17])&&fun('saleContactUpdate')"><el-button @click="handleEdit(scope.$index, scope.row)">编辑合同</el-button></el-dropdown-item>
                            <el-dropdown-item v-if=" fun('saleContactMoreUpdate')"><el-button @click="handleEditMore(scope.$index, scope.row)">专用编辑</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[2,16])&&fun('saleContactAudit')"><el-button @click="handleReview(scope.$index, scope.row)">复&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审</el-button> </el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[1,15])&&fun('saleContactPreAudit')"><el-button @click="handlePreReview(scope.$index, scope.row)">初&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审</el-button> </el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[3])&&fun('saleContactDump')"><el-button @click="handleDump(scope.$index, scope.row)">打印合同</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[5])&&fun('saleContactConfirm')"><el-button @click="handleConfirm(scope.$index, scope.row)">签约成功</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[6,13])&&fun('saleContactWeiyue')"><el-button @click="handleWeiyue(scope.$index, scope.row)">违 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;约</el-button></el-dropdown-item>
					<el-dropdown-item v-if="ztin(scope.row,[6,7,8,9,10,11])&&fun('addOptimize')"><el-button  @click="handleViolated(scope.$index, scope.row)">违约跟进</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[6,13])&&fun('saleContactJieyue')"><el-button @click="handleJieyue(scope.$index, scope.row)">解 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;约</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[9])&&fun('saleContactJieyueFinish')"><el-button @click="handleJieyuewancheng(scope.$index, scope.row)">解约完成</el-button></el-dropdown-item>
                            <!--<el-dropdown-item v-if="ztin(scope.row,[10])"><el-button @click="handleCheckJieyue(scope.$index, scope.row)">查看协议</el-button></el-dropdown-item>-->
                            <el-dropdown-item v-if="ztin(scope.row,[7])&&fun('saleContactEnd')"><el-button @click="openEndDialog(scope.$index, scope.row)">合同终止</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[6,13])&&fun('addSaleOptimize')"><el-button @click="handleOptimize(scope.$index, scope.row)">优 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;化</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[12])&&fun('editSaleOptimize')"><el-button @click="editOptimize(scope.$index, scope.row)">修改补充协议</el-button></el-dropdown-item>
                            <!--<el-dropdown-item v-if="ztin(scope.row,[13])"><el-button @click="handleCheckOptimize(scope.$index, scope.row)">当前补充协议</el-button></el-dropdown-item>-->
                            <el-dropdown-item v-if="ztin(scope.row,[6,7,8,9,10,11,12,13])&&fun('saleContactZH')"><el-button @click="handleZhanghao(scope.$index, scope.row)">付款账号</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="fun('saleContactHD')"><el-button @click="handleHedan(scope.$index, scope.row)">合单管理</el-button></el-dropdown-item>
                            <!--<el-dropdown-item v-if="ztin(scope.row,[12,13])"><el-button @click="checkhistoryOptimize(scope.$index, scope.row)">历史补充协议</el-button></el-dropdown-item>-->
                            <el-dropdown-item v-if="ztin(scope.row,[6,7,8,9,10,11,12,13])&&fun('saleContactUpload')"><el-button @click="handleUplod(scope.$index, scope.row)">扫描件&nbsp;&nbsp;&nbsp;</el-button></el-dropdown-item>
                            <el-dropdown-item v-if="ztin(scope.row,[6,7,8,9,10,11,12,13])&&fun('saleContactSummary')"><el-button @click="handleSummary(scope.$index, scope.row)">打印核心数据</el-button></el-dropdown-item>
                            <el-dropdown-item  v-if="ztin(scope.row,[0,1,2,3,4,5])&&fun('saleContactZF')"><el-button @click="handleZuofei(scope.$index, scope.row)">合同作废</el-button></el-dropdown-item>
                            <el-dropdown-item  v-if="ztin(scope.row,[6,7,8,9,10,11,12,13,15,16,17])&&fun('saleContactJGD')"><el-button @click="handleJiaogedan(scope.$index, scope.row)">交割单&nbsp;&nbsp;&nbsp;</el-button></el-dropdown-item>
                            <el-dropdown-item  v-if="ztin(scope.row,[6,7,8,9,10,11,12,13])&&fun('saleContactAppZH')"><el-button @click="handleAppZhanghao(scope.$index, scope.row)">用户账号管理</el-button></el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </template>
            </el-table-column>
        </el-table>
        <div style="margin-top:30px"></div>
        <!-- 分页-->
        <el-col :span="24" class="toolbar" >
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="currentPage"
                    :page-sizes="pageSizes"
                    :page-size="pageSize"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total=total
                    style="float:right"
            >
            </el-pagination>
        </el-col>
        <el-dialog title="违约" v-model="weiYue.Visible" :close-on-click-modal="false">
            <el-form  label-width="120px"  ref="sureForm" :rules="weiYueRules" :model="weiYue">
                <el-input type="hidden" prop="tHetongId"  v-model="weiYue.tHetongId" auto-complete="off"></el-input>
                <el-form-item label="合同编号：" prop="tHetongBianhao">
                    {{weiYue.tHetongBianhao}}
                </el-form-item>
                <el-form-item label="违约类型：" prop="weiyueleixing" >
                    <el-radio-group v-model="weiYue.weiyueleixing">
                        <el-radio class="radio" label="1" >租户违约</el-radio>
                        <el-radio class="radio" label="2" >幼狮违约</el-radio>
                        <el-radio class="radio" label="3" >不可抗拒</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="终止时间：" prop="zhongzhidate">
                    <el-date-picker type = "date" placeholder="结束时间"   v-model="weiYue.zhongzhidate" @change="changeEnd">
                    </el-date-picker>
                </el-form-item>
                <el-row>
                    <el-col :span="14">
                        <el-form-item label="应收金额：" prop="yingshoujine">
                            <el-input v-model="weiYue.yingshoujine"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="14">
                        <el-form-item label="应付金额：" prop="yingfujine">
                            <el-input v-model="weiYue.yingfujine"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="weiYue.Visible = false">取消</el-button>
                <el-button type="primary" @click.native="handleEnd" :loading="weiYue.Loading">提交</el-button>
            </div>
        </el-dialog>
    </el-row>
</template>


<script>
    import Viewer from 'viewerjs';
    import '../../../../../node_modules/viewerjs/dist/viewer.min.css';
    import {
        yongyouUpdataSaleContract,
        getSaleContractList,
        removeSaleContract,
        confirmSaleContract,
        confirmedSaleContract,
        weiyueSaleContract,
        endSaleContract,
        jieyueSaleContract,
        weiYueSaveSaleContract,
        weiYueInfoSaleContract,
        approvingSaleContract,
        approvingPreSaleContract,
        getSaleContractInfo,
        getSaleContractOmc,
        buchongSaleContract,
        saleContractCancelled,
    } from '../../api/api.js';
    export default {
        data() {
            return {
                filters: {
                    name: '',
                    region: '',
                    status:'',
                    startDate:null,
                    endDate:null,
                    yongyouid: '',
                },
                weiYueRules:{
                    weiyueleixing: [
                        { required: true, message: '不能为空'}
                    ],
                    zhongzhidate: [
                        { required: true, message: '不能为空'}
                    ],
                    yingshoujine: [
                        { required: true, message: '不能为空'}
                    ],
                    yingfujine: [
                        { required: true, message: '不能为空'}
                    ],
                },
                yongyouid:'',
                weiYue:{
                    id:null,
                    Visible:false,
                    tHetongId:null,
                    tHetongBianhao:null,
                    weiyueleixing:null,
                    zhongzhidate:null,
                    yingshoujine:null,
                    yingfujine:null,
                    Loading:null,
                },
                options:[
                    {value:0, label:'已创建',},
                    {value:1, label:'待初审',},
                    {value:15, label:'初审中',},
                    {value:16, label:'待复审',},
                    {value:17, label:'初审拒绝',},
                    {value:2, label:'复审中',},
                    {value:3, label:'待打印',},
                    {value:4, label:'复审拒绝',},
                    {value:5, label:'待确认',},
                    {value:6, label:'履约中',},
                    {value:7, label:'违约处理中',},
                    {value:8, label:'合同终止（违约处理完成）',},
                    {value:9, label:'解约中',},
                    {value:10, label:'合同终止（解约完成）',},
                    {value:11, label:'合同终止（合同到期）',},
                    {value:12, label:'优化中',},
                    {value:13, label:'已优化，履约中',},
                    {value:14, label:'合同作废',},
                ],
                //分页类数据
                total:0,
                page:null,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                lists:[],
                listLoading: false,
                sels: [],//列表选中列
            }
        },
        components:{

        },
        methods: {
            //作废
            handleZuofei(index,row){
                this.$confirm('确认将合同作废吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:row.id,
                    }
                    saleContractCancelled(para).then((res)=>{
                        this.$message({
                            message: '设置成功',
                            type: 'success'
                        });
                    });
                    this.saleContractList();
                })

            },
            //打印核心数据
            handleSummary(index,row){
                window.open('/#/saleContract/summary?id='+row.id);
            },
            handleUplod(index,row){
                //this.$router.push('saleContract/upload?id='+row.id)
                window.open('/#/saleContract/upload?id='+row.id);
            },
            handleJiaogedan(index,row){
                //this.$router.push('/#/saleContract/jiaogedan?id='+row.id)
                window.open('/#/saleContract/jiaogedan?id='+row.id);
            },
            changeEnd(value){
                //获取三个信息：合同ID，违约类型，以及本日期
                let para = {
                    hetongId:this.id,
                    hetongType:0,
                    weiYueType:this.weiYue.weiyueleixing,
                    zhongZhiDate:new Date(this.weiYue.zhongzhidate).toLocaleDateString(),
                }
                weiYueInfoSaleContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '获取应付信息失败',
                            type: 'error'
                        });
                    }
                    //把值赋予weiYue
                    this.weiYue.yingfujine = res.data.data.yfMoney;
                    this.weiYue.yingshoujine = res.data.data.ysMoney;
                })

            },
            ztin(row,arr){
                var status = arr.indexOf(row.zhuangtai);
                if(status>-1){
                    return true;
                }else{
                    return false;
                }
            },
            //搜索
            search(){

            },
            getUsers() {
                let para = {
                    page: this.page,
                    pageSize: this.pageSize,
                    name: this.filters.name
                };
                this.listLoading = true;
                getUserListPage(para).then((res) => {
                    this.total = res.data.total;
                    this.users = res.data.data;
                    this.listLoading = false;
                });
            },
            //新增
            addContract() {
                var _this = this;
                _this.$router.push("/saleContract/add");
            },
            formatStatus(row, column){
                let status = [];
                status[0] = '已创建';
                status[1] = '待初审';
                status[15] = '初审中';
                status[16] = '待复审';
                status[17] = '初审拒绝';
                status[2] = '复审中';
                status[3] = '等待打印';
                status[4] = '复审拒绝';
                status[5] = '正在确认';
                status[6] = '履约中';
                status[7] = '违约处理中';
                status[8] = '合同终止（违约处理完成）';
                status[9] = '解约中';
                status[10] = '合同终止（解约完成）';
                status[11] = '合同终止（合同到期）';
                status[12] = '优化中';
                status[13] = '已优化，履约中';
                status[14] = '合同作废';
                return status[row.zhuangtai];
            },
            //时间戳转日期格式
            changeDate(row, column){
                var newDate = new Date();
                newDate.setTime(row.qianyuedate);
                if(row.qianyuedate!=null){
                    return newDate.toLocaleDateString()
                }
            },
            changeUpdateTime(row, column){
                var newDate = new Date();
                newDate.setTime(row.updateTime);
                if(row.updateTime!=null){
                    return newDate.toLocaleDateString()
                }
            },
            //获取合同列表
            saleContractList() {
                let para = {
                    pn: this.page,
                    cnt: this.pageSize,
                    selectItem: this.filters.name,
                    zhuangtai:this.filters.status,
                    startDate:this.filters.startDate,
                    endDate:this.filters.endDate,
                    yongyouid:this.filters.yongyouid,
                }
                //console.log(111);
                //console.log(this.page);
                //console.log(this.pageSize);
                //console.log(para);
                this.listLoading = true;
                getSaleContractList(para).then((res) => {
                    //console.log(12222);
                    //console.log(res.data.data);
                    this.total = res.data.total;
                    this.lists = res.data.data;
                    this.listLoading = false;

                });
            },
            updatayongyouid(index, row) {
                let para = {
                    id: row.id,
                    yongyouid: row.yongyouid,
                };
                yongyouUpdataSaleContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '数据没有保存成功',
                            type: 'error'
                        });
                    }
                });
            },
            handleSizeChange(val) {
                /*console.log(`每页 ${val} 条`);*/
                this.pageSize = val;
                this.saleContractList();
            },
            handleCurrentChange(val) {
                /*console.log(`当前页: ${val}`);*/
                this.page = val;
                this.saleContractList();
            },
            selsChange: function (sels) {
                this.sels = sels;
            },
            handlSee(index, row){
                window.open('/#/saleContract/see?id=' + row.id);
            },
            handleEdit(index, row){
                let para = {
                    id:row.id,
                }
                this.$router.push('/saleContract/edit?id=' + row.id+'&status='+row.zhuangtai);
                // this.$router.push('/purchaseContact/add?id='+row.id);
            },
            handleEditMore(index, row){
                this.$router.push('/saleContract/edit?id=' + row.id+'&status=0&zt=1');
            },
            handleReview(index, row){
                let para = {
                    id:row.id,
                }
                approvingSaleContract(para).then((res)=>{
                });
                this.$router.push('/saleContract/review?id=' + row.id+'&flag=1');
            },
            handlePreReview(index, row){
                let para = {
                    id:row.id,
                }
                approvingPreSaleContract(para).then((res)=>{
                });
                this.$router.push('/saleContract/review?id=' + row.id+'&flag=0');
            },
            handleJieyue(index, row){
                this.$confirm('确认将合同设置为解约中吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    let para = {id: row.id};
                    jieyueSaleContract(para).then((res) => {
                        this.listLoading = false;
                        this.$message({
                            message: '设置成功',
                            type: 'success'
                        });
                        this.saleContractList();
                    });
                }).catch(() => {

                });
            },
            handleJieyuewancheng(index, row){
                let para = {
                    id:row.id,
                }
                jieyueSaleContract(para).then((res)=>{
                });
                this.$router.push('/saleContract/jieyue?id=' + row.id);
            },
            handleCheckJieyue(index, row){
                this.$router.push('/saleContract/checkJieyue?id=' + row.id);
            },

            handleDump(index, row){
                getSaleContractInfo({id:row.id}).then((res) => {
                    var version = res.data.data.version;
					var resoin = res;
                    let para = {
                        id: row.id,
                    }
                    confirmSaleContract(para).then((res)=>{
                    if(res.data.code=="200"){
                        this.saleContractList();
						console.log(resoin);
                        if(version == 'x20180907' || version == 'x20180927' || version == 'x20181101' || version == 'x20181106'|| version == 'x20181119'){
                            window.open('/#/saleContract/dump'+version+'homepage?id=' + row.id+'&isdump=1');
                        }
                        window.open('/#/saleContract/dump'+version+'?id=' + row.id+'&isdump=1');
                        window.open('/#/saleContract/dump'+version+'pub?id=' + row.id+'&isdump=1');
                        if(version == 'x20180907' || version == 'x20180927'){
                            window.open('/#/saleContract/dump'+version+'enclosure?id=' + row.id+'&isdump=1');
                        }
						
						if(version == 'x20181101' && resoin.data.data.mianzuqiList.length > 0){
							window.open('/#/saleContract/dump'+version+'suppleagree?id=' + row.id+'&isdump=1');
						}
						}
                    });
                    //window.open('/#/purchaseContract/dump?id='+row.id)
                })
            },
            handleDel(index, row){
                this.$confirm('确认删除该记录吗？', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    let para = {id: row.id};
                    removeSaleContract(para).then((res) => {
                        this.listLoading = false;
                        this.message({
                            message: '删除成功',
                            type: 'success'
                        });
                        this.getUsers();
                    });
                }).catch(() => {

                });
            },
		//违约处理跟进记录
			handleViolated(index,row){
				this.$router.push('/purchaseContract/violated?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao+'&status='+row.zhuangtai);
			},
            //合同确认
            handleConfirm(index, row){
                this.$confirm('确认合同已完成吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    //alert(00);
                    this.listLoading = true;
                    let para = {id: row.id};
                    //alert(11);
                    confirmedSaleContract(para).then((res) => {
                        //alert(22);
                        this.listLoading = false;
                        //alert(33);
                        this.$message({
                            message: '设置成功',
                            type: 'success'
                        });
                        getSaleContractInfo(para).then((res)=>{
                            if(res.data.code=='200'){
                                for (let x=0;x < res.data.data.xsOffice.length;x++){
                                    let para = {
                                        id:0,
                                        fyid:res.data.data.xsOffice[x].omcId,
                                        name:res.data.data.chengzuren[0].name,
                                        phone:res.data.data.chengzuren[0].tel,
                                        wdfh:res.data.data.xsOffice[x].fanghao+"-"+res.data.data.xsOffice[x].subleaseno,
                                        xzhzj:res.data.data.zujinList[0].price,
                                        zq1:res.data.data.startdate,
                                        zq2:res.data.data.enddate,
                                        fkfs:"押"+res.data.data.fukuanFangshiList[0].yajinyue+"付"+res.data.data.fukuanFangshiList[0].zujinyue,
                                        zlmj:res.data.data.xsOffice[x].qianyuemianji,
                                    };
                                    //console.log(para);
                                    getSaleContractOmc(para).then((res)=>{
                                        if(res.data.success){
                                            //console.log(res.data.data)
                                            //把数据分别赋值给三个组件的变量
                                            this.$message({
                                                message: '传输数据成功',
                                                type: 'success'
                                            });
                                        }else {
                                            this.$message({
                                                message: '传输数据失败',
                                                type: 'error'
                                            });
                                        }
                                    })
                                }
                            }else {
                                this.$message({
                                    message: '获取数据失败',
                                    type: 'error'
                                });
                            }
                        })
                        this.saleContractList();
                    });
                }).catch(() => {

                });
            },
            handleWeiyue(index, row){
                this.$confirm('确认将合同设置为违约中吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    let para = {id: row.id};
                    weiyueSaleContract(para).then((res) => {
                        this.listLoading = false;
                        this.$message({
                            message: '设置成功',
                            type: 'success'
                        });
                        this.saleContractList();
                    });
                }).catch(() => {

                });
            },
            //终止 弹窗确认是否终止
            openEndDialog(index,row){
                this.id = row.id;
                this.weiYue.tHetongBianhao = row.bianhao;
                this.weiYue.Visible = true;
                this.weiYue.tHetongId = this.id;
                this.weiYue.id = this.id;

            },
            //合同终止
            handleEnd(index,row){
                this.$refs.sureForm.validate((valid) => {
                    if(valid){
                        this.$confirm('确认终止合同吗?', '提示', {
                            type: 'warning'
                        }).then(() => {
                            let para1 = this.weiYue;
                            this.weiYue.Visible = false;
                            weiYueSaveSaleContract(para1).then((res)=>{
                                if(res.data.code!='200'){
                                    this.$message({
                                        message: '数据没有保存成功',
                                        type: 'error'
                                    });
                                }
                            });
                            this.listLoading = true;
                            let para = { id:this.id };
                            endSaleContract(para).then((res) => {
                                this.listLoading = false;
                                //NProgress.done();
                                this.$message({
                                    message: '设置成功',
                                    type: 'success'
                                });
                                this.saleContractList();
                            });
                        }).catch(() => {

                        });
                    }
                });

            },
            //添加补充协议
            handleOptimize(index,row){
                this.$confirm('确认优化合同吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:row.id,
                    }
                    buchongSaleContract(para).then((res)=>{
                        if(res.data.code=='200'){
                            this.listLoading = false;
                            this.$message({
                                message: '设置成功',
                                type: 'success'
                            });
                            this.saleContractList();
                        }else{
                            this.$message({
                                message: '设置failed',
                                type: 'error'
                            });
                        }
                    });

                })

                //this.$router.push('/saleContract/buchong?hetongid='+row.id+'&bianhao='+row.bianhao);
            },
            //修改补充协议
            editOptimize(index,row){
                let para = {
                    id:row.id,
                }
                buchongSaleContract(para).then((res)=>{
                });
                this.$router.push('/saleContract/buchong?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao);
            },
            /*handleCheckOptimize(index,row){
                this.$router.push('/saleContract/checkBuchong?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao);
            },*/
            /*checkhistoryOptimize(index,row){
                this.$router.push('/saleContract/checkBuchongList?id='+row.id+'&bianhao='+row.bianhao);
            },*/
            //账号管理
            handleZhanghao(index,row){
                //this.$router.push('/saleContract/zhanghao?id='+row.id);
                window.open('/#/saleContract/zhanghao?id='+row.id);
            },
            //app账号管理
            handleAppZhanghao(index,row){
                //alert(222);
                //this.$router.push('/purchaseContract/zhanghao?id='+row.id);
                window.open('/#/saleContract/appzhanghao?id='+row.id);
            },
            //合单管理
            handleHedan(index,row){
                this.$router.push('/saleContract/hedan?id='+row.id+'&bianhao='+row.bianhao);
                window.open('/#/saleContract/hedan?id='+row.id+'&bianhao='+row.bianhao);
            },
            /*handleEnd(index, row){
                this.$confirm('确认合同终止吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para1 = this.weiYue;
                    this.weiYue.Visible = false;
                    weiYueSaveSaleContract(para1).then((res)=>{
                        if(res.data.code!='200'){
                            this.$message({
                                message: '数据没有保存成功',
                                type: 'error'
                            });
                        }
                    });
                    this.listLoading = true;
                    let para = {id:this.id};
                    endSaleContract(para).then((res) => {
                        this.listLoading = false;
                        this.$message({
                            message: '设置成功',
                            type: 'success'
                        });
                        this.saleContractList();
                    });
                }).catch(() => {
                });
            },*/

        },
        mounted(){
            this.saleContractList();
        }

    }
    //vue中和后台交汇数据时，是
</script>
