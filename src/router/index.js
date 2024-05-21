import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../views/HomePage.vue';
import ProductDetail from '../views/ProductDetail.vue';
import ProfilePage from '../views/ProfilePage.vue';
import EditProfile from '../views/EditProfile.vue';

const routes = [
  { path: '/', name: 'home', component: HomePage },
  { path: '/product/:id', name: 'product-detail', component: ProductDetail, props: true },
  { path: '/profile', name: 'profile', component: ProfilePage },
  { path: '/edit-profile', name: 'edit-profile', component: EditProfile }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

export default router;
