<template>
    <div class="product-card">
        <h2>{{ product.productName }}</h2>
        <p>{{ product.category }}</p>
        <p>{{ product.description }}</p>
        <p>Precio: {{ product.price }}</p>
        <p>Ubicación: {{ product.location }}</p>
        <p>Propietario: {{ product.ownername }} - {{ product.owneremail }}</p>
    </div>
    
</template>

<script>
export default {
    name: 'product-card',
    methods: {
    },
    props: {
        product: Object
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.product-card {
    border: 1px solid navy;
    border-radius: 5px;
    padding: 10px;
    margin: 10px;
    width: 200px;
    display: inline-block;
    background-color: white;
    color: black;
    cursor: pointer;
}
.product-card:hover {
    background-color: navy;
    color: white;
    border-color: black;
}
</style>

