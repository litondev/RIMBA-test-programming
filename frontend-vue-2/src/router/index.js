import Vue from 'vue'
import Router from 'vue-router'

import CustomerIndex from '@/views/customer/index';
import CustomerForm from  '@/views/customer/form';

import ItemIndex from '@/views/item/index';
import ItemForm from  '@/views/item/form';

import SaleIndex from '@/views/sale/index';
import SaleForm from  '@/views/sale/form';
import SaleShow from "@/views/sale/show";

Vue.use(Router)

export default new Router({
  mode : 'history',
  routes: [
    {
      path: '/',
      name: 'customer-index',
      component: CustomerIndex
    },
    {
    	path : '/form/:id',
    	name : 'customer-form',
    	component : CustomerForm
    },
 	  {
      path: '/item',
      name: 'item-index',
      component: ItemIndex
    },
    {
    	path : '/item/form/:id',
    	name : 'item-form',
    	component : ItemForm
    },
    {
    	path: '/sale',
    	name: 'sale-index',
    	component: SaleIndex
    },
    {
    	path : '/sale/form/:id',
    	name : 'sale-form',
    	component : SaleForm
    },
    {
      path : "/sale/show/:id",
      name : 'sale-show',
      component : SaleShow
    }
  ]
})
