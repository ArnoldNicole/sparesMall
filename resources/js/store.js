import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state : {        
        user:false, 
        counter:1000,
        cart:{
            items:'',
            products:[],
        },  
        index:-1, 
        product:[], 
        res:''
    },
    getters: {
        getCounter(state){
           return state.conuter
        },
    },

    mutations: {
        changeTheCounter(state, data){
            state.conuter += data
        },       
        setUpdateUser(state, data){
            state.user = data
         },
         setUpdateCart(state, data){
            state.cart = data
         },
        updateCart(state, data){
            if (state.cart == false) {
                let obj = {
                    items:0,
                    products:[],
                }
              state.cart = obj
            }
            state.cart.items = Number(state.cart.items)+1
            state.cart.products.unshift(data)
        }, 
        downGradeCart(state, index){
            state.cart.items = state.cart.items-1
            state.cart.products.splice(index, 1)
        },  
        IncrementProduct(state, index){
        this.index = index  
        this.product = state.cart.products[this.index]
        this.res = this.product.pivot.quantity++        
        },
        DecreamentProduct(state, index){
        this.index = index  
        this.product = state.cart.products[this.index]
        this.res = this.product.pivot.quantity--        
        }
    },
    actions :{
        changeCounterAction({commit}, data){
            commit('changeTheCounter', data)
        }
    }


})
