<template>
  <div>
    <!--Listado de categorias-->
    <div class="categories">
      <button @click="filterCategory('all')">All</button>
      <button v-for="category in categories" :key="category.id" @click="filterCategory(category.category)">{{ category.category }}</button>
    </div>
    <h2>Listado de Productos</h2>
    <section class="product-container">
      <router-link :to="'/product/' + product.id" v-for="product in products" :key="product.id">
        <product-card :product="product"></product-card>
      </router-link>
    </section>
  </div>
</template>

<script>
import ProductCard from '../components/productCard.vue';
export default {
  components: {
    ProductCard
  },
  data() {
    return {
      allProducts: [],
      products: [],
      categories: [],
      currentCategory: null
    };
  },
  mounted() {
    this.fetchProducts();
    this.fetchCategories();
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await fetch('http://34.234.97.167:8080/index.php?path=products');
        const data = await response.json();
        console.log('Products:', data)
        this.allProducts = data;
        this.products = data;
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    },
    async fetchCategories() {
      try {
        const response = await fetch('http://34.234.97.167:8080/index.php?path=categories');
        const data = await response.json();
        console.log('Categories:', data)
        this.categories = data;
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    filterCategory(category) {
      this.currentCategory = category;
      console.log('Current category:', this.currentCategory);
      if (category == 'all') {
        this.products = this.allProducts;
      } else {
        this.products = this.allProducts.filter(product => product.category === category);
      }
    }
  }
};
</script>

<style>
.categories {
  display: flex;
  margin: -9em 0 0 15.85em;
  list-style-type: none;
  padding: 0;
  padding: 2em;
  border-radius: 5px;
}

.categories button {
  display: inline-block;
  margin: 0 10px;
  cursor: pointer;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: #0E2945;
  color: white;
  transition: transform 0.2s, box-shadow 0.2s;
  padding: 1em;
  border-radius: 5px;
  border: none;
}

.categories button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.categories button:active {
  transform: translateY(+0px);
}

.product-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}
</style>
