<template>
    <div class="add-review">
      <h1>Agregar Valoración</h1>
      <form @submit.prevent="submitReview">
        <label for="rating">Calificación:</label>
        <input type="number" id="rating" v-model="rating" min="1" max="5" step="0.01" required>
        <label for="comment">Comentario:</label>
        <textarea id="comment" v-model="comment" rows="4" required></textarea>
        <button type="submit">Enviar Valoración</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        rating: null,
        comment: ''
      };
    },
    methods: {
      async submitReview() {
        try {
          const userId = 1; // Aquí debes obtener el ID del usuario actual
          const ownerId = 2; // Aquí debes obtener el ID del propietario del producto
          const transactionId = 1; // Opcional: Puedes definir un ID de transacción si lo necesitas
  
          // Aquí realizarías la inserción en la base de datos
          const response = await fetch('URL_DEL_API', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              userId,
              ownerId,
              rate: this.rating,
              comment: this.comment,
              transactionId
            })
          });
  
          if (!response.ok) {
            throw new Error('Error al enviar la valoración');
          }
  
          // Lógica adicional como mostrar un mensaje de éxito o redirigir al usuario
        } catch (error) {
          console.error('Error al enviar la valoración:', error);
          // Manejar errores si es necesario
        }
      }
    }
  };
  </script>
  
  <style scoped>

  .add-review {
    padding: 20px;
    text-align: center;
  }
  
  h1 {
    font-weight: 700;
    color: #0E2945;
  }
  
  .add-review form {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .add-review label {
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  .add-review textarea,
  .add-review input[type="number"] {
    margin-bottom: 10px;
    width: 100%;
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