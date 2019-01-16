<template>
    <el-row>
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px">
            <el-form-item label="合同编号:">
                <el-input v-model="filters.htno" placeholder="请输入合同编号"></el-input>
            </el-form-item>
            <el-form-item label="项目名称:">
                <el-input v-model="filters.xm" placeholder="请输入项目名称"></el-input>
            </el-form-item>
            <!-- <el-form-item label="租户名称:">
                <el-input v-model="filters.zh" placeholder="请输入租户名称"></el-input>
            </el-form-item> -->
            <!-- <br/> -->
            <el-form-item label="收款日期:">
                <el-date-picker type="date" value-format="yyyy-MM-dd" placeholder="请选择开始日期" v-model="filters.startdate">
                </el-date-picker>
            </el-form-item>
            <el-form-item label="至">
                <el-date-picker type="date" value-format="yyyy-MM-dd" placeholder="请选择结束日期" v-model="filters.enddate">
                </el-date-picker>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search" v-on:click="getReceivable">搜索</el-button>
                <!-- <el-button type="primary" class="el-icon-plus" v-if="fun('receivableAddYXJ')" @click="handleAdd">新增</el-button> -->
            </el-form-item>
        </el-form>
        <div class="totals_box">
        	<!-- <p>
        		合计 <span class="totals">应付金额：{{DataSum.sumMoney}}</span>
        		<span class="totals">实付金额：{{DataSum.shijiMoney}}</span>
        	</p> -->
        	<p>
		        <span style="color:red;font-size: 14px;">（注：红色日期表示付款已延期，请尽快处理）</span>
        	</p>
        </div>
        
        <el-tabs v-model="activeName2" type="border-card" @tab-click="handleClick">
            <el-tab-pane label="全部" name="first"></el-tab-pane>
            <el-tab-pane label="初审通过" name="second"></el-tab-pane>
            <el-tab-pane label="复审通过" name="fourth"></el-tab-pane>
            <el-tab-pane label="复审驳回" name="fifth"></el-tab-pane>
            <el-table :data="Receivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
                      @selection-change="selsChange" style="width: 100%;">
                <el-table-column prop="hetongbianhao" label="合同编号" width="200">
                </el-table-column>
                <el-table-column prop="xiangmu" label="项目">
                </el-table-column>
               
                <el-table-column prop="yuezujin" label="月租金" width="110">
                </el-table-column>
								
								<el-table-column prop="kemu" label="类型" :formatter="formatType" width="100">
								</el-table-column>
								<el-table-column prop="zhouqi" label="周期" width="130">
								</el-table-column>
                <el-table-column prop="shoukuandate" label="应收日期" width="150">
                    <template slot-scope="scope">
                        <span :class="tableClassName(scope.row.shoukuandate,scope.row.fkstatus)">  {{ changeDate(scope.row.shoukuandate)
                            }}</span>
                    </template>
                </el-table-column>
                <el-table-column prop="shoukuanmoney" label="应收金额" width="110">
                </el-table-column>
               <!-- <el-table-column prop="tijiaomoney" label="提交金额" width="100">
                </el-table-column> -->
                <el-table-column prop="sjyingshoumoney" label="实际应收" width="110">
                </el-table-column>
								<el-table-column prop="tijiaomoney" label="提交金额" width="110">
								</el-table-column>
                <el-table-column prop="status" label="状态" :formatter="formatState" width="100">
                </el-table-column>
                <el-table-column label="操作" width="180">
                    <template slot-scope="scope">
                        <el-dropdown menu-align="start">
                            <el-button type="primary" size="normal" splitButton="true">
                                  操作<i class="el-icon-caret-bottom el-icon--right"></i>
                              </el-button>
                              <el-dropdown-menu slot="dropdown">
                                  <!--    <el-dropdown-item v-if="ztin(scope.row,[0,1,3,4])&&fun('receivableAdd')">
                                       <el-button @click="handleRokeBack(scope.$index, scope.row)">提交收款</el-button>
                                   </el-dropdown-item>-->
                                  <!-- <el-dropdown-item v-if="scope.row.shiugaizhuangtai=='已修改'&&fun('editRecord')">
                                      <el-button @click="handleOpen(scope.$index, scope.row)">修改记录</el-button>
                                  </el-dropdown-item>
                                  <el-dropdown-item v-if="ztin(scope.row,[1,2,3,4])&&fun('receivableRecord')">
                                      <el-button @click="handleOpenUp(scope.$index, scope.row)">提交记录</el-button>
                                  </el-dropdown-item>
																	
																	<el-dropdown-item>
																			<el-button @click="handleCollect(scope.$index, scope.row)">催收跟进</el-button>
																	</el-dropdown-item>
																	
                                  <el-dropdown-item v-if="ztin(scope.row,[0,1,3,4])&&fun('receivableEidtDate')">
                                      <el-button  v-if="scope.row.sktype<20"  @click="handleEdit(scope.$index, scope.row)">编辑收款日期</el-button>
                                  </el-dropdown-item> -->
																	<el-dropdown-item v-if="ztin(scope.row,[2])&&fun('ELExamineAudit')">
																			<el-button @click="handleRokeBack(scope.$index, scope.row)">审批</el-button>
																	</el-dropdown-item>
																	<el-dropdown-item v-if="ztin(scope.row,[2,4,5])">
																			<el-button @click="handleEditYS(scope.$index, scope.row)">审批记录</el-button>
																	</el-dropdown-item>
                                  <el-dropdown-item v-if="ztin(scope.row,[1,2,3,4,5])"><el-button @click="handleView(scope.$index, scope.row)">查看备注</el-button></el-dropdown-item>
																	<!-- <el-dropdown-item>
																			<el-button @click="handleEditYS(scope.$index, scope.row)">修改应收金额</el-button>
																	</el-dropdown-item>
																	<el-dropdown-item>
																			<el-button @click="handleCollect(scope.$index, scope.row)">修改记录</el-button>
																	</el-dropdown-item>
																	<el-dropdown-item>
																			<el-button @click="handleSubmission(scope.$index, scope.row)">提交记录</el-button>
																	</el-dropdown-item> -->
																	
																	
																	
																	
                                 <!-- <el-dropdown-item v-if="ztin(scope.row,[0,1,3,4])&&fun('receivableEidtMoney')">
                                      <el-button v-if="scope.row.sktype<20" @click="handleMoneyEdit(scope.$index, scope.row)">编辑收款金额</el-button>
                                  </el-dropdown-item>
                                  <el-dropdown-item v-if="ztin(scope.row,[0,1,3,4])&&fun('receivableEidt')">
                                      <el-button v-if="scope.row.sktype==20" @click="handleEditYS(scope.$index, scope.row)">编辑</el-button>
                                  </el-dropdown-item>
                                  <el-dropdown-item v-if="ztin(scope.row,[0,1,3,4])&&fun('receivableFinish')">
                                      <el-button @click="handleFinish(scope.$index, scope.row)">完成</el-button>
                                  </el-dropdown-item> -->
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
        <el-dialog title="审核" size="tiny" v-model="rokeBackFormVisible" :close-on-click-modal="false">
        	<el-form :model="addForm" label-width="100px" ref="addForm">
                <p style="margin-bottom: 10px;margin-top: -10px;"> 审批通过后，这条记录会同步至应收计划中，并显示在客户的付款计划中，请知晓。 </p>
        		<el-input type="textarea" placeholder="审核意见" v-model="addForm.beizhu"></el-input>
        	</el-form>
        	<div slot="footer" class="dialog-footer">
        		<el-button type="primary" @click.native="addFormSubmit(3)" :loading="addFormLoading">通过</el-button>
        		<el-button type="primary" @click.native="addFormSubmit(4)" :loading="addFormLoadingbo">驳回</el-button>
        		<el-button @click.native="rokeBackFormVisible = false">关闭</el-button>
        	</div>
        </el-dialog>

        <el-dialog title="审批意见" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form v-model="beizhu" ref="viewDateForm"  >
            				{{beizhu==null?'无数据':beizhu}}
            </el-form>
        </el-dialog>

        <el-dialog title="编辑收款日期" v-model="editDateFormVisible" :close-on-click-modal="false">
            <el-form :model="editDateForm" label-width="120px" :rules="editDateFormRules" ref="editDateForm">
                <el-row>
                    <el-col :span="7">
                        <el-form-item label="提前收款：" prop="tiQianDays">
                            <el-input v-model.number="editDateForm.tiQianDays" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="1" style="text-align: right;margin-top: 8px;">
                        天
                    </el-col>
                    <el-col :span="10">
                        <el-form-item label="" prop="isBenQi">
                            <el-radio-group v-model="editDateForm.isBenQi">
                                <el-radio class="radio" label=true>本期</el-radio>
                                <el-radio class="radio" label=false>所有</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                </el-row>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editDateFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editDateFormSubmit" :loading="editDateFormLoading">保存
                </el-button>
            </div>
        </el-dialog>

        <el-dialog title="编辑收款金额" v-model="editMoneyFormVisible" :close-on-click-modal="false">
            <el-form :model="editMoneyForm" label-width="120px" :rules="editMoneyFormRules" ref="editMoneyForm">
                <el-row>
                    <el-col :span="16">
                        <el-form-item label="本期收款金额：" prop="fuKuanMoney">
                            <el-input v-model.number="editMoneyForm.fuKuanMoney" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>

                </el-row>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editMoneyFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editMoneyFormSubmit" :loading="editMoneyFormLoading">保存
                </el-button>
            </div>
        </el-dialog>
        <el-dialog title="查看备注" v-model="viewDateFormVisible" :close-on-click-modal="false">
            <el-form v-model="beizhu" label-width="120px"   ref="viewDateForm">
                {{beizhu==null?'无数据':beizhu}}
            </el-form>
        </el-dialog>
    </el-row>
</template>
<style>
	.el-table .cell{text-align: left !important;}
	.el-table .info-row {
			color: red;
	}
	.el-textarea__inner{height: 200px;}

</style>

<script>

    import {
			bleSubmission,
			
			
			
			
        getReceivableAuditList,
        editReceivable,
        addYXJReceivable,
        saveShouKuan,
        skeditMoney,
        skeditDate,
        getLoupanList,
        getLoudongList,
        getFanghaoList,
        createFanghao,
        finishReceivable
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
                    zt: 2,
                },
								beizhu:'',
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
                downloadLoading:false,
								addFormLoadingbo: false,
                //楼盘数据
                options1: [],
                list1: [],
                loupanloading: false,
                estate: [],//服务器搜索的楼盘数据放入这个数组中
                // 楼栋数据
                options2: [],
                list2: [],
                loudongloading: false,
                building: [],//服务器搜索的楼盘数据放入这个数组中
                // 房间数据
                options3: [],
                list3: [],
                housenoloading: false,
                house: [],//服务器搜索的楼盘数据放入这个数组中
                houseData: [],
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
                rokeBackFormVisible: false,//收款界面是否显示
                rokeBackLoading: false,
                rokeBackFormRules: {
                    tijiaomoney: [{required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字', trigger: 'blur'},
                        {
                            required: true, validator: (rule, value, callback) => {
                            if (value <= 0) {
                                callback(new Error("必须大于0"));
                            } else {
                                callback();
                            }
                        }, trigger: 'blur'
                        }],
                    fkzhanghu: [
                        {required: true, message: '不能为空'},
                    ],
                    fkyinhang: [
                        {required: true, message: '不能为空'},
                    ],
                    fkzhanghao: [
                        {required: true, message: '不能为空'},
                    ],
                },
                YXJ: '',
                editDateFormVisible: false,//编辑界面是否显示
                editDateFormLoading: false,
                editDateFormRules: {
                    tiQianDays: [
                        {required: true, message: '不能为空'},
                    ],
                    isBenQi: [
                        {required: true, message: '不能为空'},
                    ],
                },
                //编辑界面数据
                editDateForm: {
                    tiQianDays: '',
                    isBenQi: '',
                    tCwSrId: '',
                    hetongId: '',
                },
                editMoneyFormVisible: false,//编辑界面是否显示
                editMoneyFormLoading: false,
                editMoneyFormRules: {
                    fuKuanMoney: [{required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字', trigger: 'blur'},
                        {
                            required: true, validator: (rule, value, callback) => {
                            if (value < 0) {
                                callback(new Error("不能小于0"));
                            } else {
                                callback();
                            }
                        }, trigger: 'blur'
                        }],
                },
                //编辑界面数据
                editMoneyForm: {
                    fuKuanMoney: '',
                    yuanMoney: '',
                    tCwSrId: '',
                    hetongId: '',

                },
                //收款界面数据
                rokeBackForm: {
                    tCwSrId: '',
                    tijiaomoney: '',
                },
                headerDate:['合同编号','项目','租户','付款方式','月租金','收款日期','收款科目','应收房租','提交金额','实收金额','修改状态','支付状态','操作'],
                addFormVisible: false,//新增界面是否显示
                addFormLoading: false,
                addFormRules: {
                    skmoney: [
                        {required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字'}
                    ],
                    zuhu: [
                        {required: true, message: '不能为空'},
                    ],
                    skdate: [
                        {required: true, message: '不能为空'},
                    ],

                },
                //新增界面数据
                addForm: {
										tCwSrSubmitId:'',
										status: '',
										beizhu: '',
                },
                viewDateFormVisible: false,//查看界面是否显示
                //查看界面数据
                viewDateForm: {
                    beizhu:'',
                },
                beizhu:null,

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
                var status = arr.indexOf(row.status);
                if (status > -1) {
                    return true;
                } else {
                    return false;
                }
            },
            //标签切换时
            handleClick(tab, event) {

                if (tab.index == 0) {
                    this.filters.zt = 6;
                    this.getReceivable();
                } else if (tab.index == 1) {
                    this.filters.zt = 2;
                    this.getReceivable();
                } else if (tab.index == 2) {
                    this.filters.zt = 4;
                    this.getReceivable();
                } else if (tab.index == 3) {
                    this.filters.zt = 5;
                    this.getReceivable();
                }
            },
            formatFKType(row, column){
                let status = [];
                status[1] = '待审批';
                status[2] = '已通过';
                status[3] = '已驳回';
                return status[row.sktype];
            },
            //状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[1] = '待初审';
                status[2] = '初审通过';
                status[3] = '初审驳回';
                status[4] = '复审通过';
                status[5] = '复审驳回';
                return status[row.status];
            },
						//类型显示转换
						formatType: function (row, column) {
								let status = [];
								status[0] = '押金';
								status[1] = '房租';
								return status[row.kemu];
						},
						//是否修改显示转换
						formatModified: function (row, column) {
								let status = [];
								status[0] = '押金';
								status[1] = '待审批';
								return status[row.srstate];
						},
            //显示查看备注页面
            handleView: function (index, row) {
                this.beizhu = this.Receivable[index].beizhu;
                this.viewDateFormVisible = true;
                // this.viewDateForm  = Object.assign({}, row);
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
            //显示完成弹框
            handleFinish: function (index, row) {
                this.$confirm('确认完成吗？', '提示', {}).then(() => {
                    let para = {
                        id: row.tCwSrId,
                    };
                    finishReceivable(para).then((res) => {
                        if(res.data.code==200) {
                            this.$message({
                                message: '完成成功',
                                type: 'success'
                            });
                        } else{
                            this.$message({
                                message: res.data.msg,
                                type: 'error'
                            });
                        }
                        this.getReceivable();
                    });
                });
            },
            //获取楼盘
            remoteMethod1(query) {
                let para = {
                    str: query
                };
                this.loupanloading = true;
                getLoupanList(para).then((res) => {
                    let arr = [];
                    arr[0] = '';
                    for (var i in res.data) {
                        arr[i] = res.data [i];
                    }
                    this.estate = arr;
                    this.loupanloading = false;
                    this.list = this.estate.map((item, index) => {
                        return {value: index, label: item};
                    });
                    if (query !== '') {
                        this.options1 = this.list.filter(item => {
                            return item.label.toLowerCase()
                                    .indexOf(query.toLowerCase()) > -1;
                        });
                    } else {
                        this.options1 = [];
                    }
                });

            },
            //获取楼栋
            remoteMethod2(query) {
                let para = {
                    loupanOmcId: this.addForm.loupanid,
                };
                this.loupanloading = true;
                getLoudongList(para).then((res) => {
                    let arr = [];
                    arr[0] = '';
                    for (var i in res.data) {
                        arr[i] = res.data [i];
                    }
                    this.building = arr;
                    this.loupanloading = false;
                    this.list2 = this.building.map((item, index) => {
                        return {value: index, label: item};
                    });
                    if (query !== '') {
                        this.options2 = this.list2.filter(item => {
                            return item.label.toLowerCase()
                                    .indexOf(query.toLowerCase()) > -1;
                        });
                    } else {
                        this.options2 = [];
                    }
                });

            },
            //获取房号
            remoteMethod3(query) {
                let para = {
                    lpid: this.addForm.loupanid,
                    zdid: this.addForm.loudongid,
                };
                this.housenoloading = true;
                //console.log(para);
                getFanghaoList(para).then((res) => {
                    this.houseData = res.data;
                    let arr = [];
                    arr[0] = '';
                    for (var i in res.data) {
                        arr[res.data[i].id] = res.data[i].fybh;
                    }
                    this.house = arr;
                    this.housenoloading = false;
                    this.list3 = this.house.map((item, index) => {
                        return {value: index, label: item};
                    });
                    if (query !== '') {
                        this.options3 = this.list3.filter(item => {
                            return item.label.toLowerCase()
                                    .indexOf(query.toLowerCase()) > -1;
                        });
                    } else {
                        this.options3 = [];
                    }
                });

            },
            //得到房间号以后，提取OMC的对应信息
            change1(){
                //楼盘
                for (var x in this.options1) {
                    if (this.options1[x].label == this.addForm.loupanName) {
                        this.addForm.loupanid = this.options1[x].value;
                        this.addForm.loudongName = null;//清除楼栋和房号的缓存
                        this.addForm.loudongid = null;//清除楼栋和房号的缓存
                        this.addForm.houseno = null;//清除楼栋和房号的缓存
                        this.addForm.omcId = null;//清除楼栋和房号的缓存
                    }
                }
            },
            change2(){
                //楼栋
                for (var x in this.options2) {
                    if (this.options2[x].label == this.addForm.loudongName) {
                        this.addForm.loudongid = this.options2[x].value;
                        this.addForm.houseno = null;//清除楼栋和房号的缓存
                        this.addForm.omcId = null;//清除楼栋和房号的缓存
                    }
                }
            },
            change3(){
                //房号
                for (var x in this.options3){
                    if(this.options3[x].label==this.addForm.houseno){
                        this.addForm.omcId=this.options3[x].value;
                    }
                }
                if (this.addForm.omcId == null&& this.addForm.houseno!=null) {
                    let para = {
                        loupanOmcId: this.addForm.loupanid,
                        loudongOmcId: this.addForm.loudongid,
                        fanghao: this.addForm.houseno,
                    }
                    createFanghao(para).then((res => {
                        this.addForm.omcId = res.data.data;
                        this.$message({
                            message: '楼盘字典中不存在该房源，已自动创建',
                            type: 'success'
                        });
                    }))
                }
            },
            //获取应收款列表
            getReceivable() {
                let para = {
                    page: this.page,
                    size: this.pageSize,
					isfirst:1,
                    htno: this.filters.htno,
                    xm: this.filters.xm,
                    zt: this.filters.zt,
                    startdate: this.filters.startdate,
                    enddate: this.filters.enddate,
                };
                console.log(para);
                this.listLoading = true;
                getReceivableAuditList(para).then((res) => {
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
            //新增应收页面
            handleAdd: function (index, row) {
                this.addFormVisible = true;
                this.YXJ = '新增';
                this.addForm = {
                    loupanid: null,
                    loudongid: null,
                    skmoney: '',
                    loupanName: '',
                    loudongName: '',
                    houseno: null,
                    zuhu: '',
                    skdate: '',
                };
            },
            //修改应收金额页面
            handleEditYS: function (index, row) {
                this.$router.push('/receivableSubmissionAudit?id=' + row.tCwSrSubmitId);
            },
            //显示编辑界面
            handleEdit: function (index, row) {

                this.editDateFormVisible = true;
                this.editDateForm = Object.assign({}, row);
                this.editDateForm = {
                    tiQianDays: '',
                    isBenQi: '',
                    tCwSrId: row.tCwSrId,
                    hetongId: row.hetongid,
                };
            },
            //显示编辑界面
            handleMoneyEdit: function (index, row) {

                this.editMoneyFormVisible = true;
                this.editMoneyForm = Object.assign({}, row);
                this.editMoneyForm = {
                    fuKuanMoney: '',
                    yuanMoney: '',
                    tCwSrId: row.tCwSrId,
                    hetongId: row.hetongid,
                };
            },
            //显示付款界面
            handleRokeBack: function (index, row) {
								this.rokeBackFormVisible = true;
								// this.renlingData.tCwSrCaiwuId=row.tCwSrCaiwuId;
								this.addForm = Object.assign({}, row);
								this.addForm = {
									tCwSrSubmitId: row.tCwSrSubmitId,
									status: '',
									beizhu: '',
								};
            },
            //提交审核的数据1 通过 2驳回
            addFormSubmit(type){
							this.addForm.status = type;
							this.$refs.addForm.validate((valid) => {
								if (valid) {
									this.$confirm('确认提交吗？', '提示', {}).then(() => {
											if(this.addForm.status == 3){
												this.addFormLoading = true;
											}else{
												this.addFormLoadingbo = true;
											}
											
											let para = Object.assign({}, this.addForm);
											bleSubmission(para).then((res) => {
												if(this.addForm.status == 3){
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
												if(this.addForm.status == 3){
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
            //提交数据
            editFormSubmit(){
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editFormLoading = true;
                            let para = Object.assign({}, this.editForm);
                            editReceivable(para).then((res) => {
                                this.editFormLoading = false;
                                if (res.data.code == 200) {
                                    this.$message({
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    this.$refs['editForm'].resetFields();
                                } else {
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                                this.editFormVisible = false;
                                this.getReceivable();
                            });
                        });
                    }
                });
            },
            //编辑
            editDateFormSubmit: function () {
                this.$refs.editDateForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editDateFormLoading = true;
                            let para = Object.assign({}, this.editDateForm);
                            skeditDate(para).then((res) => {
                                this.editDateFormLoading = false;
                                if (res.data.code == 200) {
                                    this.$message({
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    this.$refs['editDateForm'].resetFields();
                                } else {
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                                this.editDateFormVisible = false;
                                this.getReceivable();
                            });
                        });
                    }
                });
            },
            editMoneyFormSubmit: function () {
                this.$refs.editMoneyForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editMoneyFormLoading = true;
                            let para = Object.assign({}, this.editMoneyForm);
                            skeditMoney(para).then((res) => {
                                this.editMoneyFormLoading = false;
                                if (res.data.code == 200) {
                                    this.$message({
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    this.$refs['editMoneyForm'].resetFields();
                                } else {
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                                this.editMoneyFormVisible = false;
                                this.getReceivable();
                            });
                        });
                    }
                });
            },
            //收款
            rokeBackSubmit: function () {
                this.$refs.rokeBackForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.rokeBackLoading = true;
                            let para = Object.assign({}, this.rokeBackForm);
                            saveShouKuan(para).then((res) => {
                                this.rokeBackLoading = false;
                                if (res.data.code == 200) {
                                    this.$message({
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    this.$refs['rokeBackForm'].resetFields();
                                } else {
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                                this.rokeBackFormVisible = false;
                                this.getReceivable();
                            });
                        });
                    }
                });
            },
            //打开应付记录页面
            handleOpen: function (index, row) {
                this.$router.push('/accountsReceivable?id=' + row.tCwSrId);
            },
            handleOpenUp: function (index, row) {
                this.$router.push('/receivableRecord?id=' + row.tCwSrId);
            },
						handleCollect: function (index, row) {
								// this.$router.push('/receivableCollect?id=' + row.tCwSrId + '&hetongid=' + row.hetongid);
								this.$router.push('/receivableModify?id=' + row.tCwSrId + '&hetongid=' + row.hetongid);
						},
						handleSubmission: function (index, row) {
								this.$router.push('/receivableSubmission?id=' + row.tCwSrId + '&hetongid=' + row.hetongid);
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