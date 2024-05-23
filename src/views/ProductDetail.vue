<!-- src/components/DetallaProducto.vue -->
<template>
  <div class="product-detail">
    <h1>Detalle del Producto</h1>
    <div class="product-content" v-if="product">
      <img :src="product.images" alt="Product image" class="product-image">
      <div class="product-info">
        <h2>{{ product.productName }}</h2>
        <p class="product-category">{{ product.category }}</p>
        <p class="product-description">{{ product.description }}</p>
        <p class="product-price">Precio: {{ product.price }}</p>
        <p class="product-location">Ubicación: {{ product.location }}</p>
        <p class="product-owner">Propietario: {{ product.ownername }} - {{ product.owneremail }}</p>
      </div>
    </div>
    <div id="loading" v-else>
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
    }
  }
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap');

body {
  font-family: 'Merriweather', serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.product-detail {
  padding: 20px;
}

h1, h2 {
  font-weight: 700;
}

.product-content {
  display: flex;
  align-items: flex-start;
  gap: 20px;
}

.product-image {
  width: 30%;
  border-radius: 8px;
}

.product-info {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.product-category, .product-description, .product-price, .product-location, .product-owner {
  font-weight: 400;
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
