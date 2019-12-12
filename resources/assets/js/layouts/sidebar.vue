<template>
	<aside class="left-sidebar">
        <div class="scroll-sidebar">
            <div class="user-profile">
                <div class="profile-img"> <img :src="getAvatar" alt="user" /> </div>
                <div class="profile-text"> <a href="#" class="dropdown-toggle link u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{getAuthUserFullName()}}<span class="caret"></span></a>
                    <div class="dropdown-menu">
                        <router-link to="/profile" class="dropdown-item"><i class="fa fa-user"></i> Thông tin</router-link>
                        <div class="dropdown-divider"></div>
                        <router-link to="/configuration" class="dropdown-item"><i class="fa fa-cogs"></i> Cấu hình</router-link>
                        <div class="dropdown-divider"></div> <a href="#" class="dropdown-item" @click.prevent="logout"><i class="fa fa-power-off"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li>
                        <router-link to="/home" exact><i class="fa fa-home"></i> <span class="hide-menu">Dashboard</span></router-link>
                    </li>
                    <li>
                        <router-link to="/order" exact><i class="fa fa-shopping-bag"></i> <span class="hide-menu">Đơn hàng</span></router-link>
                    </li>
                    <li>
                        <router-link to="/data" exact><i class="fa fa-tasks"></i> <span class="hide-menu">Dữ liệu mẫu</span></router-link>
                    </li>
                    <li>
                        <router-link to="/task" exact><i class="fa fa-tasks"></i> <span class="hide-menu">Tác vụ</span></router-link>
                    </li>
                    <li>
                        <router-link to="/facebook" exact><i class="fa fa-facebook"></i> <span class="hide-menu">Facebook</span></router-link>
                    </li>
                    <li>
                        <router-link to="/setting" exact><i class="fa fa-cog"></i> <span class="hide-menu">Thiết lập</span></router-link>
                    </li>
                    <li>
                        <router-link to="/user" exact><i class="fa fa-users"></i> <span class="hide-menu">Tài khoản</span></router-link>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="sidebar-footer">
            <router-link to="/configuration" class="link" data-toggle="tooltip" title="Configuration"><i class="fa fa-cogs"></i></router-link>
            <router-link to="/profile" class="link" data-toggle="tooltip" title="Profile"><i class="fa fa-user"></i></router-link>
            <a href="#" class="link" data-toggle="tooltip" title="Logout" @click.prevent="logout"><i class="fa fa-power-off"></i></a>
        </div>
    </aside>
</template>

<script>

    import helper from '../services/helper'

    export default {
        mounted() {
        },
        methods : {
            logout(){
                helper.logout().then(() => {
                    this.$store.dispatch('resetAuthUserDetail');
                    this.$router.replace('/login')
                })
            },
            getAuthUserFullName(){
                return this.$store.getters.getAuthUserFullName;
            },
            getAuthUser(name){
                return this.$store.getters.getAuthUser(name);
            }
        },
        computed: {
            getAvatar(){
                return '/images/users/'+this.getAuthUser('avatar');
            }
        }
    }
</script>
