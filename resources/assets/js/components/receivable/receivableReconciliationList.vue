<template>
    <el-row>
        <el-form :inline="true" :model="filters" class="demo-form-inline" label-width="80px">

            <el-form-item label="日期:">
                <el-date-picker type="date" placeholder="请选择开始日期" v-model="filters.startdate">
                </el-date-picker>
            </el-form-item>
            <el-form-item label="至">
                <el-date-picker type="date" placeholder="请选择结束日期" v-model="filters.enddate">
                </el-date-picker>
            </el-form-item>
            <el-form-item label="状态">
                <el-select v-model="filters.zt"   placeholder="请选择状态">
                    <el-option
                            v-for="item in optionszt"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search" v-on:click="getReceivable">搜索</el-button>
                <!-- <el-button type="primary" class="el-icon-plus" v-if="fun('receivableAddYXJ')" @click="handleAdd">新增</el-button> -->
            </el-form-item>

        </el-form>

            <el-table :data="Receivable" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中"
                      @selection-change="selsChange" style="width: 100%;">
                <el-table-column prop="reconciliationDate" label="日期"   :formatter="changeDate">
                </el-table-column>
                <el-table-column prop="payMoney" label="ERP实收金额"  >
                </el-table-column>
               
                <el-table-column prop="reconciliationMoney" label="第三方实收金额"  >
                </el-table-column>

                <el-table-column prop="reconciliationStatus" label="状态"    >
                </el-table-column>
                <el-table-column label="操作"  >
                    <template slot-scope="scope">
                        <el-button type="primary" @click="handleOpen(scope.$index, scope.row)">详情</el-button>
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
        accountslist,
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
                    startdate: '',
                    enddate: '',
                    zt: 0,
                },

                optionszt: [
                    {
                        value: 0,
                        label: '请选择'
                    }, {
                        value: 1,
                        label: '正常'
                    },
                    {
                        value: 2,
                        label: '异常'
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

                //被选中的权限
                checked: [],
            }
        },
        methods: {

            ztin(row, arr){
                var status = arr.indexOf(row.srstate);
                if (status > -1) {
                    return true;
                } else {
                    return false;
                }
            },

            //时间戳转日期格式
            changeDate: function (row, column) {
                if (row.reconciliationDate != null) {
                    var newDate = new Date();
                    newDate.setTime(row.reconciliationDate);
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

            //获取应收款列表
            getReceivable() {
                let para = {
                    page: this.page,
                    size: this.pageSize,
                    zt: this.filters.zt,
                    startdate: this.filters.startdate,
                    enddate: this.filters.enddate,
                };
                this.listLoading = true;
                accountslist(para).then((res) => {
                    this.total = res.data.total;
                    this.Receivable = res.data.data;
                    this.listLoading = false;
                });
            },

            //打开应付记录页面
            handleOpen: function (index, row) {
                this.$router.push('/paymentAccounts?id=' + row.reconciliationId);
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