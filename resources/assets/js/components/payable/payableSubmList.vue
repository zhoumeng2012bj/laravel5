<template>
    <el-row>
        <el-row>
            <div style="margin-top:30px;"></div>
            <li  style="list-style-type:none;">
                <el-row>
                    <el-col :span="6">
                        <span>合同编号：{{bianhao}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>项目：{{xiangmu}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span >月租金：{{yuezjin}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span >类型：{{type}}</span>
                    </el-col>
									</el-row>
									<el-row class="rowspacing">
                    <el-col :span="6">
                        <span>周期： {{hzouqi}}</span>
                    </el-col>
                    <el-col :span="6">
                    		<span>应付日期： {{ysdata}}</span>
                    </el-col>
					<el-col :span="6">
							<span>应付金额： {{ysjine}}</span>
					</el-col>
					<el-col :span="6">
							<span>实际应付： {{sjys}}</span>
					</el-col>
                </el-row>
            </li>
        </el-row>
        <el-row class="rowspacing">
            <el-table height="500" :data="financeReceivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"   style="width: 100%;">
							
				<el-table-column prop="tijiaodate" label="提交日期" :formatter="changeDate1">
				</el-table-column>
				<el-table-column prop="tijiaomoney" label="提交金额">
				</el-table-column>
				<el-table-column prop="daifumoney" label="代付金额">
				</el-table-column>
				<el-table-column prop="shifumoney" label="实付金额">
				</el-table-column>
				<el-table-column prop="faqiren" label="发起人" >
				</el-table-column>
				<el-table-column prop="fukuanzhanghao" label="收款账号"   width="180">
				</el-table-column>
				<el-table-column prop="nodeName" label="当前节点">
				</el-table-column>
				<el-table-column prop="status" label="审批状态"  :formatter="formatState2">
				</el-table-column>
				<el-table-column prop="zhifustate" label="支付状态"  :formatter="formatState1">
				</el-table-column>
				<!-- <el-table-column prop="zhuangtai" label="备注"  :formatter="formatState">
				</el-table-column> -->
				<el-table-column label="操作" width="120">
						 <template slot-scope="scope">
								 <el-dropdown   menu-align="start">
										 <el-button type="primary" size="normal" splitButton="true">
												 操作<i class="el-icon-caret-bottom el-icon--right"></i>
										 </el-button>
										 <el-dropdown-menu slot="dropdown" >
												 <el-dropdown-item><el-button @click="handleView(scope.$index, scope.row)">查看备注</el-button></el-dropdown-item>
												 <el-dropdown-item v-if="ztin(scope.row,[2,3,4,5,6])"><el-button @click="handleAdd(scope.$index, scope.row)">审批记录</el-button></el-dropdown-item>
												 <el-dropdown-item v-if="fun('paSubListDedList')"><el-button @click="handleviewRokeBack(scope.$index,scope.row)">扣款记录</el-button></el-dropdown-item>
												 

										 </el-dropdown-menu>
								 </el-dropdown>
						 </template>
				</el-table-column>
			</el-table>
        </el-row>
        <el-dialog title="审核" v-model="rokeBackFormVisible" :close-on-click-modal="false">
			<el-form :model="addForm" label-width="100px" ref="addForm">
				<el-input type="textarea" placeholder="审核意见" v-model="addForm.beizhu"></el-input>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button type="primary" @click.native="addFormSubmit(1)" :loading="addFormLoading">通过</el-button>
				<el-button type="primary" @click.native="addFormSubmit(2)" :loading="addFormLoadingbo">驳回</el-button>
				<el-button @click.native="rokeBackFormVisible = false">关闭</el-button>
			</div>
		</el-dialog>
		<el-dialog title="审批意见" v-model="addFormVisible" :close-on-click-modal="false">
			<el-form v-model="shenpr" ref="viewDateForm"  >
							审批人&nbsp;&nbsp;:&nbsp;&nbsp;{{shenpr==''?'无数据':shenpr}}
			</el-form><br />
			<el-form v-model="shenpshij" ref="viewDateForm"  >
							审批时间&nbsp;&nbsp;:&nbsp;&nbsp;{{shenpshij==''?'无数据':shenpshij}}
			</el-form><br />
				<el-form v-model="beizhushenp" ref="viewDateForm">
							备注&nbsp;&nbsp;:&nbsp;&nbsp;{{beizhushenp==''?'无数据':beizhushenp}}
				</el-form>
		</el-dialog>
        <el-dialog title="查看提交备注" v-model="viewDateFormVisible" :close-on-click-modal="false">
        		<el-form v-model="beizhu" label-width="120px"   ref="viewDateForm">
						{{beizhu==null?'无数据':beizhu}}
        		</el-form>
        </el-dialog>
    </el-row>
</template>
<style>
	.rowspacing{margin-top: 20px;}
	.el-table .cell{text-align: center;}
	.el-textarea__inner{height: 200px;}
</style>
<script>
    import {getPurchaseContractInfo,
        getHedanqiannameSaleList,
        getHedanbumenSaleList,
        hedanSavePurchaseContract,
        getHedanPurchaseContractList,
        removeHedanPurchaseContract,
        hedanUpdataPurchaseContract,
		
		
				getPayablePayment,
				bleSubmission,
				
				getReceivableListPage,
				editReceivable,
				renling,
				addBrokerCompanyUser,
				batchRemoveBrokerCompanyUser,
				getbkNameList,} from '../../api/api';
    export default {
        data() {
            return {
				srid:'',
				shenpr:'',
				shenpshij:'',
				beizhushenp:'',
                rokeBackFormVisible:false,
				rokeBackFormVisiblevie:false,
				addFormLoading: false,
				addFormLoadingbo: false,
				addFormVisible: false,
				addFormRules: {
					
				},
				
				addForm: {
					tCwSrSubmitId:'',
					status: '',
					beizhu: '',
				},
                lists:[],
                htype:1,//合同的类型
                listLoading:false,
                bianhao:null,
				xiangmu:'',
				yuezjin:'',
				type:'',
				hzouqi:'',
				ysdata:'',
				ysjine:'',
				sjys:'',
				
				
				financeReceivable:[],
                tabIndex:1,
                officeList:[{
                    //hetongtype:null,
                    loupanName:null,
                    loudongName:null,
                    fanghao:null,
                }],
                Visible:false,
                hetongid:null,
                createtime:null,
                hedan:{
                    qiandanren:[
                        {
                            //id:null,
                            contractid:null,
                            signpersonnelname:'',//签单人姓名
                            signpersonnelnameId:'',
                            ratio:null,//签单人所占比例
                            leaderpersonnelname:null,//签单人上级领导
                            leaderpersonnelnameId:'',
                            departmentname:null,//签单人部门
                            departmentnameId:'',
                            //Loading:null,
                        }
                    ],
                },
                fristyslxrloading1: false,
                fristyslxrloading2:false,
                optionsyslxr1: [],
                optionsyslxr2: [],
                optionsyslxr3: [],
                hedanRules:{

                },
                //分页类数据
                total:0,
                page:null,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                sels: [],//列表选中列
				Receivable:[],//应收列表
				filters:{
						fkyh: '',
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
				//付款界面数据
				
				viewDateFormVisible: false,//查看界面是否显示
				//查看界面数据
				viewDateForm: {
						beizhu:'',
				},
				totalrl:0,
				currentPagerl:0,
				pageSizerl:10,
				renlingData :{
					tCwSrId:null,
					tCwSrCaiwuId:null,
				},
				beizhu:null,
				udopinion:null,
            }
        },
        methods: {
            //时间戳转日期格式
            changeDate(row, column){
                var newDate = new Date();
                newDate.setTime(row.tianjiadate);
                if(row.tianjiadate!=null){
                    return newDate.toLocaleDateString()
                }
            },
			ztin(row, arr){
				var status = arr.indexOf(row.status);
				if (status > -1) {
						return true;
				} else {
						return false;
				}
			},
            cancel(){
                this.resetForm('hedan');
                this.Visible = false;
                history.go(0)
            },
            //获取付款账号的数据列表
            saleHedanContractList() {
                let para = {
                    pn: this.page,
                    cnt: this.pageSize,
                    htid: this.$route.query.id,
                }
                this.listLoading = true;
                getHedanPurchaseContractList(para).then((res) => {
                    //console.log(12222);
                    //console.log(res.data.data);
                    this.total = res.data.total;
                    this.lists = res.data.data;
                    this.listLoading = false;

                });
            },

            changeyslxr1(){
                for (var x in this.optionsyslxr1) {
                    if (this.optionsyslxr1[x].value == this.hedan.qiandanren[this.tabIndex-1].signpersonnelname) {
                        this.hedan.qiandanren[this.tabIndex-1].signpersonnelname = this.optionsyslxr1[x].label;
                    }
                }
            },
            changeyslxr2(){
                for (var x in this.optionsyslxr2) {
                    if (this.optionsyslxr2[x].value == this.hedan.qiandanren[this.tabIndex-1].leaderpersonnelname) {
                        this.hedan.qiandanren[this.tabIndex-1].leaderpersonnelname = this.optionsyslxr2[x].label;
                    }
                }
            },
            changeyslxr3(){
                for (var x in this.optionsyslxr3) {
                    if (this.optionsyslxr3[x].value == this.hedan.qiandanren[this.tabIndex-1].departmentname) {
                        this.hedan.qiandanren[this.tabIndex-1].departmentname = this.optionsyslxr3[x].label;
                    }
                }
            },

            //获取领导人列表
            remoteMethodyslxr1(query) {
                let para = {
                    uname: query
                };
                this.list = [];
                this.fristyslxrloading1 = true;
                getHedanqiannameSaleList(para).then((res) => {
                    let arr = [];
                    arr[0] = '';
                    for (var i in res.data) {
                        arr[i] = res.data[i]
                    }
                    this.estate = arr;
                    this.fristyslxrloading1 = false;
                    this.list = this.estate.map((item, index) => {
                        return {value: index, label: item};
                    });
                    if (query !== '') {
                        this.fristyslxrloading1 = true;
                        setTimeout(() => {

                            this.fristyslxrloading1 = false;
                            this.optionsyslxr1 = this.list.filter(item => {
                                return item.label.toLowerCase()
                                        .indexOf(query.toLowerCase()) > -1;
                            });
                        }, 200);
                    } else {
                        this.optionsyslxr1 = [];
                    }
                });
            },
            remoteMethodyslxr2(query) {
                let para = {
                    uname: query
                };
                this.list = [];
                this.fristyslxrloading1 = true;
                getHedanqiannameSaleList(para).then((res) => {
                    let arr = [];
                    arr[0] = '';
                    for (var i in res.data) {
                        arr[i] = res.data[i]
                    }
                    this.estate = arr;
                    this.fristyslxrloading1 = false;
                    this.list = this.estate.map((item, index) => {
                        return {value: index, label: item};
                    });
                    if (query !== '') {
                        this.fristyslxrloading1 = true;
                        setTimeout(() => {

                            this.fristyslxrloading1 = false;
                            this.optionsyslxr2 = this.list.filter(item => {
                                return item.label.toLowerCase()
                                        .indexOf(query.toLowerCase()) > -1;
                            });
                        }, 200);
                    } else {
                        this.optionsyslxr2 = [];
                    }
                });
            },
            remoteMethodyslxr3(query) {
                let para = {
                    dname: query
                };
                this.list = [];
                this.fristyslxrloading2 = true;
                getHedanbumenSaleList(para).then((res) => {
                    let arr = [];
                    arr[0] = '';
                    for (var i in res.data) {
                        arr[i] = res.data[i]
                    }
                    this.estate = arr;
                    this.fristyslxrloading2 = false;
                    this.list = this.estate.map((item, index) => {
                        return {value: index, label: item};
                    });
                    if (query !== '') {
                        this.fristyslxrloading2 = true;
                        setTimeout(() => {

                            this.fristyslxrloading2 = false;
                            this.optionsyslxr3 = this.list.filter(item => {
                                return item.label.toLowerCase()
                                        .indexOf(query.toLowerCase()) > -1;
                            });
                        }, 200);
                    } else {
                        this.optionsyslxr3 = [];
                    }
                });
            },
            handleSizeChange(val) {
                /*console.log(`每页 ${val} 条`);*/
                this.pageSize = val;
                this.saleHedanContractList();
            },
            handleCurrentChange(val) {
                /*console.log(`当前页: ${val}`);*/
                this.page = val;
                this.saleHedanContractList();
            },
            //增加租期租金
            addqiandan() {
                this.tabIndex = this.tabIndex + 1;
                //console.log(this.hedan.qiandanren);
                this.hedan.qiandanren.push({
                    contractid:this.hedan.qiandanren[0].contractid,
                    signpersonnelname:'',
                    ratio:'',
                    leaderpersonnelname:'',
                    departmentname:'',
                });
            },
            removeQiandan(item) {
                if(this.hedan.qiandanren.length > 1) {
                    this.hedan.qiandanren.pop();
                    var index = this.hedan.qiandanren.indexOf(item);
                    --this.tabIndex;
                    if (index !== -1) {
                        this.hedan.qiandanren.splice(index, 1)
                    }
                }
            },
//             getSaleContract(id){
//                 getPurchaseContractInfo(id).then((res)=>{
//                     if(res.data.code=='200'){
//                         this.fuzhi(res);
//                     }else {
//                         this.$message({
//                             message: '获取数据失败',
//                             type: 'error'
//                         });
//                     }
//                 })
//             },
            selsChange: function (sels) {
                this.sels = sels;
            },
            //新增
            addContract(){
                this.Visible = true;
            },
            //新增的提交
            handleEnd(index,row){
                this.$refs.hedan.validate((valid) => {
                    if(valid){
                        let para1 = this.hedan.qiandanren;
                        //console.log(para1);
                        this.Visible = false;
                        hedanSavePurchaseContract(para1).then((res)=>{
                            if(res.data.code!='200'){
                                this.$message({
                                    message: '数据没有保存成功',
                                    type: 'error'
                                });
                            }
                            this.saleHedanContractList();
                            this.resetForm('hedan');
                            location.reload('http://127.0.0.1:8000/#/purchaseContract');
                        });
                    }
                });

            },
            //失去焦点事件
            updataHedan1(index, row){
                for (var x in this.optionsyslxr1) {
                    if (this.optionsyslxr1[x].value == row.signpersonnelname) {
                        row.signpersonnelname = this.optionsyslxr1[x].label;
                        //alert(55);
                    }
                }
                let para = {
                    id: row.id,
                    signpersonnelname: row.signpersonnelname,
                    ratio: row.ratio,
                    leaderpersonnelname: row.leaderpersonnelname,
                    departmentname: row.departmentname,
                };
                hedanUpdataPurchaseContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '数据没有保存成功',
                            type: 'error'
                        });
                    }
                });
            },

            updataHedan(index, row){
                let para = {
                    id: row.id,
                    signpersonnelname: row.signpersonnelname,
                    ratio: row.ratio,
                    leaderpersonnelname: row.leaderpersonnelname,
                    departmentname: row.departmentname,
                };
                hedanUpdataPurchaseContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '数据没有保存成功',
                            type: 'error'
                        });
                    }
                });
            },
            updataHedan2(index, row){
                for (var x in this.optionsyslxr2) {
                    if (this.optionsyslxr2[x].value == row.leaderpersonnelname) {
                        row.leaderpersonnelname = this.optionsyslxr2[x].label;
                        //alert(55);
                    }
                }
                let para = {
                    id: row.id,
                    signpersonnelname: row.signpersonnelname,
                    ratio: row.ratio,
                    leaderpersonnelname: row.leaderpersonnelname,
                    departmentname: row.departmentname,
                };
                hedanUpdataPurchaseContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '数据没有保存成功',
                            type: 'error'
                        });
                    }
                });
            },
            updataHedan3(index, row){
                for (var x in this.optionsyslxr3) {
                    if (this.optionsyslxr3[x].value == row.departmentname) {
                        row.departmentname = this.optionsyslxr3[x].label;
                        //alert(55);
                    }
                }
                let para = {
                    id: row.id,
                    signpersonnelname: row.signpersonnelname,
                    ratio: row.ratio,
                    leaderpersonnelname: row.leaderpersonnelname,
                    departmentname: row.departmentname,
                };
                hedanUpdataPurchaseContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '数据没有保存成功',
                            type: 'error'
                        });
                    }
                });
            },
            //签单人删除
            handleDel(index, row){
                this.$confirm('确认删除该记录吗？', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    let para = {id: row.id};
                    removeHedanPurchaseContract(para).then((res) => {
                        this.listLoading = false;
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                        this.saleHedanContractList();
                    });
                }).catch(() => {
                });
            },
            fuzhi(res){
                this.hedan.qiandanren.contractid = res.data.data.id;
                this.bianhao = res.data.data.bianhao;
                this.officeList = res.data.data.officeList;
                var fj="";
                for (var x in this.officeList){
                    fj=fj+this.officeList[x].fanghao +",";
                }
                this.officeList[0].fanghao = fj.substring(0,fj.length-1);
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
			tableRowClassName(row, index) {
				if (row.tCwSrCaiwuId === this.renlingData.tCwSrCaiwuId) {
					return 'info-row';
				}else{
					return '';
				}
			},
			//审核的审批
			handleRokeBack(row){
				this.rokeBackFormVisible = true;
				// this.renlingData.tCwSrCaiwuId=row.tCwSrCaiwuId;
				this.addForm = Object.assign({}, row);
				this.addForm = {
					tCwSrSubmitId: row.tCwSrSubmitId,
					status: '',
					beizhu: '',
				};
			},
			//扣款记录
			handleviewRokeBack(index, row){
				this.$router.push('/payableFinanceSubm?id=' + row.tCwFcSubmitId);
// 				this.udopinion = this.financeReceivable[index].auditdesc;
// 				this.rokeBackFormVisiblevie = true;
				// this.renlingData.tCwSrCaiwuId=row.tCwSrCaiwuId;
// 				this.addForm = Object.assign({}, row);
// 				this.addForm = {
// 					tCwSrSubmitId: row.tCwSrSubmitId,
// 					status: '',
// 					beizhu: '',
// 				};
			},
			formatFKType(row, column){
					let status = [];
					status[0] = '押金';
					status[1] = '房租';
					status[5] = '杂费';
					status[20] = '意向金';
					return status[row.sktype];
			},
			//支付状态显示转换
			formatState1: function (row, column) {
					let status = [];
					status[0] = '未支付';
					status[1] = '付款成功';
					status[2] = '付款失败';
					status[3] = '已撤销';
					return status[row.zhifustate];
			},
			//审批状态显示转换
			formatState2: function (row, column) {
					let status = [];
					status[1] = '待初审';
					status[2] = '初审通过';
					status[3] = '初审驳回';
					status[4] = '复审通过';
					status[5] = '复审驳回';
					status[6] = '已撤销';
					return status[row.status];
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
					newDate.setTime(value.tijiaodate);
					return newDate.toLocaleDateString()
			},
			//时间戳转日期格式
			changeDate2(row, column){
					if(row.skdate!=null) {
							var newDate = new Date();
							newDate.setTime(row.skdate);
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
			//时间戳转化为日期
			timeconversion(timestamp){
				var date = new Date(timestamp);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
				var Y = date.getFullYear() + '/';
				var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '/';
				var D = date.getDate() + ' ';
				var h = date.getHours() + ':';
				var m = date.getMinutes() + ':';
				var s = date.getSeconds();
				return Y+M+D;
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
			//获取实收款列表
			getReceivable() {
				let para = {
					id: this.srid,
				};
				this.listLoading = true;
				getPayablePayment(para).then((res) => {
					this.financeReceivable = res.data.data.dfInfos;
					for(var i=0;i<this.financeReceivable.length;i++){
						this.financeReceivable[i].fukuanzhanghao = this.financeReceivable[i].fukuanyinhang + this.financeReceivable[i].fukuanzhanghao;
					}
					this.bianhao = res.data.data.htbianhao;
					this.xiangmu = res.data.data.loupanName + ' ' + res.data.data.loudongName + ' ' + res.data.data.houseno;
					this.yuezjin = res.data.data.monthmoney;
					if(res.data.data.fktype == 1){
						this.type = '房租';
					}else{
						this.type = '押金';
					}
					this.hzouqi = res.data.data.zhouqi;
					this.ysdata = this.timeconversion(res.data.data.fkdate);
					this.ysjine = res.data.data.fkmoney;
					this.sjys = res.data.data.shifumoney;
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
							zt:10,
					};
					this.listLoading = true;
					getReceivableListPage(para).then((res) => {
							this.totalrl = res.data.total;
							this.ReceivableRL = res.data.data;
							this.listLoading = false;
					});
			},
			//新增实收页面
			handleAdd: function (index, row) {
				this.$router.push('/payableSubmitAudit?id=' + row.tCwFcId + '&subid=' + row.tCwFcSubmitId);
			},
			//显示查看备注页面
			handleView: function (index, row) {
				this.beizhu = this.financeReceivable[index].beizhu;
				this.viewDateFormVisible = true;
			},
			//提交审核的数据1 通过 2驳回
			addFormSubmit(type){
				this.addForm.status = type;
				this.$refs.addForm.validate((valid) => {
					if (valid) {
						this.$confirm('确认提交吗？', '提示', {}).then(() => {
								if(this.addForm.status == 1){
									this.addFormLoading = true;
								}else{
									this.addFormLoadingbo = true;
								}
								
								let para = Object.assign({}, this.addForm);
								bleSubmission(para).then((res) => {
									if(this.addForm.status == 1){
										this.addFormLoading = false;
									}else{
										this.addFormLoadingbo = false;
									}
									if(res.data.code==200) {
									this.$message({
											message: '提交成功',
											type: 'success'
									});
									this.$refs['addForm'].resetFields();
									this.rokeBackFormVisible = false;
									} else{
											this.$message({
													message: res.data.msg,
													type: 'error'
											});
									}
									if(this.addForm.status == 1){
										this.addFormLoading = false;
									}else{
										this.addFormLoadingbo = false;
									}
									this.getReceivable();
								});
						});
					}
				});
			},
			//认领提交
			rokeBackSubmit: function () {
					if(this.renlingData.tCwSrId!=null){
							this.$confirm('确认提交吗？', '提示', {}).then(() => {
									let para = this.renlingData;
									renling(para).then((res)=>{
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
											tCwSrId:null,
											tCwSrCaiwuId:null,
									};
							});
					}else{
							alert('至少点击选中一项')
					}
			},
			//选中以后
			handleCurrentChange1(val) {
					this.renlingData.tCwSrId=val.tCwSrId;

			},
			selsChange(val,row){
					this.$refs.multipleTable.clearSelection();
					this.$refs.multipleTable.toggleRowSelection(row);
			},
        },
        mounted(){
			this.srid = this.$route.query.id;
			this.hedan.qiandanren[0].contractid = this.$route.query.id;
			this.page=1;
			this.getReceivable();
        }
    }
</script>
