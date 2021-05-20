<template>
    <div>
        <!-- Top bar Start -->
        <div class="top-bar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4 text-center font-weight-bold">
                        <i class="fa fa-envelope"></i>
                        helpline@aseshopit.com
                    </div>
                    <div class="col-sm-4 text-center font-weight-bold text-center">
                        <i class="fa fa-phone-alt"></i>
                            SCHOOL SHOPPING AND DELIVERY
                    </div>
                    <div class="col-sm-4 text-center font-weight-bold">
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
                            <router-link to="/" class="nav-item nav-link" active-class="active" exact-active-class="font-weight-bold active text-primary">Home</router-link>
                            <router-link to="/browse/products" class="nav-item nav-link" active-class="active" exact-active-class="font-weight-bold active text-primary">Products</router-link>                            
                            <router-link to="/customer/cart" class="nav-item nav-link" active-class="active" exact-active-class="font-weight-bold active text-primary">Cart</router-link>
                            <router-link to="/customer/purchase/checkout" class="nav-item nav-link" active-class="active" exact-active-class="font-weight-bold active text-primary" v-if="$store.state.user">Checkout</router-link>
                            <router-link to="/customer/account" class="nav-item nav-link" active-class="active" exact-active-class="font-weight-bold active text-primary" v-if="$store.state.user">My Account</router-link>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">More</a>
                                <div class="dropdown-menu">        
                                    <a href="/login" class="dropdown-item" v-if="$store.state.user==false">Login</a>
                                    <a href="/register" class="dropdown-item" v-if="$store.state.user==false">Register</a>
                                    <router-link class="dropdown-item" to="/about_us">About Us</router-link>
                                    <router-link class="dropdown-item" to="/terms_and_conditions">Terms of Service</router-link>
                                    <router-link class="dropdown-item" to="/privacy_policy">Privacy Policy</router-link>
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
                <div class="row">
                    <div class="col-md-2">
                        <div class="logo">                            
                                <img src="/img/logo.png" class="img-fluid w-50 m-2 rounded-circle" alt="Logo">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <search/>
                    </div>
                    <div class="col-md-2">
                        <div class="user">
                            <router-link to="/customer/cart" class="btn cart m-2">
                                <i class="fa fa-shopping-cart"></i>
                                <span v-if="$store.state.cart">{{$store.state.cart.items}}</span>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <router-view>                    
            </router-view>
        </div>

        <featured-product/>

        <div class="feature">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
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
                                We deliver in <b>Schools</b> all over Kenya and free for select locations
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 feature-col">
                        <div class="feature-content">
                            <i class="fa fa-comments"></i>
                            <h2>24/7 Support</h2>
                            <p>
                                Always Online For You
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
                                <p><i class="fa fa-map-marker"></i>Kakamega Kenya</p>
                                <p><i class="fa fa-envelope"></i>help@aseshopit.com</p>
                                <p><i class="fa fa-phone"></i><a href="tel:+254708509365">0708509XXX</a></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Follow Us</h2>
                            <div class="contact-info">
                                <div class="social">
                                    <a href="https://twitter.com/ase_ltd"><i class="fab fa-twitter"></i></a>
                                    <a href="https://facebook.com/ase_ltd"><i class="fab fa-facebook-f"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Company Info</h2>
                            <ul>
                               <li><router-link to="/about_us">About Us</router-link></li>
                               <li><router-link to="/terms_and_conditions">Terms & Conditions</router-link></li>
                               <li><router-link to="/privacy_policy">Privacy Policy</router-link></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h2>Purchase Info</h2>
                            <ul>
                                <li><a href="#">Payment Policy</a></li>
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
       },
    mounted(){
        window.scrollTo(0,0)
    }
    }
</script>t>