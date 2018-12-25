<template>
	<div>
		<div>
			<el-button type="primary" class="el-icon-plus" @click="addFollow"> 写跟进</el-button>
		</div>
		<el-dialog title="写跟进" :visible.sync="dialogFormVisibleFollow">
			<el-form :model="formFollow" :rules="addformFollowRules" ref="formFollow">
				<el-form-item label="跟进日期" :label-width="formLabelWidthFollow" prop="followdate">
					<div class="block">
						<el-date-picker
							v-model="formFollow.followdate"
							type="date"
							placeholder="选择日期">
						</el-date-picker>
					</div>
				</el-form-item>
				<el-form-item label="跟进内容" :label-width="formLabelWidthFollow" prop="followcontext">
					<el-input type="textarea" v-model="formFollow.followcontext" placeholder="请输入跟进内容"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="cancelFollow">取 消</el-button>
				<el-button type="primary" @click="formFollowsure">确 定</el-button>
			</div>
		</el-dialog>
		<el-table
			:data="tableData5"
		  v-loading="listLoading" 
			element-loading-text="拼命加载中"
			style="width: 100%"
		  class="listtab">
			<el-table-column type="expand">
				<template slot-scope="props">
					<el-form label-position="left" inline class="demo-table-expand">
						<el-form-item label="">
							<span>{{ props.row.followcontext}}</span>
						</el-form-item>
					</el-form>
				</template>
			</el-table-column>
			<el-table-column type="index" width="80" label="序号">
			</el-table-column>
			<el-table-column
				label="跟进时间"
				prop="followdate">
			</el-table-column>
			<el-table-column
				label="跟进人"
				prop="createusername">
			</el-table-column>
			<el-table-column
				label="信息录入时间"
				prop="createtime">
			</el-table-column>
		</el-table>
		<div class="block pagingcss">
			<el-pagination
				@size-change="handleSizeChange"
				@current-change="handleCurrentChange"
				:current-page="currentPage"
				:page-sizes="[10,20,30,40,50,100]"
				:page-size="pageSize"
				layout="total, sizes, prev, pager, next, jumper"
				:total="total"
				>
			</el-pagination>
		</div>
	</div>
</template>

<style>
  .demo-table-expand {
    font-size: 0;
  }
  .demo-table-expand label {
    width: 90px;
    color: #99a9bf;
  }
  .demo-table-expand .el-form-item {
    margin-right: 0;
    margin-bottom: 0;
    width: 50%;
  }
	.el-textarea__inner{
		height: 120px;
	}
	.listtab{margin-top: 20px;}
	.pagingcss{margin-top: 40px;display: flex;justify-content: flex-end;}
	.el-table th, .el-table td>div{text-align: center;}
	.demo-table-expand .el-form-item{width: 100%;word-break: break-all;overflow: auto;}
</style>

<script>
	import {optiplanPurchaseContract,viewoptimiPurchaseContract,formfollowPurchaseContract,vieformfollowPurchaseContract,violatePurchaseContract,addviolatePurchaseContract} from  '../../api/api';
  export default {
    data() {
      return {
				dialogFormVisibleFollow: false,
				addformFollowRules:{
					followdate:[
						{required: true, message: '不能为空'}
					],
					followcontext:[
						{required: true, message: '不能为空'}
					],
				},
				formFollow: {
					id: '',
					followcontext: '',///跟进内容
					followdate:null,//制定方案日期
					hetongid: null,//合同id
					hetongtid: 0,//合同类型
				},
        formLabelWidth: '120px',
				formLabelWidthFollow: '120px',
				bianhao:null,
				hetongid:'',
        tableData5: [{
          optimizationnum: '',
          followdate: '',
          createusername: '',
          createtime: '',
          followcontext: '',
					sernumber:1
        }],
				dialogFormVisibleView: false,
				tableDataView: [{
					optimizationnum: '',
					plandate: '',
					createusername: '',
					plancontext: '',
				}],
				listLoading: false,
				total:0,
				currentPage:1,
				pageSize:10,
				zhuangtai:'',
				
      }
    },
		methods:{
			getdata(){
				this.purchaseContractList();
			},
			handleSizeChange(val) {
				this.listLoading = true;
				this.pageSize =val;
				this.purchaseContractList();
			},
			handleCurrentChange(val) {
				this.listLoading = true;
				this.tableData5 = [];
				this.currentPage = val;
				this.purchaseContractList();
			},
			//获取列表
			purchaseContractList() {
				let para1 = {
					"htid":this.hetongid,
					"httid":0,
					"currentPage":this.currentPage,
					"pageSize":this.pageSize,
				};
				violatePurchaseContract(para1).then((res)=>{
						if(res.data.code == 200)　{
							this.total = res.data.total;
							for(var i=0;i<res.data.data.length;i++){
								res.data.data[i].followdate = this.followchage(res.data.data[i].followdate);
								res.data.data[i].createtime = this.followchage(res.data.data[i].createtime);
							}
							this.tableData5 = res.data.data;
							this.listLoading = false;
						}else{
							this.$message({
									message:res.data.msg,
									type:'error'
							})
						}
				})
			},
			addOptimization(){
				
				this.dialogFormVisible = true;
			},
			addFollow(){
				this.dialogFormVisibleFollow = true;
			},
			cancelFollow(){
				this.$refs.formFollow.resetFields();
				this.formFollow.optimizationnum = '';
				this.formFollow.followcontext = '';
				this.formFollow.followdate = '';
				this.dialogFormVisibleFollow = false;
			},
			formFollowsure(){
				this.$refs.formFollow.validate((valid) => {
					if(valid){
						this.formFollow.hetongid = this.hetongid;
						let para = Object.assign({}, this.formFollow);
						addviolatePurchaseContract(para).then((res)=>{
								if(res.data.code == 200)　{
									this.dialogFormVisibleFollow = false;
									location.reload();
								}else{
									this.$message({
											message:res.data.msg,
											type:'error'
									})
								}
						})
					}
				})
			},
			//将中国标准时间转化为yyy-mmm-ddd格式的日期
			followchage(nS){
				var datetime=new Date(parseInt(nS)).toLocaleString().replace(/:\d{1,2}$/,' ');
				return datetime.replace('/','-').replace('/','-');
			},
			
			
			
			
			
			
			
			
		},
		mounted(){
			this.bianhao = this.$route.query.bianhao;
			this.hetongid = this.$route.query.hetongid;
			this.zhuangtai = this.$route.query.status;
			this.listLoading = true;
			this.getdata();
		}
  }
</script>