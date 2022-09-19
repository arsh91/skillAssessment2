<template>
    <div class="vue-tempalte">
        <form @submit="Login" id="createAdministrator">
             <div class="col-md-12 text-center" style="padding-top: 17px;">
                <p v-if="errors.length">
                    <b>Please correct the following error(s):</b>
                    <ul class="list-group">
                      <li v-for="error in errors" :key="error" class="list-group-item list-group-item-danger" style="margin-bottom: 20px !important;">{{ error }}</li>
                    </ul>
                </p>
            </div>
            <div class="formDiv">
                <h2>Log In</h2>
    
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" class="form-control form-control-lg" v-model="email"/>
                </div>
    
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control form-control-lg" v-model="password"/>
                </div>
     
                <button type="submit" class="btn btn-dark btn-lg btn-block loginBtn" style="background-image: linear-gradient(33deg, #A16BFE , #DEB0DF);border:0px solid #A16BFE">Log In</button>
       
            </div>
        </form>
    </div>
        <router-view></router-view>

</template>

<script>
import router from '@/router';


    export default {
         mounted() {
            console.log('Component mounted.')
        },
         data() {
            return {
                errors: [],
                email: '',
                password: ''
              
            }
        },
        methods:{
        Login: function (e) {
             e.preventDefault();
          this.errors = [];
          
          if (!this.email) {
            this.errors.push('Email required.');
          }
          if (!this.password) {
            this.errors.push('Password required.');
          }
          console.log(this.errors);
     
           alert('hgfda');
           let formContents ={
            "email": this.email,
            "password": this.password
           };

          axios.post('/login', formContents).then(function(response, status, request) {  
            if(response.data.status =='success'){
               router.push({ path: '/home' });
            }else{
                router.push({path:'/'});     
            }
                    }, function() {
                        console.log('failed');
                    });
      
       
         
        }
      }
    }
</script>