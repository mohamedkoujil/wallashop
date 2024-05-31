<template>
  <div>
    <AppHeader @search="handleSearch"></AppHeader>
    <div class="container">
      <div class="section-buttons">
        <button @click="toggleSection('productos')"
          :class="{ active: currentSection === 'productos' }">Productos</button>
        <button @click="toggleSection('ventas')" :class="{ active: currentSection === 'ventas' }">Ventas</button>
        <button @click="toggleSection('compras')" :class="{ active: currentSection === 'compras' }">Compras</button>
        <button @click="toggleSection('favoritos')"
          :class="{ active: currentSection === 'favoritos' }">Favoritos</button>
        <button @click="toggleSection('purchaseRequests')"
          :class="{ active: currentSection === 'purchaseRequests' }">Solicitudes</button>
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
            <div class="productCard" v-for="sale in soldProducts" :key="sale.id">
              <router-link :to="'/product/' + sale.id">
                <product-card :product="sale"></product-card>
              </router-link>
              
            </div>
          </div>
        </div>
      </section>
      <section v-show="currentSection === 'compras'" id="compras" class="section">
        <div class="container">
          <h2>Gestiona tus Compras</h2>
          <p>Aquí podrás ver tus productos comprados.</p>
          <div class="productos-lista">
            <div class="productCard" v-for="purchase in purchases" :key="purchase.id">
              <router-link :to="'/product/' + purchase.id">
                <product-card :product="purchase"></product-card>
              </router-link>
              <button @click="addValoration(purchase)">Añadir valoración</button>
            </div>
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
          <h2>Solicitudes</h2>
          <p>Aquí podrás ver las solicitudes de compra de tus productos o tus solicitudes de compra.</p>
          <h3>Tus solicitudes de compra</h3>
          <div class="purchase-requests-list">
            <requestCard v-for="purchaseRequest in purchaseRequests" :key="purchaseRequest.id"
              :request="purchaseRequest"></requestCard>
          </div>

          <h3>Solicitudes de venta</h3>
          <div class="purchase-requests-list">
            <requestCard v-for="saleRequest in saleRequests " :key="saleRequest.id" :request="saleRequest" :sale="true">
            </requestCard>
          </div>
        </div>
      </section>
    </div>
  </div>
  <AppFooter></AppFooter>
</template>

<script>
import ProductCard from '../components/productCard.vue';
import AppHeader from '../components/AppHeader.vue';
import requestCard from '../components/requestComponent.vue';
import AppFooter from '../components/AppFooter.vue';

export default {
  name: 'ProfilePage',
  components: {
    ProductCard,
    AppHeader,
    requestCard,
    AppFooter,
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
      saleRequests: [],
    };
  },
  methods: {
    toggleSection(section) {
      this.currentSection = section;
    },
    async fetchData(url, callback) {
      try {
        const response = await fetch(url);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        callback(data);
      } catch (error) {
        console.error('Fetch error:', error);
      }
    },
    fetchProducts() {
      this.fetchData(`http://44.218.60.222:8080/index.php?path=products-for-sale&userid=${this.user.id}`, data => {
        this.products = data;
        console.log('Products:', data);
      });
    },
    fetchSales() {
      this.fetchData(`http://44.218.60.222:8080/index.php?path=sales-history&userid=${this.user.id}`, data => {
        this.soldProducts = data;
        console.log('Sales:', data);
      });
    },
    fetchPurchases() {
      this.fetchData(`http://44.218.60.222:8080/index.php?path=purchase-history&userid=${this.user.id}`, data => {
        this.purchases = data;
        console.log('Purchases:', data);
      });
    },
    fetchFavorites() {
      this.fetchData(`http://44.218.60.222:8080/index.php?path=get-favorites&userid=${this.user.id}`, data => {
        this.favorites = data;
        console.log('Favorites:', data);
      });
    },
    fetchPurchaseRequests() {
      this.fetchData(`http://44.218.60.222:8080/index.php?path=get-purchase-requests&userid=${this.user.id}`, data => {
        this.purchaseRequests = data;
        console.log('Purchase Requests:', data);
      });
    },
    fetchSaleRequests() {
      this.fetchData(`http://44.218.60.222:8080/index.php?path=get-sale-requests&userid=${this.user.id}`, data => {
        this.saleRequests = data;
        console.log('Sale Requests:', data);
      });
    },
    deleteProduct(id) {
      fetch(`http://44.218.60.222:8080/index.php?path=product&id=${id}`, {
        method: 'DELETE',
      })
        .then(response => response.json())
        .then(data => {
          console.log('Product deleted:', data);
          this.fetchProducts();
        });
    },
    //redirect to the add valoration page with the parameter of the product
    addValoration(product) {
      this.$router.push({ name: 'add-review', params: { productid: product.id } });
    }

  },
  mounted() {
    this.fetchProducts();
    this.fetchSales();
    this.fetchPurchases();
    this.fetchFavorites();
    this.fetchPurchaseRequests();
    this.fetchSaleRequests();
  },
};
</script>

<style scoped>
.productos-lista {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.productCard {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.productCard button {
  background-color: #0E2945;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: -1em;
  width: 90%;
}

.productCard button:hover {
  background-color: #8292A4;
}


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

.purchase-requests-list {
  display: flex;
  flex-wrap: wrap;
}
</style>
