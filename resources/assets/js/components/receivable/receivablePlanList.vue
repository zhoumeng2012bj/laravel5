<template>
    <el-row>
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px">
            <el-form-item label="合同编号:">
                <el-input v-model="filters.htno" placeholder="请输入合同编号"></el-input>
            </el-form-item>
            <el-form-item label="项目名称:">
                <el-input v-model="filters.xm" placeholder="请输入项目名称"></el-input>
            </el-form-item>
            <el-form-item label="收款日期:">
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
        </div>
        
        <el-tabs v-model="activeName2" type="border-card" @tab-click="handleClick">
            <el-tab-pane label="全部" name="first"></el-tab-pane>
            <el-tab-pane label="未付款" name="second"></el-tab-pane>
            <el-tab-pane label="付款成功" name="fourth"></el-tab-pane>
            <el-tab-pane label="付款失败" name="fifth"></el-tab-pane>
						<el-tab-pane label="已撤销" name="five"></el-tab-pane>
            <el-table :data="Receivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
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
                <el-table-column label="操作" width="180">
                    <template slot-scope="scope">
                        <el-dropdown menu-align="start">
                            <el-button type="primary" size="normal" splitButton="true">
                                  操作<i class="el-icon-caret-bottom el-icon--right"></i>
                              </el-button>
                              <el-dropdown-menu slot="dropdown">
																	<el-dropdown-item v-if="ztin(scope.row,[1,3])&&fun('planWithdraw')">
																			<el-button @click.native="handleRokeBack(scope.$index, scope.row)" :loading="addFormLoading">撤回</el-button>
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
        </el-tabs>
    </el-row>
</template>
<style>
	.el-table .cell{text-align: left !important;}
	.el-table .info-row {
			color: red;
	}

</style>

<script>

    import {
			getReceivablePlanList,
			withdrawReceivable,
			
			
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
                    zt: 1,
										dzzt:'',
                },

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
                status[2] = '付款成功';
                status[3] = '付款失败';
								status[4] = '已撤回';
                return status[row.paystatus];
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
										isfirst:1,
                    htno: this.filters.htno,
                    xm: this.filters.xm,
                    zt: this.filters.zt,
										dzzt: '',
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