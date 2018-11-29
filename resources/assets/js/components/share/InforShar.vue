
<template>
    <el-row >
        <el-form :inline="true" :model="filters" class="demo-form-inline">
			<el-form-item label="">
				<el-input v-model="filters.name" @keyup.enter.native="purchaseContractList" placeholder="请输入项目"></el-input>
			</el-form-item>
            <el-form-item label="">
                <el-select v-model="filters.status" @change="purchaseContractList" placeholder="请选择出房合同">
                    <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
			<el-form-item label="">
				<el-select v-model="filters.statuspur" @change="purchaseContractList" placeholder="请选择收房合同">
					<el-option
							v-for="item in options2"
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
                        placeholder="请选择收房开始时间"
                        @keyup.enter.native="purchaseContractList"
                >
                </el-date-picker>
            </el-form-item>
            <el-form-item label="——">
                <el-date-picker
                        v-model="filters.endDate"
                        type="date"
                        placeholder="请选择收房结束时间"
                        @keyup.enter.native="purchaseContractList"
                >
                </el-date-picker>
            </el-form-item>
			<el-form-item>
				<el-date-picker
						v-model="filters.startDateSale"
						type="date"
						placeholder="请选择出房开始时间"
						@keyup.enter.native="purchaseContractList"
				>
				</el-date-picker>
			</el-form-item>
			<el-form-item label="——">
				<el-date-picker
						v-model="filters.endDateSale"
						type="date"
						placeholder="请选择出房结束时间"
						@keyup.enter.native="purchaseContractList"
				>
				</el-date-picker>
			</el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search" @click="purchaseContractList">搜索</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="lists" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
            <el-table-column prop="loupanName" label="楼盘"   >
                <!--<template slot-scope="scope">/-->
                    <!--<span v-for="(item,index) in  Estate(scope.row.loupanName)">{{item}}</span>-->
                <!--</template>-->
            </el-table-column>
            <el-table-column prop="loudongName" label="楼栋"   >
            </el-table-column>
            <el-table-column prop="fanghao" label="房间号"  >
            </el-table-column>
            <el-table-column prop="zhuangtai" label="面积" :formatter="formatStatus"   >
            </el-table-column>
			<el-table-column prop="zhuangtai" label="收房合同状态" :formatter="formatStatus"   >
			</el-table-column>
			<el-table-column prop="zhuangtai" label="出房合同状态" :formatter="formatStatus"   >
			</el-table-column>
			<el-table-column prop="zhuangtai" label="收房合同更新日期" :formatter="formatStatus"   >
			</el-table-column>
			<el-table-column prop="zhuangtai" label="出房合同更新日期" :formatter="formatStatus"   >
            </el-table-column>
            <el-table-column label="操作" width="170">
                <template slot-scope="scope">
                    <el-dropdown   menu-align="start">
                        <el-button type="primary" size="normal" splitButton="true">
                            操作<i class="el-icon-caret-bottom el-icon--right"></i>
                        </el-button>
                        <el-dropdown-menu slot="dropdown" >
                            <el-dropdown-item><el-button @click="handHousekeeper(scope.$index, scope.row)">管家信息共享</el-button> </el-dropdown-item>
							<el-dropdown-item><el-button @click="handBreach(scope.$index, scope.row)">违约信息共享</el-button> </el-dropdown-item>
							<el-dropdown-item><el-button @click="handOptimi(scope.$index, scope.row)">优化信息共享</el-button> </el-dropdown-item>
							<el-dropdown-item><el-button @click="handConstruction(scope.$index, scope.row)">施工记录</el-button> </el-dropdown-item>
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
        <contract-pay-type :payType="payType" v-on:refreshbizlines="purchaseContractList"></contract-pay-type>

        <el-dialog title="违约" v-model="weiYue.Visible" :close-on-click-modal="false">
            <el-form  label-width="120px"  ref="sureForm" :rules="weiYueRules" :model="weiYue">
                <el-input type="hidden" prop="tHetongId"  v-model="weiYue.tHetongId" auto-complete="off"></el-input>
                <el-form-item label="合同编号：" prop="tHetongBianhao">
                    {{weiYue.tHetongBianhao}}
                </el-form-item>
                <el-form-item label="违约类型：" prop="weiyueleixing">
                    <el-radio-group v-model="weiYue.weiyueleixing" >
                        <el-radio class="radio" label="1" >业主违约</el-radio>
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
                <el-button type="primary" @click.native="handleEnd"  :loading="weiYue.Loading">提交</el-button>
            </div>
        </el-dialog>
    </el-row>
</template>
<script>
    import contractPayType from '../Commission/contractPayType.vue';//佣金支付方式
    import {
        yongyouUpdataPurchaseContract,
        getPurchaseContractList,
        confirmPurchaseContract,
        approvingPurchaseContract,
        approvingPrePurchaseContract,
        dumpingPurchaseContract,
        weiyuePurchaseContract,
        endPurchaseContract,
        youhuaPurchaseContract,
        youhuaPurchaseContractList,
        weiYueInfoPurchaseContract,
        weiYueSavePurchaseContract,
        getPurchaseContractInfo,
        purchaseContractCancelled,
    } from '../../api/api.js';
    export default {
        data() {
            return {
                id:null,
                payType:{
                    sureFormVisible:false,//佣金支付方式显示
                    tHetongId:this.id,
                    tHetongBianhao:null,
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
                filters: {
                    name: '',
                    status:'',
					statuspur:'',
                    startDate:null,
                    endDate:null,
					startDateSale:null,
					endDateSale:null,
                    yongyouid: '',
                },
                options:[
                    {value:0, label:'已创建',},
                    {value:1, label:'待初审',},
                    {value:13, label:'初审中',},
                    {value:15, label:'初审拒绝',},
                    {value:14, label:'待复审',},
                    {value:2, label:'复审中',},
                    {value:4, label:'复审拒绝',},
                    {value:3, label:'待打印',},
                    {value:5, label:'待确认',},
                    {value:6, label:'履约中',},
                    {value:7, label:'违约处理中',},
                    {value:8, label:'合同终止（违约处理完成）',},
                    {value:9, label:'优化中',},
                    {value:10, label:'已优化，履约中',},
                    {value:11, label:'合同终止（合同到期）',},
                    {value:12, label:'合同作废',},
                ],
				options2:[
					{value:0, label:'已创建',},
					{value:1, label:'待初审',},
					{value:13, label:'初审中',},
					{value:15, label:'初审拒绝',},
					{value:14, label:'待复审',},
					{value:2, label:'复审中',},
					{value:4, label:'复审拒绝',},
					{value:3, label:'待打印',},
					{value:5, label:'待确认',},
					{value:6, label:'履约中',},
					{value:7, label:'违约处理中',},
					{value:8, label:'合同终止（违约处理完成）',},
					{value:9, label:'优化中',},
					{value:10, label:'已优化，履约中',},
					{value:11, label:'合同终止（合同到期）',},
					{value:12, label:'合同作废',},
				],
                //分页类数据
                total:0,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                lists:[],
                listLoading: false,
                sels: [],//列表选中列
            }
        },
        components:{
            contractPayType
        },
        methods: {
            /*Estate(value){
                console.log(value)
                var ss = '建外soho,望京soho'.split(",");

                return ss;
            },*/
            handleupload(index,row){
                //this.$router.push('purchaseContract/upload?id='+row.id)
                window.open('/#/purchaseContract/upload?id='+row.id);
            },
            handleJiaogedan(index,row){
                //this.$router.push('purchaseContract/Jiaogedan?id='+row.id)
                window.open('/#/purchaseContract/Jiaogedan?id='+row.id);
            },
            changeEnd(value){
                //获取三个信息：合同ID，违约类型，以及本日期
                let para = {
                    hetongId:this.id,
                    hetongType:0,
                    weiYueType:this.weiYue.weiyueleixing,
                    zhongZhiDate:new Date(this.weiYue.zhongzhidate).toLocaleDateString(),
                }
                weiYueInfoPurchaseContract(para).then((res)=>{
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
            //新增
            addContract() {
                this.$router.push("purchaseContract/add");
            },
            formatStatus(row, column){
                let status = [];
                status[0] = '已创建';
                status[1] = '待初审';
                status[13] = '初审中';
                status[14] = '待复审';
                status[15] = '初审拒绝';
                status[2] = '复审中';
                status[3] = '待打印';
                status[4] = '复审拒绝';
                status[5] = '待确认';
                status[6] = '履约中';
                status[7] = '违约处理中';
                status[8] = '合同终止(违约处理完成)';
                status[9] = '优化中';
                status[10] = '已优化，履约中';
                status[11] = '合同终止(合同到期)';
                status[12] = '合同作废';

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
            purchaseContractList() {
                let para = {
                    pn: this.page,
                    cnt: this.pageSize,
                    selectItem:this.filters.name,
                    zhuangtai:this.filters.status,
                    startDate:this.filters.startDate,
                    endDate:this.filters.endDate,
                    yongyouid:this.filters.yongyouid,
                }
                this.listLoading = true;
                getPurchaseContractList(para).then((res) => {
                    this.total = res.data.total;
                    this.lists = res.data.data;
                    this.listLoading = false;
                });
            },
            //编辑用友编号
            updatayongyouid(index, row) {
                let para = {
                    id: row.id,
                    yongyouid: row.yongyouid,
                };
                yongyouUpdataPurchaseContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '数据没有保存成功',
                            type: 'error'
                        });
                    }
                });
            },
            handleSizeChange(val) {
                this.pageSize =val;
                this.purchaseContractList();
            },
            handleCurrentChange(val) {
                this.page = val;
                this.purchaseContractList();
            },
            selsChange: function (sels) {
                this.sels = sels;
            },
            handleEdit(index,row){
                this.$router.push('/purchaseContract/edit?id='+row.id+'&status='+row.zhuangtai);
            },
            handleEditMore(index, row){

                this.$router.push('/purchaseContract/edit?id=' + row.id+'&status=0&zt=1');
                // this.$router.push('/purchaseContact/add?id='+row.id);
            },
            //初审
            handlePreReview(index,row){
                //审核状态变更：审核中
                let para = {
                    id:row.id,
                }
                approvingPrePurchaseContract(para).then((res)=>{
                });
                this.$router.push('/purchaseContract/review?id='+row.id+'&flag=0');
            },
            //复审核
            handleReview(index,row){
                //审核状态变更：审核中
                let para = {
                    id:row.id,
                }
                approvingPurchaseContract(para).then((res)=>{
                });
                this.$router.push('/purchaseContract/review?id='+row.id+'&flag=1');
            },
            //作废
            handleZuofei(index,row){
                this.$confirm('确认将合同作废吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:row.id,
                    }
                    purchaseContractCancelled(para).then((res)=>{
                        this.$message({
                            message: '设置成功!',
                            type: 'success'
                        });
                    });
                    this.purchaseContractList();
                })

            },
            //管家信息共享
            handHousekeeper(index,row){
                //this.$router.push('/purchaseContract/view?id='+row.id);
                window.open('/#/inforShar/viewHousekeeper?id='+row.id);
            },
            //违约 弹窗确认是否违约
            handleWeiyue(index,row){
                this.$confirm('确认将合同设置为违约中吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    let para = { id: row.id };
                    weiyuePurchaseContract(para).then((res) => {
                        this.listLoading = false;
                        this.weiYue.formVisible =false;
                        //NProgress.done();
                        this.$message({
                            message: '设置成功',
                            type: 'success'
                        });
                        this.purchaseContractList();
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
                            weiYueSavePurchaseContract(para1).then((res)=>{
                                if(res.data.code!='200'){
                                    this.$message({
                                        message: '数据没有保存成功',
                                        type: 'error'
                                    });
                                }
                            });
                            this.listLoading = true;
                            let para = { id:this.id };
                            endPurchaseContract(para).then((res) => {
                                this.listLoading = false;
                                //NProgress.done();
                                this.$message({
                                    message: '设置成功',
                                    type: 'success'
                                });
                                this.purchaseContractList();
                            });
                        }).catch(() => {

                        });
                    }
                });

            },
            //二次优化添加协议
            handleOptimize(index,row){
                this.$confirm('确认优化合同吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:row.id,
                    }
                    youhuaPurchaseContract(para).then((res)=>{
                        if(res.data.code=='200'){
                            this.listLoading = false;
                            this.$message({
                                message: '设置成功',
                                type: 'success'
                            });
                            this.purchaseContractList();
                        }else{
                            this.$message({
                                message: '设置failed',
                                type: 'error'
                            });
                        }
                    });

                    //this.$router.push('/purchaseContract/optimize?hetongid='+row.id+'&bianhao='+row.bianhao);
                })
            },
			//优化跟进记录
			handleOfur(index,row){
				// window.open('/#/purchaseContract/ofur?id='+row.id);
				// this.$router.push('/purchaseContract/ofur?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao);12
				this.$router.push('/purchaseContract/ofur?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao+'&status='+row.zhuangtai);
			},
			//违约处理跟进记录
			handleViolated(index,row){
				this.$router.push('/purchaseContract/violated?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao+'&status='+row.zhuangtai);
			},
            //修改协议
            editOptimize(index,row){
                let para = {
                    id:row.id,
                }
                youhuaPurchaseContract(para).then((res)=>{
                });
                this.$router.push('/purchaseContract/optimize?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao);
            },
            handleCheckOptimize(index,row){
                this.$router.push('/purchaseContract/checkoptimize?hetongid='+row.id+'&type=1'+'&bianhao='+row.bianhao);
            },
            checkhistoryOptimize(index,row){
                this.$router.push('/purchaseContract/checkOptimizeList?id='+row.id+'&bianhao='+row.bianhao);
            },
            //打印
            handleDump(index,row){
                getPurchaseContractInfo({id:row.id}).then((res) => {
                    var version = res.data.data.version;
                    let para = {
                        id:row.id,
                    }
                    dumpingPurchaseContract(para).then((res)=>{
                        if(res.data.code=="200"){
                            this.purchaseContractList();
                            if(version == 's20180907' || version == 's20180927'){
                                window.open('/#/purchaseContract/dump'+version+'homepage?id='+row.id+'&isdump=1')
                            }
                            window.open('/#/purchaseContract/dump'+version+'?id='+row.id+'&isdump=1')
                            window.open('/#/purchaseContract/dump'+version+'pub?id='+row.id+'&isdump=1')
                            if(version == 's20180927'){
                                window.open('/#/purchaseContract/dump'+version+'enclosure?id='+row.id+'&isdump=1')
                            }
                        }
                    });
                })
            },
            //打印核心数据
            handleSummary(index,row){
                window.open('/#/purchaseContract/summary?id='+row.id);
            },
            //合同确认
            handleConfirm(index,row){

                getPurchaseContractInfo({id:row.id}).then((res) => {
                    if (res.data.code == '200') {
                        //把数据分别赋值给三个组件的变量
                        if (res.data.data.farenzhengjian == 'l') {
                            this.$confirm('确认没有居间方的信息吗?', '提示', {
                                type: 'warning'
                            }).then(() => {
                                let para1 = {
                                    id: row.id,
                                }
                                //直接执行签约完成的状态
                                confirmPurchaseContract(para1).then((res) => {
                                    if (res.data.code == '200') {
                                        this.$message({
                                            message: '签约完成',
                                            type: 'success'
                                        });
                                        this.purchaseContractList();
                                    }
                                });
                            })

                        } else {
                            this.payType.sureFormVisible = true;
                            this.payType.tHetongId = row.id;
                            this.payType.tHetongBianhao = row.bianhao;
                        }
                    } else {
                        this.$message({
                            message: '获取数据失败',
                            type: 'error'
                        });
                    }
                })
            },
            //账号管理
            handleZhanghao(index,row){
                //alert(222);
                //this.$router.push('/purchaseContract/zhanghao?id='+row.id);
                window.open('/#/purchaseContract/zhanghao?id='+row.id);
            },
            //app账号管理
            handleAppZhanghao(index,row){
                //alert(222);
                //this.$router.push('/purchaseContract/zhanghao?id='+row.id);
                window.open('/#/purchaseContract/appzhanghao?id='+row.id);
            },
            //合单管理
            handleHedan(index,row){
                //this.$router.push('/purchaseContract/hedan?id='+row.id);
                window.open('/#/purchaseContract/hedan?id='+row.id);
            }
        },
        mounted(){
            this.purchaseContractList();
//            function showNotice() {
//                Notification.requestPermission(function (perm) {
//                    if (perm == "granted") {
//                        var notification = new Notification("这是一个通知:", {
//                            dir: "auto",
//                            lang: "hi",
//                            tag: "testTag",
//                            icon: "",
//                            body: "通知content"
//                        });
//                    }
//                })
//            }
//            showNotice();
        }

    }
</script>
