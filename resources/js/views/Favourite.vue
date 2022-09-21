
<template>
<main>
  <nav class="nav">
        <div class="row nav-inner">
            <div class="col-md-2">
            <span>🌊 kanye.rest</span>
            </div>
            <div class="col-md-8 favorites">
                <router-link to="/home" class="FavLink">Home</router-link>
                <router-link to="/favourite" class="FavLink ml-6" style="margin-left: 30px;">favorites</router-link>
            </div>
            <div v-for="(u, index) in user" :key="index" class="col-md-1 favorites" style="padding-left:70px;">
               {{u.name}}
            </div> 
              <div class="col-md-1 favorites">
                <a to="/logout" class="FavLink ml-6" style="margin-left: 30px;" @click.prevent="logout()">Logout</a>
            </div> 

        </div>
    </nav>

    <div style="padding-top:100px;padding-bottom:50px;">
        <div class="row">
            <div class="col-md-4" style="text-align: right;">
                <i class="fa fa-star fa-3" style="color:#f3ffb3;font-size: 1.5em;"></i>
            </div>
             <div class="col-md-3">
                <h1 class="nav-inner text-center">Favourite quotes </h1>
            </div>
            <div class="col-md-5">
                 <i class="fa fa-star fa-3" style="color:#f3ffb3;font-size: 1.5em;"></i>
            </div>
        </div>
        
    </div>
    <div v-if="successAlert" class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Deleted from favorites successfully!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="cardDiv">
        <div class="card" style="width: 65rem;">
            <div class="card-body">
            <div v-for="(item, index) in favourite" :key="index" style="padding-bottom:30px;">
                <div class="row">
                    <div class="col-md-10">{{item.favourite_quotes}}</div>
                    <div class="col-md-2"> <i class="fa fa-trash fa-3 mr-3 mt-2" style="color:#f3ffb3;font-size: 1.5em;" title="Delete from favourites" @click="deleteFromFavoutrite(item.id)"></i></div>
                </div>
            </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
import { useRouter } from 'vue-router';
import { mapActions } from 'vuex';

export default {
    name: "Favourite",
    mounted() {
            this.getFavoutrite();
        },
    data() {
        return {
            user:this.$store.state.auth.user.User,
            router:useRouter(),
            successAlert:false,
            delete:[],
            favourite: []
        }
    },
    methods:{

        // FUNCTION TO GET API OF FAVOURITE QUOTES
        getFavoutrite(){
                
            axios
            .get('/api/favourite')
            .then(response => (this.favourite = response.data.Favourites))
            .catch(error => console.log(error))
        },

        // FUNCTION TO DELETE QUOTES FROM FAVORITE
         deleteFromFavoutrite(id){
                
            axios
            .delete('/api/quotes/'+id)
            .then(response => {
                this.delete = response.data.Favourites;
                this.successAlert =true;
                setTimeout(() => this.successAlert = false, 2000);
            }
            ).catch(error => console.log(error));
            this.getFavoutrite();

        },

        // GETTING STATE LOGOUT ACTION FUNCTION
         ...mapActions({
            signOut:"auth/logout"
        }),

        // FUCTION TO PERFORM LOGOUT FUNCTIONALITY
        logout() {
                axios.get('/logout')
                .then(response => {
                 this.signOut()
                 this.router.push({ path: '/' });
                })
                .catch(error => {});
            }
    }

}
</script>

<style>

  body {
    width: 100%;
    height: 100vh;
    overflow: hidden;
    margin: 0;
}

::-webkit-scrollbar { 
    display: none;
}

/* Nav */
.nav {
    width: 100%;
    padding: 15px;
    height: 65px;
    background-image: linear-gradient(33deg, #F78FAD, #FDEB82);
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 999;
     -webkit-box-shadow:0px 1px 10px #F78FAD !important;
    -moz-box-shadow:0px 1px 10px #F78FAD !important; 
    box-shadow: 0px 1px 10px #F78FAD !important; 
}

.nav-inner {
    width: 100%;
    font-style: italic;
    font-size: 17px;
    font-weight: 700;
    font-size: 25px;
}
.favorites{
    font-style: italic;  
    font-weight: unset;
    font-size: 16px;
    padding-top: 8px;

}
.card{
    background-image: linear-gradient(33deg, #F78FAD, #FDEB82);
    margin: 0 auto; 
    float: none; 
    
    
}
.cardDiv{
    -webkit-box-shadow:0px 1px 10px #F78FAD !important;
    -moz-box-shadow:0px 1px 10px #F78FAD !important; 
    box-shadow: 0px 1px 10px #F78FAD !important; 
    max-height: 458px;
    overflow-y: auto;
    border-radius: 15px;
}
.FavLink{
    color:black;
}

.FavLink:hover{
    color:black;
    text-decoration: none;
}
</style>