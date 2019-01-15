<template>
    <el-row>
        <el-row>
            <div style="margin-top:30px;list-style-type:none;"></div>
            <li   style="list-style-type:none;" >
                <el-row>
                    <el-col :span="6">
                        <span>合同编号：{{bianhao}}</span>
                    </el-col>
                    <el-col :span="4">
                        <span>合同类型：收房合同</span>
                    </el-col>
                    <el-col :span="4">
                        <span >楼盘：{{officeList[0].loupanName}}</span>
                    </el-col>
                    <el-col :span="4">
                        <span >楼栋：{{officeList[0].loudongName}}</span>
                    </el-col>
                    <el-col :span="4">
                        <span>房间号： {{officeList[0].fanghao}}</span>
                    </el-col>
                    <el-col :span="0" style="margin-rigjt:0;float:right;margin-top:50;">
                        <el-button type="primary" v-if="fun('purchaseContactZHAdd')"  class="el-icon-plus" @click="addContract"> 新增</el-button>
                    </el-col>
                </el-row>
            </li>
        </el-row>
        <el-row>
            <el-table :data="lists" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
                <!--<if condition="laiyuantype eq 2">-->
                    <el-table-column prop="zhanghu"  label="户名"  width="100">
                    </el-table-column>
                    <el-table-column prop="shoufangzonghang"  label="银行"  width="220"> 
                    </el-table-column>
										<el-table-column prop="yinhang"  label="开户行"  width="220">
										</el-table-column>
                    <el-table-column prop="zhanghao"  label="账号"  width="220">
                    </el-table-column>
										<el-table-column prop="shengfen"  label="开户省">
										</el-table-column>
										<el-table-column prop="shi"  label="市">
										</el-table-column>
										<el-table-column prop="duizhangtype"  label="账户类型" width="100" :formatter="forduizhmatStat">
										</el-table-column>
                    <el-table-column prop="tianjiadate" label="添加时间"  :formatter="changeDate" width="120">
                    </el-table-column>
                    <el-table-column prop="laiyuantype"  label="来源"   :formatter="formatStatus"  >
                    </el-table-column>
                    <el-table-column prop="stateid" label="状态"  :formatter="formatState"   >
                    </el-table-column>
                    <el-table-column label="操作" width="300">
                        <template slot-scope="scope">
                                <el-button v-if="fun('purchaseContactZHEdit')&&!hanshu(scope.row)&&scope.row.stateid>2"   type="primary" size="normal" splitButton="true" @click="editYHZH(scope.$index, scope.row)">
                                    编辑
                                </el-button>
                                <el-button  v-if="fun('purchaseContactZHAudit')&&scope.row.stateid==1"  type="primary" size="normal" splitButton="true" @click="handleShenHe(scope.$index, scope.row)">
                                    审核
                                </el-button>
                                <el-button  v-if="fun('purchaseContactZHStop')&&scope.row.stateid==2"  type="primary" size="normal" splitButton="true" @click="handleStop(scope.$index, scope.row)">
                                   停用
                                </el-button>
                                <el-button v-if="fun('purchaseContactZHStart')&&scope.row.stateid>2"  type="primary" size="normal" splitButton="true" @click="handleStart(scope.$index, scope.row)">
                                    启用
                                </el-button>
                                <el-button v-if="fun('purchaseContactZHDel')&&!hanshu(scope.row)"  type="primary" size="normal" splitButton="true" @click="handleDel(scope.$index, scope.row)">
                                    删除
                                </el-button>
                        </template>
                    </el-table-column>
            </el-table>
        </el-row>
        <!-- 分页-->
        <el-col :span="24" class="toolbar" >
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="currentPage"
                    :page-sizes="pageSizes"
                    :page-size="pageSize"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total=total
                    style="float:right"
            >
            </el-pagination>
        </el-col>
        <el-dialog size="tiny" title="付款账号管理" v-model="Visible" :close-on-click-modal="false" label-width="120px">
            <el-form  label-width="80px"  ref="zhanghao" :rules="zhanghaoRules" :model="zhanghao">
							<el-form-item label="账户类型" prop="duizhangType">
									<el-radio-group v-model="zhanghao.duizhangtype" >
											<el-radio label="1">公户</el-radio>
											<el-radio label="2">私户</el-radio>
									</el-radio-group>
							</el-form-item>
							<el-row>
									<el-col :span="8">
											<el-form-item label="户名" prop="zhanghu" required>
													<el-input v-model="zhanghao.zhanghu" :disabled="lydisabled"></el-input>
											</el-form-item>
									</el-col>
									<el-col :span="10">
											<el-form-item label="银行" required prop="shoufangzonghang">
													<el-select
																	v-model="zhanghao.shoufangzonghangid"
																	filterable
																	default-first-option
																	remote
																	@change="change1"
																	placeholder=""
																	:remote-method="bankCompany"
																	:loading="bankCNameloading"
																	:disabled="lydisabled"
													>
															<el-option
																			v-for="item in options3"
																			:key="item.value"
																			:label="item.label"
																			:value="item.value">
															</el-option>
													</el-select>
											</el-form-item>
									</el-col>
							</el-row>
							<el-row>
									<el-col :span="8">
											<el-form-item label="开户行" prop="yinhang" required>
													<el-input v-model="zhanghao.yinhang" :disabled="lydisabled"></el-input>
											</el-form-item>
									</el-col>
									<el-col :span="10">
											<el-form-item label="账号" prop="zhanghao" required>
													<input v-model="zhanghao.zhanghao" :disabled="lydisabled"
																style="-webkit-appearance: none;
																			-moz-appearance: none;
																			appearance: none;
																			background-color: #fff;
																			background-image: none;
																			border-radius: 4px;
																			border: 1px solid #bfcbd9;
																			box-sizing: border-box;
																			color: #1f2d3d;
																			font-size: inherit;
																			height: 36px;
																			line-height: 1;
																			outline: 0;
																			padding: 3px 10px;
																			transition: border-color .2s cubic-bezier(.645,.045,.355,1);
																			width: 100%;" onkeyup="this.value=this.value.replace(/\D/g,'').replace(/....(?!$)/g,'$& ')"
																>
											</el-form-item>
									</el-col>
							</el-row>
							<el-row>
									<el-col :span="8">
											<el-form-item label="开户省" required prop="shengfen">
													<el-select
																	v-model="zhanghao.shengfenid"
																	filterable
																	default-first-option
																	remote
																	@change="change2"
																	placeholder=""
																	:remote-method="bankCompany2"
																	:loading="bankCName2loading"
																	:disabled="lydisabled"
													>
															<el-option
																			v-for="item in options4"
																			:key="item.value"
																			:label="item.label"
																			:value="item.value">
															</el-option>
													</el-select>
											</el-form-item>
									</el-col>
									<el-col :span="10">
											<el-form-item label="市" prop="shi" required>
													<el-input v-model="zhanghao.shi" :disabled="lydisabled" ></el-input>
											</el-form-item>
									</el-col>
							</el-row>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="Visible = false">取消</el-button>
                <el-button type="primary" @click.native="handleEnd" :loading="Loading">提交</el-button>
            </div>
        </el-dialog>
        <el-dialog size="tiny" title="付款账号管理" v-model="editVisible" :close-on-click-modal="false">
            <el-form  label-width="120px"  ref="zhanghaoedit" :rules="zhanghaoeditRules" :model="zhanghaoedit">
							<el-form-item label="账户类型" prop="duizhangType">
									<el-radio-group v-model="zhanghaoedit.duizhangtype" >
											<el-radio label="1">公户</el-radio>
											<el-radio label="2">私户</el-radio>
									</el-radio-group>
							</el-form-item>
							<el-row>
									<el-col :span="8">
											<el-form-item label="户名" prop="zhanghu" required>
													<el-input v-model="zhanghaoedit.zhanghu" :disabled="lydisabled"></el-input>
											</el-form-item>
									</el-col>
									<el-col :span="10">
											<el-form-item label="银行" required prop="shoufangzonghang">
													<el-select
																	v-model="zhanghaoedit.shoufangzonghangid"
																	filterable
																	default-first-option
																	remote
																	@change="chaedtnge1"
																	placeholder=""
																	:remote-method="bankedtCompany"
																	:loading="bankedtCNameloading"
																	:disabled="lydisabled"
													>
															<el-option
																			v-for="item in options3"
																			:key="item.value"
																			:label="item.label"
																			:value="item.value">
															</el-option>
													</el-select>
											</el-form-item>
									</el-col>
							</el-row>
							<el-row>
									<el-col :span="8">
											<el-form-item label="开户行" prop="yinhang" required>
													<el-input v-model="zhanghaoedit.yinhang" :disabled="lydisabled"></el-input>
											</el-form-item>
									</el-col>
									<el-col :span="10">
											<el-form-item label="账号" prop="zhanghao" required>
													<input v-model="zhanghaoedit.zhanghao" :disabled="lydisabled"
																style="-webkit-appearance: none;
																			-moz-appearance: none;
																			appearance: none;
																			background-color: #fff;
																			background-image: none;
																			border-radius: 4px;
																			border: 1px solid #bfcbd9;
																			box-sizing: border-box;
																			color: #1f2d3d;
																			font-size: inherit;
																			height: 36px;
																			line-height: 1;
																			outline: 0;
																			padding: 3px 10px;
																			transition: border-color .2s cubic-bezier(.645,.045,.355,1);
																			width: 100%;" onkeyup="this.value=this.value.replace(/\D/g,'').replace(/....(?!$)/g,'$& ')"
																>
											</el-form-item>
									</el-col>
							</el-row>
							<el-row>
									<el-col :span="8">
											<el-form-item label="开户省" required prop="shengfen">
													<el-select
																	v-model="zhanghaoedit.shengfenid"
																	filterable
																	default-first-option
																	remote
																	@change="chaedtnge2"
																	placeholder=""
																	:remote-method="bankedtCompany2"
																	:loading="bankedtCName2loading"
																	:disabled="lydisabled"
													>
															<el-option
																			v-for="item in options4"
																			:key="item.value"
																			:label="item.label"
																			:value="item.value">
															</el-option>
													</el-select>
											</el-form-item>
									</el-col>
									<el-col :span="10">
											<el-form-item label="市" prop="shi" required>
													<el-input v-model="zhanghaoedit.shi" :disabled="lydisabled" ></el-input>
											</el-form-item>
									</el-col>
							</el-row>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editVisible = false">取消</el-button>
                <el-button type="primary" @click.native="handleEdit" :loading="editLoading">提交</el-button>
            </div>
        </el-dialog>
        <el-dialog size="tiny" title="审核" v-model="aduitVisible" :close-on-click-modal="false">
             确认启用该账号吗？
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click.native="handleAduit" :loading="Loading">通过</el-button>
                <el-button type="primary" @click.native="handleAduitEnd" :loading="Loading">驳回</el-button>
            </div>
        </el-dialog>
    </el-row>
</template>
<style>
		.el-dialog--tiny{width:50%;}
</style>
<script>
    import {getPurchaseContractInfo,
        zhanghaoSavePurchaseContract,
        getZhanghaoPurchaseContractList,
        removeZhanghaoPurchaseContract,
        zhanghaoUpdataPurchaseContract,
				
				getbankCNameList,
				getbankCName2List,
				
        changeSFZhanghao} from '../../api/api';
    export default {
        data() {
            return {
                lists:[],
                listLoading:false,
                bianhao:null,
                officeList:[{
                    loupanName:null,
                    loudongName:null,
                    fanghao:null,
                }],
                id:null,
                Visible:false,
                aduitVisible:false,
                editVisible:false,
								lydisabled:false,
								bankCNameloading:false,
								bankedtCNameloading:false,
								bankCName2loading:false,
								bankedtCName2loading:false,
								esbanktate: [],//服务器搜索的银行数据放入这个数组中
								esbank2tate: [],//服务器搜索的开户省数据放入这个数组中
								options3: [
										{
												value: null,
												label: null,
												lianhao: null,
										}
								],
								options4: [
										{
												value: null,
												label: null,
										}
								],
                zhanghao:{
                    hetongid:null,
                    hetongbianhao:null,
                    zhanghu:null,
                    shoufangzonghang:null,
										shoufangzonghangid:null,
										lianhanghao:null,
                    zhanghao:null,
                    hetongtid: 0,
										duizhangtype:'1',
										yinhang:null,
										shengfen:null,
										shengfenid:null,
										shi:null,
                },
                zhanghaoedit:{
                    hetongid:null,
                    hetongbianhao:null,
										
										zhanghu:null,
										shoufangzonghang:null,
										shoufangzonghangid:null,
										lianhanghao:null,
										zhanghao:null,
										duizhangtype:null,
										yinhang:null,
										shengfen:null,
										shengfenid:null,
										shi:null,
                    hetongtid: 0,
                    tHtYinhangzhanghaoId:0,
                },
                tianjiadate:null,
                Loading:null,
                editLoading:null,
                zhanghaoRules:{
                    zhanghu: [
                        { required: true, message: '不能为空'}
                    ],
										duizhangtype: [
												{ required: true, message: '不能为空'}
										],
                    yinhang: [
                        { required: true, message: '不能为空'}
                    ],
                    zhanghao: [
                        { required: true, message: '不能为空'}
                    ],
										shoufangzonghang: [
												{ required: true, message: '不能为空'}
										],
										shengfen: [
												{ required: true, message: '不能为空'}
										],
										shi: [
												{ required: true, message: '不能为空'}
										],
                },
                zhanghaoeditRules:{
									
									zhanghu: [
											{ required: true, message: '不能为空'}
									],
									yinhang: [
											{ required: true, message: '不能为空'}
									],
									zhanghao: [
											{ required: true, message: '不能为空'}
									],
									shoufangzonghang: [
											{ required: true, message: '不能为空'}
									],
									shengfen: [
											{ required: true, message: '不能为空'}
									],
									shi: [
											{ required: true, message: '不能为空'}
									],
                },
                auditid:0,
                //分页类数据
                total:0,
                page:null,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                sels: [],//列表选中列
            }
        },
        methods: {
            hanshu(row){
                if(row.laiyuantype==1 || row.laiyuantype==3){
                    return true;
                }else{
                    return false;
                }
            },
						
						//银行的chage事件
						change1(){
								var arr = this.options3;
								for (let i=0;i<arr.length;i++ ){
										if(arr[i].value==this.zhanghao.shoufangzonghangid){
												this.zhanghao.shoufangzonghang = arr[i].label;
												this.zhanghao.lianhanghao = arr[i].lianhao;
										}
								}

						},
						//获取银行公司名称
						bankCompany(query) {
								let para = {
										name: query,
										id:this.zhanghao.shoufangzonghangid!=null?this.zhanghao.shoufangzonghangid:'',
								};
								this.bankCNameloading = true;
								getbankCNameList(para).then((res) => {
										let arr = [];
										arr[0] = '';
										for ( var i in res.data.data ){
												arr[i]=res.data.data[i]
										}
										this.esbanktate = arr;
										this.bankCNameloading = false;
										this.list = this.esbanktate.map((item,index) => {
												return { value: item.id, label: item.yinhangname , lianhao: item.yinhanglianhao };
										});
										if (query !== '') {
												this.bankCNameloading = true;
												setTimeout(() => {
														this.bankCNameloading = false;
														this.options3 = this.list;
												}, 200);
										} else {
												this.options3 = [];
										}
								});

						},
						//开户省的chage事件
						change2(){
								var arr = this.options4;
								for (let i=0;i<arr.length;i++ ){
										if(arr[i].value==this.zhanghao.shengfenid){
												this.zhanghao.shengfen = arr[i].label;
										}
								}

						},
						//获取开户省公司名称
						bankCompany2(query) {
								let para = {
										name: query,
										id:this.zhanghao.shengfenid!=null?this.zhanghao.shengfenid:'',
								};
								this.bankCName2loading = true;
								getbankCName2List(para).then((res) => {
										let arr = [];
										arr[0] = '';
										for ( var i in res.data.data ){
												arr[i]=res.data.data[i]
										}
										this.esbank2tate = arr;
										this.bankCName2loading = false;
										this.list = this.esbank2tate.map((item,index) => {
												return { value: item.id, label: item.sname };
										});
										if (query !== '') {
												this.bankCName2loading = true;
												setTimeout(() => {
														this.bankCName2loading = false;
														this.options4 = this.list;
												}, 200);
										} else {
												this.options4 = [];
										}
								});

						},
						
						//银行的chage事件
						chaedtnge1(){
								var arr = this.options3;
								for (let i=0;i<arr.length;i++ ){
										if(arr[i].value==this.zhanghaoedit.shoufangzonghangid){
												this.zhanghaoedit.shoufangzonghang = arr[i].label;
												this.zhanghaoedit.lianhanghao = arr[i].lianhao;
										}
								}

						},
						//获取银行公司名称
						bankedtCompany(query) {
								let para = {
										name: query,
										id:this.zhanghaoedit.shoufangzonghangid!=null?this.zhanghaoedit.shoufangzonghangid:'',
								};
								this.bankedtCNameloading = true;
								getbankCNameList(para).then((res) => {
										let arr = [];
										arr[0] = '';
										for ( var i in res.data.data ){
												arr[i]=res.data.data[i]
										}
										this.esbanktate = arr;
										this.bankedtCNameloading = false;
										this.list = this.esbanktate.map((item,index) => {
												return { value: item.id, label: item.yinhangname , lianhao: item.yinhanglianhao };
										});
										if (query !== '') {
												this.bankedtCNameloading = true;
												setTimeout(() => {
														this.bankedtCNameloading = false;
														this.options3 = this.list;
												}, 200);
										} else {
												this.options3 = [];
										}
								});

						},
						//开户省的chage事件
						chaedtnge2(){
								var arr = this.options4;
								for (let i=0;i<arr.length;i++ ){
										if(arr[i].value==this.zhanghaoedit.shengfenid){
												this.zhanghaoedit.shengfen = arr[i].label;
										}
								}

						},
						//获取开户省公司名称
						bankedtCompany2(query) {
								let para = {
										name: query,
										id:this.zhanghaoedit.shengfenid!=null?this.zhanghaoedit.shengfenid:'',
								};
								this.bankedtCName2loading = true;
								getbankCName2List(para).then((res) => {
										let arr = [];
										arr[0] = '';
										for ( var i in res.data.data ){
												arr[i]=res.data.data[i]
										}
										this.esbank2tate = arr;
										this.bankedtCName2loading = false;
										this.list = this.esbank2tate.map((item,index) => {
												return { value: item.id, label: item.sname };
										});
										if (query !== '') {
												this.bankedtCName2loading = true;
												setTimeout(() => {
														this.bankedtCName2loading = false;
														this.options4 = this.list;
												}, 200);
										} else {
												this.options4 = [];
										}
								});

						},
												
            //时间戳转日期格式
            changeDate(row, column){
                var newDate = new Date();
                newDate.setTime(row.tianjiadate);
                if(row.tianjiadate!=null){
                    return newDate.toLocaleDateString()
                }
            },
            formatStatus(row, column){
                let status = [];
                status[1] = '合同';
                status[2] = '新增';
                status[3] = '财务';
                return status[row.laiyuantype];
            },
						forduizhmatStat(row, column){
								let status = [];
								status[1] = '公户';
								status[2] = '私户';
								return status[row.duizhangtype];
						},
            formatState(row, column){
                let status = [];
                status[1] = '待审核';
                status[2] = '已启用';
                status[3] = '已驳回';
                status[4] = '已停用';
                return status[row.stateid];
            },
            //获取付款账号的数据列表
            purchaseZhanghaoContractList() {
                let para = {
                    page: this.page,
                    size: this.pageSize,
                    htid: this.$route.query.id,
                    httid:0,
                }
                getZhanghaoPurchaseContractList(para).then((res) => {
                    this.total = res.data.total;
                    this.lists = res.data.data;
                    this.listLoading = false;
                });
            },
            handleSizeChange(val) {
                this.pageSize = val;
                this.purchaseZhanghaoContractList();
            },
            handleCurrentChange(val) {
                this.page = val;
                this.purchaseZhanghaoContractList();
            },
            getPurchaseContract(id){
                getPurchaseContractInfo(id).then((res)=>{
                    if(res.data.code=='200'){
                        this.fuzhi(res);
                    }else {
                        this.$message({
                            message: '获取数据失败',
                            type: 'error'
                        });
                    }
                })
            },
            selsChange: function (sels) {
                this.sels = sels;
            },
            //新增
            addContract(){
                this.Visible = true;
                this.zhanghao= {
                      hetongid: this.$route.query.id,
                        hetongbianhao:null,
												
												zhanghu:null,
												shoufangzonghang:null,
												shoufangzonghangid:null,
												lianhanghao:null,
												zhanghao:null,
												duizhangtype:'1',
												yinhang:null,
												shengfen:null,
												shengfenid:null,
												shi:null,
                        hetongtid: 0,
                }
            },
            //新增的提交
            handleEnd(index,row){
                this.$refs.zhanghao.validate((valid) => {
                    if(valid){
                        let para1 = this.zhanghao;
                        this.Visible = false;
                        zhanghaoSavePurchaseContract(para1).then((res)=>{
                            if(res.data.code!='200'){
                                this.$message({
                                    message: '数据没有保存成功',
                                    type: 'error'
                                });
                            }
                            this.resetForm('zhanghao');
                            this.purchaseZhanghaoContractList();

                        });
                    }
                });

            },
            //审核
            handleShenHe(index,row){
                this.aduitVisible = true;
                this.auditid=row.tHtYinhangzhanghaoId;
            },
            //审核通过
            handleAduit(index,row){
                this.$confirm('确认审核通过吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:this.auditid,
                        stateid:2,
                    }
                    changeSFZhanghao(para).then((res)=>{
                        if(res.data.code=='200'){
                            this.listLoading = false;
                            this.$message({
                                message: '设置成功',
                                type: 'success'
                            });
                            this.purchaseZhanghaoContractList();
                        }else{
                            this.$message({
                                message: '设置失败',
                                type: 'error'
                            });
                        }
                    });
                })
                this.aduitVisible = false;
            },
            //审核通过
            handleAduitEnd(index,row){
                this.$confirm('确认驳回吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:this.auditid,
                        stateid:3,
                    }
                    changeSFZhanghao(para).then((res)=>{
                        if(res.data.code=='200'){
                            this.listLoading = false;
                            this.$message({
                                message: '设置成功',
                                type: 'success'
                            });
                            this.purchaseZhanghaoContractList();
                        }else{
                            this.$message({
                                message: '设置失败',
                                type: 'error'
                            });
                        }
                    });
                })
                this.aduitVisible = false;
            },
            //停用
            handleStop(index,row){
                this.$confirm('确认停用吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:row.tHtYinhangzhanghaoId,
                        stateid:4,
                    }
                    changeSFZhanghao(para).then((res)=>{
                        if(res.data.code=='200'){
                            this.listLoading = false;
                            this.aduitVisible = false;
                            this.$message({
                                message: '设置成功',
                                type: 'success'
                            });
                            this.purchaseZhanghaoContractList();
                        }else{
                            this.$message({
                                message: '设置失败',
                                type: 'error'
                            });
                        }
                    });
                    //this.$router.push('/purchaseContract/optimize?hetongid='+row.id+'&bianhao='+row.bianhao);
                })

            },
            //启用
            handleStart(index,row){
                this.$confirm('确认启用吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {
                        id:row.tHtYinhangzhanghaoId,
                        stateid:1,
                    }
                    changeSFZhanghao(para).then((res)=>{
                        if(res.data.code=='200'){
                            this.listLoading = false;
                            this.aduitVisible = false;
                            this.$message({
                                message: '设置成功',
                                type: 'success'
                            });
                            this.purchaseZhanghaoContractList();
                        }else{
                            this.$message({
                                message: '设置失败',
                                type: 'error'
                            });
                        }
                    });
                })

            },
						
						
						

            //编辑
            editYHZH(index, row){
                this.editVisible = true;
								
                this.zhanghaoedit = Object.assign({}, row);
								this.options3[0].value = row.shoufangzonghangid;
								this.options3[0].label = row.shoufangzonghang;
								this.options3[0].lianhao = row.lianhanghao;
								this.options4[0].value = row.shengfenid;
								this.options4[0].label = row.shengfen;
            },
            //新增的提交
            handleEdit(index,row){
                this.$refs.zhanghaoedit.validate((valid) => {
									console.log(this.zhanghaoedit);
                    if(valid){
                        let para = Object.assign({}, this.zhanghaoedit);
                        this.editVisible = false;
                        zhanghaoUpdataPurchaseContract(para).then((res)=>{
                            if(res.data.code!='200'){
                                this.$message({
                                    message: '数据没有保存成功',
                                    type: 'error'
                                });
                            } else{
                                this.$message({
                                    message: '数据保存成功',
                                    type: 'success'
                                });
                            }
                            this.purchaseZhanghaoContractList();

                        });
                    }
                });

            },
            //失去焦点事件
            updataZhanghao(index, row){
                let para = {
                    tHtYinhangzhanghaoId: row.tHtYinhangzhanghaoId,
                    zhanghu: row.zhanghu,
                    yinhang: row.yinhang,
                    zhanghao: row.zhanghao,
                };
                zhanghaoUpdataPurchaseContract(para).then((res)=>{
                    if(res.data.code!='200'){
                        this.$message({
                            message: '数据没有保存成功',
                            type: 'error'
                        });
                    }
                });

            },
            //账号删除
            handleDel(index, row){
                this.$confirm('确认删除该记录吗？', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    let para = {id: row.tHtYinhangzhanghaoId};
                    removeZhanghaoPurchaseContract(para).then((res) => {
                        this.listLoading = false;
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                        this.purchaseZhanghaoContractList();
                    });
                }).catch((res) => {
                    console.log(res)
                });
            },
            fuzhi(res){
                this.hetongid = res.data.data.id;
                this.bianhao = res.data.data.bianhao;
                this.officeList = res.data.data.officeList;
                var fj="";
                for (var x in this.officeList){
                    fj=fj+this.officeList[x].fanghao +",";
                }
                this.officeList[0].fanghao = fj.substring(0,fj.length-1);
                //console.log(this.officeList)
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            }
        },
        mounted(){
            this.purchaseZhanghaoContractList();
            this.zhanghao.hetongid = this.$route.query.id;
            //根据url得到的合同ID，来获取数据
            if(this.$route.query.id!=null){
                this.getPurchaseContract(this.$route.query);
            }
        }
    }
</script>
