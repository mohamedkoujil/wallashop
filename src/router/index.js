import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../views/HomePage.vue';
import ProductDetail from '../views/ProductDetail.vue';
import ProfilePage from '../views/ProfilePage.vue';
import EditProfile from '../views/EditProfile.vue';
import addProductPage from './../views/addProductPage.vue'
import AddReview from './../views/AddReview'
import AddBalance from '@/views/AddBalance.vue';

const routes = [
  { path: '/', name: 'home', component: HomePage },
  { path: '/product/:id', name: 'product-detail', component: ProductDetail, props: true },
  { path: '/profile', name: 'profile', component: ProfilePage },
  { path: '/edit-profile', name: 'edit-profile', component: EditProfile },
  { path: '/add-product', name: 'add-product', component: addProductPage },
  { path: '/add-review', name: 'add-review', component: AddReview },
  { path: '/add-balance', component: AddBalance },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

export default router;
