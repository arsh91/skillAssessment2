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
<router-view></router-view>
    <div class="main-container dragscroll" id="js-parallax-container">
      <h1 class="nav-inner text-center">Kanye West quotes </h1>
         <div v-if="successAlert" class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-left: 280px;max-width: 837px;">
            <strong>Added to favorites successfully!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
         <div v-if="successAlertAlready" class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-left: 280px;max-width: 837px;">
            <strong>Already added to favorites!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
      <div style="text-align:center;color:#f3ffb3;font-size: 2.5em;"><i class="fa fa-refresh" aria-hidden="true" title="Refresh" @click="getQuotes"></i></div>
        <div class="scrollable-area" id="js-dragscroll-area">
                <div class="slide one">
                    <div class="masonry">
                        <div class="item" v-for="(quote, index) in quotes" :key="index">
                            <div class="qouteText">
                                {{quote}}<br>
                                <i class="fa fa-star fa-3 mt-3" style="color:#f3ffb3;font-size: 1.5em;" title="Add to favourites" @click="AddToFavourite(quote)"></i>
                            </div>
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
    name: "Home",
    data() {
        return {
            user:this.$store.state.auth.user.User,
            router:useRouter(),
            successAlert:false,
            successAlertAlready:false,
            quotes: [],
            favourites:[],
            moreQuotesFetched:false,
            moreQuotes: []
        };
    },
 	mounted() {

        // API call
        this.getQuotes();

        // FUNCTION TO MAKE CONTAINER SCROLLABLE WITH MOUSE
        (function (root, factory) {
                if (typeof define === 'function' && define.amd) {
                    define(['exports'], factory);
                } else if (typeof exports !== 'undefined') {
                    factory(exports);
                } else {
                    factory((root.dragscroll = {}));
                }
            }(this, function (exports) {
                var _window = window;
                var _document = document;
                var mousemove = 'mousemove';
                var mouseup = 'mouseup';
                var mousedown = 'mousedown';
                var EventListener = 'EventListener';
                var addEventListener = 'add'+EventListener;
                var removeEventListener = 'remove'+EventListener;
                var newScrollX, newScrollY;

                var dragged = [];
                var reset = function(i, el) {
                    for (i = 0; i < dragged.length;) {
                        el = dragged[i++];
                        el = el.container || el;
                        el[removeEventListener](mousedown, el.md, 0);
                        _window[removeEventListener](mouseup, el.mu, 0);
                        _window[removeEventListener](mousemove, el.mm, 0);
                    }

                    // cloning into array since HTMLCollection is updated dynamically
                    dragged = [].slice.call(_document.getElementsByClassName('dragscroll'));
                    for (i = 0; i < dragged.length;) {
                        (function(el, lastClientX, lastClientY, pushed, scroller, cont){
                            (cont = el.container || el)[addEventListener](
                                mousedown,
                                cont.md = function(e) {
                                    if (!el.hasAttribute('nochilddrag') ||
                                        _document.elementFromPoint(
                                            e.pageX, e.pageY
                                        ) == cont
                                    ) {
                                        pushed = 1;
                                        lastClientX = e.clientX;
                                        lastClientY = e.clientY;

                                        e.preventDefault();
                                    }
                                }, 0
                            );

                            _window[addEventListener](
                                mouseup, cont.mu = function() {pushed = 0;}, 0
                            );

                            _window[addEventListener](
                                mousemove,
                                cont.mm = function(e) {
                                    if (pushed) {
                                        (scroller = el.scroller||el).scrollLeft -=
                                            newScrollX = (- lastClientX + (lastClientX=e.clientX));
                                        scroller.scrollTop -=
                                            newScrollY = (- lastClientY + (lastClientY=e.clientY));
                                        if (el == _document.body) {
                                            (scroller = _document.documentElement).scrollLeft -= newScrollX;
                                            scroller.scrollTop -= newScrollY;
                                        }
                                    }
                                }, 0
                            );
                        })(dragged[i++]);
                    }
                }
                if (_document.readyState == 'complete') {
                    reset();
                } else {
                    _window[addEventListener]('load', reset, 0);
                }

                exports.reset = reset;
            }));

            // Parallax effect using Tweenmax
            $("#js-parallax-container").mousemove(function(e) {
                parallaxIt(e, ".slide", -100);
                parallaxIt(e, "img", -30);
            });
        
            function parallaxIt(e, target, movement) {
                var $this = $("#js-parallax-container");
                var relX = e.pageX - $this.offset().left;
                var relY = e.pageY - $this.offset().top;
            
                TweenMax.to(target, 1, {
                x: (relX - $this.width() / 2) / $this.width() * movement,
                y: (relY - $this.height() / 2) / $this.height() * movement
                });
            }

            var scrollingWidth = $('#js-dragscroll-area').width();
            var scrollingHeight = $('#js-dragscroll-area').height();

            var windowWidth = $(window).width();
            var windowHeight = $(window).height();

            var centerX = scrollingWidth / 2 - windowWidth / 2;
            var centerY = scrollingHeight / 2 - windowHeight / 2;
            
            $('#js-parallax-container').scrollLeft(centerX);
            $('#js-parallax-container').scrollTop(centerY);
	},
	methods:{
        // GET API TO GET QUOTES
        getQuotes(){
            if(this.quotes.length > 0){
                this.quotes.splice(0,5);
            }
            let n=5;
            for (let i = 0; i < n; i++) {
                 axios
            .get('https://api.kanye.rest/')
            .then(response => {
                this.moreQuotes.push(response.data.quote);
                this.quotes = this.moreQuotes;
                });
            }
  
        },

        // FUNCTION TO SAVE FAVOURITE QUOTE
        AddToFavourite(quote){

            axios
            .post('/api/quotes',{"favourite_quotes":quote})
            .then(response => {
                this.favourites = response;
                if(response.data.status == false){
                    this.successAlertAlready =true;
                    setTimeout(() => this.successAlertAlready = false, 2000);
                }
                if(response.data.status == true){
                    this.successAlert =true;
                    setTimeout(() => this.successAlert = false, 2000);
                }
                
            })
            .catch(error => console.log(error))
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
.qouteText{
    height: 150px;
     background-image: linear-gradient(33deg, #F78FAD, #FDEB82);
    font-style: italic;
    font-size: 17px;
    padding-top: 37px;
    font-weight: 700;

}
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
.main-container {
    z-index: 99;
    width: 100%; 
    height: 100%; 
    overflow: scroll; 
    cursor: grab; 
    cursor: -o-grab; 
    cursor: -moz-grab; 
    cursor: -webkit-grab;
    position: relative;
    padding-top: 100px;
}

.scrollable-area {
    width: 160%;
    height: 160%;
    padding-top: 40px;
}

@media only screen and (max-width: 1024px) {

    .main-container {
        overflow-x: hidden;
    }

    .scrollable-area {
        width: 100%;
        height: 100%;
    }

}

.slide {
    display: block;
    margin: 0 auto;
}

.block {
    background-color: red;
    height: 200px !important;
}

/* Masonry */
.masonry {
    -webkit-column-count: 4;
    -moz-column-count: 4;
    column-count: 4;
    -webkit-column-gap: 1em;
    -moz-column-gap: 1em;
    column-gap: 1em;
    margin: 1.5em;
    padding: 0;
    -moz-column-gap: 1.5em;
    -webkit-column-gap: 1.5em;
    column-gap: 1.5em;
    font-size: .85em;
}

.item {
    display: inline-block;
    background: #ffffff;
    margin: 0 0 1.5em;
    width: 100%;
    -webkit-transition: 1s ease all;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    overflow: hidden;
    text-align: center;
    border: 1px solid #F78FAD;
    -webkit-box-shadow:0px 1px 10px #F78FAD !important;
    -moz-box-shadow:0px 1px 10px #F78FAD !important; 
    box-shadow: 0px 1px 10px #F78FAD !important; 
    border-radius: 80px;
}

.item img {
    max-width: 100%;
    margin: 0 auto;
    display: block;
}

@media only screen and (max-width: 320px) {
    .masonry {
        -moz-column-count: 1;
    }
    }

.FavLink{
    color:black;
}

.FavLink:hover{
    color:black;
    text-decoration: none;
}
</style>