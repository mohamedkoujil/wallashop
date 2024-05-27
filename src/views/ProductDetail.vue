<template>
  <div class="product-detail">
    <h1>Detalle del Producto</h1>
    <div class="product-content" v-if="product">
      <img :src="product.images" alt="Product image" class="product-image">
      <div class="product-info">
        <h2>{{ product.productName }}</h2>
        <p class="product-category"><strong>Categoría:</strong> {{ product.category }}</p>
        <p class="product-description"><strong>Descripción:</strong> {{ product.description }}</p>
        <p class="product-price"><strong>Precio:</strong> {{ product.price }} €</p>
        <p class="product-location"><strong>Ubicación:</strong> {{ product.location }}</p>
        <p class="product-owner"><strong>Propietario:</strong> {{ product.ownername }} - {{ product.owneremail }}</p>
        <div class="heart-container" title="Like">
          <input type="checkbox" class="checkbox" id="Give-It-An-Id" @change="toggleFavorite" :checked="isFavorite">
          <div class="svg-container">
            <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
              <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"></path>
            </svg>
            <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
              <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"></path>
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
export default {
  props: ['id'],
  data() {
    return {
      product: null,
      isFavorite: false,
    };
  },
  created() {
    this.fetchProduct();
    this.checkIfFavorite();
  },
  methods: {
    async fetchProduct() {
      try {
        const response = await fetch(`http://54.234.76.112:8080/index.php?path=product&id=${this.id}`);
        const data = await response.json();
        if (data.status === 'Product not found') {
          this.product = null;
        } else {
          this.product = data;
          this.checkIfFavorite();
        }
      } catch (error) {
        console.error('Error fetching product:', error);
      }
    },
    async checkIfFavorite() {
      const user = JSON.parse(localStorage.getItem('user'));
      if (user) {
        try {
          const response = await fetch(`http://54.226.151.19:8080/index.php?path=check-favorite&userid=${user.id}&productid=${this.id}`);
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          const data = await response.json();
          this.isFavorite = data.isFavorite;
        } catch (error) {
          console.error('Error checking favorite:', error);
        }
      }
    },
    async toggleFavorite(event) {
      const user = JSON.parse(localStorage.getItem('user'));
      if (!user) {
        alert('Tienes que iniciar sesión para poder hacer esto');
        event.target.checked = false; // Desmarcar el checkbox
        return;
      }

      try {
        const response = await fetch('http://54.226.151.19:8080/index.php?path=toggle-favorite', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            userid: user.id,
            productid: this.id
          })
        });
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await response.json();
        if (data.success) {
          this.isFavorite = !this.isFavorite;
        } else {
          alert('Error al añadir a favoritos');
          event.target.checked = !event.target.checked;  // Revertir el cambio si hay un error
        }
      } catch (error) {
        console.error('Error toggling favorite:', error);
        alert('Error al añadir a favoritos');
        event.target.checked = !event.target.checked;  // Revertir el cambio si hay un error
      }
    }
  }
};
</script>

<style scoped>
.product-detail {
  padding: 20px;
  text-align: center;
}

h1, h2 {
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

.product-category, .product-description, .product-price, .product-location, .product-owner {
  font-weight: 400;
  font-size: 16px;
}

.product-category strong, .product-description strong, .product-price strong, .product-location strong, .product-owner strong {
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
  0%, 80%, 100% {
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
  0%, 80%, 100% {
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
</style>
