<template>
	<div>
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Đơn hàng</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/home">Dashboard</router-link></li>
                    <li class="breadcrumb-item active">Đơn hàng</li>
                </ol>
            </div>
            <div class="col-md-6 col-8">
                <div class="dropdown pull-right">
                    <button type="button" href="#" role="button" id="sortByLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-info">
                        <i class="fa fa-sort"></i>
                        <span class="d-none d-sm-inline">Sắp xếp</span>
                    </button>
                    <div aria-labelledby="sortByLink" class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                        <button class="dropdown-item" style="cursor: pointer;">Tăng dần</button>
                        <button class="dropdown-item" style="cursor: pointer;">Giảm dần
                            <span class="pull-right"><i class="fa fa-check"></i></span>
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item" style="cursor: pointer;">First Name</button>
                        <button class="dropdown-item" style="cursor: pointer;">Last Name
                            <span class="pull-right"><i class="fa fa-check"></i></span>
                        </button>
                        <button class="dropdown-item" style="cursor: pointer;">Email</button>
                        <button class="dropdown-item" style="cursor: pointer;">Status</button>
                        <button class="dropdown-item" style="cursor: pointer;">Created at</button>
                    </div>
                </div>
                <button class="btn btn-info pull-right m-r-10" @click="isfilter = !isfilter">
                    <i class="fa fa-filter"></i>
                    <span class="d-none d-sm-inline">Bộ lọc</span>
                </button>
                <button class="btn btn-info pull-right m-r-10" @click="goCreate()">
                    <i class="fa fa-plus"></i>
                    <span class="d-none d-sm-inline">Thêm mới</span>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div v-if="isfilter" class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Tên khách hàng</label>
                                    <input class="form-control" v-model="filterOrderForm.name" @blur="getOrders">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Trạng thái</label>
                                    <select name="status" class="form-control" v-model="filterOrderForm.status" @change="getOrders">
                                        <option value="">Tất cả</option>
                                        <option value="new">Đơn mới</option>
                                        <option value="printing">Đang in</option>
                                        <option value="printed">Đã in</option>
                                        <option value="done">Hoàn thành</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Sort By</label>
                                    <select name="sortBy" class="form-control" v-model="filterOrderForm.sortBy" @change="getOrders">
                                        <option value="name">First Name</option>
                                        <option value="email">Email</option>
                                        <option value="status">Status</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Order</label>
                                    <select name="order" class="form-control" v-model="filterOrderForm.order" @change="getOrders">
                                        <option value="asc">Giảm dần</option>
                                        <option value="desc">Tăng dần</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title">Danh sách đơn hàng</h4>
                        <h6 class="card-subtitle" v-if="orders.total">Total {{orders.total}} result found!</h6>
                        <h6 class="card-subtitle" v-else>No result found!</h6>
                        <div class="table-responsive">
                            <table class="table" v-if="orders.total">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên khách hàng</th>
                                        <th>Email</th>
                                        <th>Trạng thái</th>
                                        <th>Số điện thoại</th>
                                        <th>Chi phí</th>
                                        <th style="width:150px;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="order in orders.data">
                                        <td v-text="order.id"></td>
                                        <td v-text="order.name"></td>
                                        <td v-text="order.email"></td>
                                        <td v-html="getStatusFormat(order.status)"></td>
                                        <td v-text="order.phone"></td>
                                        <td v-text="order.total_money"></td>
                                        <td>
                                            <router-link v-bind:to="'/order/' + order.id">
                                            <button class="btn btn-info btn-sm" data-toggle="tooltip" title="Edit Order"><i class="fa fa-edit"></i></button>
                                            </router-link>
                                            <click-confirm yes-class="btn btn-success" no-class="btn btn-danger">
                                                <button class="btn btn-danger btn-sm" @click.prevent="deleteOrder(order.id)" data-toggle="tooltip" title="Delete Order"><i class="fa fa-trash"></i></button>
                                            </click-confirm>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-8">
                                    <pagination :data="orders" :limit=3 v-on:pagination-change-page="getOrders"></pagination>
                                </div>
                                <div class="col-md-4">
                                    <div class="float-right">
                                        <select name="pageLength" class="form-control" v-model="filterOrderForm.pageLength" @change="getOrders" v-if="orders.total">
                                            <option value="5">5 per page</option>
                                            <option value="10">10 per page</option>
                                            <option value="25">25 per page</option>
                                            <option value="100">100 per page</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import pagination from 'laravel-vue-pagination'
    import helper from '../../services/helper'
    import ClickConfirm from 'click-confirm'
    import TaskForm from './form'

    export default {
        components : { pagination, ClickConfirm, TaskForm },
        data() {
            return {
                orders: {},
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                isfilter: false,
                filterOrderForm: {
                    sortBy : 'id',
                    order: 'asc',
                    status: '',
                    pageLength: 5,
                    name: ''
                }
            }
        },
        mounted() {
            this.getOrders();
        },
        methods: {
            edit(id) {
              this.$router.push('/order/' + id)
            },
            getOrders(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterOrderForm);
                axios.get('/api/order?page=' + page + url)
                    .then(response => this.orders = response.data );
            },
            getVisaFormat(number_of_visa, visa_type, applicant) {
                return '<p>Số visa: ' + number_of_visa + '</p><p>Loại visa: ' + visa_type + '</p><p>Số người làm đơn: ' + applicant + '</p>';
            },
            getStatusFormat(status) {
                if(status == 'new')
                    return '<span class="label label-info">Đơn mới</span>';
                else if(status == 'printing')
                    return '<span class="label label-warning">Đang in</span>';
                else if(status == 'printed')
                    return '<span class="label label-danger">Đã in</span>';
                else if(status == 'done')
                    return '<span class="label label-primary">Hoàn thành</span>';
                else
                return;
            },
            deleteOrder(orderId){
                axios.delete('/api/order/'+orderId).then(response => {
                    toastr['success'](response.data.message);
                    this.getOrders();
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            goCreate(){
                this.$router.push('/order/create')
            }
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        ready : function(){
            this.getVueItems(this.pagination.current_page);
        },
        filters: {
            moment(date) {
                return helper.formatDate(date);
            },
            ucword(value) {
                return helper.ucword(value);
            }
        }
    }
</script>
