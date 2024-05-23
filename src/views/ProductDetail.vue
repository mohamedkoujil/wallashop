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
      product: null
    };
  },
  created() {
    this.fetchProduct();
  },
  methods: {
    async fetchProduct() {
      try {
        const response = await fetch(`http://54.175.247.76:8080/index.php?path=product&id=${this.id}`);
        const data = await response.json();
        if (data.status === 'Product not found') {
          this.product = null;
        } else {
          console.log('Product:', data);
          setTimeout(() => {
            this.product = data;
          }, 750);
        }
      } catch (error) {
        console.error('Error fetching product:', error);
      }
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap');

body {
  font-family: 'Merriweather', serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

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
</style>
