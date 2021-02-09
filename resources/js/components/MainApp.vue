<template>
    <div>
        <!-- Top bar Start -->
        <div class="top-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4 font-weight-bold">
                        <i class="fa fa-envelope"></i>
                        arnoldwamae2@gmail.com
                    </div>
                    <div class="col-sm-4 font-weight-bold text-center">
                        <i class="fa fa-phone-alt"></i>
                        YOUR TRUSTED SPARE PARTS DEALER
                    </div>
                    <div class="col-sm-4 font-weight-bold">
                        <i class="fa fa-phone-alt"></i>
                        +254708509365
                    </div>
                </div>
            </div>
        </div>
        <!-- Top bar End -->

        <!-- Nav Bar Start -->
        <div class="nav">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-md bg-dark navbar-dark">
                    <a href="#" class="navbar-brand">MENU</a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto">
                            <router-link to="/" class="nav-item nav-link active">Home</router-link>
                            <router-link to="/browse/products" class="nav-item nav-link">Products</router-link>                            
                            <router-link to="/customer/cart" class="nav-item nav-link">Cart</router-link>
                            <router-link to="/customer/purchase/checkout" class="nav-item nav-link" v-if="$store.state.user">Checkout</router-link>
                            <router-link to="/customer/account" class="nav-item nav-link" v-if="$store.state.user">My Account</router-link>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">More</a>
                                <div class="dropdown-menu">        
                                    <a href="/login" class="dropdown-item" v-if="$store.state.user==false">Login</a>
                                    <a href="/register" class="dropdown-item" v-if="$store.state.user==false">Register</a>
                                </div>
                            </div>
                        </div>
                        <div class="navbar-nav ml-auto" v-if="$store.state.user">
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">User Account</a>
                                <div class="dropdown-menu">        
                                    <a href="/seller/account/index" v-if="$store.state.user.userType=='Admin'" class="dropdown-item">
                                        Seller Homepage
                                    </a> 
                                    <a href="/app/logout" class="dropdown-item">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Nav Bar End -->

        <!-- Bottom Bar Start -->
        <div class="bottom-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="logo">                            
                                <img src="/img/logo.png" alt="Logo">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <search/>
                    </div>
                    <div class="col-md-3">
                        <div class="user">
                            <!-- <a href="wishlist.html" class="btn wishlist">
                                <i class="fa fa-heart"></i>
                                <span>(0)</span>
                            </a> -->
                            <router-link to="/customer/cart" class="btn cart">
                                <i class="fa fa-shopping-cart"></i>
                                <span v-if="$store.state.cart">{{$store.state.cart.items}}</span>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom Bar End -->

        <!-- <div class="header mt-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                        <nav class="navbar bg-info">
                            <ul class="navbar-nav border border-right border-dark">
                                <li class="nav-item">
                                    <a class="nav-link" href="/"><i class="fa fa-home"></i>Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><i class="fa fa-shopping-bag"></i>Best Selling</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><i class="fa fa-plus-square"></i>New Arrivals</a>
                                </li>
                                
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-10">
                        <router-view>
                            
                        </router-view>
                    </div>
                </div>
            </div>
        </div> -->
        <div class=" container-fluid">
            <router-view>
                    
            </router-view>
        </div>

        <featured-product/>

        <!-- Brand Start -->
        <div class="brand">
            <div class="container-fluid">
                <div class="brand-slider">
                    <div class="brand-item"><img src="/img/toyota.png" alt="Toyota"></div>                    
                    <div class="brand-item"><img src="/img/mercedez.png" alt="Mercedez"></div>
                    <div class="brand-item"><img src="/img/volkswagen.png" alt="Volkswagen"></div>
                    <div class="brand-item"><img src="/img/bmw.svg" alt="BMW"></div>
                </div>
            </div>
        </div>
        <!-- Brand End -->

        <div class="feature">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fab fa-cc-mastercard"></i>
                            <h2>Secure Payment</h2>
                            <p>
                                You are in control of your wallet
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fa fa-truck"></i>
                            <h2>Country Wide Delivery</h2>
                            <p>
                                We deliver all over Kenya and free for select locations
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fa fa-sync-alt"></i>
                            <h2>15 Days Return</h2>
                            <p>
                                 Free to think otherwise for upto 15days
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fa fa-comments"></i>
                            <h2>24/7 Support</h2>
                            <p>
                                This is a joke we are always offline haha
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Get in Touch</h2>
                            <div class="contact-info">
                                <p><i class="fa fa-map-marker"></i>44 STAGE, Dandora</p>
                                <p><i class="fa fa-envelope"></i>arnoldwamae@gmail.com</p>
                                <p><i class="fa fa-phone"></i>+254 7 085 0936 5</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Follow Us</h2>
                            <div class="contact-info">
                                <div class="social">
                                    <a href="https://twitter.com/wamae_arnold"><i class="fab fa-twitter"></i></a>
                                    <a href="https://facebook.com/me_arnoldnyaga"><i class="fab fa-facebook-f"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Company Info</h2>
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms & Condition</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Purchase Info</h2>
                            <ul>
                                <li><a href="#">Pyament Policy</a></li>
                                <li><a href="#">Shipping Policy</a></li>
                                <li><a href="#">Return Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row payment align-items-center">
                    <div class="col-md-6">
                        <div class="payment-method">
                            <h2>We Accept:</h2>
                            <img src="/img/payment-method.png" alt="Payment Method" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="payment-security">
                            <h2>Secured By:</h2>
                            <img src="/img/godaddy.svg" alt="Payment Security" />
                            <img src="/img/norton.svg" alt="Payment Security" />
                            <img src="/img/ssl.svg" alt="Payment Security" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import featuredProduct from './guest/featuredProduct'
    import search from './guest/search'
    export default{
        props: ['user','cart'],
        data(){
            return {
                isLoggedIn : false,
            }
        },
        methods:{

        },
        components:{
            featuredProduct,
            search
        },        
     created(){     
       this.$store.commit('setUpdateUser', this.user)
       this.$store.commit('setUpdateCart', this.cart)       
       }
    }
</script>t>