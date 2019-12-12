<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Tạo đơn hàng</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link to="/home">Dashboard</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link to="/order">Đơn hàng</router-link>
          </li>
          <li class="breadcrumb-item active">Tạo đơn hàng</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Thông tin đơn hàng</h4>
            <div class="row">
              <div class="form-group col-xs-12 col-md-4">
                <select class="form-control" placeholder="Chọn loại" value="" v-model="tempCart.type">
                  <option value="ao">Áo</option>
                  <option value="ly">Ly</option>
                  <option value="op">Ốp</option>
                </select>
              </div>
              <div class="form-group col-xs-12 col-md-4" v-if="tempCart.type=='ao'">
                <select class="form-control" placeholder="Chọn size" v-model="tempCart.size">
                  <option value="3">Size 3</option>
                  <option value="4">Size 4</option>
                  <option value="5">Size 5</option>
                  <option value="6">Size 6</option>
                  <option value="S">Size S</option>
                  <option value="M">Size M</option>
                  <option value="L">Size L</option>
                  <option value="XL">Size XL</option>
                  <option value="2XL">Size 2XL</option>
                  <option value="3XL">Size 3XL</option>
                </select>
              </div>
              <div class="form-group col-xs-12 col-md-4">
                <input class="form-control" type="number" min="1" placeholder="Số lượng" value="" v-model="tempCart.number">
              </div>
              <div class="form-group col-xs-12 col-md-4">
                <input class="form-control" type="number" min="1000" step="100" placeholder="Giá" value="" v-model="tempCart.price">
              </div>
              <div class="form-group col-xs-12 col-md-4">
                <textarea class="form-control" placeholder="Ghi chú" value="" v-model="tempCart.note"></textarea>
              </div>
              <div class="col-xs-12 col-md-8">
                <span>
                  <label class="btn btn-info">
                    <input type="file" multiple @change="changeTempImage" id="avatarUpload" class="upload-button">
                    <i class="fa fa-upload margin-correction"></i>
                    Chọn hình ảnh
                  </label>
                </span>
              </div>
            </div>
            <button class="btn btn-info waves-effect waves-light float-right m-b-10" @click="addProduct">
              <i class="fa fa-plus"></i> Thêm sản phẩm
            </button>
            <div class="=row">
              <table class="table table-bordered" style="table-layout: fixed; word-wrap: break-word;">
                <thead>
                  <th style="width: 80px">STT</th>
                  <th style="width: 120px">Loại</th>
                  <th>Hình ảnh</th>
                  <th>Đơn giá</th>
                  <th style="width: 100px">Số lượng</th>
                  <th>Tổng</th>
                  <th>Ghi chú</th>
                  <th style="width: 120px">Hành động</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in cart">
                    <td style="width: 80px">{{index + 1}}</td>
                    <td style="width: 120px" v-if="item.type == 'shirt'">Áo ({{item.size}})</td>
                    <td style="width: 120px" v-else>Ly</td>
                    <td>
                      <div v-for="(image, key) in item.images">
                        <img class="preview" v-bind:src="image" style="width: 100%"/>
                      </div>
                    </td>
                    <td>{{formatMoney(item.price)}}</td>
                    <td style="width: 100px">{{item.number}}</td>
                    <td>{{formatMoney(item.total)}}</td>
                    <td>{{item.note}}</td>
                    <td style="width: 120px">
                      <button class="btn btn-success btn-sm" data-toggle="tooltip" title="Chỉnh sửa" @click="editCartItem(item)">
                        <i class="fa fa-edit"></i>
                      </button>
                      <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Xóa" @click="deleteCartItem(item)">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5" class="text-right"><b>Phí giao hàng</b></td>
                    <td>
                      <input class="form-control" type="number" placeholder="Phí giao hàng" value="" v-model="order.shipping">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="5" class="text-right"><b>Thành tiền</b></td>
                    <td><b>{{totalCart}}</b></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Thông tin khách hàng</h4>
            <div class="form-group" v-if="false">
              <input class="form-control" type="text" placeholder="Tìm kiếm khách hàng" value="" v-model="customer.id">
            </div>
            <div class="form-group">
              <label for="">Họ và tên</label>
              <input class="form-control" type="text" placeholder="Họ và tên" value="" v-model="customer.name">
            </div>
            <div class="form-group">
              <label for="">Số điện thoại</label>
              <input class="form-control" type="text" placeholder="Số điện thoại" value="" v-model="customer.phone">
            </div>
            <div class="form-group">
              <label for="">Email</label>
              <input class="form-control" type="text" placeholder="Email" value="" v-model="customer.email">
            </div>
            <div class="form-group">
              <label for="">Địa chỉ</label>
              <textarea class="form-control" placeholder="Địa chỉ" value="" v-model="customer.address"></textarea>
            </div>
            <div class="form-group">
              <label for="">Ngày sinh</label>
              <datepicker v-model="customer.date_of_birth" placeholder="Ngày sinh" :bootstrapStyling="true"></datepicker>
            </div>
            <div class="form-group">
              <label for="">Giới tính</label>
              <div class="radio radio-info">
                <input type="radio" value="male" id="gender_male" v-model="customer.gender" :checked="customer.gender === 'male'">
                <label for="gender_male"> Nam </label>
              </div>
              <div class="radio radio-success">
                <input type="radio" value="female" id="gender_female" v-model="customer.gender" :checked="customer.gender === 'female'">
                <label for="gender_female"> Nữ </label>
              </div>
            </div>
            <div class="form-group">
              <label for="">Facebook</label>
              <input class="form-control" type="text" placeholder="Facebook" value="" v-model="customer.facebook">
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Thông tin giao hàng</h4>
            <div class="form-group">
              <label for="">Họ và tên</label>
              <input class="form-control" type="text" placeholder="Họ và tên" value="" v-model="shippingAddress.name">
            </div>
            <div class="form-group">
              <label for="">Số điện thoại</label>
              <input class="form-control" type="text" placeholder="Số điện thoại" value="" v-model="shippingAddress.phone">
            </div>
            <div class="form-group">
              <label for="">Địa chỉ</label>
              <textarea class="form-control" placeholder="Địa chỉ" value="" v-model="shippingAddress.address"></textarea>
            </div>
            <div class="form-group">
              <label for="">Ghi chú giao hàng</label>
              <textarea class="form-control" placeholder="Ghi chú giao hàng" value="" v-model="shippingAddress.note"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label for="">Trạng thái đơn hàng</label>
              <select class="form-control" placeholder="Trạng thái đơn hàng" value="" v-model="order.status">
                <option value="new">Mới</option>
                <option value="printing">Đang in</option>
                <option value="printed">Đã in</option>
                <option value="done">Đã hoàn thành</option>
                <option value="cancel">Bị hủy</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Số thứ tự in</label>
              <input class="form-control" type="number" placeholder="Số thứ tự in" value=""
                     v-model="order.print_number">
            </div>
            <div class="form-group">
              <label for="">Trạng thái vận chuyển</label>
              <select class="form-control" placeholder="Trạng thái vận chuyển" value="" v-model="order.shipping_status">
                <option value="pending">Chưa giao hàng</option>
                <option value="processing">Đang vận chuyển</option>
                <option value="done">Đã giao</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Ghi chú đơn hàng</label>
              <textarea class="form-control" placeholder="Ghi chú đơn hàng" value="" v-model="order.note"></textarea>
            </div>
          </div>
        </div>
      </div>
      <button style="left: 45%" type="submit" class="btn btn-info waves-effect waves-light m-t-10" @click="addOrder">Tạo đơn</button>
    </div>
  </div>
</template>

<script>
  import datepicker from 'vuejs-datepicker'
  import ClickConfirm from 'click-confirm'

  export default {
    components: {datepicker, ClickConfirm},
    data() {
      return {
        tempCart: {
          type: 'ao',
          size: 'S',
          number: 1,
          images: [],
          note: ''
        },
        cart: [],
        customer: {
          id: '',
          name: '',
          email: '',
          phone: '',
          address: '',
          birthday: '',
          gender: '',
          facebook: ''
        },
        shippingAddress: {
          name: '',
          phone: '',
          address: '',
          note: '',
        },
        order: {
          status: 'new',
          print_number: '',
          shipping_status: 'new',
          shipping: 0,
          note: ''
        },
        user_id: ''
      };
    },
    mounted(){
      axios.get('/api/auth/user').then(response => response.data).then(response => {
          this.user_id = response.profile.user_id;
      });
    },
    methods: {
      addProduct() {
        let tempcart = this.tempCart;
        if (!('price' in tempcart) || typeof tempcart.price === 'undefined') {
          toastr['error']('Bạn chưa nhập trường giá cho sản phẩm');
          return false;
        }
        if (tempcart.number < 1) {
          toastr['error']('Số lượng lớn hơn 1');
          return;
        }
        tempcart.id = new Date().getTime();
        tempcart.total = tempcart.price*tempcart.number;
        this.cart.push(tempcart);
        this.tempCart = {
          type: 'ao',
          size: 'S',
          number: 1,
          images: [],
          note: ''
        }
      },
      onImageChange(e) {
          let files = e.target.files || e.dataTransfer.files;
          if (!files.length)
              return;
          this.createImage(files[0]);
      },
      createImage(file) {
          let reader = new FileReader();
          let vm = this.tempCart.images;
          reader.onload = (e) => {
              vm.push(e.target.result);
          };
          reader.readAsDataURL(file);
      },
      changeTempImage(e){
        var selectedFiles = e.target.files || e.dataTransfer.files;
        for (var i=0; i < selectedFiles.length; i++){
            this.createImage(selectedFiles[i]);
        }

        for (var i=0; i<this.tempCart.images.length; i++){
            let reader = new FileReader(); //instantiate a new file reader
            reader.addEventListener('load', function(){
              this.$refs['image' + parseInt( i )][0].src = reader.result;
            }.bind(this), false);  //add event listener

            reader.readAsDataURL(this.tempCart.images[i]);
        }
      },
      editCartItem(product) {
        console.log(product);
      },
      deleteCartItem(product) {
        this.cart = this.cart.filter(item => {
          return item.id != product.id
        })
      },
      formatMoney(num) {
        if (num) num = num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + 'đ';
        return num;
      },
      updateProfile() {
        this.profileForm.date_of_birth = moment(this.profileForm.date_of_birth).format('YYYY-MM-DD');
        this.profileForm.post('/api/user/update-profile').then(response => {
          toastr['success'](response.message);
          this.$store.dispatch('setAuthUserDetail', {
            first_name: this.profileForm.first_name,
            last_name: this.profileForm.last_name
          });
        }).catch(response => {
          toastr['error'](response.message);
        });
      },
      addOrder() {
        var data = {};
        data['cart'] = this.cart;
        data['customer'] = this.customer;
        data['order'] = this.order;
        data['shippingAddress'] = this.shippingAddress;
        data['user'] = this.user_id;
        if (!this.validateEmail(data['customer']['email'])) {
          toastr['error']('Email không hợp lệ!');
          return true;
        }
        if (!this.validatePhone(data['customer']['phone']) || !this.validatePhone(data['shippingAddress']['phone'])) {
          toastr['error']('Số điện thoại không hợp lệ!');
          return true;
        }
        if (data['order']['print_number'] == '') {
          toastr['error']('Chưa có số thử tự in!');
          return true;
        }
        console.log(data);
        axios.post('/api/add-order', data).then(response => {
            if (response.data.status) {
                toastr['success'](response.data.message);
                this.$router.push('/home');
            } else {
                toastr['error'](response.data.message);
            }
        }).catch(response => {
            toastr['error'](response.data.message);
        });
      },
      validateEmail(email) {
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return re.test(String(email).toLowerCase());
      },
      validatePhone(phone) {
          var re = /(0[3|5|7|8|9])([0-9]{8})$/;
          return re.test(String(phone));
      }
    },
    computed: {
      totalCart() {
        let total = 0;
        this.cart.forEach(item => {
          total += item.total;
        });
        total += (this.order.shipping - 0);
        return this.formatMoney(total);
      }
    }
  }
</script>
