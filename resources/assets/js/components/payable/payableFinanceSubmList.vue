<template>
	<el-row>
		<el-row class="rowspacing">
			<el-table height="500" :data="financeReceivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
			 style="width: 100%;">
				<el-table-column prop="faqiren" label="修改人">
				</el-table-column>
				<el-table-column prop="tijiaodate" label="修改时间" :formatter="changeDate1">
				</el-table-column>
				<el-table-column prop="wuye" label="代缴物业">
				</el-table-column>
				<el-table-column prop="gongnuan" label="代缴供暖">
				</el-table-column>
				<el-table-column prop="zhileng" label="代缴制冷">
				</el-table-column>
				<el-table-column prop="kongtiaozhuanhuan" label="代缴空调转换">
				</el-table-column>
				<el-table-column prop="weixiu" label="代缴维修费">
				</el-table-column>
				<el-table-column prop="qita" label="代缴其他">
				</el-table-column>
				<el-table-column prop="beizhu" label="财务备注">
				</el-table-column>
			</el-table>
		</el-row>
	</el-row>
</template>
<style>
	.rowspacing {
		margin-top: 20px;
	}

	.el-table .cell {
		text-align: center;
	}

	.el-textarea__inner {
		height: 200px;
	}
</style>
<script>
	import {

		getPayableDeduction,

	} from '../../api/api';
	export default {
		data() {
			return {
				srid: '',

				listLoading: false,

				financeReceivable: [],


				sels: [], //列表选中列
				Receivable: [], //应收列表



				beizhu: null,
			}
		},
		methods: {
			//时间戳转日期格式
			changeDate(row, column) {
				var newDate = new Date();
				newDate.setTime(row.tianjiadate);
				if (row.tianjiadate != null) {
					return newDate.toLocaleDateString()
				}
			},
			selsChange: function(sels) {
				this.sels = sels;
			},

			resetForm(formName) {
				this.$refs[formName].resetFields();
			},
			tableRowClassName(row, index) {
				if (row.tCwSrCaiwuId === this.renlingData.tCwSrCaiwuId) {
					return 'info-row';
				} else {
					return '';
				}
			},
			//银行账号转换
			formatskyh: function(row, column) {
				return row.fukuanyinhang + '\r账号' + row.fukuanzhanghao;
			},
			//时间戳转日期格式
			changeDate1(value) {
				var newDate = new Date();
				newDate.setTime(value.tijiaodate);
				return newDate.toLocaleDateString()
			},
			//时间戳转日期格式
			changeDate2(row, column) {
				if (row.skdate != null) {
					var newDate = new Date();
					newDate.setTime(row.skdate);
					return newDate.toLocaleDateString()
				}
			},
			//时间戳转化为日期
			timeconversion(timestamp) {
				var date = new Date(timestamp * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
				var Y = date.getFullYear() + '/';
				var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '/';
				var D = date.getDate() + ' ';
				var h = date.getHours() + ':';
				var m = date.getMinutes() + ':';
				var s = date.getSeconds();
				return Y + M + D;
			},
			//获取实收款列表
			getReceivable() {
				let para = {
					id: this.srid,
				};
				this.listLoading = true;
				getPayableDeduction(para).then((res) => {
					this.financeReceivable = res.data.data;
					this.listLoading = false;
				});
			},
			//认领提交
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
			this.srid = this.$route.query.id;
			this.page = 1;
			this.getReceivable();
		}
	}
</script>
