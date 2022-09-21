<template>
<main>
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
</main>
</template>

<script>
import { useRouter } from 'vue-router';
import { mapActions } from 'vuex';
    export default {
         mounted() {
            console.log('Component mounted.')
        },
         data() {
            return {
                router:useRouter(),
                errors: [],
                email: '',
                password: '',  
            }
        },
        computed:{
     
        },
        methods:{
        // GETTING AUTH LOGIN FUNCTION
        ...mapActions({
            signIn:'auth/login'
        }),

        // FUNCTION TO PERFORM LOGIN FUNCTIONALITY
        Login: function (e) {
             e.preventDefault();
             var self=this;
          this.errors = [];
          
          if (!this.email) {
            this.errors.push('Email required.');
          }
          if (!this.password) {
            this.errors.push('Password required.');
          }
           let formContents ={
            "email": this.email,
            "password": this.password
           };
          
           if(this.errors.length == 0){
                axios.get('/sanctum/csrf-cookie')
                    axios.post('/login', formContents).  then(response => {
                        self.signIn();
                        if(response.data.status =='success'){
                        this.router.push({ path: '/home' });
                        }else{
                            this.router.push({path:'/'});     
                        } 
                        })
                        .catch(error => console.log(error))
           }
             
        }
      }
    }
</script>
