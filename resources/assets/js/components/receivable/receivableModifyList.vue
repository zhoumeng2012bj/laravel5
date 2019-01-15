
<template>
    <el-row >
        <el-table height="500" :data="financeReceivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"   style="width: 100%;">
					<el-table-column prop="personname" label="修改人" width="100">
					</el-table-column>
            <el-table-column prop="updatedate" label="修改时间" :formatter="changeDate1" width="260">
            </el-table-column>
            <el-table-column prop="xiugaimoney" label="优惠金额" width="100">
            </el-table-column>
						<el-table-column prop="auditname" label="审批人" width="100">
						</el-table-column>
						<el-table-column prop="audittime" label="审批时间" :formatter="changeDate2" width="260">
						</el-table-column>
            <el-table-column prop="status" label="审批结果"  :formatter="formatState" width="100">
            </el-table-column>
						<el-table-column prop="xiugaixinxi" label="备注">
						</el-table-column>
            <el-table-column label="操作" width="120">
                   <template slot-scope="scope">
                       <el-dropdown   menu-align="start">
                           <el-button type="primary" size="normal" splitButton="true">
                               操作<i class="el-icon-caret-bottom el-icon--right"></i>
                           </el-button>
                           <el-dropdown-menu slot="dropdown" >
                               <!-- <el-dropdown-item   v-if="scope.row.zhuangtai==0||scope.row.zhuangtai==2" ><el-button v-if="fun('claim')"   @click="handleRokeBack(scope.row)">认领</el-button></el-dropdown-item>
                               <el-dropdown-item v-if="fun('queryMemo')" ><el-button   @click="handleView(scope.$index, scope.row)">查看备注</el-button></el-dropdown-item> -->
															 <el-dropdown-item v-if="ztin(scope.row,[1])"><el-button @click="handleRokeBack(scope.row)">审核</el-button></el-dropdown-item>
															 <el-dropdown-item v-if="ztin(scope.row,[2,3])"><el-button @click="handleView(scope.$index, scope.row)">查看备注</el-button></el-dropdown-item>

                           </el-dropdown-menu>
                       </el-dropdown>
                   </template>
            </el-table-column>
           </el-table>
           <div style="margin-top:30px"></div>
        <el-dialog title="查看备注" v-model="viewDateFormVisible" :close-on-click-modal="false">
            <el-form v-model="beizhu" ref="viewDateForm"  >
                    {{beizhu==null?'无数据':beizhu}}
            </el-form>
        </el-dialog>
        <el-dialog title="审核" v-model="rokeBackFormVisible" :close-on-click-modal="false">
						<el-form :model="addForm" ref="addForm">
							<el-input type="textarea" placeholder="审核意见" v-model="addForm.auditdesc"></el-input>
						</el-form>
						<div slot="footer" class="dialog-footer">
							<el-button type="primary" @click.native="addFormSubmit(1)" :loading="addFormLoading">通过</el-button>
							<el-button type="primary" @click.native="addFormSubmit(2)" :loading="addFormLoadingbo">驳回</el-button>
							<el-button @click.native="rokeBackFormVisible = false">关闭</el-button>
						</div>
        </el-dialog>
        <el-dialog title="新增收款" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="120px" :rules="addFormRules" ref="addForm"  >
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="收款金额：" prop="shoukuanmoney">
                            <el-input    v-model.number="addForm.shoukuanmoney" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="收款时间：" prop="shoukuandate">
                            <el-date-picker type = "date" v-model="addForm.shoukuandate" auto-complete="off">
                            </el-date-picker>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="付款方户名：" prop="fukuanzhanghu">
                            <el-input    v-model="addForm.fukuanzhanghu" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="16">
                        <el-form-item label="付款银行：" prop="fukuanyinhang">
                            <el-input   v-model="addForm.fukuanyinhang" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="付款账号：" prop="fukuanzhanghao">
                    <el-input    v-model="addForm.fukuanzhanghao" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item   label="备注：" prop="beizhu">
                    <el-input type="textarea" v-model="addForm.beizhu" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="addFormSubmit" :loading="addFormLoading">保存</el-button>
            </div>
        </el-dialog>

    </el-row>
</template>
<style>
	.el-table .cell{text-align: center;}
	.el-dialog .el-table__body tr.current-row>td {
			background: rgba(26, 135, 249, 0.75) !important;
	}
	.el-textarea__inner{height: 200px;}
</style>
<script>
    import {
        getReceiveModifyList,
				
				bleedidSubmission,
				
				
				
				
        getReceivableListPage,
        editReceivable,
        financeSaveShouKuan,
        renling,
        addBrokerCompanyUser,
        batchRemoveBrokerCompanyUser,
        getbkNameList,
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
								beizhu:null,
                Receivable:[],//应收列表
								id:'',//应收id
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
                //分页类数据
                total:0,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                financeReceivable:[],
                ReceivableRL:[],
                RLlistLoading:false,
                listLoading: false,
                sels: [],//列表选中列
                rokeBackFormVisible: false,//收款界面是否显示
                rokeBackLoading: false,
                addFormVisible: false,//收款界面是否显示
                addFormLoading: false,
								addFormLoadingbo: false,
                addFormRules: {
                    shoukuanmoney:[
                        {required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字'}
                    ],
                    shoukuandate: [
                        {required: true, message: '不能为空'},
                    ],
                 //    fukuanzhanghu: [
                 //     {required: true, message: '不能为空'},
                 //   ],
                 //   fukuanyinhang: [
                 //       {required: true, message: '不能为空'},
                 //   ],
                 //  fukuanzhanghao: [
                 //      {required: true, message: '不能为空'},
                 //  ],
                },
                //付款界面数据
                addForm: {
									tCwSrUpdateId:'',
									status: '',
									auditdesc: '',
                },
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
            }
        },
        methods:{
            tableRowClassName(row, index) {
                if (row.tCwSrCaiwuId === this.renlingData.tCwSrCaiwuId) {
                    return 'info-row';
                }else{
                    return '';
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
            handleRokeBack(row){
//                 this.rokeBackFormVisible = true;
//                 this.renlingData.tCwSrCaiwuId=row.tCwSrCaiwuId;
//                 this.pagerl = 1;
//                 this.getReceivableRL();
							this.rokeBackFormVisible = true;
							// this.renlingData.tCwSrCaiwuId=row.tCwSrCaiwuId;
							this.addForm = Object.assign({}, row);
							this.addForm = {
								tCwSrUpdateId: row.tCwSrUpdateId,
								status: '',
								auditdesc: '',
							};
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
											bleedidSubmission(para).then((res) => {
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
            formatFKType(row, column){
                let status = [];
                status[0] = '押金';
                status[1] = '房租';
                status[5] = '杂费';
                status[20] = '意向金';
                return status[row.sktype];
            },
            //支付状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[1] = '待审核';
                status[2] = '已通过';
								status[3] = '已驳回';
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
                newDate.setTime(value.updatedate);
                return newDate.toLocaleString()
            },
            //时间戳转日期格式
            changeDate2(row, column){
                if(row.audittime!=null) {
                    var newDate = new Date();
                    newDate.setTime(row.audittime);
                    return newDate.toLocaleString()
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
            //获取实收款列表
            getReceivable() {
                let para = {
                    id: this.id,
                };
                this.listLoading = true;
                getReceiveModifyList(para).then((res) => {
                    this.financeReceivable = res.data.data;
                    this.listLoading = false;
                });
            },
            //获取应付款列表
            getReceivableRL() {
                let para = {
                    id: this.id,
                };
                this.RLlistLoading = true;
                getReceivableListPage(para).then((res) => {
                    this.totalrl = res.data.total;
                    this.ReceivableRL = res.data.data;
                    this.RLlistLoading = false;
                });
            },
            //新增实收页面
            handleAdd: function (index, row) {
                this.addFormVisible = true;
            },
            //显示查看备注页面
            handleView: function (index, row) {
								this.beizhu = this.financeReceivable[index].auditdesc;
                this.viewDateFormVisible = true;
                // this.viewDateForm  = Object.assign({}, row);
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
        mounted() {
            this.page=1;
						this.id = this.$route.query.id;
            this.getReceivable();

        }
    }
</script>
