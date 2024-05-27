<template>
  <AppHeader @search="handleSearch"></AppHeader>
  <div>
    <!--Listado de categorias-->
    <div class="categories">
      <h3>Categories</h3>
      <button v-if="this.categories" @click="filterCategory('all')">All</button>
      <button v-for="category in categories" :key="category.id" @click="filterCategory(category.category)">{{ category.category }}</button>
    </div>

    <section class="product-section">
      <div class="product-container">
        <router-link :to="'/product/' + product.id" v-for="product in products" :key="product.id">
          <product-card :product="product"></product-card>
        </router-link>
      </div>
      
    </section>
    <button id="add-product" @click="addProduct">Añadir producto</button>
  </div>
</template>

<script>
import ProductCard from '../components/productCard.vue';
import AppHeader from '../components/AppHeader.vue';

export default {
  components: {
    ProductCard,
    AppHeader
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
    window.addEventListener('scroll', this.handleScroll);
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await fetch('http://54.234.76.112:8080/index.php?path=products');
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
        const response = await fetch('http://54.234.76.112:8080/index.php?path=categories');
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
    },
    handleSearch(search) {
      console.log('Search:', search);
      this.products = this.allProducts.filter(product => product.productname.toLowerCase().includes(search.toLowerCase()));
    },
    addProduct() {
      this.$router.push('/add-product');
    },
  }
};
</script>

<style>
.categories {
  position: sticky;
  margin: 0.8em 0em 0 0em;
  top: 1em;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: white;
  list-style-type: none;
  padding: 1em;
  width: 10%;
  border-radius: 5px;
  float: left
}

.categories h3 {
  color: rgb(0, 0, 70);
}

.categories button {
  display: inline-block;
  width: 95%;
  margin: 10px 0 0 0;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  padding: 1em;
  border-radius: 5px;
  border: none;
  box-shadow: 0 0px 3px rgba(0, 0, 0, 0.3);
}

.categories button:hover {
  transform: translateX(10px);
  
}


.product-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.product-section {
  margin-top: -3em;
}

#add-product {
  position: sticky;
  left: 52%;
  bottom: 1em;
  margin: 2em auto;
  padding: 1em 2em;
  background-color: #0E2945;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  
}

#add-product:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

#add-product:active {
  transform: translateY(+0px);
}
</style>
