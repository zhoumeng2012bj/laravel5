<template>
    <el-row>
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px">
            <el-form-item label="户名:">
                <el-input v-model="filters.accountName" placeholder="请输入户名"></el-input>
            </el-form-item>
						<el-form-item label="支付状态">
							<el-select v-model="filters.rechargeStatus" @change="getReceivable" placeholder="请输入支付状态">
								<el-option
										v-for="item in options"
										:key="item.value"
										:label="item.label"
										:value="item.value">
								</el-option>
							</el-select>
						</el-form-item>
						<el-form-item label="对账状态">
							<el-select v-model="filters.payStatus" @change="getReceivable" placeholder="请输入对账状态">
								<el-option
										v-for="item in options2"
										:key="item.value"
										:label="item.label"
										:value="item.value">
								</el-option>
							</el-select>
						</el-form-item>
						<el-form-item label="操作状态">
							<el-select v-model="filters.maintainStatus" @change="getReceivable" placeholder="请输入操作状态">
								<el-option
										v-for="item in options3"
										:key="item.value"
										:label="item.label"
										:value="item.value">
								</el-option>
							</el-select>
						</el-form-item><br />
						<el-form-item label="充值时间:">
								<el-date-picker type="date" placeholder="请选择开始日期" v-model="filters.sDate">
								</el-date-picker>
						</el-form-item>
						<el-form-item label="至">
								<el-date-picker type="date" placeholder="请选择结束日期" v-model="filters.eDate">
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
                <el-table-column prop="rechargeAmount" label="充值金额" width="110">
                </el-table-column>
                <el-table-column prop="recharge_uname" label="操作人" width="80">
                </el-table-column>
								<el-table-column prop="rechargeDate" label="充值时间" :formatter="changeDate1">
								</el-table-column>
								<el-table-column prop="rechargeMode" label="类型" :formatter="formatFKType" width="100">
								</el-table-column>
								<el-table-column prop="accountName" label="户名">
								</el-table-column>
								<el-table-column prop="rechargeBank" label="银行" width="110">
								</el-table-column>
                <el-table-column prop="cardNumber" label="卡号" width="190">
                </el-table-column>
                <el-table-column prop="rechargeStatus" label="支付状态" :formatter="formatState" width="100">
                </el-table-column>
								<el-table-column prop="payStatus" label="对账状态" :formatter="formatState2" width="100">
								</el-table-column>
								<el-table-column prop="maintainStatus" label="操作状态" :formatter="formatState3" width="100">
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
									<el-form-item label="账户类型" prop="rechargeMode">
											<el-radio-group v-model="zhanghao.rechargeMode" >
													<el-radio label="2">公户</el-radio>
													<el-radio label="1">私户</el-radio>
											</el-radio-group>
									</el-form-item>
									<el-row>
											<el-col :span="15">
													<el-form-item label="户名">
															<el-input v-model="zhanghao.accountName"></el-input>
													</el-form-item>
											</el-col>
									</el-row>
									<el-row>
											<el-col :span="15">
													<el-form-item label="银行">
															<el-input v-model="zhanghao.rechargeBank"></el-input>
													</el-form-item>
											</el-col>
									</el-row>
									<el-row>
											<el-col :span="15">
													<el-form-item label="账号">
															<input v-model="zhanghao.cardNumber" style="-webkit-appearance: none;
																			-moz-appearance: none;
																			appearance: none;
																			background-color: #fff;
																			background-image: none;
																			border-radius: 4px;
																			border: 1px solid #bfcbd9;
																			box-sizing: border-box;
																			color: #1f2d3d;
																			font-size: inherit;
																			height: 36px;
																			line-height: 1;
																			outline: 0;
																			padding: 3px 10px;
																			transition: border-color .2s cubic-bezier(.645,.045,.355,1);
																			width: 100%;" onkeyup="this.value=this.value.replace(/\D/g,'').replace(/....(?!$)/g,'$& ')">
													</el-form-item>
											</el-col>
									</el-row>
									<el-row>
											<el-col :span="15">
													<el-form-item label="金额" prop="rechargeAmount" required>
															<el-input v-model="zhanghao.rechargeAmount" ></el-input>
													</el-form-item>
											</el-col>
									</el-row>
								</el-form>
								<div slot="footer" class="dialog-footer">
										<el-button @click.native="Visible = false">取消</el-button>
										<el-button type="primary" @click.native="handleEnd" :loading="Loading">提交</el-button>
								</div>
						</el-dialog>
						<el-dialog size="tiny" title="确认支付" v-model="Vizhifusible" :close-on-click-modal="false" :before-close="handleZhifuEnd" label-width="120px">
								<div class="zhifudis tishizhfu">请在打开的支付页面中完成支付！</div><br />
								<div class="zhifudis">支付完成前请不要关闭此窗口</div>
								<div slot="footer" class="dialog-footer">
									<el-button type="primary" @click.native="handleZhifuEnd" :loading="Loading">已完成支付</el-button>
									<el-button @click.native="handleZhifuEnd">未完成支付</el-button>
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
			withdrawReceivable,
			saveRecharge,
			getRechargeList,
			
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
                filters: {
                    accountName: '',
                    rechargeStatus: '',
                    sDate: '',
                    eDate: '',
                    maintainStatus: '',
										payStatus:'',
                },
								options:[
									{value:1, label:'未付款',},
									{value:2, label:'支付成功',},
									{value:3, label:'支付失败',},
								],
								options2:[
									{value:1, label:'对账成功',},
									{value:2, label:'对账失败',},
								],
								options3:[
									{value:1, label:'未处理',},
									{value:2, label:'已处理',},
									{value:3, label:'无需处理',},
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
									rechargeMode:'2',
									accountName:null,
									rechargeBank:null,
									cardNumber:null,
									rechargeAmount:null,
								},
								zhanghaoRules:{
									rechargeMode: [
											{ required: true, message: '不能为空'}
									],
									rechargeAmount: [
											{ required: true, message: '不能为空'},
											// { type: 'number', message: '必须为数字'},
									],
								},
								Visible:false,
								Vizhifusible:false,
								Loading:false,
            }
        },
        methods: {
            tableClassName(skdate, srstate){
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
                status[1] = '私户';
                status[2] = '公户';
                return status[row.rechargeMode];
            },
            //状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[1] = '未付款';
                status[2] = '支付成功';
                status[3] = '支付失败';
                return status[row.rechargeStatus];
            },
						//对账状态显示转换
						formatState2: function (row, column) {
								let status = [];
								status[1] = '对账成功';
								status[2] = ' 对账失败';
								return status[row.payStatus];
						},
						//对账状态显示转换
						formatState3: function (row, column) {
								let status = [];
								status[1] = '未处理';
								status[2] = '已处理';
								status[3] = '无需处理';
								return status[row.maintainStatus];
						},
						//时间戳转日期格式
						changeDate1(value){
								var newDate = new Date();
								newDate.setTime(value.rechargeDate);
								return newDate.toLocaleString()
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
										
										accountName: this.filters.accountName,
										sDate: this.filters.sDate,
										eDate: this.filters.eDate,
										rechargeStatus: this.filters.rechargeStatus,
										payStatus: this.filters.payStatus,
										maintainStatus: this.filters.maintainStatus,
                };
                this.listLoading = true;
                getRechargeList(para).then((res) => {
                    this.total = res.data.total;
                    this.Receivable = res.data.data;
                    this.listLoading = false;
                });
            },
						
						//充值
						addContract(){
								this.Visible = true;
								this.zhanghao= {
									rechargeMode:'2',
									accountName:null,
									rechargeBank:null,
									cardNumber:null,
									rechargeAmount:null,
								}
						},
						handleEnd(index,row){
							this.$refs.zhanghao.validate((valid) => {
									if(valid){
											let para1 = this.zhanghao;
											this.Visible = false;
											saveRecharge(para1).then((res)=>{
													if(res.data.code!='200'){
															this.$message({
																	message: '数据没有保存成功',
																	type: 'error'
															});
													}
													if(res.data.code=='200'){
														window.open("http://192.168.1.47/recharge?recharge_mode=" + this.zhanghao.rechargeMode + "&recharge_id=" + res.data.data); 
														this.resetForm('zhanghao');
														this.Vizhifusible = true;
													}
											});
									}
							});

						},
						//支付的弹框
						handleZhifuEnd(){
							this.Vizhifusible = false;
							this.getReceivable();
						},
						resetForm(formName) {
							this.$refs[formName].resetFields();
						},
            //撤回
            handleRokeBack: function (index, row) {
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