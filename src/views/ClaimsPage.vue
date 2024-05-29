<template>
    <div class="reclamos">
      <h1>Reclamaciones</h1>
      <form @submit.prevent="submitForm">
        <div>
          <label for="email">Correo Electrónico:</label>
          <input type="email" id="email" v-model="formData.email" required>
        </div>
        <div>
          <label for="message">Reclamación:</label>
          <textarea id="message" v-model="formData.message" required></textarea>
        </div>
        <button type="submit">Enviar Reclamación</button>
      </form>
      <div v-if="status" :class="statusClass">{{ statusMessage }}</div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        formData: {
          email: '',
          message: ''
        },
        status: '',
        statusMessage: ''
      };
    },
    computed: {
      statusClass() {
        return {
          success: this.status === 'success',
          error: this.status === 'error'
        };
      }
    },
    methods: {
      async submitForm() {
        try {
          const response = await fetch('https://formspree.io/f/xrgnvwnq', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(this.formData)
          });
  
          if (response.ok) {
            this.status = 'success';
            this.statusMessage = 'Reclamación enviada con éxito.';
            this.formData = {
              email: '',
              message: ''
            };
          } else {
            this.status = 'error';
            this.statusMessage = 'Error al enviar la reclamación.';
          }
        } catch (error) {
          this.status = 'error';
          this.statusMessage = 'Error al enviar la reclamación.';
          console.error('Error:', error);
        }
      }
    }
  };
  </script>
  
  <style scoped>
.reclamos {
  max-width: 600px;
  margin: 0 auto;
  padding: 30px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.reclamos h1 {
  text-align: center;
  margin-bottom: 20px;
}

.reclamos form div {
  margin-bottom: 20px;
}

.reclamos form label {
  display: block;
  margin-bottom: 5px;
}

.reclamos form input,
.reclamos form textarea {
  width: calc(100% - 16px);
  padding: 8px;
  margin-top: 5px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

.reclamos form button {
  display: block;
  width: 100%;
  padding: 10px;
  background-color: #0E2945;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.reclamos form button:hover {
  background-color: #8292A4;
}

.success,
.error {
  text-align: center;
  margin-top: 20px;
}

.success {
  color: green;
}

.error {
  color: red;
}
</style>
