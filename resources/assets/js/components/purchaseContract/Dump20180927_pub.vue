<template>
    <div class="whole">
        <p>
            <b>&nbsp;&nbsp;第十四条  补充条款</b> <br>
            &nbsp; 以下条款内容与本合同其它各条款具备同等法律效力,若补充条款与本合同不一致或发生冲突时，应以补充条款为准。<br>
            <u v-html="buchongtiaokuan"></u>
            <br>
            <input v-if="buchongtiaokuan==null||buchongtiaokuan==''" type="text" name="" disabled style="width:600px;" value="以下空白">
            <br><br>

            <b> 业主方（甲方）：<input type="text" name="" style="width:175px;font-size:10px" value=""></b>　<br>
            <b>委托代理人：<input type="text" name="" style="width:202px;font-size:10px" value=""></b><br>
            联系地址：<input type="text" name="" style="width:217px;font-size:10px" value="">
            <br>联系方式：<input type="text" name="" style="width:217px;" disabled value=""><br>
            <font class="datemar">_______年____月____日</font>
            <br><br><br><br>
            <b>管理方（乙方）：<input type="text" name="" style="width:175px;font-size:10px" value=""></b>　<br>
            联系地址：<input type="text" name="" style="width:217px" value="">
            <br>联系方式：<input type="text" name="" style="width:217px;font-size:10px" value=""><br>
            <font style="margin-left:152px" >_______年____月____日</font><br>
            <br>
        <p v-if="farenzhengjian=='s'">
            <b> 居间方（丙方）：</b><input type="text" style="width:175px;font-size:10px" v-model="jujianfang">
            <br>联系地址：<input type="text" name="" style="width:217px;font-size:10px" value="">
            <br>联系方式：<input type="text" name="" style="width:217px;font-size:10px" value="">
            <br>
            <font class="datemar">_______年____月____日</font>
        </p>
        </p>
    </div>
</template>
<style>
    body{
        text-align: center;
        height: 100%;
        position: relative;
    }
    .whole{
        margin: auto;
        top:0px;
        right: 0;
        left:0;
        bottom: 0;
        width: 90%;
        height: 20%;
    }
    .whole h1{
        font-size:32px;
    }
    .whole span {
        display: block;
    }
    .whole p{
        font-size: 14px; text-align:left;
        line-height: 2;
    }
    .whole  input{border: none;border-bottom: 1px solid#333333;outline: none; font-size: 14px!important;text-align:center}
    .whole u{
        font-size:14px!important;
    }
    .whole input[disabled]{
        background-color:white;
        color:#000000;
    }
    .datemar{margin-left: 152px;}
</style>
<script>
    import {getPurchaseContractInfo} from '../../api/api';
    export default {
        data(){
            return {
                buchongtiaokuan:null,
                farenzhengjian:null,
                jujianfang:null,
            }
        },
        methods:{
            //根据url得到的合同ID，来获取数据
            getPurchaseContract(id){
                getPurchaseContractInfo(id).then((res)=>{
                    if(res.data.code=='200'){
                        //把数据分别赋值给三个组件的变量
                        this.buchongtiaokuan = res.data.data.yingyezhizhao;
                        this.farenzhengjian = res.data.data.farenzhengjian;
                        this.jujianfang = res.data.data.jujianfang;
                    }else {
                        this.$message({
                            message: '获取数据失败',
                            type: 'error'
                        });
                    }
                })
            },
        },
        mounted(){
            this.getPurchaseContract(this.$route.query);
            document.title = "合同编号"+this.$route.query.bianhao;
        }

    }
</script>

