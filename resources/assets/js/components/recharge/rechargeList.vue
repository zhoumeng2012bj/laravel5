<template>
    <el-row>
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px">
            <el-form-item label="户名:">
                <el-input v-model="filters.htno" placeholder="请输入合同编号"></el-input>
            </el-form-item>
            <!-- <el-form-item label="项目名称:">
                <el-input v-model="filters.xm" placeholder="请输入项目名称"></el-input>
            </el-form-item> -->
            
						<el-form-item label="支付状态">
							<el-select v-model="filters.status" @change="getReceivable" placeholder="请输入支付状态">
								<el-option
										v-for="item in options"
										:key="item.value"
										:label="item.label"
										:value="item.value">
								</el-option>
							</el-select>
						</el-form-item>
						<el-form-item label="对账状态">
							<el-select v-model="filters.duizhangstatus" @change="getReceivable" placeholder="请输入对账状态">
								<el-option
										v-for="item in options2"
										:key="item.value"
										:label="item.label"
										:value="item.value">
								</el-option>
							</el-select>
						</el-form-item>
						<el-form-item label="操作状态">
							<el-select v-model="filters.duizhangstatus" @change="getReceivable" placeholder="请输入对账状态">
								<el-option
										v-for="item in options2"
										:key="item.value"
										:label="item.label"
										:value="item.value">
								</el-option>
							</el-select>
						</el-form-item><br />
						<el-form-item label="充值时间:">
								<el-date-picker type="date" placeholder="请选择开始日期" v-model="filters.startdate">
								</el-date-picker>
						</el-form-item>
						<el-form-item label="至">
								<el-date-picker type="date" placeholder="请选择结束日期" v-model="filters.enddate">
								</el-date-picker>
						</el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search" v-on:click="getReceivable">搜索</el-button>
            </el-form-item>
        </el-form>
        <div class="totals_box">
        	<p>
		        <span style="color:red;font-size: 14px;">（注：红色日期表示收款已延期，请尽快处理）</span>
        	</p>
					<p>
						<el-button type="primary" @click="addContract"> 充值 </el-button>
					</p>
        </div>
            <el-table height="500" :data="Receivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
                      @selection-change="selsChange" style="width: 100%;">
                <el-table-column prop="hetongbianhao" label="合同编号">
                </el-table-column>
                <el-table-column prop="xiangmu" label="项目">
                </el-table-column>
								<el-table-column prop="kemu" label="类型" :formatter="formatFKType" width="100">
								</el-table-column>
								<el-table-column prop="zhouqi" label="周期">
								</el-table-column>
								<el-table-column prop="tijiaomoney" label="本期应收" width="110">
								</el-table-column>
                <el-table-column prop="shoukuanmoney" label="实收金额" width="110">
                </el-table-column>
								<el-table-column prop="shoukuandate" label="实收日期" width="150">
										<template slot-scope="scope">
												<span :class="tableClassName(scope.row.shoukuandate,scope.row.paystatus)">  {{ changeDate(scope.row.shoukuandate)
														}}</span>
										</template>
								</el-table-column>
                <el-table-column prop="paystatus" label="状态" :formatter="formatState" width="100">
                </el-table-column>
								<el-table-column prop="duizhangstatus" label="对账状态" :formatter="formatState2" width="100">
								</el-table-column>
                <el-table-column label="操作" width="180">
                    <template slot-scope="scope">
                        <el-dropdown menu-align="start">
                            <el-button type="primary" size="normal" splitButton="true">
                                  操作<i class="el-icon-caret-bottom el-icon--right"></i>
                              </el-button>
                              <el-dropdown-menu slot="dropdown">
																	<el-dropdown-item v-if="ztin(scope.row,[1,3])&&fun('financeWithdraw')">
																			<el-button @click.native="handleRokeBack(scope.$index, scope.row)" :loading="addFormLoading">处理</el-button>
																	</el-dropdown-item>
                              </el-dropdown-menu>
                          </el-dropdown>
                      </template>
                  </el-table-column>
              </el-table>
              <div style="margin-top:30px"></div>
              <!-- 分页-->
            <el-col :span="24" class="toolbar">
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
						<el-dialog size="tiny" title="充值" v-model="Visible" :close-on-click-modal="false" label-width="120px">
								<el-form  label-width="80px"  ref="zhanghao" :rules="zhanghaoRules" :model="zhanghao">
									<el-form-item label="账户类型" prop="duizhangtype">
											<el-radio-group v-model="zhanghao.duizhangtype" >
													<el-radio label="1">公户</el-radio>
													<el-radio label="2">私户</el-radio>
											</el-radio-group>
									</el-form-item>
									<el-row>
											<el-col :span="15">
													<el-form-item label="户名">
															<el-input v-model="zhanghao.huming"></el-input>
													</el-form-item>
											</el-col>
									</el-row>
									<el-row>
											<el-col :span="15">
													<el-form-item label="银行">
															<el-input v-model="zhanghao.yinhang"></el-input>
													</el-form-item>
											</el-col>
									</el-row>
									<el-row>
											<el-col :span="15">
													<el-form-item label="账号">
															<el-input v-model="zhanghao.zhanghao"></el-input>
													</el-form-item>
											</el-col>
									</el-row>
									<el-row>
											<el-col :span="15">
													<el-form-item label="金额" prop="jine" required>
															<el-input v-model="zhanghao.jine" ></el-input>
													</el-form-item>
											</el-col>
									</el-row>
								</el-form>
								<div slot="footer" class="dialog-footer">
										<el-button @click.native="Visible = false">取消</el-button>
										<el-button type="primary" @click.native="handleEnd" :loading="Loading">提交</el-button>
								</div>
						</el-dialog>
						<el-dialog size="tiny" title="确认支付" v-model="Vizhifusible" :close-on-click-modal="false" label-width="120px">
								<div class="zhifudis tishizhfu">请在打开的支付页面中完成支付！</div><br />
								<div class="zhifudis">支付完成前请不要关闭此窗口</div>
								<div slot="footer" class="dialog-footer">
									<el-button type="primary" @click.native="handleZhifuEnd" :loading="Loading">已完成支付</el-button>
									<el-button @click.native="Visible = false">未完成支付</el-button>
								</div>
						</el-dialog>
    </el-row>
</template>
<style>
	.el-table .cell{text-align: left !important;}
	.el-table .info-row {
			color: red;
	}
	.zhifudis{display: flex;justify-content: center;}
	.tishizhfu{font-size: 16px;color: black;}
</style>

<script>

    import {
			getReceivablePlanList,
			withdrawReceivable,
			zhanghaoSavePurchaseContract,
			
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
                filters: {
                    htno: '',
                    zh: '',
                    xm: '',
                    startdate: '',
                    enddate: '',
                    status: '',
										duizhangstatus:'',
										
                },
								options:[
									{value:1, label:'未付款',},
									{value:2, label:'支付成功',},
									{value:3, label:'支付失败',},
									{value:4, label:'已撤回',},
								],
								options2:[
									{value:1, label:'支付成功',},
									{value:2, label:'支付失败',},
								],
                optionszt: [
                    {
                        value: 0,
                        label: '未提交'
                    }, {
                        value: 1,
                        label: '已提交'
                    },
                    {
                        value: 2,
                        label: '已完成'
                    }, {
                        value: 3,
                        label: '部分已付'
                    },
                    {
                        value: 4,
                        label: '已驳回'
                    }
                ],
                //分页类数据
                total: 0,
                currentPage: 0,
                pageSize: 10,
                pageSizes: [10, 20, 30, 40, 50, 100],
                Receivable: [],
                DataSum: [],
                listLoading: false,
                sels: [],//列表选中列
                activeName2: 'second',
                addFormLoading: false,
                //被选中的权限
                checked: [],
								zhanghao:{
									duizhangtype:'1',
									huming:null,
									yinhang:null,
									zhanghao:null,
									jine:null,
								},
								zhanghaoRules:{
									duizhangtype: [
											{ required: true, message: '不能为空'}
									],
									jine: [
											{ required: true, message: '不能为空'}
									],
								},
								Visible:false,
								Vizhifusible:false,
								Loading:false,
            }
        },
        methods: {
            tableClassName(skdate, srstate){
                //return 'info-row';
                if (skdate < new Date() && srstate != 2) {
                    return 'info-row';
                } else {
                    return '';
                }
            },
            ztin(row, arr){
                var status = arr.indexOf(row.paystatus);
                if (status > -1) {
									if(row.paystatus == 3){
										if(row.duizhangstatus == 2 || row.duizhangstatus == null){
											return true;
										}else{
											return false;
										}
									}else{
										return true;
									}
                } else {
                    return false;
                }
            },
            //标签切换时
            handleClick(tab, event) {

                if (tab.index == 0) {
                    this.filters.zt = '';
                    this.getReceivable();
                } else if (tab.index == 1) {
                    this.filters.zt = 1;
                    this.getReceivable();
                } else if (tab.index == 2) {
                    this.filters.zt = 2;
                    this.getReceivable();
                } else if (tab.index == 3) {
                    this.filters.zt = 3;
                    this.getReceivable();
                } else if (tab.index == 4) {
                    this.filters.zt = 4;
                    this.getReceivable();
                }
            },
            formatFKType(row, column){
                let status = [];
                status[0] = '押金';
                status[1] = '房租';
                return status[row.kemu];
            },
            //状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[1] = '未付款';
                status[2] = '支付成功';
                status[3] = '支付失败';
								status[4] = '已撤回';
                return status[row.paystatus];
            },
						//对账状态显示转换
						formatState2: function (row, column) {
								let status = [];
								status[1] = '支付成功';
								status[2] = '支付失败';
								return status[row.duizhangstatus];
						},
						//类型显示转换
						formatType: function (row, column) {
								let status = [];
								status[0] = '押金';
								status[1] = '房租';
								return status[row.srstate];
						},
            //时间戳转日期格式
            changeDate(skdate){
                if(skdate!=null) {
                    var newDate = new Date();
                    newDate.setTime(skdate);
                    return newDate.toLocaleDateString()
                }
            },
            //页面跳转后
            handleCurrentChange(val) {
                this.page = val;
                this.getReceivable();
            },
            //更改每页显示数据
            handleSizeChange(val){
                this.pageSize = val;
                this.getReceivable();
            },
            //获取应收款列表1
            getReceivable() {
                let para = {
                    page: this.page,
                    size: this.pageSize,
                    htno: this.filters.htno,
                    xm: this.filters.xm,
                    zt: this.filters.status,
										dzzt: this.filters.duizhangstatus,
                    sdate: this.filters.startdate,
                    edate: this.filters.enddate, 
                };
                this.listLoading = true;
                getReceivablePlanList(para).then((res) => {
                    this.total = res.data.total;
                    this.Receivable = res.data.data;
                    // this.DataSum = res.data.dataSum;
//                     if (res.data.dataSum == null) {
//                         this.DataSum =
//                             {sumMoney: 0, tijiaoMoney: 0, shijiMoney: 0};
//                     }
                    this.listLoading = false;
                });
            },
						
						//充值
						addContract(){
								this.Visible = true;
								this.zhanghao= {
									duizhangtype:'1',
									huming:null,
									yinhang:null,
									zhanghao:null,
									jine:null,
								}
						},
						handleEnd(index,row){
							this.$refs.zhanghao.validate((valid) => {
									if(valid){
											let para1 = this.zhanghao;
											this.Visible = false;
											zhanghaoSavePurchaseContract(para1).then((res)=>{
													if(res.data.code!='200'){
															this.$message({
																	message: '数据没有保存成功',
																	type: 'error'
															});
													}
													this.resetForm('zhanghao');
											});
									}
							});

						},
						//支付的弹框
						handleZhifuEnd(){
							this.Vizhifusible = true;
						},
            //撤回
            handleRokeBack: function (index, row) {
                // this.rokeBackFormVisible = true;
//                 this.addForm = Object.assign({}, row);
//                 this.addForm = {
//                     tCwSrId: row.tCwSrId,
//                     tijiaomoney: '',
//                 };
								this.$confirm('确认将这条应收计划撤回吗?', '提示', {
										type: 'warning'
								}).then(() => {
										this.addFormLoading = true;
										let para = { 
											tCwSrPlanId: row.tCwSrPlanId,
											tCwSrSubmitId:row.tCwSrSubmitId,
											paystatus: 4,
										};
										withdrawReceivable(para).then((res) => {
												this.addFormLoading = false;
												//NProgress.done();
												this.$message({
														message: '撤回成功',
														type: 'success'
												});
												this.getReceivable();
										});
								}).catch(() => {

								});
            },
						handleCollect: function (index, row) {
								// this.$router.push('/receivableCollect?id=' + row.tCwSrId + '&hetongid=' + row.hetongid);
								this.$router.push('/receivableModify?id=' + row.tCwSrId + '&hetongid=' + row.hetongid);
						},

            selsChange: function (sels) {
                this.sels = sels;
            },

        },
        mounted() {
            this.page = 1;
            this.getReceivable();
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