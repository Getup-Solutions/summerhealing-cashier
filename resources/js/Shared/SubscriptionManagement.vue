<template>
    <div>
        <h3>Manage Your Subscription</h3>
        <label>Card Holder Name</label>
        <input
            id="card-holder-name"
            type="text"
            v-model="name"
            class="form-control mb-2"
        />

        <label>Card</label>
        <div id="card-element"></div>
        <button
            class="sh-button"
            id="add-card-button"
            v-on:click="submitPaymentMethod()"
        >
            Save Payment Method
        </button>
    </div>
</template>
<script>
export default {
    props: ["intentToken"],
    data() {
        return {
            stripeAPIToken:
                "pk_test_51Mf3NTSH7DwQxAxNA3U9oF3GMWKzOP9e9XDJWRXWskVfu8OzlW8QLqCoHlezfE3wBtsZSR0Z1f0SYlCk9f0V6hnf00xemnSdAq",
            stripe: "",
            elements: "",
            card: "",
            name: "",
            addPaymentStatus: 0,
            addPaymentStatusError: "",
        };
    },
    methods: {
        /*
        Includes Stripe.js dynamically
    */
        includeStripe(URL, callback) {
            let documentTag = document,
                tag = "script",
                object = documentTag.createElement(tag),
                scriptTag = documentTag.getElementsByTagName(tag)[0];
            object.src = "//" + URL;
            if (callback) {
                object.addEventListener(
                    "load",
                    function (e) {
                        callback(null, e);
                    },
                    false
                );
            }
            scriptTag.parentNode.insertBefore(object, scriptTag);
        },
        /*
    Configures Stripe by setting up the elements and 
    creating the card element.
*/
        configureStripe() {
            this.stripe = Stripe(this.stripeAPIToken);

            this.elements = this.stripe.elements();
            this.card = this.elements.create("card");

            this.card.mount("#card-element");
        },

        submitPaymentMethod() {
            this.addPaymentStatus = 1;

            this.stripe
                .confirmCardSetup(this.intentToken.client_secret, {
                    payment_method: {
                        card: this.card,
                        billing_details: {
                            name: this.name,
                        },
                    },
                })
                .then(
                    function (result) {
                        if (result.error) {
                            this.addPaymentStatus = 3;
                            this.addPaymentStatusError = result.error.message;
                        } else {
                            console.log(result.setupIntent.payment_method);
                            this.savePaymentMethod(
                                result.setupIntent.payment_method
                            );
                            this.addPaymentStatus = 2;
                            this.card.clear();
                            this.name = "";
                        }
                    }.bind(this)
                );
        },
        /*
    Saves the payment method for the user and
    re-loads the payment methods.
*/
        savePaymentMethod(method) {
            axios
                .post("/dashboard/setup-intent", {
                    payment_method: method,
                })
                .then(
                    function () {
                        //this.loadPaymentMethods();
                    }.bind(this)
                );
        },
    },
    mounted() {
        console.log(this.intent);
        this.includeStripe(
            "js.stripe.com/v3/",
            function () {
                this.configureStripe();
            }.bind(this)
        );
    },
};
</script>
