<template>
  <AppHeader @search="handleSearch"></AppHeader>
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
      products: [],
      purchases: [],
      favorites: [],
    };
  },
  methods: {
    async fetchProfile() {
      try {
        const [productsResponse, purchasesResponse, favoritesResponse] = await Promise.all([
          fetch(`http://54.167.0.31:8080/index.php?path=products-for-sale&userid=${this.user.id}`),
          fetch(`http://54.167.0.31:8080/index.php?path=purchase-history&userid=${this.user.id}`),
          fetch(`http://54.167.0.31:8080/index.php?path=favorites&userid=${this.user.id}`)
        ]);

        // Verificar respuestas y convertir a JSON
        if (!productsResponse.ok) {
          throw new Error('Failed to fetch products');
        }
        if (!purchasesResponse.ok) {
          throw new Error('Failed to fetch purchases');
        }
        if (!favoritesResponse.ok) {
          throw new Error('Failed to fetch favorites');
        }

        // Obtener textos de las respuestas
        const productsText = await productsResponse.text();
        const purchasesText = await purchasesResponse.text();
        const favoritesText = await favoritesResponse.text();

        // Log para depuración antes de la conversión a JSON
        console.log('Products response:', productsText);
        console.log('Purchases response:', purchasesText);
        console.log('Favorites response:', favoritesText);

        // Parsear respuestas si no están vacías
        this.products = productsText ? JSON.parse(productsText) : [];
        this.purchases = purchasesText ? JSON.parse(purchasesText) : [];
        this.favorites = favoritesText ? JSON.parse(favoritesText) : [];

        // Log para depuración después de la conversión a JSON
        console.log('Products:', this.products);
        console.log('Purchases:', this.purchases);
        console.log('Favorites:', this.favorites);
      } catch (error) {
        console.error('Error fetching profile data:', error);
      }
    },
    navigateToAddReview() {
      this.$router.push({ name: 'add-review' });
    }
  },
  mounted() {
    this.fetchProfile();
  },
};
</script>

<style scoped>
.section {
  margin-bottom: 40px;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.productos-lista, .compras-lista, .favorites-lista {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.product-card {
  background-color: #FFF;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
  width: 250px; /* Ajusta el ancho según sea necesario */
  text-align: center;
  transition: transform 0.3s, box-shadow 0.3s;
}

.product-card img {
  max-width: 100%;
  height: auto;
  border-radius: 4px;
  margin-bottom: 10px;
}

.product-card h3 {
  margin-top: 10px;
  color: #333;
}

.product-card p {
  color: #777;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

#add-product {
  
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
  