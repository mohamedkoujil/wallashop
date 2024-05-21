<template>
  <div>
    <!--Listado de categorias-->
    <ul>
      <li v-for="category in categories" :key="category.id">
        <div @click="filterCategory(category.category)">{{ category.category }}</div>
      </li>
    </ul>
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
        const response = await fetch('http://54.174.66.62:8080/index.php?path=products');
        const data = await response.json();
        console.log('Products:', data)
        this.products = data;
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    },
    async fetchCategories() {
      try {
        const response = await fetch('http://54.174.66.62:8080/index.php?path=categories');
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
      this.products = this.products.filter(product => product.category == this.currentCategory);
    }
  }
};
</script>
