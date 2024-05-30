<template>
  <div>
    <AppHeader />
    <div class="add-review">
      <h1>Agregar Valoración</h1>
      <form @submit.prevent="submitReview">
        <label for="rating">Puntuación</label>
        <input type="number" id="rating" v-model="rating" min="1" max="5" required>
        <label for="comment">Comentario</label>
        <textarea id="comment" v-model="comment" required></textarea>
        <button type="submit">Enviar</button>
      </form>
    </div>
  </div>
</template>

<script>
import AppHeader from '../components/AppHeader.vue';

export default {
  name: 'AddReview',
  props: {
    productid: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      rating: null,
      comment: '',
      product: null
    };
  },
  components: {
    AppHeader
  },
  methods: {
    async submitReview() {
      try {
        await fetch('http://44.218.60.222:8080/index.php?path=valoracion' , {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            userid: JSON.parse(localStorage.getItem('user')).id,
            rate: this.rating,
            comment: this.comment,
            ownerid: this.product.ownerid,
            productid: this.productid
          })
        })
        .then(response => response.json())
        .then(data => {
          console.log('Success:', data);
          this.$router.push({ name: 'product-detail', params: { id: this.productid } });
        });
        
      } catch (error) {
        console.error('Error submitting review:', error);
      }
    },
    async fetchProduct() {
      try {
        const response = await fetch(`http://44.218.60.222:8080/index.php?path=product&id=${this.productid}`);
        const data = await response.json();
        if (data.status === 'Product not found') {
          this.product = null;
        } else {
          this.product = data;
          console.log('Product:', data);
        }
      } catch (error) {
        console.error('Error fetching product:', error);
      }
    },
  },
  mounted() {
    console.log(this.productid);
    this.fetchProduct();
  }
}
</script>

<style scoped>
.add-review {
  padding: 20px;
  margin: 130px;
  text-align: center;
  margin-top: 200px;
  margin-bottom: 200px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f9f9f9;
}

h1 {
  font-weight: 700;
  color: #0E2945;
  margin-bottom: 20px;
}

.add-review form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.add-review label {
  font-weight: bold;
  margin-bottom: 5px;
}

.add-review textarea,
.add-review input[type="number"] {
  margin-bottom: 10px;
  width: 80%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.add-review button {
  padding: 10px 20px;
  background-color: #0E2945;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.add-review button:hover {
  background-color: #1a4473;
}
</style>
