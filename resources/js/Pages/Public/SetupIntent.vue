<template>
    <div class=" max-w-md mx-auto bg-white">
    <SubscriptionManagement :intentToken="intent"></SubscriptionManagement>
    </div>
</template>
<script>
export default {
    props:["intent"]
}
</script>
<script setup>
import SubscriptionManagement from "../../Shared/SubscriptionManagement.vue";
</script>