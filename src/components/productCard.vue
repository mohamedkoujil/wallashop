<template>
    <div class="product-card">
        <img class="product-img" :src="product.images" alt="Product image">
        <h2 class="product-name">{{ product.productname }}</h2>
        <p class="product-category">{{ product.category }}</p>
        <p class="product-price">Precio: {{ product.price }}€</p>
    </div>
</template>

<script>
export default {
    name: 'ProductCard',
    props: {
        product: Object
    }
}
</script>

<style scoped>
.product-card {
    border: 1px solid #0E2945; /* Azul oscuro */
    border-radius: 10px;
    padding: 20px;
    margin: 15px;
    width: 250px;
    display: inline-block;
    background-color: #FFF;
    color: #0E2945;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.product-name {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #0E2945;
}

.product-category {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #8292A4;
}

.product-description {
    font-size: 14px;
    margin-bottom: 10px;
    color: #657680;
}

.product-price {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #0E2945;
}

.product-location,
.product-owner {
    font-size: 12px;
    margin-bottom: 5px;
    color: #657680;
}

.product-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
}
</style>
