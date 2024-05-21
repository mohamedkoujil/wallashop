<!-- src/components/DetallaProducto.vue -->
<template>
  <div>
    <h1>Detalle del Producto</h1>
    <div v-if="product">
      <h2>{{ product.productName }}</h2>
      <p>{{ product.description }}</p>
      <p>Precio: {{ product.price }}</p>
      <p>Ubicación: {{ product.location }}</p>
      <p>Propietario: {{ product.ownername }} - {{ product.owneremail }}</p>
    </div>
    <div v-else>
      <p>Cargando...</p>
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
      const response = await fetch(`http://54.174.66.62:8080/index.php?path=product&id=${this.id}`);
      const data = await response.json();
      if (data.status === 'Product not found') {
        this.product = null;
      } else {
        console.log('Product:', data);
        this.product = data;
      }
    }
  }
};
</script>
