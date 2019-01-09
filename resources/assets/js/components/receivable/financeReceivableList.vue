
<template>
    <el-row >
        <el-form :inline="true" :model="filters" class="demo-form-inline">
					<el-form-item label="付款方户名:">
							<el-input v-model="filters.fkzh" placeholder="请输入付款方户名"></el-input>
					</el-form-item>
					<el-form-item label="收款日期:">
							<el-date-picker type="date" placeholder="请选择开始日期" v-model="filters.sdate">
							</el-date-picker>
					</el-form-item>
					<el-form-item label="至">
							<el-date-picker type="date" placeholder="请选择结束日期" v-model="filters.edate">
							</el-date-picker>
					</el-form-item>
					<!-- <el-form-item>
            <el-form-item label="付款银行:">
                <el-input v-model="filters.fkyh" placeholder="请输入付款银行"></el-input>
            </el-form-item>
            <el-form-item label="付款账号:">
                <el-input v-model="filters.fkzh" placeholder="请输入付款账号"></el-input>
            </el-form-item> -->
            <el-form-item>
                <el-button type="primary" icon="search"  v-on:click="getReceivable">搜索</el-button>
                <!-- <el-button type="primary" class="el-icon-plus"  v-if="fun('claimAdd')"  @click="handleAdd">新增</el-button> -->
								<el-button type="primary" class="el-icon-plus" @click="handleAdd">新增</el-button>
            </el-form-item>
        </el-form>
        <el-tabs v-model="activeName2" type="border-card" @tab-click="handleClick">
            <el-tab-pane label="全部" name="first"></el-tab-pane>
            <el-tab-pane label="待认领" name="second"></el-tab-pane>
            <el-tab-pane label="部分认领" name="fourth"></el-tab-pane>
            <el-tab-pane label="认领完成" name="fifth"></el-tab-pane>
        <el-table height="500" :data="financeReceivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"   style="width: 100%;">
            <el-table-column prop="shoukuandate" label="收款日期" :formatter="changeDate1" >
            </el-table-column>
            <el-table-column prop="shoukuanmoney" label="收款金额" >
            </el-table-column>
            <el-table-column prop="fukuanzhanghu" label="付款方户名"  >
            </el-table-column>
           <!-- <el-table-column prop="renlingmoney" label="已认领金额"   >
            </el-table-column>
            <el-table-column prop="shengyvmoney" label="剩余金额"  >
            </el-table-column> -->
            <el-table-column  label="付款银行及账号"   :formatter="formatskyh">
            </el-table-column>
            <el-table-column prop="zhuangtai" label="状态"  :formatter="formatState"  >
            </el-table-column>
            <el-table-column label="操作" width="120">
                   <template slot-scope="scope">
                       <el-dropdown   menu-align="start">
                           <el-button type="primary" size="normal" splitButton="true">
                               操作<i class="el-icon-caret-bottom el-icon--right"></i>
                           </el-button>
                           <el-dropdown-menu slot="dropdown" >
                               <!-- <el-dropdown-item   v-if="scope.row.zhuangtai==0||scope.row.zhuangtai==2" ><el-button v-if="fun('claim')"   @click="handleRokeBack(scope.row)">认领</el-button></el-dropdown-item> -->
															 <el-dropdown-item v-if="ztin(scope.row,[0,2])&&fun('receiptsClaim')"><el-button  @click="handleRokeBack(scope.row)">认领</el-button></el-dropdown-item>
                               <el-dropdown-item v-if="fun('receiptsClaimList')"><el-button   @click="handleclaimRecord(scope.$index, scope.row)">认领记录</el-button></el-dropdown-item>
																<el-dropdown-item v-if="ztin(scope.row,[0,1,2])&&fun('RUpload')"><el-button   @click="handleUpload(scope.$index, scope.row)">上传凭证</el-button></el-dropdown-item>
																<el-dropdown-item v-if="ztin(scope.row,[1,2])&&fun('RRUploadView')"><el-button   @click="handleView(scope.$index, scope.row)">查看凭证</el-button></el-dropdown-item>
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
                    :page-size="10"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total=total
                    style="float:right"
            >
            </el-pagination>
        </el-col>
        </el-tabs>
        <el-dialog title="查看备注" v-model="viewDateFormVisible" :close-on-click-modal="false">
            <el-form :model="viewDateForm" label-width="120px"   ref="viewDateForm"  >
                    {{viewDateForm.beizhu==null?'无数据':viewDateForm.beizhu}}
            </el-form>
        </el-dialog>
        <el-dialog title="认领列表" v-model="rokeBackFormVisible" :close-on-click-modal="false"  size="large">
            <el-form :inline="true" :model="filters" class="demo-form-inline">
                <el-form-item label="合同编号:">
                    <el-input v-model="filters.contractNo" placeholder="请输入合同编号"></el-input>
                </el-form-item>
                <el-form-item label="项目名称:">
                    <el-input v-model="filters.xm" placeholder="请输入项目名称"></el-input>
                </el-form-item>
                <el-form-item label="租户名称:">
                    <el-input v-model="filters.zh" placeholder="请输入租户名称"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="search"  v-on:click="getReceivableRL">搜索</el-button>
                </el-form-item>
            </el-form>
            <el-table  :data="ReceivableRL" highlight-current-row v-loading="RLlistLoading" ref="multipleTable" element-loading-text="拼命加载中"
                      @current-change="handleCurrentChange1" @selection-change="handleCurrentChange1" @select="selsChange"  style="width: 100%;">

                <el-table-column prop="hetongbianhao" label="合同编号" width="200">
                </el-table-column>
                <el-table-column prop="xiangmu" label="项目" width="260">
                </el-table-column>
                <el-table-column prop="zuhu" label="租户"  >
                </el-table-column>
                <el-table-column prop="shoukuandate" label="收款日期" :formatter="changeDate2"  >
                </el-table-column>
								<el-table-column prop="kemu" label="类型"  :formatter="formatFKType" >
								</el-table-column>
								
                <!-- <el-table-column prop="sktype" label="收款科目"    :formatter="formatFKType"  >
                </el-table-column> -->
                <!-- <el-table-column prop="skmoney" label="应收房租"  >
                </el-table-column> -->
                <el-table-column prop="shoukuanmoney" label="应付金额"   >
                </el-table-column>
                <el-table-column prop="tijiaomoney" label="提交金额"  >
                </el-table-column>
                <!-- <el-table-column prop="shiugaizhuangtai" label="修改状态"    >
                </el-table-column>
                <el-table-column prop="srstate" label="支付状态"  :formatter="formatRLState" >
                </el-table-column> -->
            </el-table>
            <div style="margin-top:30px"></div>
            <!-- 分页-->
            <el-col :span="24" class="toolbar" >
                <el-pagination
                        @size-change="handleSizeChangerl"
                        @current-change="handleCurrentChangerl"
                        :current-page="currentPagerl"
                        :page-size="10"
                        layout="total, sizes, prev, pager, next, jumper"
                        :total=totalrl
                        style="float:right"
                >
                </el-pagination>
            </el-col>

            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click.native="rokeBackSubmit">认领</el-button>
                <el-button @click.native="rokeBackFormVisible = false">取消</el-button>
            </div>
        </el-dialog>
        <el-dialog title="新增收款记录" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="120px" :rules="addFormRules" ref="addForm"  >
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="收款金额：" prop="shoukuanmoney">
                            <el-input    v-model.number="addForm.shoukuanmoney" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="收款时间：" prop="shoukuandate">
                            <el-date-picker type = "date" v-model="addForm.shoukuandate" auto-complete="off">
                            </el-date-picker>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="付款方户名：" prop="fukuanzhanghu">
                            <el-input    v-model="addForm.fukuanzhanghu" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="16">
                        <el-form-item label="付款银行：" prop="fukuanyinhang">
                            <el-input   v-model="addForm.fukuanyinhang" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="付款账号：" prop="fukuanzhanghao">
                    <el-input    v-model="addForm.fukuanzhanghao" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item   label="备注：" prop="beizhu">
                    <el-input type="textarea" v-model="addForm.beizhu" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="addFormSubmit" :loading="addFormLoading">保存</el-button>
            </div>
        </el-dialog>

    </el-row>
</template>
<style>

    .el-dialog .el-table__body tr.current-row>td {
        background: rgba(26, 135, 249, 0.75) !important;
    }
</style>
<script>
    import {
			getReceivablefinanceList,
			getReceivableClaimList,
			renlFinance,
			financefSave,
			
        getReceiveList,
        getReceivableListPage,
        editReceivable,
        financeSaveShouKuan,
        renling,
        addBrokerCompanyUser,
        batchRemoveBrokerCompanyUser,
        getbkNameList,
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
                Receivable:[],//应收列表
								filters:{
										sdate: '',
										edate:'',
										fkzh:'',
										zt:'',
										contractNo:'',
										xm:'',
										zg:'',
								},
                options:[
                    {
                        value: 1,
                        label: '押金'
                    }, {
                        value: 2,
                        label: '房租'
                    },
                ],
                activeName2:'first',
                //分页类数据
                total:0,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                financeReceivable:[],
                ReceivableRL:[],
                RLlistLoading:false,
                listLoading: false,
                sels: [],//列表选中列
                rokeBackFormVisible: false,//收款界面是否显示
                rokeBackLoading: false,
                addFormVisible: false,//收款界面是否显示
                addFormLoading: false,
                addFormRules: {
                    shoukuanmoney:[
                        {required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字'}
                    ],
                    shoukuandate: [
                        {required: true, message: '不能为空'},
                    ],
                 //    fukuanzhanghu: [
                 //     {required: true, message: '不能为空'},
                 //   ],
                 //   fukuanyinhang: [
                 //       {required: true, message: '不能为空'},
                 //   ],
                 //  fukuanzhanghao: [
                 //      {required: true, message: '不能为空'},
                 //  ],
                },
                //付款界面数据
                addForm: {
                    beizhu:'',
                    fukuanyinhang:'',
                    fukuanzhanghao:'',
                    fukuanzhanghu:'',
                    shoukuandate:'',
                    shoukuanmoney:'',
                    zhuangtai:'',
                },
                viewDateFormVisible: false,//查看界面是否显示
                //查看界面数据
                viewDateForm: {
                    beizhu:'',
                },
                totalrl:0,
                currentPagerl:0,
                pageSizerl:10,
                renlingData :{
									tCwSrId:"",
									tCwSrPlanId:null,
									tCwSrCaiwuId:null,
                },
            }
        },
        methods:{
            tableRowClassName(row, index) {
                if (row.tCwSrCaiwuId === this.renlingData.tCwSrCaiwuId) {
                    return 'info-row';
                }else{
                    return '';
                }
            },
						ztin(row, arr){
								var status = arr.indexOf(row.zhuangtai);
								if (status > -1) {
										return true;
								} else {
										return false;
								}
						},
            handleRokeBack(row){
                this.rokeBackFormVisible = true;
								this.renlingData.tCwSrCaiwuId=row.tCwSrCaiwuId;
                this.pagerl = 1;
                this.getReceivableRL();
            },
            formatFKType(row, column){
                let status = [];
                status[0] = '押金';
                status[1] = '房租';
                return status[row.kemu];
            },
            //支付状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[0] = '未认领';
                status[1] = '认领完成';
                status[2] = '部分认领';
                return status[row.zhuangtai];
            },
            //认领支付状态显示转换
            formatRLState: function (row, column) {
                let status = [];
                status[0] = '未提交';
                status[1] = '已提交';
                status[2] = '已完成';
                status[3] = '部分已付';
                status[4] = '已驳回';
                return status[row.srstate];
            },
            //银行账号转换
            formatskyh: function (row, column) {
                return  row.fukuanyinhang+'\r账号'+row.fukuanzhanghao;
            },
            //时间戳转日期格式
            changeDate1(value){
                var newDate = new Date();
                newDate.setTime(value.shoukuandate);
                return newDate.toLocaleDateString()
            },
            //时间戳转日期格式
            changeDate2(row, column){
                if(row.shoukuandate!=null) {
                    var newDate = new Date();
                    newDate.setTime(row.shoukuandate);
                    return newDate.toLocaleDateString()
                }
            },
            //标签切换时
            handleClick(tab, event) {
                if (tab.index == 0) {
                    this.filters.zt = '';
                    this.getReceivable();
                }else if(tab.index == 1) {
                    this.filters.zt = 0;
                    this.getReceivable();
                }else if (tab.index == 2) {
                    this.filters.zt = 2;
                    this.getReceivable();
                }else if (tab.index == 3) {
                    this.filters.zt = 1;
                    this.getReceivable();
                }
            },
            //页面跳转后
            handleCurrentChange(val) {
                this.page = val;
                this.getReceivable();
            },
            //更改每页显示数据
            handleSizeChange(val){
                this.pageSize =val;
                this.getReceivable();
            },
            //页面跳转后
            handleCurrentChangerl(val) {
                this.pagerl = val;
                this.getReceivableRL();
            },
            //更改每页显示数据
            handleSizeChangerl(val){
                this.pageSizerl =val;
                this.getReceivableRL();
            },
            //获取实收款列表
            getReceivable() {
//                 let para = {
//                     page: this.page,
//                     pageSize: this.pageSize,
//                     fkyh: this.filters.fkyh,
//                     fkzh: this.filters.fkzh,
//                     zt: this.filters.zt,
//                 };
								let para = {
										page: this.pagerl,
										pageSize: this.pageSizerl,
										sdate: this.filters.sdate,
										edate: this.filters.edate,
										fkzh: this.filters.fkzh,
										zt:this.filters.zt,
								};
                this.listLoading = true;
                getReceivablefinanceList(para).then((res) => {
                    this.total = res.data.total;
                    this.financeReceivable = res.data.data;
                    this.listLoading = false;
                });
            },
            //获取应付款列表
            getReceivableRL() {
                let para = {
                    page: this.pagerl,
                    pageSize: this.pageSizerl,
                    htno: this.filters.contractNo,
                    xm: this.filters.xm,
                    zh: this.filters.zh,
                };
                this.RLlistLoading = true;
                getReceivableClaimList(para).then((res) => {
                    this.totalrl = res.data.total;
                    this.ReceivableRL = res.data.data;
                    this.RLlistLoading = false;
                });
            },
            //新增实收页面
            handleAdd: function (index, row) {
                this.addFormVisible = true;
            },
            //上传凭证
						handleUpload:function(index, row){
							this.$router.push('/receivableUpload?id=' + row.tCwSrCaiwuId);
						},
						//查看凭证
            handleView: function (index, row) {
								this.$router.push('/receivableView?id=' + row.tCwSrCaiwuId);
            },
						//认领记录列表
						handleclaimRecord: function (index, row) {
								this.$router.push('/receivableClaimRecord?id=' + row.tCwSrCaiwuId + '&hetongid=' + row.hetongid);
						},
            //提交数据
            addFormSubmit(){
                this.$refs.addForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.addFormLoading = true;
                            let para = Object.assign({}, this.addForm);
                            financefSave(para).then((res) => {
                                this.addFormLoading = false;
                                if(res.data.code==200) {
                                this.$message({
                                    message: '提交成功',
                                    type: 'success'
                                });
                                this.$refs['addForm'].resetFields();
                                } else{
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                                this.addFormVisible = false;
                                this.getReceivable();
                            });
                        });
                    }
                });
            },
            //认领提交
            rokeBackSubmit: function () {
                if(this.renlingData.tCwSrPlanId!=null){
                    this.$confirm('确认认领吗？', '提示', {}).then(() => {
                        let para = this.renlingData;
                        renlFinance(para).then((res)=>{
                            if(res.data.code=='200'){
                                this.$message({
                                    message: '提交成功',
                                    type: 'success'
                                });

                            }else{
                                this.$message({
                                    message: '提交失败',
                                    type: 'error'
                                });
                            }
                            this.getReceivable();
                            this.rokeBackFormVisible = false;
                        });
                        this.renlingData = {
                            tCwSrPlanId:null,
                            tCwSrCaiwuId:null,
                        };
                    });
                }else{
                    alert('至少点击选中一项')
                }
            },
            //选中以后
            handleCurrentChange1(val) {
                this.renlingData.tCwSrPlanId=val.tCwSrPlanId;

            },
            selsChange(val,row){
                this.$refs.multipleTable.clearSelection();
                this.$refs.multipleTable.toggleRowSelection(row);
            },
        },
        mounted() {
            this.page=1;
						this.pagerl = 1;
            this.getReceivable();

        }
    }
</script>
