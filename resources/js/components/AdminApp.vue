<template>
    <div class="layout">
            <Layout>
                <Header :style="{width: '100%'}">
                    <Menu mode="horizontal" theme="dark" active-name="1">
                        <div class="layout-logo"></div>
                        <div class="layout-nav">
                            <MenuItem name="1" to="/seller/account/index">
                                <Icon type="ios-navigate"></Icon>
                                Home
                            </MenuItem>
                            <MenuItem name="2" to="/seller/account/products">
                                <Icon type="ios-keypad"></Icon>
                                Products
                            </MenuItem>
                            <MenuItem name="3" to="/seller/account/product_categories">
                                <Icon type="ios-analytics"></Icon>
                                Product Categories
                            </MenuItem>
                            <MenuItem name="4" to="/seller/account/client_orders">
                                <Icon type="ios-paper"></Icon>
                                Orders
                            </MenuItem>
                        </div>
                    </Menu>
                </Header>
                <Content class="m-2" :style="{background: '#fff', minHeight: '500px'}">
                    <router-view>
                        
                    </router-view>
                </Content>
                <Footer class="layout-footer-center">2018-2021 &copy; <a href="https://digitecsolutions.com" target=_blank> N&A Digital Solutions</a> </Footer>
            </Layout>
        </div>
</template>
<script>   
    export default{
        props: ['user'],
        data(){
            return {
                isLoggedIn : false,
            }
        },
        methods:{

        },                
     created(){     
       this.$store.commit('setUpdateUser', this.user)       
       }
    }
</script>
<style scoped>
    .layout{
        border: 1px solid #d7dde4;
        background: #f5f7f9;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    }
    .layout-logo{
        width: 100px;
        height: 30px;
        background: white;
        border-radius: 3px;
        float: left;
        position: relative;
        top: 15px;
        left: 20px;
    }
    .layout-nav{
        width: 530px;
        margin: 0 auto;
        margin-right: 20px;
    }
    .layout-footer-center{
        text-align: center;
    }
</style>