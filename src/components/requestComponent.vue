<template>
    <div class="request-card">

        <img :src="product ? product.images : 'https://via.placeholder.com/150'" alt="Product image">
        <h3>{{ product ? product.productname : 'Producto no encontrado' }} - {{ product ? product.price + '€' : 'Precio no disponible' }} </h3>

        <div v-if="sale" class="buttons">
            <button @click="acceptRequest">Aceptar</button>
            <button @click="rejectRequest">Rechazar</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'requestCard',
    props: {
        request: {
            type: Object,
            required: true
        },
        sale: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            product: null,
            user : JSON.parse(localStorage.getItem('user')) || null
        }
    },
    methods: {
        acceptRequest() {
            console.log('Solicitud aceptada');
            fetch('http://3.87.167.210:8080/index.php?path=accept-purchase', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    userid: this.request.buyerid,
                    ownerid: this.request.ownerid,
                    productid: this.request.productid,
                    price: this.product.price
                })
            }).then(response => response.json())
            .then(data => {
                if (data.status === 'Purchase successful') {
                    console.log('Purchase accepted:', data);
                    alert('Compra aceptada');
                    window.location.reload();
                } else {
                    console.error('Error accepting purchase:', data);
                }
            });
        },
        rejectRequest() {
            console.log('Solicitud rechazada');
            fetch('http://3.87.167.210:8080/index.php?path=reject-purchase&userid=' + this.request.buyerid + '&productid=' + this.request.productid, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Reject purchase response:', data);
                });
        },
        async fetchProduct() {
            try {
                const response = await fetch(`http://3.87.167.210:8080/index.php?path=product&id=` + this.request.productid);
                const data = await response.json();
                if (data.status === 'Product not found') {
                    this.product = null;
                } else {
                    this.product = data;
                }
            } catch (error) {
                console.error('Error fetching product:', error);
            }
            console.log('Product:', this.product);

        },
    },
    mounted() {
        this.fetchProduct();
    }
}
</script>

<style scoped>
.request-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 10px;
    padding: 10px;
    width: 15em;
}

.request-card img {
    border-radius: 5px;
    width: 100%;
    height: auto;
    margin: 0;
}

.buttons {
    display: flex;
    justify-content: space-around;
    width: 100%;
}
</style>