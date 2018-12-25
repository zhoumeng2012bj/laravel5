<template>
	<el-row>
		<el-table height="500" :data="financeReceivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
		 style="width: 100%;">
			<el-table-column prop="hetongbianhao" label="合同编号">
			</el-table-column>
			<el-table-column prop="xiangmu" label="项目">
			</el-table-column>
			<el-table-column prop="kemu" label="类型" :formatter="formatState">
			</el-table-column>
			<el-table-column prop="zhouqi" label="周期">
			</el-table-column>
			<el-table-column prop="shoukuanDate" label="收款日期" :formatter="changeDate1">
			</el-table-column>
			<el-table-column prop="renlingmoney" label="认领金额">
			</el-table-column>
			<el-table-column prop="createUserName" label="认领人">
			</el-table-column>
			<el-table-column prop="createTime" label="认领时间" :formatter="changeDate2">
			</el-table-column>
			<el-table-column label="操作" width="120">
				<template slot-scope="scope">
					<el-dropdown menu-align="start">
						<el-button type="primary" size="normal" splitButton="true">
							操作<i class="el-icon-caret-bottom el-icon--right"></i>
						</el-button>
						<el-dropdown-menu slot="dropdown">
							<el-dropdown-item v-if="fun('RCLC')">
								<el-button @click="handleView(scope.$index, scope.row)">取消认领</el-button>
							</el-dropdown-item>
						</el-dropdown-menu>
					</el-dropdown>
				</template>
			</el-table-column>
		</el-table>
		<div style="margin-top:30px"></div>
		<!-- 分页-->
		<el-col :span="24" class="toolbar">
			<el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage"
			 :page-size="10" layout="total, sizes, prev, pager, next, jumper" :total=total style="float:right">
			</el-pagination>
		</el-col>
	</el-row>
</template>
<style>
	.el-dialog .el-table__body tr.current-row>td {
		background: rgba(26, 135, 249, 0.75) !important;
	}
</style>
<script>
	import {
		claimRecord,
		getReceivableClaimRecoedList,



	} from '../../api/api';
	import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
	export default {
		components: {
			ElForm
		},
		data() {
			return {
				id: '',
				Receivable: [], //应收列表
				pagerl:1,
				filters:{
						sdate: '',
						edate:'',
						fkzh:'',
						zt:'',
						contractNo:'',
						xm:'',
						zg:'',
				},
				options: [{
					value: 1,
					label: '押金'
				}, {
					value: 2,
					label: '房租'
				}, ],
				activeName2: 'first',
				//分页类数据
				total: 0,
				currentPage: 0,
				pageSize: 10,
				pageSizes: [10, 20, 30, 40, 50, 100],
				financeReceivable: [],
				ReceivableRL: [],
				RLlistLoading: false,
				listLoading: false,
				sels: [], //列表选中列
				totalrl: 0,
				currentPagerl: 0,
				pageSizerl: 10,
				renlingData: {
					tCwSrId: null,
					tCwSrCaiwuId: null,
				},
			}
		},
		methods: {
			handleRokeBack(row) {
				this.rokeBackFormVisible = true;
				this.renlingData.tCwSrCaiwuId = row.tCwSrCaiwuId;
				this.pagerl = 1;
				this.getReceivableRL();
			},
			formatFKType(row, column) {
				let status = [];
				status[0] = '押金';
				status[1] = '房租';
				return status[row.kemu];
			},
			//支付状态显示转换
			formatState: function(row, column) {
				let status = [];
				status[0] = '未认领';
				status[1] = '认领完成';
				status[2] = '部分认领';
				return status[row.kemu];
			},
			//认领支付状态显示转换
			formatRLState: function(row, column) {
				let status = [];
				status[0] = '未提交';
				status[1] = '已提交';
				status[2] = '已完成';
				status[3] = '部分已付';
				status[4] = '已驳回';
				return status[row.srstate];
			},
			//银行账号转换
			formatskyh: function(row, column) {
				return row.fukuanyinhang + '\r账号' + row.fukuanzhanghao;
			},
			//时间戳转日期格式
			changeDate1(row, column) {
				if (row.shoukuanDate != null) {
					var newDate = new Date();
					newDate.setTime(row.shoukuanDate);
					return newDate.toLocaleDateString()
				}
			},
			//时间戳转日期格式
			changeDate2(row, column) {
				if (row.createTime != null) {
					var newDate = new Date();
					newDate.setTime(row.createTime);
					return newDate.toLocaleDateString()
				}
			},
			handleView(index, row){
				this.$confirm('确认认领吗？', '提示', {}).then(() => {
					let para = {
						id: row.t_cw_sr_renling_id,
					};
					claimRecord(para).then((res)=>{
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
				});

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
			//页面跳转后
			handleCurrentChangerl(val) {
				this.pagerl = val;
				this.getReceivableRL();
			},
			//更改每页显示数据
			handleSizeChangerl(val) {
				this.pageSizerl = val;
				this.getReceivableRL();
			},
			//获取实收款列表
			getReceivable() {
				let para = {
					page: this.pagerl,
					pageSize: this.pageSizerl,
					id: this.id,
				};
				this.listLoading = true;
				getReceivableClaimRecoedList(para).then((res) => {
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
					zt: 10,
				};
				this.RLlistLoading = true;
				getReceivableClaimList(para).then((res) => {
					this.totalrl = res.data.total;
					this.ReceivableRL = res.data.data;
					this.RLlistLoading = false;
				});
			},
			//选中以后
			handleCurrentChange1(val) {
				this.renlingData.tCwSrId = val.tCwSrId;

			},
			selsChange(val, row) {
				this.$refs.multipleTable.clearSelection();
				this.$refs.multipleTable.toggleRowSelection(row);
			},
		},
		mounted() {
			this.page = 1;
			this.id = this.$route.query.id;
			this.getReceivable();
		}
	}
</script>
