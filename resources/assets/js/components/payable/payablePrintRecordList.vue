
<template>
    <el-row >
        <el-table :data="PayableRecord" highlight-current-row v-loading="listLoading" element-loading-text="拼命加载中" @selection-change="selsChange" style="width: 100%;">
            <el-table-column type="expand">
                <template slot-scope="scope">
                    <el-form label-position="left" inline class="demo-table-expand">
                        <el-form-item label="备注：">
                            <span>{{scope.row.beizhu}}</span>
                        </el-form-item>
                    </el-form>
                </template>
            </el-table-column>
            <el-table-column prop="hetongbianhao" label="合同编号"  >
            </el-table-column>
            <el-table-column prop="xiangmu" label="项目"  >
            </el-table-column>
            <el-table-column prop="tijiaodate" label="提交日期"   :formatter="changeTJDate">
            </el-table-column>
            <el-table-column prop="fukuankemu" label="付款科目" :formatter="formatFKType">
            </el-table-column>
            <el-table-column prop="tijiaomoney" label="提交金额" >
            </el-table-column>
            <el-table-column prop="fukuanmoney" label="付款金额" >
            </el-table-column>
            <el-table-column prop="fukuandate" label="付款日期"  :formatter="changeFKDate">
            </el-table-column>
            <el-table-column prop="faqiren" label="发起人" >
            </el-table-column>
            <el-table-column prop="" label="收款银行及账号"   width="200" :formatter="formatskyh">
            </el-table-column>
            <el-table-column label="操作" width="150">
                <template slot-scope="scope">
                    <el-button   @click="handleDump(scope.$index, scope.row)">打印凭证</el-button>
                </template>
            </el-table-column>
           </el-table>

    </el-row>
</template>
<script>

    import {
        getPayableRecordList,

       // getUserNameByID,

    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
                options:[
                    {
                        value: 1,
                        label: '押金'
                    }, {
                        value: 2,
                        label: '房租'
                    },
                ],
                showed:false,
                //分页类数据
                total:0,
                currentPage:0,
                pageSize:10,
                pageSizes:[10, 20, 30, 40, 50, 100],
                PayableRecord:[],
                listLoading: false,
                sels: [],//列表选中列
                //被选中的权限
                checked:[],
            }
        },
        methods:{
            //时间戳转日期格式
            changeTJDate(row, column){
                var newDate = new Date();
                newDate.setTime(row.tijiaodate);
                return newDate.toLocaleDateString()
            },
            //收款账号显示转换
            formatskyh: function (row, column) {
                return  row.fukuanyinhang+"\r账号:"+row.fukuanzhanghao;
            },
            //时间戳转日期格式
            changeFKDate(row, column){
                var newDate = new Date();
                newDate.setTime(row.fukuandate);
                return newDate.toLocaleDateString()
            },
            //页面跳转后
            handleCurrentChange(val) {
                this.page = val;
                this.getPayableRecord();
            },
            //更改每页显示数据
            handleSizeChange(val){
                this.pageSize =val;
                this.getPayableRecord();
            },
            formatFKType(row, column){
                let status = [];
                status[0] = '押金';
                status[1] = '租金';
                return status[row.fukuankemu];
            },
            //获取渠道公司列表
            getPayableRecord() {
                let para = {
                    id:this.$route.query.id,
                };
                this.listLoading = true;
                getPayableRecordList(para).then((res) => {
                    this.total = res.data.total;
                    this.PayableRecord = res.data.data;
                    this.listLoading = false;
                });
            },
            handleDump(index,row){
                    window.open('/#/payOrder?id='+row.tCwFcSubmitId)
            },
            selsChange: function (sels) {
                this.sels = sels;
            },

        },
        mounted() {
            this.page=1;
            this.getPayableRecord();

        }
    }
</script>
