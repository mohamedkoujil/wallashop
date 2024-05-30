<template>
  <div>
    <AppHeader @search="handleSearch"></AppHeader>
    <div>
      <PopUpInfo ref="popup" @accept="handleAccept" @decline="handleDecline" />
      <!--Listado de categorias-->
      <div class="categories-container">
        <div class="categories">
          <h3>Categories</h3>
          <button v-if="this.categories" @click="filterCategory('all')">All</button>
          <button v-for="category in categories" :key="category.id" @click="filterCategory(category.category)">{{
            category.category }}</button>
        </div>


        <div class="price-filter">
            <h4>Ordenar por Precio</h4>
            <button class="sort-button" @click="sortProductsAscending">Ascendente</button>
            <button class="sort-button" @click="sortProductsDescending">Descendente</button>
          </div>

          <div class="price-range-filter">
            <h4>Filtrar por Precio</h4>
            <form @submit.prevent="filterByPrice" id="filter">
              <br><label for="min_price">Precio mínimo:</label>
              <input class="price-input" type="number" id="min_price" name="min_price"
                placeholder="Ingrese el precio mínimo">
              <br><label for="max_price">Precio máximo:</label>
              <input class="price-input" type="number" id="max_price" name="max_price"
                placeholder="Ingrese el precio máximo">
              <button class="filter-button" type="submit">Filtrar</button>
            </form>
          </div>
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

  </div>
  <AppFooter></AppFooter> 
</template>

<script>
import ProductCard from '../components/productCard.vue';
import AppHeader from '../components/AppHeader.vue';
import PopUpInfo from '../components/PopUpInfo.vue';
import AppFooter from '../components/AppFooter.vue';

export default {
  components: {
    ProductCard,
    AppHeader,
    PopUpInfo,
    AppFooter
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
        const response = await fetch('http://44.218.60.222:8080/index.php?path=products');
        const data = await response.json();
        console.log('Products:', data);
        this.allProducts = data;
        this.products = data;
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    },
    async fetchCategories() {
      try {
        const response = await fetch('http://44.218.60.222:8080/index.php?path=categories');
        const data = await response.json();
        console.log('Categories:', data);
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
    async filterByPrice() {
      const minPrice = parseFloat(document.getElementById('min_price').value);
      const maxPrice = parseFloat(document.getElementById('max_price').value);

      if (isNaN(minPrice) || isNaN(maxPrice)) {
        console.error('Por favor ingrese valores numéricos válidos para el precio mínimo y máximo.');
        return;
      }

      // Filtrar los productos por precio
      this.products = this.allProducts.filter(product => {
        const price = parseFloat(product.price);
        return price >= minPrice && price <= maxPrice;
      });

      // Log para verificar los productos filtrados
      console.log('Productos filtrados por precio:', this.products);
      document.getElementById("filter").reset();

    },

    sortProductsAscending() {
      this.products.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
      console.log('Productos ordenados por precio ascendente:', this.products);
    },

    sortProductsDescending() {
      this.products.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
      console.log('Productos ordenados por precio descendente:', this.products);
    },


    addProduct() {
      this.$router.push('/add-product');
    },
    handleSearch(search) {
      console.log('Search:', search);
      this.products = this.allProducts.filter(product => product.productname.toLowerCase().includes(search.toLowerCase()));
    },
    showPopUp() {
      this.$refs.popup.showModal();
    },

    handleAccept() {
      console.log('Accepted');

    },
    handleDecline() {
      console.log('Declined');

    },
  }
};

</script>

<style>
.categories-container {
  display: flex;
  flex-direction: column;
  float: left;
  width: 10%;
}

.categories {
  top: 1em;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: white;
  list-style-type: none;
  padding: 1em;
  margin-bottom: 1em;
  border-radius: 5px;

}

.categories .categories h3 {
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
  background-color: #0E2945;
  color: aliceblue;
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
  transform: translateX(10px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

#add-product:active {
  transform: translateY(+0px);
}

/* Estilos para los botones de ordenamiento y filtro */
.sort-button,
.filter-button {
  display: inline-block;
  width: 90%;
  margin: 10px 0 0 0;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  padding: 1em;
  border-radius: 5px;
  border: none;
  box-shadow: 0 0px 3px rgba(0, 0, 0, 0.3);
  background-color: #0E2945;
  color: aliceblue;
}

.sort-button:hover,
.filter-button:hover {
  transform: translateX(10px);
}

/* Estilos para los campos de entrada de precio */
.price-input {
  display: inline-block;
  width: 100px;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
  transition: border-color 0.3s ease;
}

.price-input:focus {
  outline: none;
  border-color: #0E2945;
}

/* Estilos para el contenedor de los botones de ordenamiento */
.price-filter {
  margin-bottom: 1em;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  align-items: center;
  background-color: white;
  padding: 1em;
  border-radius: 5px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1)
}

/* Estilos para el contenedor de los campos de entrada de precio */
.price-range-filter {
  margin-bottom: 1em;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  align-items: center;
  background-color: white;
  padding: 1em;
  border-radius: 5px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1)
}

.price-range-filter form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

</style>
