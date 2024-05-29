<template>
  <div>
    <AppHeader/>
  
  <div class="add-review">
    <h1>Agregar Valoración</h1>
    <form @submit.prevent="submitReview">
      <label for="rating">Calificación:</label>
      <input type="number" id="rating" v-model="rating" min="1" max="5" step="0.01" required>
      <label for="comment">Nombre del producto:</label>
      <textarea id="comment" v-model="comment" rows="1" required></textarea>
      <label for="comment">Comentario:</label>
      <textarea id="comment" v-model="comment" rows="4" required></textarea>
      <button type="submit">Enviar Valoración</button>
    </form>
  </div>
</div>
</template>

<script>
import AppHeader from '../components/AppHeader.vue';

export default {
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      rating: null,
      comment: ''
    };
  },
  components: {
    AppHeader,
  },
  methods: {
    async submitReview() {
      try {
        const userId = 1; 
        const ownerId = 2; 
        const transactionId = 1; 

       
        const response = await fetch('http://18.212.255.200:8080/index.php?path=categories', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            userId,
            ownerId,
            rate: this.rating,
            comment: this.comment,
            transactionId,
            productId: this.id
          })
        });

        if (!response.ok) {
          throw new Error('Error al enviar la valoración');
        }

        
      } catch (error) {
        console.error('Error al enviar la valoración:', error);
        
      }
    }
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