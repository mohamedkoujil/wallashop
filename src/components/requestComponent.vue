<template>
    <div class="request-card">

        <img :src="product ? product.images : 'https://via.placeholder.com/150'" alt="Product image">
        <h3>{{ product ? product.productname : 'Producto no encontrado' }} - {{ product ? product.price + '€' : 'Precio no disponible' }} </h3>
        <p>{{ owner ? owner.email : 'Usuario no encontrado' }}</p>

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
            user : JSON.parse(localStorage.getItem('user')) || null,
            owner: null
        }
    },
    methods: {
        acceptRequest() {
            console.log('Solicitud aceptada');
            fetch('http://44.218.60.222:8080/index.php?path=accept-purchase', {
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
            fetch('http://44.218.60.222:8080/index.php?path=reject-purchase&userid=' + this.request.buyerid + '&productid=' + this.request.productid, {
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
                const response = await fetch(`http://44.218.60.222:8080/index.php?path=product&id=` + this.request.productid);
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
        async fetchOwner() {
            try {
                const response = await fetch(`http://44.218.60.222:8080/index.php?path=user&id=` + this.request.ownerid);
                const data = await response.json();
                if (data.status === 'User not found') {
                    this.owner = null;
                } else {
                    this.owner = data;
                }
            } catch (error) {
                console.error('Error fetching owner:', error);
            }
            console.log('Owner:', this.owner);
        }
    },
    mounted() {
        this.fetchProduct();
        this.fetchOwner();
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