<template>
    <el-row>
        <el-row>
            <div style="margin-top:30px;"></div>
            <li style="list-style-type:none;">
                <el-row>
                    <el-col :span="6">
                        <span>合同编号：{{bianhao}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>项目：{{xiangmu}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>月租金：{{yuezjin}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>类型：{{type}}</span>
                    </el-col>
                </el-row>
                <el-row class="rowspacing">
                    <el-col :span="6">
                        <span>周期： {{hzouqi}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>应收日期： {{ysdata}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>应收金额： {{ysjine}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>实际应收： {{sjys}}</span>
                    </el-col>
                </el-row>
                <el-row class="rowspacing">
                    <el-col :span="6">
                        <span>提交日期： {{tjrq}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>提交金额： {{tjje}}</span>
                    </el-col>
                    <el-col :span="6">
                        <span>发起人： {{fqr}}</span>
                    </el-col>
                </el-row>
            </li>
        </el-row>
        <el-row class="rowspacing">
            <el-table height="500" :data="financeReceivable" highlight-current-row v-loading="listLoading"
                      element-loading-text="拼命加载中" style="width: 100%;">

                <el-table-column prop="createtime" label="审批时间" :formatter="changeDate2">
                </el-table-column>
                <el-table-column prop="spusername" label="审批人">
                </el-table-column>
                <el-table-column prop="status" label="审批状态" :formatter="formatState2">
                </el-table-column>
                <!-- <el-table-column prop="zhuangtai" label="备注"  :formatter="formatState">
                </el-table-column> -->
                <el-table-column label="操作" width="120">
                    <template slot-scope="scope">
                        <el-dropdown menu-align="start">
                            <el-button type="primary" size="normal" splitButton="true">
                                操作<i class="el-icon-caret-bottom el-icon--right"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item  >
                                    <el-button @click="handleviewRokeBack(scope.$index,scope.row)">审核意见</el-button>
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>


        <el-dialog title="审核意见" v-model="rokeBackFormVisiblevie" :close-on-click-modal="false">
            <el-form v-model="udopinion" label-width="120px"   ref="viewDateForm">
                {{udopinion==null?'无数据':udopinion}}
            </el-form>
        </el-dialog>

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


        getReceiveAuditList,


    } from '../../api/api';
    export default {
        data() {
            return {
                id: '',
                rokeBackFormVisible: false,
                rokeBackFormVisiblevie: false,
                addFormLoading: false,
                addFormLoadingbo: false,
                addFormRules: {},

                addForm: {
                    tCwSrSubmitId: '',
                    status: '',
                    beizhu: '',
                },
                lists: [],
                htype: 1,//合同的类型
                listLoading: false,
                bianhao: null,
                xiangmu: '',
                yuezjin: '',
                type: '',
                hzouqi: '',
                ysdata: '',
                ysjine: '',
                sjys: '',
                tjrq:'',
                tjje:'',
                fqr:'',



                financeReceivable: [],
                tabIndex: 1,
                officeList: [{
                    //hetongtype:null,
                    loupanName: null,
                    loudongName: null,
                    fanghao: null,
                }],
                Visible: false,
                hetongid: null,
                createtime: null,
                hedan: {
                    qiandanren: [
                        {
                            //id:null,
                            contractid: null,
                            signpersonnelname: '',//签单人姓名
                            signpersonnelnameId: '',
                            ratio: null,//签单人所占比例
                            leaderpersonnelname: null,//签单人上级领导
                            leaderpersonnelnameId: '',
                            departmentname: null,//签单人部门
                            departmentnameId: '',
                            //Loading:null,
                        }
                    ],
                },
                fristyslxrloading1: false,
                fristyslxrloading2: false,
                optionsyslxr1: [],
                optionsyslxr2: [],
                optionsyslxr3: [],
                hedanRules: {},
                //分页类数据
                total: 0,
                page: null,
                currentPage: 0,
                pageSize: 10,
                pageSizes: [10, 20, 30, 40, 50, 100],
                sels: [],//列表选中列
                Receivable: [],//应收列表
                filters: {
                    fkyh: '',
                    fkzh: '',
                    zt: '',
                    contractNo: '',
                    xm: '',
                    zg: '',
                },
                options: [
                    {
                        value: 1,
                        label: '押金'
                    }, {
                        value: 2,
                        label: '房租'
                    },
                ],
                activeName2: 'first',
                //付款界面数据

                viewDateFormVisible: false,//查看界面是否显示
                //查看界面数据
                viewDateForm: {
                    beizhu: '',
                },
                totalrl: 0,
                currentPagerl: 0,
                pageSizerl: 10,
                renlingData: {
                    tCwSrId: null,
                    tCwSrCaiwuId: null,
                },
                beizhu: null,
                udopinion: null,
            }
        },
        methods: {
            //时间戳转日期格式
            changeDate(row, column){
                var newDate = new Date();
                newDate.setTime(row.tianjiadate);
                if (row.tianjiadate != null) {
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

            //审核意见的审批
            handleviewRokeBack: function (index, row) {
                this.udopinion = this.financeReceivable[index].beizhu;
                this.rokeBackFormVisiblevie = true;
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

            selsChange: function (sels) {
                this.sels = sels;
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
                status[1] = '未支付';
                status[2] = '已支付';
                status[3] = '已撤回';
                return status[row.fkstatus];
            },
            //审批状态显示转换
            formatState2: function (row, column) {
                let status = [];
                status[1] = '初审通过';
                status[2] = '初审驳回';
                status[3] = '复审通过';
                status[4] = '复审驳回';
                return status[row.status];
            },

            //时间戳转日期格式
            changeDate1(value){
                var newDate = new Date();
                newDate.setTime(value.tijiaoriqi);
                return newDate.toLocaleDateString()
            },
            //时间戳转日期格式
            changeDate3(value){
                var newDate = new Date();
                newDate.setTime(value);
                return newDate.toLocaleDateString()
            },
            //时间戳转日期格式
            changeDate2(row, column){
                if (row.createtime != null) {
                    var newDate = new Date();
                    newDate.setTime(row.createtime);
                    return newDate.toLocaleString()
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
            //页面跳转后
            handleCurrentChangerl(val) {
                this.pagerl = val;
                this.getReceivableRL();
            },
            //更改每页显示数据
            handleSizeChangerl(val){
                this.pageSizerl = val;
                this.getReceivableRL();
            },
            //获取实收款列表
            getReceivable() {
                let para = {
                    id: this.id,
                };
                this.listLoading = true;
                getReceiveAuditList(para).then((res) => {
                    this.total = res.data.total;
                    this.financeReceivable = res.data.data.auditList;
                        this.bianhao = res.data.data.hetongbianhao;
                        this.xiangmu = res.data.data.xiangmu;
                        this.yuezjin = res.data.data.yuezujin;
                        if (res.data.data.kemu == 1) {
                            this.type = '房租';
                        } else {
                            this.type = '押金';
                        }
                        this.hzouqi = res.data.data.zhouqi;
                        this.ysdata = this.changeDate3(res.data.data.shoukuandate);
                        this.ysjine = res.data.data.shoukuanmoney;
                        this.sjys = res.data.data.sjyingshoumoney;
                        this.tjrq= this.changeDate3(res.data.data.tijiaoriqi);
                        this.tjje=res.data.data.tijiaomoney;
                        this.fqr=res.data.data.persnoname;

                    this.listLoading = false;
                });
            },


            //显示查看备注页面
            handleView: function (index, row) {
                this.beizhu = this.financeReceivable[index].beizhu;
                this.viewDateFormVisible = true;
                // this.viewDateForm  = Object.assign({}, row);
            },


        },
        mounted(){
            // this.saleHedanContractList();
            this.id = this.$route.query.id;

            this.page = 1;
            this.getReceivable();
        }
    }
</script>
