<template>
    <el-row>
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px">
            <el-form-item label="操作状态">
                <el-select v-model="filters.status" @change="getReceivable" placeholder="请选择操作状态">
                    <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
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

        <el-table :data="Receivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
                  @selection-change="selsChange" style="width: 100%;">
            <el-table-column prop="xiangmu" label="项目" width="300">
            </el-table-column>
            <el-table-column prop="zhouqi" label="周期" width="260">
            </el-table-column>
            <el-table-column prop="shoukuandate" label="收款日期" :formatter="changeDate">
            </el-table-column>
            <el-table-column prop="shoukuanmoney" label="支付金额">
            </el-table-column>
            <el-table-column prop="paystatus" label="支付状态" :formatter="formatState">
            </el-table-column>
            <el-table-column prop="duizhangstatus" label="对账状态" :formatter="formatState2">
            </el-table-column>
            <el-table-column prop="pingtaizhuangtai" label="状态" :formatter="formatState3">
            </el-table-column>
            <el-table-column prop="caozuozhuangtai" label="操作状态" :formatter="formatState4">
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-dropdown menu-align="start">
                        <el-button type="primary" size="normal" splitButton="true">
                            操作<i class="el-icon-caret-bottom el-icon--right"></i>
                        </el-button>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item v-if=" fun('financeWithdraw')&&scope.row.caozuozhuangtai==1">
                                <el-button @click.native="handleFrom(scope.$index, scope.row)">处理
                                </el-button>
                            </el-dropdown-item>
                            <el-dropdown-item v-if=" fun('paSubList')&&scope.row.caozuozhuangtai==2">
                                <el-button @click="handleRokeBack(scope.$index, scope.row)">处理记录</el-button>
                            </el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog title="处理记录"  size="tiny" v-model="rokeBackFormVisible" :close-on-click-modal="false">
            <el-form label-width="120px" ref="rokeBackForm" :model="rokeBackForm">
                <el-form-item label="处理人：" prop="handlePersion">
                    {{rokeBackForm.handlePersion}}
                </el-form-item>
                <el-form-item label="处理时间：" prop="handleDate">
                    {{ changeclDate(rokeBackForm.handleDate)}}
                </el-form-item>
                <el-form-item label="处理内容：" prop="handleNote">
                    {{rokeBackForm.handleNote}}
                </el-form-item>
                <el-form-item label="备注：" prop="remark">
                    {{rokeBackForm.remark}}
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="rokeBackFormVisible = false">关闭</el-button>
            </div>
        </el-dialog>
        <el-dialog title="处理记录" size="tiny" v-model="handerFormVisible" :close-on-click-modal="false">

            <el-form label-width="120px" ref="handerForm" :model="handerForm" :rules="handerFormRules">
                <el-form-item label="" prop="remark">
                    处理后会将支付状态与对账状态统一修改为处理时所选择的状态
                </el-form-item>
                <el-form-item label="状态：" prop="payState">
                    <el-select v-model="handerForm.payState"   placeholder="请选择状态">
                        <el-option
                                v-for="item in optionszt"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="备注：" prop="remark">
                    <el-input type="textarea" :rows="8" v-model="handerForm.remark"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">

                <el-button type="primary" @click.native="handleAccounts">确定</el-button>
                <el-button @click.native="handerFormVisible = false">取消</el-button>
            </div>
        </el-dialog>
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
        accountsdatalist,
        accountsdatahandlelist,
        accountsdatahandle,
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
                filters: {
                    xm: '',
                    startdate: '',
                    enddate: '',
                    status: '',
                },
                options: [
                    {value: 0, label: '请选择',},
                    {value: 1, label: '未处理',},
                    {value: 2, label: '已处理',},
                    {value: 3, label: '无需处理',},

                ],
                optionszt: [
                    {value: 1, label: '支付成功',},
                    {value: 2, label: '支付失败',},

                ],
                handerFormRules: {
                    paystatus: [
                        {required: true, message: '不能为空'}
                    ],
                },
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
                rokeBackFormVisible: false,//处理记录界面是否显示
                handerFormVisible: false,//处理界面是否显示
                //付款界面数据
                rokeBackForm: {
                    tCwSrPlanId: '',
                    handlePersion: '',
                    handleDate: '',
                    handleNote: '',
                    remark: '',
                },
                //付款界面数据
                handerForm: {
                    tCwSrPlanId: '',
                    payState: '',
                    remark: '',
                },
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
            //状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[1] = '未付款';
                status[2] = '支付成功';
                status[3] = '支付失败';
                status[4] = '已撤回';
                return status[row.paystatus];
            },
            //对账状态显示转换
            formatState2: function (row, column) {
                let status = [];
                status[1] = '对账成功';
                status[2] = '对账失败';
                return status[row.duizhangstatus];
            },
            //状态显示转换
            formatState3: function (row, column) {
                let status = [];
                status[0] = '无';
                status[1] = '平台长款';
                status[2] = '正常';
                status[3] = '支付失败';
                status[4] = '对账金额不符';
                return status[row.pingtaizhuangtai];
            },
            //操作状态显示转换
            formatState4: function (row, column) {
                let status = [];
                status[0] = '无';
                status[1] = '未处理';
                status[2] = '已处理';
                status[3] = '无需处理';
                return status[row.pingtaizhuangtai];
            },
            //时间戳转日期格式
            changeDate: function (row, column) {
                if (row.shoukuandate != null) {
                    var newDate = new Date();
                    newDate.setTime(row.shoukuandate);
                    return newDate.toLocaleDateString()
                }
            },
            //时间戳转日期格式
            changeclDate(skdate){
                if (skdate != null && skdate != '') {
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
            //显示处理记录界面
            handleRokeBack: function (index, row) {
                this.rokeBackForm = {
                    tCwSrPlanId: '',
                    handlePersion: '',
                    handleDate: '',
                    handleNote: '',
                    remark: '',
                };
                let para = {
                    id: row.tCwSrPlanId,
                };
                accountsdatahandlelist(para).then((res) => {
                    this.total = res.data.total;
                    this.rokeBackForm = {
                        tCwSrPlanId: res.data.data[0].tCwSrPlanId,
                        handlePersion: res.data.data[0].handlePersion,
                        handleDate: res.data.data[0].handleDate,
                        handleNote: res.data.data[0].handleNote,
                        remark: res.data.data[0].remark,
                    };
                });
                this.rokeBackFormVisible = true;

            },

            //确定处理
            handleAccounts(index, row){
                let para = this.handerForm;
                accountsdatahandle(para).then((res) => {
                    if (res.data.code == '200') {
                        this.listLoading = false;
                        this.$message({
                            message: '设置成功',
                            type: 'success'
                        });
                        this.getReceivable();
                    } else {
                        this.$message({
                            message: '设置失败',
                            type: 'error'
                        });
                    }
                });
                this.handerFormVisible = false;
            },
            //显示处理记录界面
            handleFrom: function (index, row) {
                this.handerForm = {
                    tCwSrPlanId: row.tCwSrPlanId,
                    payState: '',
                    remark: '',
                },
                    this.handerFormVisible = true;

            },
            //获取应收款列表1
            getReceivable() {
                let para = {
                    page: this.page,
                    size: this.pageSize,
                    xm: this.filters.xm,
                    zt: this.filters.status,
                    sdate: this.filters.startdate,
                    edate: this.filters.enddate,
                    id: this.$route.query.id,
                };
                this.listLoading = true;
                accountsdatalist(para).then((res) => {
                    this.total = res.data.total;
                    this.Receivable = res.data.data;
                    this.listLoading = false;
                });
            },
//            //处理
//            handleRokeBack: function (index, row) {
//                this.$confirm('确认将这条应收计划撤回吗?', '提示', {
//                    type: 'warning'
//                }).then(() => {
//                    this.addFormLoading = true;
//                    let para = {
//                        tCwSrPlanId: row.tCwSrPlanId,
//                        tCwSrSubmitId: row.tCwSrSubmitId,
//                        paystatus: 4,
//                    };
//                    accountsdatahandle(para).then((res) => {
//                        this.addFormLoading = false;
//                        //NProgress.done();
//                        this.$message({
//                            message: '撤回成功',
//                            type: 'success'
//                        });
//                        this.getReceivable();
//                    });
//                }).catch(() => {
//
//                });
//            },
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
    .totals {
        margin-left: 20px;
    }

    .totals_box {
        display: flex;
        justify-content: space-between;
    }
</style>