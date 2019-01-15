<template>
	<el-row>
		<el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px">
			<el-form-item label="合同编号:">
				<el-input v-model="filters.htno" placeholder="请输入合同编号"></el-input>
			</el-form-item>
			<el-form-item label="项目名称:">
				<el-input v-model="filters.xm" placeholder="请输入项目名称"></el-input>
			</el-form-item>
			<el-form-item label="付款日期:">
				<el-date-picker type="date" placeholder="请选择开始日期" v-model="filters.startdate">
				</el-date-picker>
			</el-form-item>
			<el-form-item label="至">
				<el-date-picker type="date" placeholder="请选择结束日期" v-model="filters.enddate">
				</el-date-picker>
			</el-form-item>
			<el-form-item label="支付状态">
				<el-select v-model="filters.status" @change="getReceivable" placeholder="请输入支付状态">
					<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
					</el-option>
				</el-select>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" icon="search" v-on:click="getReceivable">搜索</el-button>
			</el-form-item>
		</el-form>
		<div class="totals_box">
			<p>
				<span style="color:red;font-size: 14px;">（注：红色日期表示付款已延期，请尽快处理）</span>
			</p>
			<p style="display: flex;align-items: center;">
				<el-upload style="padding: 5px;border-radius: 5px;" action="/payable/planImportExcel" :on-success="handleSuccess"
				 :show-file-list="false" :multiple="false" :on-error="handleError()" :before-upload="beforeUpload">
					<el-button type="primary">导入实付记录</el-button>
				</el-upload>
				<span style="display:inline-block;height:36px;">
					<el-button type="primary" tyle="padding: 5px;border-radius: 5px;" v-on:click="handleExcel">导出异常数据</el-button>
				</span>

			</p>
		</div>

		<el-table height="500" :data="Receivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
		 @selection-change="selsChange" style="width: 100%;">
			<el-table-column prop="hetongbianhao" label="合同编号">
			</el-table-column>
			<el-table-column prop="xiangmu" label="项目">
			</el-table-column>
			<el-table-column prop="fktype" label="类型" :formatter="formatFKType" width="100">
			</el-table-column>
			<el-table-column prop="zhouqi" label="周期">
			</el-table-column>
			<el-table-column prop="fukuanmoney" label="实际应付" width="110">
			</el-table-column>
			<el-table-column prop="shifumoney" label="实付金额" width="110">
			</el-table-column>
			<el-table-column prop="shifudate" label="实付日期" width="150">
				<template slot-scope="scope">
					<span :class="tableClassName(scope.row.shifudate,scope.row.zhifustate)"> {{ changeDate(scope.row.shifudate)
														}}</span>
				</template>
			</el-table-column>
			<el-table-column prop="skinfo" label="收款账号" width="180">
			</el-table-column>
			<el-table-column prop="zhifustate" label="支付状态" :formatter="formatState" width="100">
			</el-table-column>
			<el-table-column prop="uuidNum" label="客户标识" width="220">
			</el-table-column>
		</el-table>
		<div style="margin-top:30px"></div>
		<!-- 分页-->
		<el-col :span="24" class="toolbar">
			<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
			 :page-size="10" layout="total, sizes, prev, pager, next, jumper" :total=total style="float:right">
			</el-pagination>
		</el-col>
		<!-- </el-tabs> -->
	</el-row>
</template>
<style>
	.el-table .cell {
		text-align: left !important;
	}

	.el-table .info-row {
		color: red;
	}
</style>

<script>
	import {
		actualPaymentPayable,
		withdrawReceivable,


	} from '../../api/api';
	import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
	export default {
		components: {
			ElForm
		},
		data() {
			return {
				filters: {
					htno: '',
					zh: '',
					xm: '',
					startdate: '',
					enddate: '',
					status: '',
					duizhangstatus: '',

				},
				options: [{
						value: 0,
						label: '未付款',
					},
					{
						value: 1,
						label: '付款成功',
					},
					{
						value: 2,
						label: '付款失败',
					},
				],
				options2: [{
						value: 1,
						label: '支付成功',
					},
					{
						value: 2,
						label: '支付失败',
					},
				],
				optionszt: [{
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
				sels: [], //列表选中列
				activeName2: 'second',
				addFormLoading: false,
				//被选中的权限
				checked: [],
			}
		},
		methods: {
			tableClassName(skdate, srstate) {
				//return 'info-row';
				if (skdate < new Date() && srstate != 1) {
					return 'info-row';
				} else {
					return '';
				}
			},
			ztin(row, arr) {
				var status = arr.indexOf(row.srstate);
				if (status > -1) {
					return true;
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
					this.filters.zt = 0;
					this.getReceivable();
				} else if (tab.index == 2) {
					this.filters.zt = 3;
					this.getReceivable();
				} else if (tab.index == 3) {
					this.filters.zt = 2;
					this.getReceivable();
				} else if (tab.index == 4) {
					this.filters.zt = 2;
					this.getReceivable();
				}
			},
			formatFKType(row, column) {
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
			formatState: function(row, column) {
				let status = [];
				status[0] = '未付款';
				status[1] = '付款成功';
				status[2] = '付款失败';
				return status[row.zhifustate];
			},
			//对账状态显示转换
			formatState2: function(row, column) {
				let status = [];
				status[0] = '无';
				status[1] = '付款成功';
				status[2] = '付款失败';
				return status[row.duizhangstate];
			},
			//类型显示转换
			formatType: function(row, column) {
				let status = [];
				status[0] = '押金';
				status[1] = '房租';
				return status[row.srstate];
			},
			//时间戳转日期格式
			changeDate(skdate) {
				if (skdate != null) {
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
			handleSizeChange(val) {
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
					zhifustate: this.filters.status,
					duizhangstate: this.filters.duizhangstatus,
					sdate: this.filters.startdate,
					edate: this.filters.enddate,
				};
				this.listLoading = true;
				actualPaymentPayable(para).then((res) => {
					this.total = res.data.total;
					this.Receivable = res.data.data;
					for(var i=0;i<this.Receivable.length;i++){
						if(this.Receivable[i].duizhangType == '1'){
							this.Receivable[i].uuidNum = "B" + this.Receivable[i].uuidNum;
						}else{
							this.Receivable[i].uuidNum = "C" + this.Receivable[i].uuidNum;
						}
					}
					this.listLoading = false;
				});
			},
			handleSuccess(response, file, fileList) {
				//响应值
				if (response.code == '300') {
					window.open(response.data);
					this.$message({
						message: '导入成功，处理失败数据已自动下载！',
						type: 'success'
					});
				} else {
					this.$message({
						message: response,
						type: 'success'
					});
				}
				this.getReceivable();
			},
			handleError(err, file, fileList) {
				if (err) {
					this.$message({
						message: '导入失败',
						type: 'error'
					});
				}
			},
			//获取导出
			handleExcel() {
				var htno = this.filters.htno;
				var xm = this.filters.xm = '';
				var sDate = this.filters.startdate == '' ? '' : this.filters.startdate.toLocaleDateString();
				var eDate = this.filters.enddate == '' ? '' : this.filters.enddate.toLocaleDateString();
				window.open("/payable/planErrorExportExcel?startdate=" + sDate + "&enddate=" + eDate + "&xm=" + xm + "&htno=" +
					htno);
			},
			beforeUpload(file) {
				//上传前配置
				let excelfileExtend = ".xls,.xlsx" //设置文件格式
				let fileExtend = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
				if (excelfileExtend.indexOf(fileExtend) <= -1) {
					this.$message.error('文件格式错误')
					return false
				}
				return true
			},
			//撤回
			handleRokeBack: function(index, row) {
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
			handleCollect: function(index, row) {
				// this.$router.push('/receivableCollect?id=' + row.tCwSrId + '&hetongid=' + row.hetongid);
				this.$router.push('/receivableModify?id=' + row.tCwSrId + '&hetongid=' + row.hetongid);
			},

			selsChange: function(sels) {
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
	.totals {
		margin-left: 20px;
	}

	.totals_box {
		display: flex;
		justify-content: space-between;
	}
</style>
