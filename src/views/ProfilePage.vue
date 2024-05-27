<template>
  <section id="productos" class="section">
    <div class="container">
      <h2>Gestiona tus productos</h2>
      <p>Aquí podrás subir productos, y visualizar los que ya tienes.</p>
      <div class="productos-lista">
        <router-link :to="'/product/' + product.id" v-for="product in products" :key="product.id">
          <product-card :product="product"></product-card>
        </router-link>
      </div>
    </div>
  </section>

  <section id="compras" class="section">
    <div class="container">
      <h2>Gestiona tus Compras</h2>
      <p>Aquí podrás ver tus productos comprados y las compras en curso, finalizadas.</p>
      <div class="compras-lista">
        <router-link :to="'/product/' + purchase.id" v-for="purchase in purchases" :key="purchase.id">
          <product-card :product="purchase"></product-card>
        </router-link>
      </div>

    </div>
  </section>

  <section id="favoritos" class="section">
    <div class="container">
      <h2>Favoritos</h2>
      <div class="favorites-lista">
        <router-link :to="'/product/' + favorite.id" v-for="favorite in favorites" :key="favorite.id">
          <product-card :product="favorite"></product-card>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
import ProductCard from '../components/productCard.vue';

export default {
  components: {
    ProductCard,
  },
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')) || null,
      products: [],
      purchases: [],
      favorites: [],
    };
  },
  methods: {
    fetchProfile() {
      this.products = fetch('http://54.226.151.19:8080/index.php?path=products-for-sale&userid=' + this.user.id)
        .then(response => response.json())

      this.purchases = fetch('http://54.226.151.19:8080/index.php?path=purchase-history&userid='+ this.user.id)
        .then(response => response.json())


      this.favorites = fetch('http://54.226.151.19:8080/index.php?path=favorites&&userid='+ this.user.id)
        .then(response => response.json())

    },
  },
  mounted() {
    this.fetchProfile();
  },
};
</script>

<style scoped>
.profile-page {
  padding: 20px;
  max-width: 800px;
  margin: auto;
  background-color: #f4f4f4;
}
</style>