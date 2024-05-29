<template>
  <AppHeader @search="handleSearch"></AppHeader>
  <div class="product-detail">
    <h1>Detalle del Producto</h1>
    <div class="product-content" v-if="product">
      <img :src="product.images" alt="Product image" class="product-image">
      <div class="product-info">
        <h2>{{ product.productname }}</h2>
        <p class="product-category"><strong>Categoría:</strong> {{ product.category }}</p>
        <p class="product-description"><strong>Descripción:</strong> {{ product.description }}</p>
        <p class="product-price"><strong>Precio:</strong> {{ product.price }} €</p>
        <p class="product-location"><strong>Ubicación:</strong> {{ product.location }}</p>
        <p class="product-owner"><strong>Propietario:</strong> {{ product.ownername }} - {{ product.owneremail }}</p>
        <button class="contactButton" @click="buyProduct">
          Comprar
          <div class="iconButton">
            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M0 0h24v24H0z" fill="none"></path>
              <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                fill="currentColor"></path>
            </svg>
          </div>
        </button>
        <div class="buyProcess" v-if="buyProcess">
          <p v-if="user.id == product.ownerid">No puedes comprar tu propio producto</p>
          <div v-else>
            <p>¿Estás seguro de que deseas comprar este producto?</p>
            <button @click="buyProduct">Sí</button>
            <button @click="buyProcess = false">No</button>
          </div>
        </div>
        <div class="heart-container" title="Like" @click="toggleFavorite">
          <input type="checkbox" class="checkbox" id="Give-It-An-Id" v-model="isFavorite">
          <div class="svg-container">
            <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z">
              </path>
            </svg>
            <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z">
              </path>
            </svg>
            <svg class="svg-celebrate" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
              <polygon points="10,10 20,20"></polygon>
              <polygon points="10,50 20,50"></polygon>
              <polygon points="20,80 30,70"></polygon>
              <polygon points="90,10 80,20"></polygon>
              <polygon points="90,50 80,50"></polygon>
              <polygon points="80,80 70,70"></polygon>
            </svg>
          </div>
        </div>
      </div>
    </div>
    <div v-else id="loading">
      <div class="loader">
        <div class="justify-content-center jimu-primary-loading"></div>
      </div>
    </div>
  </div>
</template>

<script>
import AppHeader from '../components/AppHeader.vue';

export default {
  props: ['id'],
  data() {
    return {
      product: null,
      isFavorite: false,
      searchTerm: '',
      buyProcess: false,
      user: JSON.parse(localStorage.getItem('user')) || null
    };
  },
  created() {
    this.fetchProduct();
    this.checkIfFavorite();
  },
  components: {
    AppHeader,
  },
  methods: {
    async fetchProduct() {
      try {
        const response = await fetch(`http://18.212.255.200:8080/index.php?path=product&id=${this.id}`);
        const data = await response.json();
        if (data.status === 'Product not found') {
          this.product = null;
        } else {
          this.product = data;
        }
      } catch (error) {
        console.error('Error fetching product:', error);
      }
    },
    async checkIfFavorite() {
      try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          this.isFavorite = false;
          return;
        }
        const response = await fetch(`http://18.212.255.200:8080/index.php?path=get-favorites&userid=${user.id}`);
        const data = await response.json();
        this.isFavorite = data.some(favorite => favorite.id === this.id);
      } catch (error) {
        console.error('Error checking favorite:', error);
      }
    },
    async toggleFavorite() {
      try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          alert('You need to log in to add favorites');
          return;
        }
        const method = this.isFavorite ? 'DELETE' : 'POST';
        const url = `http://18.212.255.200:8080/index.php?path=add-favorites${method === 'DELETE' ? `&userid=${user.id}&productid=${this.id}` : ''}`;

        console.log('URL:', url); // Log de URL para depuración
        console.log('Method:', method); // Log de método para depuración

        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json'
          },
          body: method === 'POST' ? JSON.stringify({ userid: user.id, productid: this.id }) : null
        });

        // Para depuración: verificar la respuesta como texto
        const text = await response.text();
        console.log('Response text:', text); // Log para depuración

        try {
          const data = JSON.parse(text);
          if (data.status === 'Favorite added') {
            this.isFavorite = true;
          } else if (data.status === 'Favorite deleted') {
            this.isFavorite = false;
          } else {
            console.error('Error toggling favorite:', data.status);
          }
        } catch (e) {
          console.error('Error parsing JSON:', e);
          console.error('Response text:', text);
        }
      } catch (error) {
        console.error('Error toggling favorite:', error);
      }
    },
    async buyProduct() {
      if (!this.buyProcess) {
        this.buyProcess = true;
      } else {
        console.log('Buying product:', this.product);
        try {
          const user = JSON.parse(localStorage.getItem('user'));
          if (!user) {
            alert('You need to log in to buy products');
            return;
          }

          const response = await fetch('http://18.212.255.200:8080/index.php?path=purchase', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(
              { 
              userid: user.id, 
              ownerid: this.product.ownerid,
              productid: this.product.id, 
            })
          });

          const data = await response.json();
          if (data.status === 'Purchase request created') {
            alert('Purchase request created');
            this.$router.push('/');
          } else {
            alert('Error during purchase');
          }
        } catch (error) {
          console.error('Error buying product:', error);
        }
      }

    },
    handleSearch(term) {
      this.searchTerm = term;
    }
  }
};
</script>

<style scoped>
.buyProcess {
  position: absolute;
  top: 150%;
  left: 40%;
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
  background-color: #0E2945;
  color: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  z-index: 100;
  transition: top 0.3s;
}

.buyProcess button {
  background-color: #fff;
  color: #0E2945;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 700;
  transition: 0.3s;
  margin: 1em;
}

.buyProcess button:hover {
  transform: translateY(-2px);
}

.buyProcess button:active {
  transform: translateY(2px);
}

.product-detail {
  padding: 20px;
  text-align: center;
}

h1,
h2 {
  font-weight: 700;
  color: #0E2945;
}

.product-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  max-width: 800px;
  margin: 20px auto;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.product-image {
  width: 100%;
  max-width: 400px;
  border-radius: 10px;
  border: 2px solid #0E2945;
}

.product-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
  gap: 10px;
}

.product-category,
.product-description,
.product-price,
.product-location,
.product-owner {
  font-weight: 400;
  font-size: 16px;
}

.product-category strong,
.product-description strong,
.product-price strong,
.product-location strong,
.product-owner strong {
  color: #0E2945;
}

#loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.loader {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}

.jimu-primary-loading:before,
.jimu-primary-loading:after {
  position: absolute;
  top: 0;
  content: '';
}

.jimu-primary-loading:before {
  left: -19.992px;
}

.jimu-primary-loading:after {
  left: 19.992px;
  -webkit-animation-delay: 0.32s !important;
  animation-delay: 0.32s !important;
}

.jimu-primary-loading:before,
.jimu-primary-loading:after,
.jimu-primary-loading {
  background: #076fe5;
  -webkit-animation: loading-keys-app-loading 0.8s infinite ease-in-out;
  animation: loading-keys-app-loading 0.8s infinite ease-in-out;
  width: 13.6px;
  height: 32px;
}

.jimu-primary-loading {
  text-indent: -9999em;
  margin: auto;
  position: absolute;
  right: calc(50% - 6.8px);
  top: calc(50% - 16px);
  -webkit-animation-delay: 0.16s !important;
  animation-delay: 0.16s !important;
}

@-webkit-keyframes loading-keys-app-loading {

  0%,
  80%,
  100% {
    opacity: .75;
    box-shadow: 0 0 #076fe5;
    height: 32px;
  }

  40% {
    opacity: 1;
    box-shadow: 0 -8px #076fe5;
    height: 40px;
  }
}

@keyframes loading-keys-app-loading {

  0%,
  80%,
  100% {
    opacity: .75;
    box-shadow: 0 0 #076fe5;
    height: 32px;
  }

  40% {
    opacity: 1;
    box-shadow: 0 -8px #076fe5;
    height: 40px;
  }
}

.heart-container {
  --heart-color: rgb(240, 8, 0);
  position: relative;
  left: 95%;
  width: 30px;
  height: 30px;
  transition: .3s;
}

.heart-container .checkbox {
  position: absolute;
  left: -5px;
  top: -5px;
  width: 110%;
  height: 110%;
  opacity: 0;
  z-index: 20;
  cursor: pointer;
  padding: 10em;
}

.heart-container .svg-container {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.heart-container .svg-outline,
.heart-container .svg-filled {
  fill: var(--heart-color);
  position: absolute;
}

.heart-container .svg-filled {
  animation: keyframes-svg-filled 1s;
  display: none;
}

.heart-container .svg-celebrate {
  position: absolute;
  animation: keyframes-svg-celebrate .5s;
  animation-fill-mode: forwards;
  display: none;
  stroke: var(--heart-color);
  fill: var(--heart-color);
  stroke-width: 2px;
}

.heart-container .checkbox:checked~.svg-container .svg-filled {
  display: block;
}

.heart-container .checkbox:checked~.svg-container .svg-celebrate {
  display: block;
}

@keyframes keyframes-svg-filled {
  0% {
    transform: scale(0);
  }

  25% {
    transform: scale(1.2);
  }

  50% {
    transform: scale(1);
    filter: brightness(1.5);
  }
}

@keyframes keyframes-svg-celebrate {
  0% {
    transform: scale(0);
  }

  50% {
    opacity: 1;
    filter: brightness(1.5);
  }

  100% {
    transform: scale(1.4);
    opacity: 0;
    display: none;
  }
}

.contactButton {
  background: #0E2945;
  color: white;
  font-family: inherit;
  padding: 0.45em;
  padding-left: 1em;
  font-size: 17px;
  font-weight: 500;
  border-radius: 0.9em;
  border: none;
  cursor: pointer;
  letter-spacing: 0.05em;
  display: flex;
  align-items: center;
  box-shadow: inset 0 0 1.6em -0.6em #0E2945;
  overflow: hidden;
  position: relative;
  height: 2.8em;
  padding-right: 3em;
}

.iconButton {
  margin-left: 1em;
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 2.2em;
  width: 2.2em;
  border-radius: 0.7em;
  box-shadow: 0.1em 0.1em 0.6em 0.2em #7a8cf3;
  right: 0.3em;
  transition: all 0.3s;
}

.contactButton:hover {
  transform: translate(-0.05em, -0.05em);
  box-shadow: 0.15em 0.15em #5566c2;
}

.contactButton:active {
  transform: translate(0.05em, 0.05em);
  box-shadow: 0.05em 0.05em #5566c2;
}
</style>
