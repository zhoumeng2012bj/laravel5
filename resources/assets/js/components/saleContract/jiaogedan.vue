<template>
    <div>
        <el-upload
                action="/saleContract/addCopyImage?type=9"
                list-type="picture-card"
                :headers="headers"
                :data="data"
                multiple
                :on-preview="handlePictureCardPreview"
                :on-remove="handleRemove"
                :on-success="handleSuccess"
                :file-list="jiaogedan"
                :disabled="!fun('saleJGDUploadEdit')"
        >
            <i v-if="fun('saleJGDUploadEdit')" class="el-icon-plus"></i>
        </el-upload>
        <el-dialog v-model="dialogVisible" size="large">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
		<div style="position: fixed;right:10%;top:50%;">
			<el-button type="primary" v-if="fun('saleJGDUploadEdit')"  @click="save" style="margin-top:100px;">保存</el-button>
			<!--<el-button type="warning"   @click="cansel" style="margin-top:100px;">取消</el-button>-->
		</div>
    </div>
</template>
<script>
    import {
        copySaleImageList,
        copySaleImageDelete,
        isCopySaleComplete,
        isCopySaleCompleteList,
        getSaleContractInfo,
    } from  '../../api/api';
    export default {
        data() {
            return {
                copyRules:{
                    iscompletehetong: [
                        { required: true, message: '不能为空' }
                    ],
                },
                copyForm:{
                    iscompletehetong:null,
                    iscompleteczrzj: null,
                    iscompleteyyzz:null,
                    iscompletefrzj:null,
                    iscompleteczrsqdlrwts:null,
                    iscompletewtrsfz:null,
                    iscompletefwjgd:null,
                },
                hetongList:[],
                chengzuren: [],
                yingyezhizhao:[],
                faren:[],
                chengzurenshouquan:[],
                weituoren:[],
                jiaogedan:[],
                zuhuleixing:null,
                dialogImageUrl: [],
                dialogVisible: false,
                /*form:{
                 name:[],
                 },*/
                headers:{
                    'X-CSRF-TOKEN':window.Laravel.csrfToken
                },
                data:{
                    id:null,
                },
            };
        },
        methods: {
            save(){
//                 this.$refs.copyForm.validate((valid) => {
//                     if(valid){
//                         let para = Object.assign({}, this.copyForm,{id:parseInt(this.$route.query.id)});
//                         isCopySaleComplete(para).then((res)=>{
//                             if(res.data.code=='200'){
									this.$message({
										message: '保存成功',
										type: 'success'
									});
									window.location.reload();									
//                             }else{
//                                 this.$message({
//                                     message: res.data.msg,
//                                     type: 'error'
//                                 });
//                             }
// 
//                         });
//                     }
//                 });
// 

            },
            cansel(){},
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },
            handleRemove(file, fileList) {
                if(!this.fun('saleJGDUploadDel')){
                    this.$message.error('你没有删除权限!');
                    return false;
                }
                this.$confirm('确认删除吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para ={
                        id :file.id?file.id:file.response.data,
                    }
                    copySaleImageDelete(para).then((res)=>{});
                }).catch(()=>{
					fileList.push(file);
				})

                //console.log(file.id);
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            handleSuccess(response, file, fileList){
                //响应值
                console.log(response)
            },
            getCopyImageList(){
                let para = {
                    id:this.$route.query.id
                }
                copySaleImageList(para).then((res)=>{
                    if(res.data.code=='200'){
                        this.jiaogedan = res.data.data[9];//合同
						if(!this.fun('saleJGDUploadDel')){
							setTimeout(function(){
								$('.el-upload-list--picture-card .el-upload-list__item-actions .el-upload-list__item-delete').remove();
							})						
						}
                    }
                })
            },
        },
        mounted(){
            this.data.id = this.$route.query.id;
            getSaleContractInfo(this.$route.query).then((res)=>{
                //查数据，得租户leixing（得到租户类型就可以判断营业执照显不显示）
                this.zuhuleixing = res.data.data.zuhuleixing;
            }),
                isCopySaleCompleteList(this.$route.query).then((res)=>{
                    //查数据，得租户leixing
                    this.copyForm = res.data.data;
                })
            this.getCopyImageList();
        }
    }
</script>