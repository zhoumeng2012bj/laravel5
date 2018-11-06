<template>
    <el-form style="margin-top:2%" :model="copyForm" ref="copyForm" :rules="copyRules" label-position="top">
        <el-upload
                action="/purchaseContract/addCopyImage?type=9"
                list-type="picture-card"
                :headers="headers"
                :data="data"
                multiple
                :on-preview="handlePictureCardPreview"
                :on-remove="handleRemove"
                :on-success="handleSuccess"
                :file-list="jiaogedan"
                :disabled="!fun('purchaseJGDUploadEdit')"
        >
            <i v-if="fun('purchaseJGDUploadEdit')" class="el-icon-plus"></i>
        </el-upload>
        <el-dialog v-model="dialogVisible" size="large">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
		<div style="position: fixed;right:10%;top:50%;">
			<el-button type="primary" v-if="fun('purchaseJGDUploadEdit')"  @click="save" style="margin-top:100px;">保存</el-button>
			<!--<el-button type="warning"   @click="cansel" style="margin-top:100px;">取消</el-button>-->
		</div>
    </el-form>
</template>
<script>
    import {
        copyImageList,
        copyImageDelete,
        isCopyComplete,
        isCopyCompleteList,
        getPurchaseContractInfo,
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
                    iscompletefwjgd:null,
                },
                jiaogedan:[],
                yezhuleixing:null,
                dialogImageUrl: '',
                dialogVisible: false,
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
//                         isCopyComplete(para).then((res)=>{
//                             if(res.data.code=='200'){
                                this.$message({
                                    message: '保存成功',
                                    type: 'success'
                                });
								window.location.reload();
//                                 history.go(-1);
//                             }else{
//                                 this.$message({
//                                     message: res.data.msg,
//                                     type: 'error'
//                                 }) ;
//                             }
// 
//                         });
//                     }
//                 });

            },
            cansel(){
                history.go(-1);
            },
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
                if(!this.fun('purchaseJGDUploadDel')){
                    this.$message.error('你没有删除权限!');
                    return false;
                }
                this.$confirm('确认删除吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para ={
                        id :file.id?file.id:file.response.data,
                    }
                    copyImageDelete(para).then((res)=>{

                    })
                }).catch(()=>{
					fileList.push(file);
				});

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
                copyImageList(para).then((res)=>{
                    if(res.data.code=='200'){
                        this.jiaogedan = res.data.data[9];//合同
						if(!this.fun('purchaseJGDUploadDel')){
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
            getPurchaseContractInfo(this.$route.query).then((res)=>{
                //查数据，得业主leixing
                this.yezhuleixing = res.data.data.yezhuleixing;
            }),
                isCopyCompleteList(this.$route.query).then((res)=>{
                    //查数据，得业主leixing
                    this.copyForm = res.data.data;
                })
            this.getCopyImageList();
        }


    }
</script>