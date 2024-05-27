<template>
  <main>
    <section id="productos" class="section">
      <div class="container">
        <h2>Gestiona tus productos</h2>
        <p>Aquí podrás subir productos y visualizar los que ya tienes.</p>
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
        <p>Aquí podrás ver tus productos comprados y seleccionar uno para valorar.</p>
        <div class="compras-lista">
          <router-link @click="selectProduct(purchase.id)" v-for="purchase in purchases" :key="purchase.id">
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

    <section id="addReview" class="section">
      <div class="container">
        <h2>Añadir Valoración</h2>
        <form @submit.prevent="submitReview" id="addReviewForm">
          <div class="form-group">
            <label for="username">Nombre:</label>
            <input type="text" id="username" name="username" v-model="username">
          </div>
          <div class="form-group">
            <label for="review">Valoración:</label>
            <textarea id="review" name="review" v-model="review"></textarea>
          </div>
          <div class="form-group">
            <label for="purchaseSelect">Selecciona el producto que deseas valorar:</label>
            <select id="purchaseSelect" name="purchaseSelect" v-model="selectedPurchase">
              <option disabled value="">Seleccione una opción</option>
              <option v-for="purchase in purchases" :key="purchase.id" :value="purchase.id">{{ purchase.productname }}</option>
            </select>
          </div>
          <button type="submit" class="btn">Añadir Valoración</button>
        </form>
      </div>
    </section>
  </main>
</template>

<script>
import ProductCard from '../components/productCard.vue';

export default {
  name: 'ProfilePage',
  components: {
    ProductCard,
  },
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')) || null,
      products: [],
      purchases: [],
      favorites: [],
      username: '',
      review: '',
      selectedPurchase: null,
    };
  },
  methods: {
    async fetchProfile() {
      try {
        // Tu código de fetch
      } catch (error) {
        console.error('Error fetching profile data:', error);
      }
    },
    async submitReview() {
      try {
        const response = await fetch('URL_DEL_SERVIDOR', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            userid: this.user.id,
            ownerid: this.selectedPurchase,
            rate: 5, // Aquí debes obtener la calificación del formulario, actualmente está fija en 5
            comment: this.review,
            transactionid: null, // No se proporciona en el formulario, puede requerir ajustes según tus necesidades
          }),
        });

        const data = await response.json();
        console.log('Valoración enviada:', data);
      } catch (error) {
        console.error('Error submitting review:', error);
      }
    },
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

/* Estilos para el formulario de valoración */
#addReview {
  background-color: #f4f4f4;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-top: 20px;
}

#addReview h2 {
  color: #333;
  margin-bottom: 20px;
}

#addReviewForm {
  max-width: 600px;
  margin: 0 auto;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 16px;
  margin-bottom: 5px;
  color: #333;
}

.form-group input[type="text"],
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
  font-size: 16px;
}

.form-group textarea {
  height: 100px;
}

button[type="submit"] {
  background-color: #0e2945;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  font-size: 16px;
}

button[type="submit"]:hover {
  background-color: #8292a4;
}
</style>
