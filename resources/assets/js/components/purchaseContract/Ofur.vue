<template>
	<div>
		<div>
			<el-button type="primary" v-if="ztin([6,9,10])" class="el-icon-plus" @click="addOptimization"> 新增优化方案</el-button>
			<el-button type="primary" v-if="viewseexind" class="el-icon-view" @click="viewOptimi"> 查看优化方案</el-button>
			<el-button type="primary" v-if="ztin([6,9,10])" class="el-icon-plus" @click="addFollow"> 写跟进</el-button>
		</div>
		<el-dialog title="新增优化方案" :visible.sync="dialogFormVisible">
			<el-form :model="form" :rules="addformRules" ref="form">
				<el-form-item label="方案制定日期" prop="plandate" :label-width="formLabelWidth">
						<el-date-picker type="date" placeholder="请输入方案制定日期"v-model="form.plandate">
						</el-date-picker>
				</el-form-item>
				<el-form-item label="优化次数" prop="optimizationnum" :label-width="formLabelWidth">
					<el-select v-model="form.optimizationnum" placeholder="请选择优化次数">
						<el-option label="第一次优化" value='1'></el-option>
						<el-option label="第二次优化" value='2'></el-option>
						<el-option label="第三次优化" value='3'></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="方案具体内容" :label-width="formLabelWidth" prop="plancontext">
					<el-input type="textarea" v-model="form.plancontext" placeholder="请输入方案具体内容"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sure">确 定</el-button>
			</div>
		</el-dialog>
		<el-dialog title="优化方案" :visible.sync="dialogFormVisibleView">
			<el-table
				:data="tableDataView"
				style="width: 100%"
				class="listtab">
				<el-table-column type="expand">
					<template slot-scope="props">
						<el-form label-position="left" inline class="demo-table-expand">
							<el-form-item label="">
								<span>{{ props.row.plancontext}}</span>
							</el-form-item>
						</el-form>
					</template>
				</el-table-column>
				<el-table-column
					label="优化次数"
					prop="optimizationnum">
				</el-table-column>
				<el-table-column
					label="方案制定时间"
					prop="plandate">
				</el-table-column>
				<el-table-column
					label="方案制定人"
					prop="createusername">
				</el-table-column>
			</el-table>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormVisibleView = false">关 闭</el-button>
			</div>
		</el-dialog>
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
				<el-form-item label="优化次数" prop="optimizationnum" :label-width="formLabelWidthFollow">
					<el-select v-model="formFollow.optimizationnum" placeholder="请选择优化次数">
						<el-option label="第一次优化" value="1"></el-option>
						<el-option label="第二次优化" value="2"></el-option>
						<el-option label="第三次优化" value="3"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="跟进内容" prop="followcontext" :label-width="formLabelWidthFollow">
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
			<el-table-column
			   width="140" 
				label="优化次数"
				prop="optimizationnum" style="">
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
	import {optiplanPurchaseContract,viewoptimiPurchaseContract,formfollowPurchaseContract,vieformfollowPurchaseContract,youhuaPurchaseContract} from  '../../api/api';
  export default {
    data() {
      return {
        dialogFormVisible: false,
        form: { 
					id: '',
					optimizationnum: '',//优化次数
					plancontext: '',///跟进内容
					plandate:null,//制定方案日期
					hetongid: null,//合同id
					hetongtid: 0,//合同类型
        },
				addformRules:{
					optimizationnum:[
						{required: true, message: '不能为空'}
					],
					plandate:[
						{required: true, message: '不能为空'}
					],
					plancontext:[
						{required: true, message: '不能为空'}
					],
				},
				dialogFormVisibleFollow: false,
				addformFollowRules:{
					optimizationnum:[
						{required: true, message: '不能为空'}
					],
					followdate:[
						{required: true, message: '不能为空'}
					],
					followcontext:[
						{required: true, message: '不能为空'}
					],
				},
				formFollow: {
					id: '',
					optimizationnum: '',//优化次数
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
				viewseexind:false,
      }
    },
		methods:{
			getdata(){
				this.purchaseContractList();
				let para2 = {
					"htid":this.hetongid,
					"httid":0,
				};
				// let para = Object.assign({}, this.form);
				viewoptimiPurchaseContract(para2).then((res)=>{
						if(res.data.code == 200)　{
							if(res.data.data.length > 0){
								this.viewseexind = true;
							}else{
								this.viewseexind = false;
							}
							for(var i=0;i<res.data.data.length;i++){
								res.data.data[i].plandate = this.followchage(res.data.data[i].plandate);
								res.data.data[i].optimizationnum = this.youhuacishu(res.data.data[i].optimizationnum);
							}
							this.tableDataView = res.data.data;
						}else{
							this.$message({
									message:res.data.msg,
									type:'error'
							})
						}
				});
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
				vieformfollowPurchaseContract(para1).then((res)=>{
						if(res.data.code == 200)　{
							this.total = res.data.total;
							for(var i=0;i<res.data.data.length;i++){
								res.data.data[i].followdate = this.timeconversion(res.data.data[i].followdate);
								res.data.data[i].createtime = this.followchage(res.data.data[i].createtime);
								res.data.data[i].optimizationnum = this.youhuacishu(res.data.data[i].optimizationnum);
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
			cancel(){
				this.$refs.form.resetFields();//将所有字段值重置为初始值并移除校验结果
				this.form.optimizationnum = '';
				this.form.plancontext = '';
				this.form.plandate = '';
				this.dialogFormVisible = false;
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
			viewOptimi(){
				let para = {
					"htid":this.hetongid,
					"httid":0,
				};
				// let para = Object.assign({}, this.form);
				viewoptimiPurchaseContract(para).then((res)=>{
						if(res.data.code == 200)　{
							for(var i=0;i<res.data.data.length;i++){
								res.data.data[i].plandate = this.followchage(res.data.data[i].plandate);
								res.data.data[i].optimizationnum = this.youhuacishu(res.data.data[i].optimizationnum);
							}
							this.tableDataView = res.data.data;
							this.dialogFormVisibleView = true;
						}else{
							this.$message({
									message:res.data.msg,
									type:'error'
							})
						}
				});
			},
			sure(){
				this.$refs.form.validate((valid) => {//对整个表单进行校验的方法
					if(valid){
						var stts = true;
						for(var i=0;i<this.tableDataView.length;i++){
							if(this.tableDataView[i].optimizationnum == this.form.optimizationnum){
								stts = false;
								this.$message({
										message:"每次优化只可以制定一次方案！",
										type:'error'
								})
							}else{
								stts = true;
							}
						}
						if(stts){
							this.dialogFormVisible = false;
							// 				let para = {
							// 					"optimizationnum":this.form.optimiTimes,
							// 					"plancontext":this.form.content,
							// 					"plandate":this.value1,
							// 					"hetongid":this.hetongid,
							// 					"hetongtid":1,
							// 					"createuser":1,
							// 					"createusername":"123"
							// 				};
							this.form.hetongid = this.hetongid;
							//这块是不用再去一个一个的赋值了（向接口参数中），以为我定义的时候和接口参数的变量名称一模一样
							let para = Object.assign({}, this.form);
							
							optiplanPurchaseContract(para).then((res)=>{
									if(res.data.code == 200)　{
										this.form.optimizationnum = '';
										this.form.plancontext = '';
										this.form.plandate = '';
										this.viewseexind = true;
									}else{
										this.$message({
												message:res.data.msg,
												type:'error'
										})
									}
							})
						}
					}
				});
			},
			formFollowsure(){
				this.$refs.formFollow.validate((valid) => {
					if(valid){
						this.formFollow.hetongid = this.hetongid;
						//这块是不用再去一个一个的赋值了（向接口参数中），以为我定义的时候和接口参数的变量名称一模一样
						let para = Object.assign({}, this.formFollow);
						let para1 = {
							id:this.hetongid,
						};
						formfollowPurchaseContract(para).then((res)=>{
								if(res.data.code == 200)　{
									youhuaPurchaseContract(para1).then((res)=>{
										if(res.data.code=='200'){
											this.dialogFormVisibleFollow = false;
											location.reload();
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
			//时间戳转化为日期
			timeconversion(timestamp) {
				var date = new Date(timestamp); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
				var Y = date.getFullYear() + '/';
				var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '/';
				var D = date.getDate() + ' ';
				var h = date.getHours() + ':';
				var m = date.getMinutes() + ':';
				var s = date.getSeconds();
				return Y + M + D;
			},
			youhuacishu(num){
				if(num == 1){
					return "第一次优化";
				}
				if(num == 2){
					return "第二次优化";
				}
				if(num == 3){
					return "第三次优化";
				}
			},
			ztin(arr){
				var status = arr.indexOf(this.zhuangtai);
				if(status>-1){
					return false;
				}else{
					return true;
				}
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