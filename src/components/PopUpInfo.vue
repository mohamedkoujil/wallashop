<template>
    <div v-if="isVisible" class="modal">
        <article class="modal-container">
            <header class="modal-container-header">
                <span class="modal-container-title">
                    <svg aria-hidden="true" height="24" width="24" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path
                            d="M14 9V4H5v16h6.056c.328.417.724.785 1.18 1.085l1.39.915H3.993A.993.993 0 0 1 3 21.008V2.992C3 2.455 3.449 2 4.002 2h10.995L21 8v1h-7zm-2 2h9v5.949c0 .99-.501 1.916-1.336 2.465L16.5 21.498l-3.164-2.084A2.953 2.953 0 0 1 12 16.95V11zm2 5.949c0 .316.162.614.436.795l2.064 1.36 2.064-1.36a.954.954 0 0 0 .436-.795V13h-5v3.949z"
                            fill="currentColor"></path>
                    </svg>
                    Términos y Servicios
                </span>
                <button class="icon-button" @click="closeModal">
                    <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path
                            d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"
                            fill="currentColor"></path>
                    </svg>
                </button>
            </header>
            <section class="modal-container-body rtf">

                <h2> Quiénes Somos</h2>
                <p>
                    WallaShop se centra en mejorar la experiencia de compra y venta de los miembros de la comunidad
                    local.
                    Con esta iniciativa, nuestro objetivo es optimizar el proceso de intercambio de productos y
                    servicios
                    entre los usuarios de la plataforma, proporcionando una solución más eficiente y segura. Creemos en
                    la
                    importancia de crear un entorno donde la compra y venta sean prácticas fluidas y gratificantes, y
                    trabajamos arduamente para hacer de WallaShop el destino preferido para la comunidad local en busca
                    de
                    una experiencia de comercio electrónico excepcional.
                </p>

                <p>
                    Nos dedicamos a impulsar la innovación y la mejora continua en el ámbito del comercio electrónico.
                    Nuestra
                    pasión radica en crear soluciones tecnológicas que no solo simplifiquen, sino que también
                    enriquezcan
                    las experiencias de compra y venta en línea. Con un enfoque centrado en el usuario y un compromiso
                    con
                    la excelencia, trabajamos incansablemente para desarrollar plataformas como WallaShop, que no solo
                    ofrecen funcionalidades avanzadas, sino que también promueven la sostenibilidad, la eficiencia y la
                    seguridad en cada interacción.
                </p>

                <p>
                    En nuestro enfoque, no solo nos esforzamos por desarrollar una plataforma eficiente y escalable para
                    la
                    gestión de ventas online, sino que también nos comprometemos fervientemente a promover la
                    reutilización
                    de productos como un valor central de nuestra misión. Creemos firmemente en la importancia de crear
                    un
                    entorno donde la reutilización sea no solo una opción conveniente, sino también una práctica
                    incentivada
                    y celebrada. A través de nuestro proyecto, buscamos no solo facilitar el intercambio de bienes, sino
                    también fomentar una cultura de consumo consciente y sostenible, donde cada producto tenga la
                    oportunidad de encontrar un nuevo hogar y extender su ciclo de vida útil.
                </p>

                <h2>Términos de WallaShop</h2>

                <p>
                    Al utilizar WallaShop, aceptas los siguientes términos y condiciones. El incumplimiento de estos
                    términos
                    puede resultar en la suspensión o terminación de tu cuenta.
                </p>

                <p>
                    1. Responsabilidad del usuario: Eres responsable de mantener la confidencialidad de tu cuenta y
                    contraseña, así como de todas las actividades que ocurran bajo tu cuenta.
                    No nos hacemos responsables de ninguna pérdida o daño ocasionado por el uso de esta app.
                </p>

                <p>
                    2. Contenido del usuario: Al publicar contenido en WallaShop, otorgas a WallaShop una licencia
                    mundial,
                    no exclusiva, transferible, sublicenciable y libre de regalías para usar, reproducir, modificar,
                    adaptar,
                    publicar, distribuir y mostrar dicho contenido.
                </p>

                <p>
                    3. Conducta del usuario: No puedes utilizar WallaShop de ninguna manera que pueda dañar,
                    deshabilitar,
                    sobrecargar o perjudicar el sitio web o interferir con el uso y disfrute de otro usuario del sitio.
                </p>

                <h2>Política de Privacidad</h2>

                <p>
                    En WallaShop, nos comprometemos a proteger tu privacidad y seguridad en línea. Nuestra política de
                    privacidad describe cómo recopilamos, utilizamos y protegemos la información que proporcionas cuando
                    utilizas
                    nuestros servicios.
                </p>

                <p>
                    Para obtener más información sobre cómo manejamos tus datos personales, consulta nuestra Política de
                    Privacidad
                    completa en nuestro sitio web.
                </p>


            </section>
            <footer class="modal-container-footer">
                <button class="button is-ghost" @click="decline">Rechazar</button>
                <button class="button is-primary" @click="accept">Aceptar</button>
            </footer>
        </article>
    </div>
</template>

<script>
export default {
    name: 'PopUpInfo',
    data() {
        return {
            isVisible: false,
        };
    },
    mounted() {
        // Verificar si se ha mostrado el popup anteriormente
        if (!localStorage.getItem('popupShown')) {
            this.isVisible = true;
        }
    },
    methods: {
        decline() {
            // Volver atras chrome
            window.history.back();
        },
        accept() {
            // Guardar en localStorage durante 7 dias que se ha aceptado el popup para que no vuelva a aparecer
            localStorage.setItem('popupShown', true);
            this.isVisible = false;
            
        },
    },
};
</script>
<style scoped>
.button,
.input,
.select,
.textarea {
    font: inherit;
}

a {
    color: inherit;
}

.modal-container {
    max-height: 400px;
    max-width: 500px;
    background-color: #fff;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.25);
    z-index: 1000;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}


@media (max-width: 600px) {
    .modal-container {
        width: 90%;
    }
}

.modal-container-header {
    padding: 16px 32px;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-container-title {
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1;
    font-weight: 700;
    font-size: 1.125;
}

.modal-container-title svg {
    width: 32px;
    height: 32px;
    color: #750550;
}

.modal-container-body {
    padding: 24px 32px 51px;
    overflow-y: auto;
}

.rtf h1,
.rtf h2,
.rtf h3,
.rtf h4,
.rtf h5,
.rtf h6 {
    font-weight: 700;
}

.rtf h1 {
    font-size: 1.5rem;
    line-height: 1.125;
}

.rtf h2 {
    font-size: 1.25rem;
    line-height: 1.25;
}

.rtf h3 {
    font-size: 1rem;
    line-height: 1.5;
}

.rtf>*+* {
    margin-top: 1em;
}

.rtf>*+ :is(h1, h2, h3) {
    margin-top: 2em;
}

.rtf> :is(h1, h2, h3)+* {
    margin-top: 0.75em;
}

.rtf ul,
.rtf ol {
    margin-left: 20px;
    list-style-position: inside;
}

.rtf ol {
    list-style: numeric;
}

.rtf ul {
    list-style: disc;
}

.modal-container-footer {
    padding: 20px 32px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    border-top: 1px solid #ddd;
    gap: 12px;
    position: relative;
}

.modal-container-footer:after {
    content: "";
    display: block;
    position: absolute;
    top: -51px;
    left: 24px;
    right: 24px;
    height: 50px;
    flex-shrink: 0;
    background-image: linear-gradient(to top, rgba(255, 255, 255, 0.75), transparent);
    pointer-events: none;
}

.button {
    padding: 12px 20px;
    border-radius: 8px;
    background-color: transparent;
    border: 0;
    font-weight: 600;
    cursor: pointer;
    transition: 0.15s ease;
}

.button.is-ghost:hover,
.button.is-ghost:focus {
    background-color: #dfdad7;
}

.button.is-primary {
    background-color: #750550;
    color: #fff;
}

.button.is-primary:hover,
.button.is-primary:focus {
    background-color: #4a0433;
}

.icon-button {
    padding: 0;
    border: 0;
    background-color: transparent;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    cursor: pointer;
    border-radius: 8px;
    transition: 0.15s ease;
}

.icon-button svg {
    width: 24px;
    height: 24px;
}

.icon-button:hover,
.icon-button:focus {
    background-color: #dfdad7;
}
</style>