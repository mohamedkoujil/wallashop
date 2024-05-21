<template>
  <div>
    <h2>Listado de Productos</h2>
    <ul>
      <div v-for="product in products" :key="product.id">
        <router-link :to="'/product/' + product.id">
          <li>{{ product.productname }} - {{ product.price }}€</li>
        </router-link>
      </div>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: []
    };
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await fetch('http://54.174.66.62:8080/index.php?path=products');
        const data = await response.json();
        console.log('Products:', data)
        this.products = data;
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    }
  }
};
</script>
