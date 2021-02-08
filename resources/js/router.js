import Router from 'vue-router'
import Vue from 'vue'
// Vue.use(ViewUI);
Vue.use(Router)
import Index from './components/guest/index'
import AdminIndexPage from './components/seller/AdminIndexPage'
import Products from './components/seller/Product'
import Categories from './components/seller/Categories'
import Orders from './components/seller/Orders'
import ProductDetails from './components/guest/ProductDetails'
import Cart from './components/guest/Cart'
import AllProducts from './components/guest/Products'
import CheckOut from './components/guest/Checkout'
import Account from './components/guest/Account'
import allOrders from './components/guest/allOrders'


const routes = [
	{
	 	path: '/',
	 	component: Index,
	},
	{
		path: '/customer/product/description/:id',
		component: ProductDetails,
	},

	//admin routes
	{
		path: '/seller/account/index',
		component: AdminIndexPage,
	},
	{
		path: '/seller/account/products',
		component: Products,
	},
	{
		path: '/seller/account/product_categories',
		component: Categories,
	},
	{
		path: '/seller/account/client_orders',
		component: Orders
	},
	{
		path: '/customer/cart',
		component: Cart
	},
	{
		path: '/browse/products',
		component: AllProducts
	},
	{
		path: '/customer/purchase/checkout',
		component: CheckOut
	},
	{
		path:'/customer/account',
		component: Account
	},
	{
		path:'/customer/product/completeOrder',
		component: allOrders
	}

	
]
export default new Router({
	mode: 'history',
	routes
})