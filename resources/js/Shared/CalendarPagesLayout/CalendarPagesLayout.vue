<template>
    <div>
      <Notification></Notification>
      <slot></slot>
      <Footer></Footer>
    </div>
  </template>
  <script>
  export default {
    // props: ["mainMenu"],
    // data() {
    //   return {
    //     cart: false,
    //     cartToggle: true,
    //   };
    // },
    // methods: {
    //   cartClick() {
    //     this.cart = true;
    //     this.cartToggle = !this.cartToggle;
    //   },
    // },
  };
  </script>
  
  <script setup>
//   import NavBar from "../PublicPagesLayout/PublicPagesLayoutComponents/NavBar.vue";
//   import Footer from "../PublicPagesLayout/PublicPagesLayoutComponents/Footer.vue";
//   import CartNew from "../../Pages/Cart/Cart.vue";
  import Notification from "../PublicPagesLayout/PublicPagesLayoutComponents/Notification.vue";
  </script>
  