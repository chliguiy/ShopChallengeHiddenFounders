<template>
   <section id="team" class="pb-5">
    <div class="container">
        <h5 class="section-title h1">Nearby Shops</h5>
        <div class="row">
            <!-- Team member -->
            <div class="col-md-3"  v-for="shops in shop ">
                <div class="image-flip" >
                    <div class="mainflip">
                        <div class="frontside">
                            <div class="card">
                                <div class="card-body text-center">
                                     <h4 class="card-title">{{shops.name}}</h4>
                                    <p><img style="width:250px;height:300px;"  v-bind:src=shops.image ></p>
                                   
                                    <a  class="btn btn-success btn-sm" :disabled="shops.vote == 1||shops.vote == 0" v-on:click="like(shops.id,shops)"><i class="fa fas fa-thumbs-up"></i></a>

                                    <a  class="btn btn-danger btn-sm" :disabled="shops.vote == 1||shops.vote == 0" v-on:click="unlike(shops.id,shops)"><i class="fa fas fa-thumbs-down"></i></a>

               <div style="height:20px">
        <vue-countdown  v-if="shops.vote== 1||shops.vote == 0"  v-on:time-expire="handleTimeExpire(shop)" :seconds="shops.date_vote"></vue-countdown>

    </div>
       
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
            <!-- ./Team member -->
           
             
            <!-- ./Team member -->

        </div>
    </div>
</section>
<!-- Team -->
</template>
<style scoped>
@import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
#team {
    background: #eee !important;
}

.btn-primary:hover,
.btn-primary:focus {
    background-color: #108d6f;
    border-color: #108d6f;
    box-shadow: none;
    outline: none;
}

.btn-primary {
    color: #fff;
    background-color: #007b5e;
    border-color: #007b5e;
}

section {
    padding: 60px 0;
}

section .section-title {
    text-align: center;
    color: #007b5e;
    margin-bottom: 50px;
    text-transform: uppercase;
}

#team .card {
    border: none;
    background: #ffffff;
}




.mainflip {
    -webkit-transition: 1s;
    -webkit-transform-style: preserve-3d;
    -ms-transition: 1s;
    -moz-transition: 1s;
    -moz-transform: perspective(1000px);
    -moz-transform-style: preserve-3d;
    -ms-transform-style: preserve-3d;
    transition: 1s;
    transform-style: preserve-3d;
    position: relative;
}

.frontside {
    position: relative;
    -webkit-transform: rotateY(0deg);
    -ms-transform: rotateY(0deg);
    z-index: 2;
    margin-bottom: 30px;
}


.frontside,
.backside {
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    backface-visibility: hidden;
    -webkit-transition: 1s;
    -webkit-transform-style: preserve-3d;
    -moz-transition: 1s;
    -moz-transform-style: preserve-3d;
    -o-transition: 1s;
    -o-transform-style: preserve-3d;
    -ms-transition: 1s;
    -ms-transform-style: preserve-3d;
    transition: 1s;
    transform-style: preserve-3d;
}

.frontside .card,
.backside .card {
    min-height: 312px;
}

.backside .card a {
    font-size: 18px;
    color: #007b5e !important;
}

.frontside .card .card-title,
.backside .card .card-title {
    color: #007b5e !important;
}

.frontside .card .card-body img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
}
</style>
<script>
console.log('okw')


import axios from 'axios';
import VueCountdown from '@dmaksimovic/vue-countdown';

export default{ 
 data: function() {
    return {   shop: [],start: false}
    },
     mounted(){
        console.log('ok');
        this.fetchshop()
        },
   components: {
        'vue-countdown': VueCountdown
    },
    methods: {
         handleTimeExpire:function (shops) {
            alert('Time is up!');

             this.fetchshop();
        },
        startTimer:function () {
            this.start = true;
        },
   like:function(id,shops){
    var vm = this;
    console.log(id);
           var page_url='like';
           axios.post(page_url,{shop_id:id})
               .then(function (response) {
             
             vm.fetchshop();
               });
       },
          unlike:function(id,shops){
    var vm = this;
           var page_url='unlike';
           axios.post(page_url,{shop_id:id})
               .then(function (response) {
             
             vm.fetchshop();
               });
       },        fetchshop: function (page_url) {
            
             var vm = this;
           page_url=page_url||'shop';
           axios.get(page_url)
               .then(function (response) {
                   // set data on vm
                   var driversReady = response.data.map(function (Driver) {
                       return Driver
                   });
                   console.log(driversReady);
                   vm.shop= driversReady;
               });
        }
    }
}
</script>