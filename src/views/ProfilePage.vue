<template>
  <div v-if="user">
    <h1>{{ user.personname }}</h1>
    <img :src="user.profilepicture" alt="Profile Picture">
    <div v-if="products.length">
      <ProductCard v-for="product in products" :key="product.id" :product="product" />
    </div>
  </div>
  <div v-else>
    <p>Loading...</p>
  </div>
</template>

<script>
import ProductCard from '../components/productCard.vue';

export default {
  components: {
    ProductCard,
  },
  data() {
    return {
      user: null,
      products: [],
    };
  },
  created() {
    try {
      this.user = JSON.parse(localStorage.getItem('user'));
      console.log(this.user); // Depuración
      this.fetchProducts();
    } catch (e) {
      console.error("Error loading user data", e);
    }
  },
  methods: {
    fetchProducts() {
      if (!this.user) {
        console.error("User is not defined");
        return;
      }
      fetch(`http://54.90.65.129:8080/index.php?path=products-for-sale&userid=${this.user.id}`)
        .then(response => response.json())
        .then(data => {
          this.products = data;
        });
    },
  },
};
</script>

<style scoped>
/* Tus estilos aquí */
</style>
