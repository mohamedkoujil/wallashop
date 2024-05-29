<template>
  <div>
    <AppHeader @search="handleSearch"></AppHeader>
    <div class="container">
      <div class="section-buttons">
        <button @click="toggleSection('productos')" :class="{ active: currentSection === 'productos' }">Productos</button>
        <button @click="toggleSection('ventas')" :class="{ active: currentSection === 'ventas' }">Ventas</button>
        <button @click="toggleSection('compras')" :class="{ active: currentSection === 'compras' }">Compras</button>
        <button @click="toggleSection('favoritos')" :class="{ active: currentSection === 'favoritos' }">Favoritos</button>
        <button @click="toggleSection('purchaseRequests')" :class="{ active: currentSection === 'purchaseRequests' }">Solicitudes de Compra</button>
      </div>
      <section v-show="currentSection === 'productos'" id="productos" class="section">
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
      <section v-show="currentSection === 'ventas'" id="ventas" class="section">
        <div class="container">
          <h2>Gestiona tus Ventas</h2>
          <p>Aquí podrás ver tus productos vendidos.</p>
          <div class="productos-lista">
            <router-link :to="'/product/' + sale.id" v-for="sale in soldProducts" :key="sale.id">
              <product-card :product="sale"></product-card>
            </router-link>
          </div>
        </div>
      </section>
      <section v-show="currentSection === 'compras'" id="compras" class="section">
        <div class="container">
          <h2>Gestiona tus Compras</h2>
          <p>Aquí podrás ver tus productos comprados.</p>
          <div class="compras-lista">
            <router-link :to="'/product/' + purchase.id" v-for="purchase in purchases" :key="purchase.id">
              <product-card :product="purchase"></product-card>
            </router-link>
          </div>
        </div>
      </section>
      <section v-show="currentSection === 'favoritos'" id="favoritos" class="section">
        <div class="container">
          <h2>Favoritos</h2>
          <p>Aquí podrás ver tus productos favoritos.</p>
          <div class="favorites-lista">
            <router-link :to="'/product/' + favorite.id" v-for="favorite in favorites" :key="favorite.id">
              <product-card :product="favorite"></product-card>
            </router-link>
          </div>
        </div>
      </section>
      <section v-show="currentSection === 'purchaseRequests'" id="purchaseRequests" class="section">
        <div class="container">
          <h2>Solicitudes de Compra</h2>
          <p>Aquí podrás ver las solicitudes de compra de tus productos o tus solicitudes de compra.</p>
          <div class="purchase-requests-list">
            <router-link :to="'/product/' + purchaseRequest.id" v-for="purchaseRequest in purchaseRequests" :key="purchaseRequest.id">
              <product-card :product="purchaseRequest"></product-card>
            </router-link>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import ProductCard from '../components/productCard.vue';
import AppHeader from '../components/AppHeader.vue';

export default {
  name: 'ProfilePage',
  components: {
    ProductCard,
    AppHeader,
  },
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')) || null,
      currentSection: 'productos', // Establecer la sección de productos como predeterminada
      products: [],
      soldProducts: [],
      purchases: [],
      favorites: [],
      purchaseRequests: [],
    };
  },
  methods: {
    toggleSection(section) {
      this.currentSection = section;
    },
    async fetchProducts() {
      console.log(this.user.id  )
      fetch('http://54.227.162.233:8080/index.php?path=products-for-sale&userid='+this.user.id)
        .then(response => response.json())
        .then(data => {
          this.products = data;
          console.log('Products:', data);
        })
        
    },
    async fetchSales() {
      fetch ('http://54.227.162.233:8080/index.php?path=sales-history&userid='+this.user.id)
        .then(response => response.json())
        .then(data => {
          this.soldProducts = data;
          console.log('Sales:', data);
        });
    },
    async fetchPurchases() {
      fetch('http://54.227.162.233:8080/index.php?path=purchase-history&userid='+this.user.id)
        .then(response => response.json())
        .then(data => {
          this.purchases = data;
          console.log('Purchases:', data);
        });
    },
    async fetchFavorites() {
      fetch('http://54.227.162.233:8080/index.php?path=get-favorites&userid=' + this.user.id)
        .then(response => response.json())
        .then(data => {
          this.favorites = data;
          console.log('Favorites:', data);
        });
    },
    async fetchPurchaseRequests() {
      // Código para obtener las solicitudes de compra
    },
    deleteProduct(id) {
      fetch('http://54.227.162.233:8080/index.php?path=product&id='+id, {
        method: 'DELETE',
      })
        .then(response => response.json())
        .then(data => {
          console.log('Product deleted:', data);
          this.fetchProducts();
        });
    },
  },
  mounted() {
    this.fetchProducts();
    this.fetchSales();
    this.fetchPurchases();
    this.fetchFavorites();
  },
};
</script>

<style scoped>
.section-buttons {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

.section-buttons button {
  background-color: #0E2945;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin: 0 10px;
}

.section-buttons button:hover {
  background-color: #8292A4;
}

.section-buttons button.active {
  background-color: #5566c2;
}
</style>
