<template>
  <div class="product-card">
    <img :src="product.images" alt="Imagen del producto" class="product-image">
    <h3>{{ product.productname }}</h3>
    <p>{{ product.category }}</p>
    <p>Precio: {{ product.price }}€</p>
  </div>
</template>

<script>
export default {
  name: 'ProductCard',
  props: {
    product: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
.product-card {
  background-color: #FFF;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
  width: 100%; /* Asegura que el ancho sea el correcto */
  text-align: center;
  transition: transform 0.3s, box-shadow 0.3s;
}

.product-image {
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
</style>
