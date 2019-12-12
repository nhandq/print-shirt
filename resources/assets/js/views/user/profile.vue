<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Thông tin tài khoản</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/home">Dashboard</router-link></li>
                    <li class="breadcrumb-item active">Tài khoản</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Chỉnh sửa tài khoản</h4>
                        <form @submit.prevent="updateProfile">
                            <div class="form-group">
                                <label for="">Tên</label>
                                <input class="form-control" type="text" value="" v-model="profileForm.first_name">
                            </div>
                            <div class="form-group">
                                <label for="">Họ</label>
                                <input class="form-control" type="text" value="" v-model="profileForm.last_name">
                            </div>
                            <div class="form-group">
                                <label for="">Ngày sinh</label>
                                <datepicker v-model="profileForm.date_of_birth" :bootstrapStyling="true"></datepicker>
                            </div>
                            <div class="form-group">
                                <label for="">Giới tính</label>
                                <div class="radio radio-info">
                                    <input type="radio" value="male" id="gender_male" v-model="profileForm.gender" :checked="profileForm.gender === 'male'">
                                    <label for="gender_male"> Name </label>
                                </div>
                                <div class="radio radio-success">
                                    <input type="radio" value="female" id="gender_female" v-model="profileForm.gender" :checked="profileForm.gender === 'female'">
                                    <label for="gender_female"> Nữ </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Facebook</label>
                                <input class="form-control" type="text" value="" v-model="profileForm.facebook_profile">
                            </div>
                            <div class="form-group">
                                <label for="">Twitter</label>
                                <input class="form-control" type="text" value="" v-model="profileForm.twitter_profile">
                            </div>
                            <div class="form-group">
                                <label for="">Google Plus</label>
                                <input class="form-control" type="text" value="" v-model="profileForm.google_plus_profile">
                            </div>
                            <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="pull-right">
                            <click-confirm yes-class="btn btn-success" no-class="btn btn-danger">
                                <button type="button" class="btn btn-sm btn-danger waves-effect waves-light m-t-10" v-if="defaultAvatar" @click="removeAvatar">Xóa Avatar</button>
                            </click-confirm>
                        </div>
                        <h4 class="card-title">Upload Avatar</h4>
                        <div class="form-group text-center m-t-20">
                            <span id="fileselector">
                                <label class="btn btn-info">
                                    <input type="file"  @change="previewAvatar" id="avatarUpload" class="upload-button">
                                    <i class="fa fa-upload margin-correction"></i>Chọn Avatar
                                </label>
                            </span>
                        </div>
                        <div class="form-group text-center">
                            <img :src="avatar" class="img-responsive" style="max-width:200px;">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info waves-effect waves-light m-t-10" v-if="avatar" @click="uploadAvatar">Upload</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light m-t-10" v-if="avatar" @click="cancelUploadAvatar">Hủy bỏ</button>
                        </div>
                    </div>
                </div>
                <div class="card" v-if="!social_auth">
                    <div class="card-body">
                        <h4 class="card-title">Thay đổi mật khẩu</h4>
                        <form @submit.prevent="changePassword">
                            <div class="form-group">
                                <label for="">Mật khẩu hiện tại</label>
                                <input class="form-control" type="password" value="" v-model="passwordForm.current_password">
                            </div>
                            <div class="form-group">
                                <label for="">Mật khẩu mới</label>
                                <input class="form-control" type="password" value="" v-model="passwordForm.new_password">
                            </div>
                            <div class="form-group">
                                <label for="">Nhập lại mật khẩu</label>
                                <input class="form-control" type="password" value="" v-model="passwordForm.new_password_confirmation">
                            </div>
                            <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Thay đổi mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker'
    import ClickConfirm from 'click-confirm'

    export default {
        components : { datepicker, ClickConfirm },
        data() {
            return {
                passwordForm: new Form({
                    current_password : '',
                    new_password : '',
                    new_password_confirmation : ''
                }),
                profileForm: new Form({
                    first_name : '',
                    last_name : '',
                    date_of_birth : '',
                    gender : '',
                    facebook_profile : '',
                    twitter_profile : '',
                    google_plus_profile : ''
                }, false),
                avatar: '',
                social_auth: 0
            };
        },
        mounted(){
            axios.get('/api/auth/user').then(response => response.data).then(response => {
                this.profileForm.first_name = response.profile.first_name;
                this.profileForm.last_name = response.profile.last_name;
                this.profileForm.date_of_birth = response.profile.date_of_birth;
                this.profileForm.gender = response.profile.gender;
                this.profileForm.facebook_profile = response.profile.facebook_profile;
                this.profileForm.twitter_profile = response.profile.twitter_profile;
                this.profileForm.google_plus_profile = response.profile.google_plus_profile;
                this.social_auth = response.social_auth;
            });
        },
        methods: {
            changePassword() {
                this.passwordForm.post('/api/user/change-password').then(response => {
                    toastr['success'](response.message);
                }).catch(response => {
                    toastr['error'](response.message);
                });
            },
            updateProfile() {
                this.profileForm.date_of_birth = moment(this.profileForm.date_of_birth).format('YYYY-MM-DD');
                this.profileForm.post('/api/user/update-profile').then(response => {
                    toastr['success'](response.message);
                    this.$store.dispatch('setAuthUserDetail',{
                        first_name: this.profileForm.first_name,
                        last_name: this.profileForm.last_name
                    });
                }).catch(response => {
                    toastr['error'](response.message);
                });
            },
            previewAvatar(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createAvatar(files[0]);
            },
            createAvatar(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.avatar = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            cancelUploadAvatar(){
                this.avatar = '';
            },
            uploadAvatar() {
                let data = new FormData();
                data.append('avatar', $('#avatarUpload')[0].files[0]);
                axios.post('/api/user/update-avatar',data)
                .then(response => {
                    this.$store.dispatch('setAuthUserDetail',{
                        avatar: response.data.profile.avatar
                    });
                    toastr['success'](response.data.message);
                    this.avatar = '';
                    $("#avatarUpload").val('');
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            removeAvatar() {
                axios.post('/api/user/remove-avatar')
                .then(response => {
                    this.$store.dispatch('setAuthUserDetail',{
                        avatar: null
                    });
                    toastr['success'](response.data.message);
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            getAuthUser(name){
                return this.$store.getters.getAuthUser(name);
            }
        },
        computed: {
            defaultAvatar(){
                return this.getAuthUser('avatar') !== 'avatar.png' ? true : false;
            }
        }
    }
</script>
