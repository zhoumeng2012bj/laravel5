<template>
    <el-row >
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px" lable-width="80px;">
            <el-form-item label="合同编号:">
                <el-input v-model="filters.contractNo" placeholder="请输入合同编号"></el-input>
            </el-form-item>
            <el-form-item label="项目名称:">
                <el-input v-model="filters.xm" placeholder="请输入项目名称"></el-input>
            </el-form-item>
            <!-- <el-form-item label="业主名称:">
                <el-input v-model="filters.yz" placeholder="请输入业主名称"></el-input>
            </el-form-item><br/> -->
            <el-form-item label="付款日期:">
            <el-date-picker  type = "date" placeholder="请选择开始日期" v-model="filters.startdate">
            </el-date-picker>
            </el-form-item>
            <el-form-item label="至">
            <el-date-picker type = "date" placeholder="请选择结束日期" v-model="filters.enddate">
            </el-date-picker>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search"  v-on:click="getPayable">搜索</el-button>
                <!-- <el-button v-if="fun('payableAddYXJ')" type="primary" class="el-icon-plus" @click="handleAdd">新增</el-button> -->
            </el-form-item>
        </el-form>
         	<div class="totals_box">
         		<!-- <p>
         			合计 <span class="totals">应付金额：{{DataSum.sumMoney}}</span>
	         		<span class="totals">提交金额：{{DataSum.tijiaoMoney}}</span>
	         		<span class="totals">实付金额：{{DataSum.shijiMoney}}</span>
         		</p> -->
         		<p>
         			<span style="color:red;font-size: 14px;">(注：红色日期表示付款已延期，请尽快处理)</span>         			
         		</p>
         	</div>
        <el-tabs v-model="activeName2" type="border-card" @tab-click="handleClick">
            <el-tab-pane label="全部" name="first"></el-tab-pane>
            <el-tab-pane label="待提交" name="second"></el-tab-pane>
            <el-tab-pane label="已提交" name="third"></el-tab-pane>
            <el-tab-pane label="已撤回" name="fifth"></el-tab-pane>
            <!-- <el-tab-pane label="已驳回" name="sixth"></el-tab-pane> -->
            <el-table :data="Payable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
                <el-table-column prop="hetongbianhao" label="合同编号" width="190">
                </el-table-column>
                <el-table-column prop="xiangmu" label="项目">
                </el-table-column>
               <!-- <el-table-column prop="yezhu" label="业主/租户"  width="100" >
                </el-table-column>
                <el-table-column prop="fkfangshi" label="付款方式"  width="95" >
                </el-table-column> -->
                <el-table-column prop="monthmoney" label="月租金" width="80" >
                </el-table-column>
								<el-table-column prop="fktype" label="类型" width="95"   :formatter="formatFKType">
								</el-table-column>
								<el-table-column prop="zhouqi" label="周期" width="130">
								</el-table-column>
								<el-table-column prop="fukuandate" label="应付日期" width="120"   >
										<template slot-scope="scope">
												<span :class="tableClassName(scope.row.fukuandate,scope.row.zhifustate)">  {{ changeDate(scope.row.fukuandate) }}</span>
										</template>
								</el-table-column>
								<el-table-column prop="fukuanmoney" label="应付金额" width="110">
								</el-table-column>
								<el-table-column prop="tijiaomoney" label="提交金额" width="110">
								</el-table-column>
								<el-table-column prop="daifumoney" label="代付金额" width="110">
								</el-table-column>
								<el-table-column prop="shifumoney" label="实际应付" width="110">
								</el-table-column>
								<el-table-column prop="skinfo" label="收款账号"   width="180">
								</el-table-column>
								<el-table-column prop="tijiaostate" label="状态"  :formatter="formatState"  width="95">
								</el-table-column>
               <!-- <el-table-column prop="tijiaomoney" label="提交金额"  width="95">
                </el-table-column>
                <el-table-column prop="shifumoney" label="实付金额"  width="95" >
                </el-table-column>
                <el-table-column prop="xiugaizhuangtai" label="修改状态"   width="95">
                </el-table-column> -->
                <el-table-column label="操作" width="120">
                       <template slot-scope="scope">
                           <el-dropdown   menu-align="start">
                               <el-button type="primary" size="normal" splitButton="true">
                                   操作<i class="el-icon-caret-bottom el-icon--right"></i>
                               </el-button>
                               <el-dropdown-menu slot="dropdown" >
																	<el-dropdown-item v-if="ztin(scope.row,[0])&&fun('EASub')"><el-button   @click="handleRokeBack(scope.$index, scope.row)">提交</el-button></el-dropdown-item>
                                  <el-dropdown-item v-if="ztin(scope.row,[0])&&fun('EADed')"><el-button   @click="handleEditYS(scope.$index, scope.row)">代付扣款</el-button></el-dropdown-item>
                                   <!-- <el-dropdown-item  v-if="scope.row.xiugaizhuangtai=='已修改'&&fun('payableRecord') "> <el-button  @click="handleOpen(scope.$index, scope.row)">修改记录</el-button> </el-dropdown-item> -->
                                  <el-dropdown-item v-if="ztin(scope.row,[0,1,2])&&fun('EADedL')"> <el-button  @click="handleOpenUp(scope.$index, scope.row)">扣款记录</el-button> </el-dropdown-item>
                                   <!-- <el-dropdown-item   v-if="ztin(scope.row,[0,1,2,4])&&fun('payableEidtDate')"><el-button  v-if="scope.row.fktype<20"  @click="handleEdit(scope.$index, scope.row)">编辑付款日期</el-button></el-dropdown-item>
                                   <el-dropdown-item   v-if="ztin(scope.row,[0,1,2,4])&&fun('payableEidtMoney')"><el-button   v-if="scope.row.fktype<20" @click="handleMoneyEdit(scope.$index, scope.row)">编辑付款金额</el-button></el-dropdown-item>
                                   <el-dropdown-item v-if="ztin(scope.row,[0,1,2,4])&&fun('payableEidt')">
                                       <el-button v-if="scope.row.fktype==20" @click="handleEditYS(scope.$index, scope.row)">编辑</el-button>
                                   </el-dropdown-item>
                                   <el-dropdown-item v-if="ztin(scope.row,[0,1,2,4])&&fun('payableFinish')">
                                       <el-button @click="handleFinish(scope.$index, scope.row)">完成</el-button>
                                   </el-dropdown-item> -->
                               </el-dropdown-menu>
                           </el-dropdown>
                       </template>
                </el-table-column>
               </el-table>
               <div style="margin-top:30px"></div>
               <!-- 分页-->
            <el-col :span="24" class="toolbar" >

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
        <el-dialog title="提示" v-model="rokeBackFormVisible" :close-on-click-modal="false">
            <el-form label-width="120px" ref="viewDateForm">
            	提交后，这条记录会同步至应付计划中，并显示在客户的付款计划中，请知晓！
            </el-form>
						<div slot="footer" class="dialog-footer">
								<el-button @click.native="rokeBackFormVisible = false">取消</el-button>
								<el-button type="primary" @click.native="rokeBackSubmit" :loading="rokeBackLoading">确认</el-button>
						</div>
        </el-dialog>


        <el-dialog title="代付扣款" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="120px" :rules="addFormRules" ref="addForm">
                <el-row>
                    
									<el-col :span="12">
										<el-form-item label="代缴物业：" prop="wuye">
												<el-input v-model.number="addForm.wuye" auto-complete="off"></el-input>
										</el-form-item>
										<el-form-item label="代缴供暖：" prop="gongnuan">
												<el-input v-model.number="addForm.gongnuan" auto-complete="off"></el-input>
										</el-form-item>
										<el-form-item label="代缴制冷：" prop="zhileng">
												<el-input v-model.number="addForm.zhileng" auto-complete="off"></el-input>
										</el-form-item>
										<el-form-item label="代缴空调转换：" prop="kongtiaozhuanhuan">
												<el-input v-model.number="addForm.kongtiaozhuanhuan" auto-complete="off"></el-input>
										</el-form-item>
										<el-form-item label="代缴维修费：" prop="weixiu">
												<el-input v-model.number="addForm.weixiu" auto-complete="off"></el-input>
										</el-form-item>
										<el-form-item label="其他：" prop="qita">
												<el-input v-model.number="addForm.qita" auto-complete="off"></el-input>
										</el-form-item>
									</el-col>
                </el-row>
                <el-row>
								<el-col :span="12">
									<el-form-item label="备注：">
											<el-input type="textarea" v-model="addForm.beizhu" auto-complete="off"></el-input>
									</el-form-item>
								</el-col>
                </el-row>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="addFormSubmit" :loading="addFormLoading">提交</el-button>
            </div>
        </el-dialog>
        <el-dialog title="编辑付款日期" v-model="editDateFormVisible" :close-on-click-modal="false" style="width:1000px;margin-left: 250px;">
            <el-form :model="editDateForm" label-width="120px" :rules="editDateFormRules" ref="editDateForm"  >
                <el-row>
                    <el-col :span="10">
                        <el-form-item label="提前付款：" prop="tiQianDays">
                            <el-input v-model.number="editDateForm.tiQianDays" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="1" style="text-align: right;margin-top: 2px;line-height: 36px;margin-right: 50px;">
                        天
                    </el-col>
                    <el-col :span="10">
                        <el-form-item label="" prop="isBenQi" style='margin-left: -120px;'>
                            <el-radio-group v-model="editDateForm.isBenQi" style='display: flex;height: 36px;align-items: center;'>
                                <el-radio class="radio" label=true>本期</el-radio>
                                <el-radio class="radio" label=false>所有</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                </el-row>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editDateFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editDateFormSubmit" :loading="editDateFormLoading">保存</el-button>
            </div>
        </el-dialog>

        <el-dialog title="编辑付款金额" v-model="editMoneyFormVisible" :close-on-click-modal="false" style="width:800px;margin-left: 400px;">
            <el-form :model="editMoneyForm" label-width="125px" :rules="editMoneyFormRules" ref="editMoneyForm" >
                <el-row>
                    <el-col :span="20">
                        <el-form-item label="本期付款金额：" prop="shouKuanMoney">
                            <el-input   v-model.number="editMoneyForm.shouKuanMoney" auto-complete="off"></el-input>
                        </el-form-item>
                    </el-col>

                </el-row>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editMoneyFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editMoneyFormSubmit" :loading="editMoneyFormLoading">保存</el-button>
            </div>
        </el-dialog>
    </el-row>
</template>
<style>
    .el-table .info-row {
        color:red;
    }
   /*.el-form-item__content{
   		margin-left: 10px!important;
   }*/
</style>
<script>

    import {
			getPayAmontList,
			subCarefulPayable,
			commissionPayable,
			
			
			
			
        getPayableListPage,
        editDate,
        saveFuKuan,
        editMoney,
        getLoupanList,
        getLoudongList,
        getFanghaoList,
        createFanghao,
        editPayable,
        addPayable,
        finishPayable
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
								tjfuid:'',
                filters:{
                    contractNo: '',
                    yz:'',
                    xm:'',
                    zt2:'',
                    startdate:'',
                    enddate:'',

                },
								xianzaje:'',
                options:[
                    {
                        value: 1,
                        label: '押金'
                    }, {
                        value: 2,
                        label: '房租'
                    },
                ],
                optionszt:[
                    {
                        value: 0,
                        label: '未提交'
                    }, {
                        value: 1,
                        label: '已提交'
                    },
                    {
                        value: 2,
                        label: '部分已付'
                    }, {
                        value: 3,
                        label: '已完成'
                    },
                    {
                        value: 4,
                        label: '已驳回'
                    }
                ],
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
                total:0,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                Payable:[],
                DataSum:[],
                activeName2:'first',
                listLoading: false,
                sels: [],//列表选中列

                rokeBackFormVisible: false,//收款界面是否显示
                rokeBackLoading: false,
                rokeBackFormRules: {
                    tijiaomoney:[{required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字', trigger: 'blur'},
                        {  required: true, validator: (rule, value, callback) => {
                            if ( value <= 0) {
                                callback(new Error("必须大于0"));
                            } else {
                                callback();
                            }
                        }, trigger: 'blur'
                        }],
                },

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
                    tiQianDays:'',
                    isBenQi:'',
                    tCwFcId:'',
                    hetongId:'',
                },
                editMoneyFormVisible: false,//编辑界面是否显示
                editMoneyFormLoading: false,
                editMoneyFormRules: {
                    shouKuanMoney: [{required: true, message: '不能为空'},
                        {type: 'number', message: '必须为数字', trigger: 'blur'},
                        {  required: true, validator: (rule, value, callback) => {
                            if ( value < 0) {
                                callback(new Error("不能小于0"));
                            } else {
                                callback();
                            }
                        }, trigger: 'blur'
                        }]
                },
                //编辑界面数据
                editMoneyForm: {
                    shouKuanMoney:'',
                    yuanMoney:'',
                    tCwFcId:'',
                    hetongId:'',

                },
                //付款界面数据
                rokeBackForm: {
                    tCwFcSubmitId:'',
                },
                YXJ: '',
                addFormVisible: false,//新增界面是否显示
                addFormLoading: false,
                addFormRules: {
                    gongnuan: [
                        {
													type: 'number',
													trigger: 'blur',
													required: false,
													message: '必须为数字',
													transform (value) {
														return _.toNumber(value)
													}
												}
                    ],
										wuye: [
												{
													type: 'number',
													trigger: 'blur',
													required: false,
													message: '必须为数字',
													transform (value) {
														return _.toNumber(value)
													}
												}
										],
										zhileng: [
												{
													type: 'number',
													trigger: 'blur',
													required: false,
													message: '必须为数字',
													transform (value) {
														return _.toNumber(value)
													}
												}
										],
										kongtiaozhuanhuan: [
												{
													type: 'number',
													trigger: 'blur',
													required: false,
													message: '必须为数字',
													transform (value) {
														return _.toNumber(value)
													}
												}
										],
										qita: [
												{
													type: 'number',
													trigger: 'blur',
													required: false,
													message: '必须为数字',
													transform (value) {
														return _.toNumber(value)
													}
												}
										],
										weixiu: [
												{
													type: 'number',
													trigger: 'blur',
													required: false,
													message: '必须为数字',
													transform (value) {
														return _.toNumber(value)
													}
												}
										],
										
                },

                //新增界面数据
                addForm: {
                    gongnuan: '',
                    wuye: '',
                    zhileng: '',
                    kongtiaozhuanhuan: '',
                    qita: '',
                    weixiu: '',
                    totalMoney: '',
										beizhu: '',
                },
                //被选中的权限
                checked:[],
            }
        },
        methods:{
            tableClassName(fkdate,fkstate){
                //return 'info-row';
                if(fkdate<new Date()&&fkstate!=1&&fkstate!=3){
                    return 'info-row';
                }else{
                    return '';
                }
            },
            ztin(row,arr){
                var status = arr.indexOf(row.tijiaostate);
                if(status>-1){
                    return true;
                }else{
                    return false;
                }
            },
            formatFKType(row, column){
                let status = [];
                status[0] = '押金';
                status[1] = '房租';
								status[2] = '补偿三方佣金';
								status[10] = '退还租金';
								status[11] = '退还房租';
								status[20] = '意向金';
                return status[row.fktype];
            },
            //状态显示转换
            formatState: function (row, column) {
                let status = [];
                status[0] = '待提交';
                status[1] = '已提交';
								status[2] = '已撤回';
                return status[row.tijiaostate];
            },
            //时间戳转日期格式
            changeDate(fkdate){
                var newDate = new Date();
                newDate.setTime(fkdate);
                return newDate.toLocaleDateString()
            },
            //标签切换时
            handleClick(tab, event) {
                var ztStatus = null;
                if(tab.index==0){
                    ztStatus = '';
                }else{
                    ztStatus = tab.index -1;
                }
                this.filters.zt2 = ztStatus;
                this.getPayable();

            },
            //页面跳转后
            handleCurrentChange(val) {
                this.page = val;
                this.getPayable();
            },
            //更改每页显示数据
            handleSizeChange(val){
                this.pageSize =val;
                this.getPayable();
            },
            //打开应付记录页面
            handleOpen: function (index, row) {
                this.$router.push('/paymentRecord?id=' + row.tCwFcId);
            },
            handleOpenUp: function (index, row) {
                this.$router.push('/payableFinanceSubm?id=' + row.tCwFcSubmitId);
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
            //获取应付款列表
            getPayable() {
                let para = {
									page: this.page,
									pageSize: this.pageSize,
									htno: this.filters.contractNo,
									xm: this.filters.xm,
									sdate: this.filters.startdate,
									edate: this.filters.enddate,
									tijiaoState:this.filters.zt2,
                };
                this.listLoading = true;
                getPayAmontList(para).then((res) => {
                    this.total = res.data.total;
                    this.Payable = res.data.data;
                    this.DataSum=res.data.dataSum;
                    if(res.data.dataSum == null){
                        this.DataSum =
                        {sumMoney: 0, tijiaoMoney: 0, shijiMoney: 0};
                    }
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
                    fkmoney: '',
                    loupanName: '',
                    loudongName: '',
                    houseno: null,
                    skzhanhu: '',
                    fkdate: '',
                    skyinhang: '',
                    zhanghao: ''
                };
            },
            //编辑应收页面
            handleEditYS: function (index, row) {
                this.addFormVisible = true;
								this.addForm  = Object.assign({}, row);
								this.xianzaje = row.shifumoney;
								this.addForm = {
										tCwFcSubmitId:row.tCwFcSubmitId,
										beizhu:'',
										gongnuan:'',
										wuye:'',
										zhileng:'',
										kongtiaozhuanhuan:'',
										qita:'',
										weixiu:'',
										totalMoney:'',
								};
            },
            //显示付款界面
            handleRokeBack: function (index, row) {
                this.rokeBackForm  = Object.assign({}, row);
                this.rokeBackForm = {
                		tCwFcSubmitId:row.tCwFcSubmitId,
                };
								this.$confirm('确认提交吗？', '提示', {}).then(() => {
										this.rokeBackLoading = true;
										// let para = Object.assign({}, this.rokeBackForm);
										let para = {
												tCwFcSubmitId:this.rokeBackForm.tCwFcSubmitId,
										}
										subCarefulPayable(para).then((res) => {
												this.rokeBackLoading = false;
												if(res.data.code==200) {
												this.$message({
														message: '提交成功',
														type: 'success'
												});
												this.rokeBackFormVisible = false;
												this.getPayable();
												this.$refs['rokeBackForm'].resetFields();
												}else{
														this.$message({
																message: res.data.msg,
																type: 'error'
														});
												}
												
										});
								});
            },
            //显示编辑界面
            handleEdit: function (index, row) {

                this.editDateFormVisible = true;
                this.editDateForm = Object.assign({}, row);
                this.editDateForm = {
                    tiQianDays:'',
                    isBenQi:'',
                    tCwFcId:row.tCwFcId,
                    hetongId:row.hetongid,
                };
            },
            //显示完成弹框
            handleFinish: function (index, row) {
                this.$confirm('确认完成吗？', '提示', {}).then(() => {
                    let para = {
                        id: row.tCwFcId,
                    };
                    finishPayable(para).then((res) => {
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
                        this.getPayable();
                    });
                });
            },

            //显示编辑界面
            handleMoneyEdit: function (index, row) {

                this.editMoneyFormVisible = true;
                this.editMoneyForm = Object.assign({}, row);
                this.editMoneyForm = {
                    shouKuanMoney:'',
                    yuanMoney:'',
                    tCwFcId:row.tCwFcId,
                    hetongId:row.hetongid,
                };
            },
            //提交数据
            addFormSubmit(){
							var jiee = Number(this.addForm.wuye)+Number(this.addForm.gongnuan)+Number(this.addForm.zhileng)+Number(this.addForm.kongtiaozhuanhuan)+Number(this.addForm.weixiu)+Number(this.addForm.qita);
							if(jiee > this.xianzaje){
								this.$message({
										message: '代付扣款金额不能大于提交金额！',
										type: 'error'
								});
							}else{
               this.$refs.addForm.validate((valid) => {
               		if (valid) {
										if(this.addForm.gongnuan != '' || this.addForm.wuye != '' || this.addForm.zhileng != '' || this.addForm.kongtiaozhuanhuan != '' || this.addForm.qita != '' || this.addForm.weixiu != ''){
												this.addForm.totalMoney = Number(this.addForm.gongnuan) + Number(this.addForm.wuye) + Number(this.addForm.zhileng) + Number(this.addForm.kongtiaozhuanhuan) + Number(this.addForm.qita) + Number(this.addForm.weixiu);
												this.$confirm('确认提交吗？', '提示', {}).then(() => {
														this.addFormLoading = true;
														let para = Object.assign({}, this.addForm);
														commissionPayable(para).then((res) => {
																this.addFormLoading = false;
																if (res.data.code == 200) {
																	if(res.data.success){
																		this.$message({
																				message: '提交成功',
																				type: 'success'
																		});
																		this.addFormVisible = false;
																		this.getPayable();
																	}else{
																		this.$message({
																				message: res.data.msg,
																				type: 'error'
																		});
																	}
																} else {
																		this.$message({
																				message: res.data.msg,
																				type: 'error'
																		});
																}
														});
													}
												);
										}else{
											this.$message({
													message: '代缴费用不能全部为空！',
													type: 'error'
											});
										}
									}
                });
							}
            },
            //编辑
            editDateFormSubmit: function () {
                this.$refs.editDateForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editDateFormLoading = true;
                            let para = Object.assign({}, this.editDateForm);
                            editDate(para).then((res) => {
                                this.editDateFormLoading = false;
                                if(res.data.code==200) {
                                    this.$message({
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    this.$refs['editDateForm'].resetFields();

                                }else{
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                                this.editDateFormVisible = false;
                                this.getPayable();
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
                            editMoney(para).then((res) => {
                                this.editMoneyFormLoading = false;
                                if(res.data.code==200) {
                                this.$message({
                                    message: '提交成功',
                                    type: 'success'
                                });
                                this.$refs['editMoneyForm'].resetFields();

                                }else{
                                    this.$message({
                                        message: res.data.msg,
                                        type: 'error'
                                    });
                                }
                                this.editMoneyFormVisible = false;
                                this.getPayable();
                            });
                        });
                    }
                });
            },
            //付款
            rokeBackSubmit: function () {
							this.$confirm('确认提交吗？', '提示', {}).then(() => {
									this.rokeBackLoading = true;
									// let para = Object.assign({}, this.rokeBackForm);
									let para = {
											tCwFcSubmitId:this.rokeBackForm.tCwFcSubmitId,
									}
									subCarefulPayable(para).then((res) => {
											this.rokeBackLoading = false;
											if(res.data.code==200) {
											this.$message({
													message: '提交成功',
													type: 'success'
											});
											this.rokeBackFormVisible = false;
											this.getPayable();
											this.$refs['rokeBackForm'].resetFields();
											}else{
													this.$message({
															message: res.data.msg,
															type: 'error'
													});
											}
											
									});
							});
            },
            selsChange: function (sels) {
                this.sels = sels;
            },

        },
        mounted() {
            this.page=1;
            this.getPayable();

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