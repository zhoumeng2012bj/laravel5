<template>
	<div>
		<div>123132132</div>
		
		
		
		
		
	</div>
</template>

<style>
	
</style>

<script>
	import {} from  '../../api/api';
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
				dialogFormVisibleFollow: false,
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
								res.data.data[i].followdate = this.followchage(res.data.data[i].followdate);
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
				this.form.optimizationnum = '';
				this.form.plancontext = '';
				this.form.plandate = '';
				this.dialogFormVisible = false;
			},
			addFollow(){
				this.dialogFormVisibleFollow = true;
			},
			cancelFollow(){
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
				
			},
			formFollowsure(){
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
			},
			//将中国标准时间转化为yyy-mmm-ddd格式的日期
			followchage(nS){
				var datetime=new Date(parseInt(nS)).toLocaleString().replace(/:\d{1,2}$/,' ');
				return datetime.replace('/','-').replace('/','-');
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