<template>
    <el-row >
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px" lable-width="80px;">
            <el-form-item label="合同编号:">
                <el-input v-model="filters.contractNo" placeholder="请输入合同编号"></el-input>
            </el-form-item>
            <el-form-item label="项目名称:">
                <el-input v-model="filters.xm" placeholder="请输入项目名称"></el-input>
            </el-form-item>
            <el-form-item label="付款日期:">
            <el-date-picker  type = "date" placeholder="请选择开始日期" v-model="filters.startdate">
            </el-date-picker>
            </el-form-item>
            <el-form-item label="至">
            <el-date-picker type = "date" placeholder="请选择结束日期" v-model="filters.enddate">
            </el-date-picker>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search"  v-on:click="getPayable">搜索</el-button>
            </el-form-item>
        </el-form>
         	<div class="totals_box">
						<p>
							<span style="font-size: 14px;">{{shouqizit}}</span>         			
						</p>
         		<p>
         			<span style="color:red;font-size: 14px;">(注：红色日期表示付款已延期，请尽快处理)</span>         			
         		</p>
         	</div>
        <el-tabs v-model="activeName2" type="border-card" @tab-click="handleClick">
            <el-tab-pane label="全部" name="first"></el-tab-pane>
            <el-tab-pane label="待初审" name="second"></el-tab-pane>
            <el-tab-pane label="初审通过" name="third"></el-tab-pane>
            <el-tab-pane label="初审驳回" name="fourth"></el-tab-pane>
						<el-tab-pane label="已撤销" name="five"></el-tab-pane>
            <el-table height="500" :data="Payable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
                <el-table-column prop="hetongbianhao" label="合同编号" width="190">
                </el-table-column>
                <el-table-column prop="xiangmu" label="项目">
                </el-table-column>
                <el-table-column prop="monthmoney" label="月租金" width="80" >
                </el-table-column>
								<el-table-column prop="fktype" label="类型" width="95"   :formatter="formatFKType">
								</el-table-column>
								<el-table-column prop="zhouqi" label="周期">
								</el-table-column>
								<el-table-column prop="fukuandate" label="应付日期" width="120"   >
										<template slot-scope="scope">
												<span :class="tableClassName(scope.row.fukuandate,scope.row.fkstate)">  {{ changeDate(scope.row.fukuandate) }}</span>
										</template>
								</el-table-column>
								<el-table-column prop="fukuanmoney" label="应付金额" width="110">
								</el-table-column>
								<el-table-column prop="tijiaomoney" label="提交金额" width="110">
								</el-table-column>
								<el-table-column prop="skinfo" label="收款账号"   width="180">
								</el-table-column>
								<el-table-column prop="status" label="状态"  :formatter="formatState"  width="95">
								</el-table-column>
                <el-table-column label="操作" width="120">
                       <template slot-scope="scope">
                           <el-dropdown   menu-align="start">
                               <el-button type="primary" size="normal" splitButton="true">
                                   操作<i class="el-icon-caret-bottom el-icon--right"></i>
                               </el-button>
                               <el-dropdown-menu slot="dropdown" >
                                   <el-dropdown-item v-if="ztin(scope.row,[1])&&fun('paRiskSubexat')"><el-button @click="handleRokeBack(scope.$index, scope.row)">审批</el-button></el-dropdown-item>
																	 <el-dropdown-item v-if="ztin(scope.row,[2,3,4,5,6])"><el-button @click="handleAdd(scope.$index, scope.row)">审批记录</el-button></el-dropdown-item>
                                   <el-dropdown-item v-if="ztin(scope.row,[2,3,6])"  > <el-button  @click="handleOpenUp(scope.$index, scope.row)">审批意见</el-button> </el-dropdown-item>
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
				<el-dialog title="审核" v-model="rokeBackFormVisible" :close-on-click-modal="false">
					<el-form :model="rokeBackForm" label-width="100px" ref="rokeBackForm">
						<el-input type="textarea" placeholder="审核意见" v-model="rokeBackForm.beizhu"></el-input>
					</el-form>
					<div slot="footer" class="dialog-footer">
						<el-button type="primary" @click.native="addFormSubmit(2)" :loading="addFormLoading">通过</el-button>
						<el-button type="primary" @click.native="addFormSubmit(3)" :loading="addFormLoadingbo">驳回</el-button>
						<el-button @click.native="rokeBackFormVisible = false">关闭</el-button>
					</div>
				</el-dialog>
				<el-dialog title="审批意见" v-model="addFormVisible" :close-on-click-modal="false">
					<el-form v-model="shenpr" ref="viewDateForm"  >
									审批人&nbsp;&nbsp;:&nbsp;&nbsp;{{shenpr==null?'无数据':shenpr}}
					</el-form><br />
					<el-form v-model="shenpshij" ref="viewDateForm"  >
									审批时间&nbsp;&nbsp;:&nbsp;&nbsp;{{shenpshij==null?'无数据':shenpshij}}
					</el-form><br />
						<el-form v-model="beizhu" ref="viewDateForm">
									备注&nbsp;&nbsp;:&nbsp;&nbsp;{{beizhu==null?'无数据':beizhu}}
						</el-form>
				</el-dialog>
    </el-row>
</template>
<style>
    .el-table .info-row {
        color:red;
    }
		.el-table .cell{text-align: left !important;}
		.el-textarea__inner{height: 200px;}
   /*.el-form-item__content{
   		margin-left: 10px!important;
   }*/
</style>
<script>

    import {
			getPaySettlementListPage,
			riskSubmissionPayable,
			yfkqrSubmission,
			yflistkqrPaymentPayable,
			
			
			
			
        getPayableListPage,
        editDate,
        saveFuKuan,
        editMoney,
        getLoupanList,
        getLoudongList,
        getFanghaoList,
        createFanghao,
        editPayable,
        addPayable,
        finishPayable
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
								isfirst:'',//1首期 2二期之后 空是全部
								shouqizit:'',
								addFormVisible: false,
								addFormLoading: false,
								addFormLoadingbo: false,
								beizhu:'',
								shenpr:'',
								shenpshij:'',
								addForm: {
										tCwSrSubmitId:'',
										status: '',
										beizhu: '',
								},
								tjfuid:'',
                filters:{
                    contractNo: '',
                    yz:'',
                    xm:'',
                    zt2:'',
                    startdate:'',
                    enddate:'',

                },
								xianzaje:'',
                options:[
                    {
                        value: 1,
                        label: '押金'
                    }, {
                        value: 2,
                        label: '房租'
                    },
                ],
                optionszt:[
                    {
                        value: 0,
                        label: '未提交'
                    }, {
                        value: 1,
                        label: '已提交'
                    },
                    {
                        value: 2,
                        label: '部分已付'
                    }, {
                        value: 3,
                        label: '已完成'
                    },
                    {
                        value: 4,
                        label: '已驳回'
                    }
                ],
                //分页类数据
                total:0,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                Payable:[],
                DataSum:[],
                activeName2:'first',
                listLoading: false,
                sels: [],//列表选中列

                rokeBackFormVisible: false,//收款界面是否显示
                rokeBackLoading: false,
                rokeBackFormRules: {
                    tijiaomoney:[{required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字', trigger: 'blur'},
                        {  required: true, validator: (rule, value, callback) => {
                            if ( value <= 0) {
                                callback(new Error("必须大于0"));
                            } else {
                                callback();
                            }
                        }, trigger: 'blur'
                        }],
                },
                //付款界面数据
                rokeBackForm: {
                    tCwFcSubmitId:'',
                    beizhu:'',
										status:'',
                },
                //被选中的权限
                checked:[],
            }
        },
        methods:{
            tableClassName(fkdate,fkstate){
                //return 'info-row';
                if(fkdate<new Date()&&fkstate!=2){
                    return 'info-row';
                }else{
                    return '';
                }
            },
            ztin(row,arr){
                var status = arr.indexOf(row.status);
                if(status>-1){
                    return true;
                }else{
                    return false;
                }
            },
            formatFKType(row, column){
                let status = [];
                status[0] = '押金';
                status[1] = '房租';
								status[2] = '补偿三方佣金';
								status[10] = '退还租金';
								status[11] = '退还房租';
								status[20] = '意向金';
                return status[row.fktype];
            },
            //状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[1] = '待初审';
                status[2] = '初审通过';
								status[3] = '初审驳回';
								status[4] = '复审通过';
								status[5] = '复审驳回';
								status[6] = '已撤销';
                return status[row.status];
            },
            //时间戳转日期格式
            changeDate(fkdate){
                var newDate = new Date();
                newDate.setTime(fkdate);
                return newDate.toLocaleDateString()
            },
						//时间戳转日期格式
						changetimeDate(time){
							var date = new Date(time);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
							var Y = date.getFullYear() + '.';
							var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '.';
							var D = date.getDate() + ' ';
							var h = date.getHours() + ':';
							var m = date.getMinutes() + ':';
							var s = date.getSeconds();
							return Y+M+D+h+m+s;
						},
            //标签切换时
            handleClick(tab, event) {
                var ztStatus = null;
                if(tab.index==0){
                    ztStatus = '';
                }
								if(tab.index==1){
										ztStatus = 1;
								}
								if(tab.index==2){
										ztStatus = 7;
								}
								if(tab.index==3){
										ztStatus = 3;
								}
								if(tab.index==4){
										ztStatus = 6;
								}
                this.filters.zt2 = ztStatus;
                this.getPayable();

            },
            //页面跳转后
            handleCurrentChange(val) {
                this.page = val;
                this.getPayable();
            },
            //更改每页显示数据
            handleSizeChange(val){
                this.pageSize =val;
                this.getPayable();
            },
            //打开应付记录页面
            handleOpen: function (index, row) {
                this.$router.push('/paymentRecord?id=' + row.tCwFcId);
            },
						handleAdd: function (index, row) {
							this.$router.push('/payableSubmitAudit?id=' + row.tCwFcId + '&subid=' + row.tCwFcSubmitId);
						},
            handleOpenUp: function (index, row) {
							this.addFormVisible = true;
							this.shenpr = this.Payable[index].auditname;
							this.shenpshij = this.changetimeDate(this.Payable[index].auditTime);
							this.beizhu = this.Payable[index].auditDesc;
            },
            //获取应付款列表
            getPayable() {
                let para = {
									page: this.page,
									pageSize: this.pageSize,
									isfirst: this.isfirst,
									htno: this.filters.contractNo,
									xm: this.filters.xm,
									sdate: this.filters.startdate,
									edate: this.filters.enddate,
									zt2:this.filters.zt2,
                };
                this.listLoading = true;
                yflistkqrPaymentPayable(para).then((res) => {
                    this.total = res.data.total;
                    this.Payable = res.data.data;
                    this.DataSum=res.data.dataSum;
                    if(res.data.dataSum == null){
                        this.DataSum =
                        {sumMoney: 0, tijiaoMoney: 0, shijiMoney: 0};
                    }
                    this.listLoading = false;
                });
            },
            //显示付款界面
            handleRokeBack: function (index, row) {
                this.rokeBackFormVisible = true;
// 								this.addForm = Object.assign({}, row);
// 								this.addForm = {
// 									tCwSrSubmitId: row.tCwSrSubmitId,
// 									status: '',
// 									beizhu: '',
// 								};
                this.rokeBackForm  = Object.assign({}, row);
                this.rokeBackForm = {
                		tCwFcSubmitId:row.tCwFcSubmitId,
                		beizhu:'',
                		status:'',
                };
            },
						
						//提交审核的数据2 通过 3驳回
						addFormSubmit(type){
							this.rokeBackForm.status = type;
							this.$refs.rokeBackForm.validate((valid) => {
								if (valid) {
									this.$confirm('确认提交吗？', '提示', {}).then(() => {
											let para = Object.assign({}, this.rokeBackForm);
											yfkqrSubmission(para).then((res) => {
												if(this.rokeBackForm.status == 2){
													this.addFormLoading = false;
												}else{
													this.addFormLoadingbo = false;
												}
												if(res.data.code==200) {
													this.$message({
															message: '提交成功',
															type: 'success'
													});
													this.$refs['rokeBackForm'].resetFields();
													this.rokeBackFormVisible = false;
													this.getPayable();
												} else{
														this.$message({
																message: res.data.msg,
																type: 'error'
														});
												}
											});
									});
								}
							});
						},
						
						
            //付款
            rokeBackSubmit: function () {
							if(this.rokeBackForm.tijiaomoney > this.xianzaje){
								this.$message({
										message: '提交金额不能大于应付金额！',
										type: 'error'
								});
							}else{
                this.$refs.rokeBackForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.rokeBackLoading = true;
                            // let para = Object.assign({}, this.rokeBackForm);
														let para = {
																tCwFcId: this.rokeBackForm.tCwFcId,
																tijiaomoney: this.rokeBackForm.tijiaomoney,
																beizhu: this.rokeBackForm.beizhu,
																shifumoney: this.rokeBackForm.shifumoney,
														}
                            riskSubmissionPayable(para).then((res) => {
                                this.rokeBackLoading = false;
                                if(res.data.code==200) {
																	if(res.data.success){
																		this.$message({
																				message: '提交成功',
																				type: 'success'
																		});
																		this.rokeBackFormVisible = false;
																		this.getPayable();
																	}else{
																		this.$message({
																				message: res.data.msg,
																				type: 'error'
																		});
																	}
                               
                                }else{
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                            });
                        });
                    }
                });
							}
            },
            selsChange: function (sels) {
                this.sels = sels;
            },

        },
        mounted() {
            this.page=1;
						if(this.fun('paExaminationDown')){
							if(this.fun('payExaminationTwo')){
								this.isfirst='';
								this.shouqizit='';
							}else{
								this.isfirst=1;
								this.shouqizit='首期';
							}
						}else{
							if(this.fun('payExaminationTwo')){
								this.isfirst=2;
								this.shouqizit='非首期';
							}
						}
            this.getPayable();

        }
    }
</script>
<style scoped>
	.totals{
		margin-left: 20px;
	}
	.totals_box{
		display: flex;
		justify-content: space-between;
	}
</style>